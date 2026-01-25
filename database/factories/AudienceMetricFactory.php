<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AudienceMetric>
 */
class AudienceMetricFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'captured_for' => fake()->unique()->date('Y-m-d'),
            'peak_listeners' => fake()->numberBetween(1200, 22000),
            'average_listeners' => fake()->numberBetween(900, 15000),
            'new_followers' => fake()->numberBetween(10, 400),
            'chat_messages' => fake()->numberBetween(20, 800),
            'podcast_streams' => fake()->numberBetween(50, 2000),
            'sms_votes' => fake()->numberBetween(5, 320),
            'top_cities' => [
                ['city' => 'Owerri', 'listeners' => fake()->numberBetween(300, 4000)],
                ['city' => 'Lagos', 'listeners' => fake()->numberBetween(400, 6000)],
                ['city' => 'Abuja', 'listeners' => fake()->numberBetween(200, 3000)],
            ],
        ];
    }
}
