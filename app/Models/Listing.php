<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Listing extends Model
{
    /** @use HasFactory<\Database\Factories\ListingFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'condition',
        'price',
        'currency',
        'country',
        'state_province',
        'city',
        'image_path',
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
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
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
