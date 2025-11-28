<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NewsPost>
 */
class NewsPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
                'title' => $title,
                'slug' => fake()->unique()->slug(),
                'excerpt' => fake()->paragraph(),
                'body' => collect(range(1, 5))->map(fn () => '<p>' . fake()->paragraph(4) . '</p>')->implode("\n"),
                'hero_image' => fake()->imageUrl(1280, 720, 'music'),
                'author_name' => fake()->name(),
                'reading_time' => fake()->numberBetween(3, 8) . ' min read',
                'tags' => fake()->randomElements(['music', 'gossip', 'politics', 'community', 'playlist'], 3),
                'status' => 'published',
                'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
                'is_featured' => fake()->boolean(30),
                'comment_count' => fake()->numberBetween(0, 50),
        ];
    }
}
