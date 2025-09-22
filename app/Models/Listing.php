<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Listing extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\ListingFactory> */
    use HasFactory, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'condition',
        'price',
        'currency',
        'country',
        'state_province',
        'city',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the listing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Register media collections for the listing.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    /**
     * Define media conversions for the listing.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        // Only add conversions if image processing is available
        if (extension_loaded('gd') || extension_loaded('imagick')) {
            $this->addMediaConversion('thumb')
                ->width(300)
                ->height(300)
                ->nonQueued(); // Process immediately

            $this->addMediaConversion('preview')
                ->width(800)
                ->height(600)
                ->nonQueued(); // Process immediately
        }
    }

    /**
     * Get the first image URL for the listing.
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('images');
    }

    /**
     * Get the thumbnail image URL for the listing.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('images', 'thumb');
    }

    /**
     * Get the preview image URL for the listing.
     */
    public function getPreviewUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('images', 'preview');
    }

    /**
     * Scope a query to only include active listings.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to filter by condition.
     */
    public function scopeByCondition($query, string $condition)
    {
        return $query->where('condition', $condition);
    }

    /**
     * Scope a query to filter by location.
     */
    public function scopeByLocation($query, string $country, ?string $city = null)
    {
        $query = $query->where('country', $country);

        if ($city) {
            $query = $query->where('city', $city);
        }

        return $query;
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopeByPriceRange($query, ?float $minPrice = null, ?float $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query = $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query = $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Scope a query to search by title or description.
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }
}
