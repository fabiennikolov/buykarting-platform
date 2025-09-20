<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'type' => fake()->randomElement(['individual', 'business']),
            'country' => fake()->country(),
            'state_province' => fake()->optional()->state(),
            'city' => fake()->city(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is an individual.
     */
    public function individual(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'individual',
        ]);
    }

    /**
     * Indicate that the user is a business.
     */
    public function business(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'business',
        ]);
    }
}
