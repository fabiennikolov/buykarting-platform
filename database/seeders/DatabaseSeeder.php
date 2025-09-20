<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Subscription;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test user with freemium subscription
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'type' => 'individual',
            'country' => 'Bulgaria',
            'city' => 'Sofia',
        ]);

        Subscription::factory()->freemium()->create([
            'user_id' => $testUser->id,
        ]);

        // Create additional users with subscriptions
        $users = User::factory(20)->create();
        
        foreach ($users as $user) {
            // 60% freemium, 25% basic, 15% premium
            $planType = match (rand(1, 100)) {
                1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15 => 'premium',
                16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40 => 'basic',
                default => 'freemium',
            };

            Subscription::factory()->{$planType}()->create([
                'user_id' => $user->id,
            ]);
        }

        // Create listings for users (respecting their subscription limits)
        $usersWithSubscriptions = User::with('subscription')->get();
        
        foreach ($usersWithSubscriptions as $user) {
            $subscription = $user->subscription;
            
            if ($subscription) {
                $maxListings = min($subscription->listings_limit, rand(1, 8)); // Random but within limits
                
                Listing::factory($maxListings)->create([
                    'user_id' => $user->id,
                ]);
            }
        }

        // Create some additional sample listings for variety
        Listing::factory(50)->active()->create();
    }
}
