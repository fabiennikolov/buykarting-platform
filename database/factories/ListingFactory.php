<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['go_kart', 'parts', 'accessories', 'consumables'];
        $conditions = ['new', 'used'];
        $currencies = ['EUR', 'BGN', 'USD'];
        $statuses = ['active', 'sold', 'draft'];
        
        $kartTitles = [
            'Professional Racing Kart - Tony Kart',
            'Vintage Go-Kart Frame',
            'Electric Kids Go-Kart',
            'Shifter Kart - CRG',
            'Rental Kart - Birel ART',
        ];
        
        $partsTitles = [
            'IAME X30 Engine',
            'Racing Wheels Set',
            'Brake Disc - 206mm',
            'Carburetor - Tillotson',
            'Chain - DID 219',
        ];
        
        $accessoriesTitles = [
            'Racing Helmet - Arai',
            'Racing Suit - Sparco',
            'Racing Gloves',
            'Tire Pressure Gauge',
            'Tool Kit - Complete',
        ];
        
        $consumablesTitles = [
            'Racing Tires - Bridgestone',
            'Engine Oil - Motul',
            'Brake Pads Set',
            'Spark Plugs',
            'Air Filter',
        ];

        $category = fake()->randomElement($categories);
        
        $titlesByCategory = [
            'go_kart' => $kartTitles,
            'parts' => $partsTitles,
            'accessories' => $accessoriesTitles,
            'consumables' => $consumablesTitles,
        ];

        return [
            'user_id' => User::factory(),
            'title' => fake()->randomElement($titlesByCategory[$category]),
            'description' => fake()->paragraphs(3, true),
            'category' => $category,
            'condition' => fake()->randomElement($conditions),
            'price' => fake()->randomFloat(2, 10, 5000),
            'currency' => fake()->randomElement($currencies),
            'country' => fake()->country(),
            'state_province' => fake()->optional()->state(),
            'city' => fake()->city(),
            'image_path' => null, // Will be handled by file upload
            'status' => fake()->randomElement($statuses, [90, 5, 5]), // 90% active, 5% sold, 5% draft
        ];
    }

    /**
     * Indicate that the listing is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the listing is sold.
     */
    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'sold',
        ]);
    }

    /**
     * Indicate that the listing is a draft.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
        ]);
    }

    /**
     * Indicate that the listing is for a go-kart.
     */
    public function goKart(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'go_kart',
            'title' => fake()->randomElement([
                'Professional Racing Kart - Tony Kart',
                'Vintage Go-Kart Frame',
                'Electric Kids Go-Kart',
                'Shifter Kart - CRG',
                'Rental Kart - Birel ART',
            ]),
            'price' => fake()->randomFloat(2, 500, 5000),
        ]);
    }

    /**
     * Indicate that the listing is for parts.
     */
    public function parts(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'parts',
            'title' => fake()->randomElement([
                'IAME X30 Engine',
                'Racing Wheels Set',
                'Brake Disc - 206mm',
                'Carburetor - Tillotson',
                'Chain - DID 219',
            ]),
            'price' => fake()->randomFloat(2, 10, 1000),
        ]);
    }
}
