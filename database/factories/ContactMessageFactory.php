<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'subject' => fake()->sentence(5),
            'type' => fake()->randomElement(['general', 'advertising', 'playlist', 'technical']),
            'message' => fake()->paragraph(2),
            'status' => fake()->randomElement(['new', 'in_progress', 'resolved']),
            'handled_by' => null,
            'handled_at' => null,
        ];
    }
}
