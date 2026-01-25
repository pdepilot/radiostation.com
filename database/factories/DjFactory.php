<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dj>
 */
class DjFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'stage_name' => fake()->boolean(70) ? fake()->unique()->word() : null,
            'slug' => fake()->unique()->slug(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'avatar_url' => fake()->imageUrl(600, 600, 'people'),
            'specialty' => fake()->randomElement(['Morning Drive', 'Lifestyle', 'Political Talk', 'Chart Show']),
            'bio' => fake()->paragraph(),
            'is_featured' => fake()->boolean(40),
            'instagram' => 'https://instagram.com/' . fake()->userName(),
            'twitter' => 'https://twitter.com/' . fake()->userName(),
            'facebook' => 'https://facebook.com/' . fake()->userName(),
            'mixcloud' => 'https://mixcloud.com/' . fake()->userName(),
            'booking_link' => fake()->url(),
            'availability' => [
                'monday' => ['start' => '05:00', 'end' => '12:00'],
                'friday' => ['start' => '20:00', 'end' => '23:00'],
            ],
        ];
    }
}
