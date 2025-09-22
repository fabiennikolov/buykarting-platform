<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'country',
        'state_province',
        'city',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the listings for the user.
     */
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Get the user's subscription.
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the active listings for the user.
     */
    public function activeListings(): HasMany
    {
        return $this->listings()->where('status', 'active');
    }

    /**
     * Check if the user can create more listings.
     */
    public function canCreateListing(): bool
    {
        $subscription = $this->subscription;

        if (!$subscription) {
            return false;
        }

        $activeListingsCount = $this->activeListings()->count();

        return $activeListingsCount < $subscription->listings_limit;
    }

    /**
     * Get the number of remaining listings for the user.
     */
    public function remainingListings(): int
    {
        $subscription = $this->subscription;

        if (!$subscription) {
            return 0;
        }

        $activeListingsCount = $this->activeListings()->count();

        return max(0, $subscription->listings_limit - $activeListingsCount);
    }
}
