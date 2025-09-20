<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('listings.index');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Listing routes - authenticated routes (specific routes first)
Route::middleware('auth')->group(function () {
    Route::get('listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::patch('listings/{listing}', [ListingController::class, 'update']);
    Route::delete('listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::get('my-listings', [ListingController::class, 'myListings'])->name('listings.my');
});

// Listing routes - public routes (parameterized routes last)
Route::get('listings', [ListingController::class, 'index'])->name('listings.index');
Route::get('listings/{listing}', [ListingController::class, 'show'])->name('listings.show');

// Subscription routes
Route::prefix('subscriptions')->middleware('auth')->group(function () {
    Route::get('/', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('upgrade/{plan}', [SubscriptionController::class, 'showUpgrade'])->name('subscriptions.upgrade.show');
    Route::post('upgrade', [SubscriptionController::class, 'upgrade'])->name('subscriptions.upgrade');
    Route::post('cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
