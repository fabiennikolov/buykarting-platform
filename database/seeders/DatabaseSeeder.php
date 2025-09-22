<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Subscription;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Subscriptions
        $freemiumSubscription = Subscription::create([
            'plan_name' => 'Freemium',
            'starts_at' => now(),
            'ends_at' => null,
            'listings_limit' => 3,
        ]);

        $premiumSubscription = Subscription::create([
            'plan_name' => 'Premium',
            'starts_at' => now(),
            'ends_at' => now()->addDays(90),
            'listings_limit' => 999,
        ]);

        $businessSubscription = Subscription::create([
            'plan_name' => 'Business',
            'starts_at' => now(),
            'ends_at' => now()->addYear(),
            'listings_limit' => 9999,
        ]);

        // Create Users
        $users = [
            User::create([
                'subscription_id' => $freemiumSubscription->id,
                'name' => 'John Karting',
                'email' => 'john@karting.com',
                'type' => 'individual',
                'country' => 'Bulgaria',
                'city' => 'Sofia',
                'password' => 'password'
            ]),
            User::create([
                'subscription_id' => $premiumSubscription->id,
                'name' => 'Racing Pro Ltd',
                'email' => 'info@racingpro.com',
                'type' => 'business',
                'country' => 'Germany',
                'city' => 'Munich',
                'password' => 'password'
            ]),
            User::create([
                'subscription_id' => $businessSubscription->id,
                'name' => 'Speed Motors',
                'email' => 'sales@speedmotors.com',
                'type' => 'business',
                'country' => 'Italy',
                'city' => 'Milan',
                'password' => 'password'
            ]),
            User::create([
                'subscription_id' => $premiumSubscription->id,
                'name' => 'Anna Racer',
                'email' => 'anna@example.com',
                'type' => 'individual',
                'country' => 'France',
                'city' => 'Paris',
                'password' => 'password'
            ]),
        ];

        // Create Karting Categories
        $categories = [
            Category::create(['name' => 'Go-Karts', 'slug' => 'go-karts']),
            Category::create(['name' => 'Engines', 'slug' => 'engines']),
            Category::create(['name' => 'Chassis', 'slug' => 'chassis']),
            Category::create(['name' => 'Wheels & Tires', 'slug' => 'wheels-tires']),
            Category::create(['name' => 'Safety Gear', 'slug' => 'safety-gear']),
            Category::create(['name' => 'Parts & Accessories', 'slug' => 'parts-accessories']),
            Category::create(['name' => 'Tools & Equipment', 'slug' => 'tools-equipment']),
            Category::create(['name' => 'Consumables', 'slug' => 'consumables']),
        ];

        // Create Test Listings
        $listings = [
            // Go-Karts
            [
                'user_id' => $users[0]->id,
                'category_id' => $categories[0]->id,
                'title' => 'Tony Kart Racer 401R - Championship Winning Kart',
                'description' => 'Professional racing go-kart used in national championships. Recently serviced with new bearings and chain. Includes spare wheels and toolkit. Perfect for competitive racing.',
                'condition' => 'used',
                'price' => 3500.00,
                'currency' => 'eur',
                'country' => 'Bulgaria',
                'state_province' => 'Sofia',
                'city' => 'Sofia',
                'status' => 'active',
            ],
            [
                'user_id' => $users[1]->id,
                'category_id' => $categories[0]->id,
                'title' => 'CRG Road Rebel - Perfect for Beginners',
                'description' => 'Excellent condition recreational go-kart suitable for beginners and recreational drivers. Very stable and forgiving handling characteristics.',
                'condition' => 'like_new',
                'price' => 2200.00,
                'currency' => 'eur',
                'country' => 'Germany',
                'state_province' => 'Bavaria',
                'city' => 'Munich',
                'status' => 'active',
            ],
            [
                'user_id' => $users[2]->id,
                'category_id' => $categories[0]->id,
                'title' => 'Birel ART RY30-S8 - Brand New 2024 Model',
                'description' => 'Latest 2024 model from Birel ART. Never raced, only used for testing. Comes with full factory warranty and setup guide.',
                'condition' => 'new',
                'price' => 4800.00,
                'currency' => 'eur',
                'country' => 'Italy',
                'state_province' => 'Lombardy',
                'city' => 'Milan',
                'status' => 'active',
            ],

            // Engines
            [
                'user_id' => $users[1]->id,
                'category_id' => $categories[1]->id,
                'title' => 'IAME X30 Engine - Fresh Rebuild',
                'description' => 'Professional X30 engine with fresh rebuild. Less than 5 hours since rebuild. Includes carburetor, exhaust, and engine mount.',
                'condition' => 'like_new',
                'price' => 2800.00,
                'currency' => 'eur',
                'country' => 'Germany',
                'state_province' => 'Bavaria',
                'city' => 'Munich',
                'status' => 'active',
            ],
            [
                'user_id' => $users[3]->id,
                'category_id' => $categories[1]->id,
                'title' => 'Rotax Max EVO - Complete Package',
                'description' => 'Complete Rotax Max EVO engine package including radiator, pipes, and starter. Well maintained with service history.',
                'condition' => 'used',
                'price' => 3200.00,
                'currency' => 'eur',
                'country' => 'France',
                'state_province' => 'Île-de-France',
                'city' => 'Paris',
                'status' => 'active',
            ],

            // Chassis
            [
                'user_id' => $users[0]->id,
                'category_id' => $categories[2]->id,
                'title' => 'Kosmic Mercury-S Chassis Frame',
                'description' => 'Professional chassis frame only. No damage, straight frame suitable for competitive racing. 32mm tubing.',
                'condition' => 'used',
                'price' => 1200.00,
                'currency' => 'bgn',
                'country' => 'Bulgaria',
                'state_province' => 'Sofia',
                'city' => 'Sofia',
                'status' => 'active',
            ],

            // Wheels & Tires
            [
                'user_id' => $users[2]->id,
                'category_id' => $categories[3]->id,
                'title' => 'MG Red Tire Set - Brand New',
                'description' => 'Complete set of 4 MG Red tires, never used. Perfect for dry conditions and competitive racing.',
                'condition' => 'new',
                'price' => 280.00,
                'currency' => 'eur',
                'country' => 'Italy',
                'state_province' => 'Lombardy',
                'city' => 'Milan',
                'status' => 'active',
            ],
            [
                'user_id' => $users[1]->id,
                'category_id' => $categories[3]->id,
                'title' => 'OTK Aluminum Wheels Set',
                'description' => 'Set of 4 OTK aluminum wheels in excellent condition. Minor scratches but perfectly functional.',
                'condition' => 'used',
                'price' => 350.00,
                'currency' => 'eur',
                'country' => 'Germany',
                'state_province' => 'Bavaria',
                'city' => 'Munich',
                'status' => 'active',
            ],

            // Safety Gear
            [
                'user_id' => $users[3]->id,
                'category_id' => $categories[4]->id,
                'title' => 'Arai GP-6S Helmet - Size Large',
                'description' => 'Professional racing helmet with latest safety certifications. Light weight carbon fiber construction. Very clean condition.',
                'condition' => 'like_new',
                'price' => 1800.00,
                'currency' => 'eur',
                'country' => 'France',
                'state_province' => 'Île-de-France',
                'city' => 'Paris',
                'status' => 'active',
            ],
            [
                'user_id' => $users[0]->id,
                'category_id' => $categories[4]->id,
                'title' => 'Sparco Racing Suit - Size Medium',
                'description' => 'FIA approved racing suit in excellent condition. Properly maintained and cleaned after each use.',
                'condition' => 'used',
                'price' => 420.00,
                'currency' => 'bgn',
                'country' => 'Bulgaria',
                'state_province' => 'Sofia',
                'city' => 'Sofia',
                'status' => 'active',
            ],

            // Parts & Accessories
            [
                'user_id' => $users[2]->id,
                'category_id' => $categories[5]->id,
                'title' => 'Complete Brake System - Front & Rear',
                'description' => 'Complete brake system including calipers, discs, pads, and brake lines. Suitable for most modern chassis.',
                'condition' => 'new',
                'price' => 650.00,
                'currency' => 'eur',
                'country' => 'Italy',
                'state_province' => 'Lombardy',
                'city' => 'Milan',
                'status' => 'active',
            ],

            // Tools & Equipment
            [
                'user_id' => $users[1]->id,
                'category_id' => $categories[6]->id,
                'title' => 'Professional Kart Stand',
                'description' => 'Heavy duty aluminum kart stand with adjustable height. Essential for maintenance and storage.',
                'condition' => 'used',
                'price' => 180.00,
                'currency' => 'eur',
                'country' => 'Germany',
                'state_province' => 'Bavaria',
                'city' => 'Munich',
                'status' => 'active',
            ],

            // Consumables
            [
                'user_id' => $users[3]->id,
                'category_id' => $categories[7]->id,
                'title' => 'Racing Fuel - 20L Can',
                'description' => 'High octane racing fuel suitable for 2-stroke engines. Unopened 20L container.',
                'condition' => 'new',
                'price' => 85.00,
                'currency' => 'eur',
                'country' => 'France',
                'state_province' => 'Île-de-France',
                'city' => 'Paris',
                'status' => 'active',
            ],

            // Some draft and sold listings for testing
            [
                'user_id' => $users[0]->id,
                'category_id' => $categories[0]->id,
                'title' => 'Work in Progress Kart Build',
                'description' => 'Custom kart build in progress. Will update when complete.',
                'condition' => 'needs_repair',
                'price' => 1500.00,
                'currency' => 'bgn',
                'country' => 'Bulgaria',
                'state_province' => 'Sofia',
                'city' => 'Sofia',
                'status' => 'draft',
            ],
            [
                'user_id' => $users[2]->id,
                'category_id' => $categories[1]->id,
                'title' => 'SOLD - Vortex ROK Engine',
                'description' => 'This engine has been sold.',
                'condition' => 'used',
                'price' => 2500.00,
                'currency' => 'eur',
                'country' => 'Italy',
                'state_province' => 'Lombardy',
                'city' => 'Milan',
                'status' => 'sold',
            ],
        ];

        foreach ($listings as $listing) {
            Listing::create($listing);
        }
    }
}
