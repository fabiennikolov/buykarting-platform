<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    /** @use HasFactory<\Database\Factories\SubscriptionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'plan_name',
        'starts_at',
        'ends_at',
        'listings_limit',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * Get the plan name (accessor for plan_name).
     */
    public function getPlanAttribute(): string
    {
        return $this->plan_name;
    }

    /**
     * Get the user that owns the subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the subscription is currently active.
     */
    public function isActive(): bool
    {
        $now = Carbon::now();
        
        return $this->starts_at <= $now && 
               ($this->ends_at === null || $this->ends_at >= $now);
    }

    /**
     * Check if the subscription has expired.
     */
    public function isExpired(): bool
    {
        return $this->ends_at !== null && $this->ends_at < Carbon::now();
    }

    /**
     * Get the number of days remaining in the subscription.
     */
    public function daysRemaining(): ?int
    {
        if ($this->ends_at === null) {
            return null; // Unlimited subscription
        }
        
        $now = Carbon::now();
        
        if ($this->ends_at <= $now) {
            return 0;
        }
        
        return $now->diffInDays($this->ends_at);
    }

    /**
     * Create a freemium subscription for a user.
     */
    public static function createFreemium(User $user): self
    {
        return self::create([
            'user_id' => $user->id,
            'plan_name' => 'Freemium',
            'starts_at' => Carbon::now(),
            'ends_at' => null, // No expiration for freemium
            'listings_limit' => 3,
        ]);
    }

    /**
     * Scope a query to only include active subscriptions.
     */
    public function scopeActive($query)
    {
        $now = Carbon::now();
        
        return $query->where('starts_at', '<=', $now)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('ends_at')
                          ->orWhere('ends_at', '>=', $now);
                    });
    }

    /**
     * Scope a query to only include expired subscriptions.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('ends_at')
                    ->where('ends_at', '<', Carbon::now());
    }
}
