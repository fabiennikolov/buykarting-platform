<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListingRequest;
use App\Models\Category;
use App\Models\Listing;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ListingController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $query = Listing::query()
            ->with(['user', 'category'])
            ->active()
            ->latest();

        // Apply filters
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('condition')) {
            $query->byCondition($request->condition);
        }

        if ($request->filled('country')) {
            $query->byLocation($request->country, $request->city);
        }

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->byPriceRange($request->min_price, $request->max_price);
        }

        $listings = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return Inertia::render('Listings/Index', [
            'listings' => $listings,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category', 'condition', 'country', 'city', 'min_price', 'max_price']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $user = auth()->user();

        if (!$user->canCreateListing()) {
            return Inertia::render('Listings/LimitReached', [
                'subscription' => $user->subscription,
                'remainingListings' => $user->remainingListings(),
            ]);
        }

        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return Inertia::render('Listings/Create', [
            'remainingListings' => $user->remainingListings(),
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ListingRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if (!$user->canCreateListing()) {
            return redirect()->route('listings.index')
                ->with('error', 'You have reached your listing limit. Please upgrade your subscription to create more listings.');
        }

        $data = $request->validated();
        $data['user_id'] = $user->id;
        $data['status'] = $data['status'] ?? 'active';

        // Remove media-related fields from create data
        unset($data['main_image'], $data['additional_images'], $data['remove_media']);

        $listing = Listing::create($data);

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $listing->addMedia($request->file('main_image'))
                ->toMediaCollection('images');
        }

        // Handle additional images upload
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $listing->addMedia($image)->toMediaCollection('images');
            }
        }

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Listing created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing): Response
    {
        $listing->load('user');

        // Add media URLs to listing
        $listing->media = $listing->getMedia('images')->map(function ($media) {
            // Use the actual request URL instead of config for correct host/port
            $baseUrl = request()->getSchemeAndHttpHost();

            // Get the storage path directly
            $storagePath = '/storage/' . str_replace('public/', '', $media->getPathRelativeToRoot());

            return [
                'id' => $media->id,
                'url' => $baseUrl . $storagePath,
                'thumb_url' => $media->hasGeneratedConversion('thumb') ?
                    $baseUrl . '/storage/' . str_replace('public/', '', $media->getPathRelativeToRoot('thumb')) :
                    $baseUrl . $storagePath,
                'preview_url' => $media->hasGeneratedConversion('preview') ?
                    $baseUrl . '/storage/' . str_replace('public/', '', $media->getPathRelativeToRoot('preview')) :
                    $baseUrl . $storagePath,
            ];
        });

        // Get related listings from the same category
        $relatedListings = Listing::query()
            ->active()
            ->where('id', '!=', $listing->id)
            ->limit(4)
            ->get();

        return Inertia::render('Listings/Show', [
            'listing' => $listing,
            'relatedListings' => $relatedListings,
            'canEdit' => auth()->check() && auth()->id() === $listing->user_id,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing): Response
    {
        $this->authorize('update', $listing);

        $listing->load('category');

        // Add media URLs to listing
        $listing->media = $listing->getMedia('images')->map(function ($media) {
            // Use the actual request URL instead of config for correct host/port
            $baseUrl = request()->getSchemeAndHttpHost();

            // Get the storage path directly
            $storagePath = '/storage/' . str_replace('public/', '', $media->getPathRelativeToRoot());

            return [
                'id' => $media->id,
                'url' => $baseUrl . $storagePath,
                'thumb_url' => $media->hasGeneratedConversion('thumb') ?
                    $baseUrl . '/storage/' . str_replace('public/', '', $media->getPathRelativeToRoot('thumb')) :
                    $baseUrl . $storagePath,
                'preview_url' => $media->hasGeneratedConversion('preview') ?
                    $baseUrl . '/storage/' . str_replace('public/', '', $media->getPathRelativeToRoot('preview')) :
                    $baseUrl . $storagePath,
            ];
        });

        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return Inertia::render('Listings/Edit', [
            'listing' => $listing,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ListingRequest $request, Listing $listing): RedirectResponse
    {
        $this->authorize('update', $listing);

        $data = $request->validated();

        // Remove media-related fields from update data
        unset($data['main_image'], $data['additional_images'], $data['remove_media']);

        // Handle media removal
        if ($request->has('remove_media') && is_array($request->remove_media)) {
            foreach ($request->remove_media as $mediaId) {
                $media = $listing->getMedia('images')->where('id', $mediaId)->first();
                if ($media) {
                    $media->delete();
                }
            }
        }

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $listing->addMedia($request->file('main_image'))
                ->toMediaCollection('images');
        }

        // Handle additional images upload
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $listing->addMedia($image)->toMediaCollection('images');
            }
        }

        $listing->update($data);

        return redirect()->route('listings.edit', $listing)
            ->with('success', 'Listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing): RedirectResponse
    {
        $this->authorize('delete', $listing);

        // Media will be automatically deleted when the model is deleted
        // due to the media library's cleanup functionality
        $listing->delete();

        return redirect()->route('listings.index')
            ->with('success', 'Listing deleted successfully!');
    }

    /**
     * Get current user's listings.
     */
    public function myListings(): Response
    {
        $listings = auth()->user()
            ->listings()
            ->latest()
            ->paginate(12);

        return Inertia::render('Listings/MyListings', [
            'listings' => $listings,
            'remainingListings' => auth()->user()->remainingListings(),
        ]);
    }
}
