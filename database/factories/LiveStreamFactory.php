<?php

namespace Database\Factories;

use App\Models\Dj;
use App\Models\Show;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LiveStream>
 */
class LiveStreamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);
        $scheduled = fake()->dateTimeBetween('-1 day', '+1 day');

        return [
            'show_id' => Show::factory(),
            'dj_id' => Dj::factory(),
            'title' => $title,
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['scheduled', 'live', 'offline']),
            'stream_url' => fake()->url(),
            'chat_enabled' => fake()->boolean(70),
            'listener_count' => fake()->numberBetween(50, 8000),
            'server_host' => fake()->domainName(),
            'bitrate' => fake()->numberBetween(64, 320),
            'scheduled_for' => $scheduled,
            'started_at' => fake()->boolean(50) ? $scheduled : null,
            'ended_at' => null,
        ];
    }
}
