<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subscription>
 */
class SubscriptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $plans = [
            'Freemium' => 3,
            'Basic' => 50,
            'Premium' => 999999, // Unlimited represented as high number
        ];

        $planName = fake()->randomElement(array_keys($plans));
        $listingsLimit = $plans[$planName];

        return [
            'user_id' => User::factory(),
            'plan_name' => $planName,
            'starts_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
            'ends_at' => $planName === 'Freemium' ? null : Carbon::now()->addDays(fake()->numberBetween(30, 365)),
            'listings_limit' => $listingsLimit,
        ];
    }

    /**
     * Indicate that the subscription is freemium.
     */
    public function freemium(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan_name' => 'Freemium',
            'starts_at' => Carbon::now(),
            'ends_at' => null,
            'listings_limit' => 3,
        ]);
    }

    /**
     * Indicate that the subscription is basic.
     */
    public function basic(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan_name' => 'Basic',
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addMonths(1),
            'listings_limit' => 50,
        ]);
    }

    /**
     * Indicate that the subscription is premium.
     */
    public function premium(): static
    {
        return $this->state(fn (array $attributes) => [
            'plan_name' => 'Premium',
            'starts_at' => Carbon::now(),
            'ends_at' => Carbon::now()->addMonths(1),
            'listings_limit' => 999999,
        ]);
    }

    /**
     * Indicate that the subscription is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => Carbon::now()->subMonths(2),
            'ends_at' => Carbon::now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }
}
