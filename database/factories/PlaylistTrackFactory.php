<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlaylistTrack>
 */
class PlaylistTrackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(2),
            'artist' => fake()->name(),
            'genre' => fake()->randomElement(['Afrobeats', 'Highlife', 'Hip-Hop', 'R&B']),
            'mood' => fake()->randomElement(['Energetic', 'Calm', 'Romantic']),
            'duration' => fake()->numberBetween(2, 6) . ':' . fake()->numberBetween(0, 59),
            'cover_image' => fake()->imageUrl(400, 400, 'music'),
            'audio_url' => fake()->url(),
            'scheduled_for' => fake()->date(),
            'is_featured' => fake()->boolean(25),
        ];
    }
}
