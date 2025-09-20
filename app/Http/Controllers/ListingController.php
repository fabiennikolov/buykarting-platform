<?php

namespace App\Http\Controllers;

use App\Http\Requests\ListingRequest;
use App\Models\Listing;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            ->with('user')
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

        return Inertia::render('Listings/Index', [
            'listings' => $listings,
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

        return Inertia::render('Listings/Create', [
            'remainingListings' => $user->remainingListings(),
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

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('listings', 'public');
            $data['image_path'] = $imagePath;
        }

        $listing = Listing::create($data);

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Listing created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing): Response
    {
        $listing->load('user');

        // Get related listings from the same category
        $relatedListings = Listing::query()
            ->active()
            ->byCategory($listing->category)
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

        return Inertia::render('Listings/Edit', [
            'listing' => $listing,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ListingRequest $request, Listing $listing): RedirectResponse
    {
        $this->authorize('update', $listing);

        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($listing->image_path) {
                Storage::disk('public')->delete($listing->image_path);
            }

            $imagePath = $request->file('image')->store('listings', 'public');
            $data['image_path'] = $imagePath;
        }

        $listing->update($data);

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Listing updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing): RedirectResponse
    {
        $this->authorize('delete', $listing);

        // Delete image
        if ($listing->image_path) {
            Storage::disk('public')->delete($listing->image_path);
        }

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
