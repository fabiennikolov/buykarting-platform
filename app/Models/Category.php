<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['title', 'slug'];

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function activeListings(): HasMany
    {
        return $this->listings()->where('status', 'active');
    }
}
