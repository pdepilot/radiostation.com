<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Podcast>
 */
class PodcastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'title' => $title,
            'slug' => fake()->unique()->slug(),
            'host' => fake()->name(),
            'sponsor' => fake()->company(),
            'cover_image' => fake()->imageUrl(800, 800, 'music'),
            'description' => fake()->paragraph(3),
            'audio_url' => fake()->url(),
            'duration' => fake()->numberBetween(15, 90) . ' mins',
            'listen_count' => fake()->numberBetween(100, 15000),
            'published_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
