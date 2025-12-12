<?php

namespace Database\Seeders;

use App\Models\Podcast;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PodcastsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Podcast::create([
            'title' => 'Behind the Music',
            'slug' => 'behind-the-music',
            'description' => 'Dive deep into the stories behind your favorite songs and artists with host DJ Alex.',
            'host' => 'DJ Alex',
            'cover_image' => 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            'audio_url' => '/assets/audio/sample.mp3',
            'duration' => '45:30',
            'listen_count' => 0,
            'published_at' => now()->subDays(5),
        ]);

        Podcast::create([
            'title' => 'Sound Waves',
            'slug' => 'sound-waves',
            'description' => 'Exploring the science and psychology of sound and music with expert guests.',
            'host' => 'Dr. Elena Martinez',
            'cover_image' => 'https://images.unsplash.com/photo-1589003077984-894e133dabab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            'audio_url' => '/assets/audio/sample.mp3',
            'duration' => '60:15',
            'listen_count' => 0,
            'published_at' => now()->subDays(3),
        ]);
    }
}
