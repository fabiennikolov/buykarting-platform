<?php

use App\Models\Listing;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users can view listings index', function () {
    $listings = Listing::factory(5)->active()->create();

    $response = $this->get(route('listings.index'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Listings/Index')
        ->has('listings.data', 5)
    );
});

test('authenticated users can create listings when within limits', function () {
    $user = User::factory()->create();
    Subscription::factory()->freemium()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->get(route('listings.create'));

    $response->assertOk();
    $response->assertInertia(fn ($page) => $page
        ->component('Listings/Create')
    );
});

test('users cannot create listings when limit is reached', function () {
    $user = User::factory()->create();
    $subscription = Subscription::factory()->freemium()->create(['user_id' => $user->id]);
    
    // Create 3 listings (freemium limit)
    Listing::factory(3)->active()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $response = $this->get(route('listings.create'));

    $response->assertInertia(fn ($page) => $page
        ->component('Listings/LimitReached')
    );
});

test('users can create a listing successfully', function () {
    $user = User::factory()->create();
    Subscription::factory()->freemium()->create(['user_id' => $user->id]);

    $this->actingAs($user);

    $listingData = [
        'title' => 'Test Kart',
        'description' => 'A great go-kart for racing',
        'category' => 'go_kart',
        'condition' => 'used',
        'price' => 1500.00,
        'currency' => 'EUR',
        'country' => 'Bulgaria',
        'city' => 'Sofia',
        'status' => 'active',
    ];

    $response = $this->post(route('listings.store'), $listingData);

    $response->assertRedirect();
    
    $this->assertDatabaseHas('listings', [
        'title' => 'Test Kart',
        'user_id' => $user->id,
        'status' => 'active',
    ]);
});

test('users can only edit their own listings', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    
    Subscription::factory()->freemium()->create(['user_id' => $user1->id]);
    Subscription::factory()->freemium()->create(['user_id' => $user2->id]);

    $listing = Listing::factory()->create(['user_id' => $user1->id]);

    // User 2 tries to edit user 1's listing
    $this->actingAs($user2);
    $response = $this->get(route('listings.edit', $listing));
    $response->assertForbidden();

    // User 1 can edit their own listing
    $this->actingAs($user1);
    $response = $this->get(route('listings.edit', $listing));
    $response->assertOk();
});

test('freemium subscription is created for new users', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'type' => 'individual',
        'country' => 'Bulgaria',
        'city' => 'Sofia',
    ];

    $response = $this->post('/register', $userData);

    $user = User::where('email', 'test@example.com')->first();
    
    expect($user)->not->toBeNull();
    expect($user->subscription)->not->toBeNull();
    expect($user->subscription->plan_name)->toBe('Freemium');
    expect($user->subscription->listings_limit)->toBe(3);
});
