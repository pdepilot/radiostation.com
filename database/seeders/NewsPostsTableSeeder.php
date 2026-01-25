<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NewsPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NewsPost::create([
            'title' => 'New Music Festival Coming This Summer',
            'slug' => 'new-music-festival-coming-this-summer',
            'excerpt' => 'We\'re excited to announce our partnership with the City Music Festival happening this August...',
            'content' => 'We\'re excited to announce our partnership with the City Music Festival happening this August...',
            'hero_image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            'status' => 'published',
            'published_at' => now()->subDays(2),
        ]);

        NewsPost::create([
            'title' => 'Interview with Rising Star: Maya Rivers',
            'slug' => 'interview-with-rising-star-maya-rivers',
            'excerpt' => 'We sat down with the amazing Maya Rivers to talk about her new album...',
            'content' => 'We sat down with the amazing Maya Rivers to talk about her new album...',
            'hero_image' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            'status' => 'published',
            'published_at' => now()->subDays(1),
        ]);

        NewsPost::create([
            'title' => 'Behind the Scenes: Recording Our Latest Track',
            'slug' => 'behind-the-scenes-recording-latest-track',
            'excerpt' => 'Get an exclusive look at how we created our newest hit single...',
            'content' => 'Get an exclusive look at how we created our newest hit single...',
            'hero_image' => 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
