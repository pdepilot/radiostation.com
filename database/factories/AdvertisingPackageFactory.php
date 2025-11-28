<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdvertisingPackage>
 */
class AdvertisingPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->randomElement(['Prime Drive', 'Sunrise Blast', 'Night Owl', 'Digital Audio Boost']);

        return [
            'name' => $name,
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'reach' => fake()->numberBetween(50000, 400000) . ' listeners',
            'duration_weeks' => fake()->numberBetween(2, 12),
            'price' => fake()->randomFloat(2, 250000, 3500000),
            'cta' => 'Book now',
            'status' => 'active',
        ];
    }
}
