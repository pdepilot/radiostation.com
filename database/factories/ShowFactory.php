<?php

namespace Database\Factories;

use App\Models\Dj;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Show>
 */
class ShowFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'dj_id' => Dj::factory(),
            'title' => $title,
            'slug' => fake()->unique()->slug(),
            'tagline' => fake()->catchPhrase(),
            'description' => fake()->paragraph(3),
            'genre' => fake()->randomElement(['Afrobeats', 'Gospel', 'Politics', 'Culture']),
            'day_of_week' => fake()->dayOfWeek(),
            'start_time' => fake()->time('H:i'),
            'end_time' => fake()->time('H:i'),
            'hero_image' => fake()->imageUrl(1200, 800, 'music'),
            'is_live' => fake()->boolean(20),
            'sponsor' => fake()->company(),
            'listener_count' => fake()->numberBetween(100, 4500),
            'stream_url' => fake()->url(),
            'status' => fake()->randomElement(['scheduled', 'live', 'completed']),
        ];
    }
}
