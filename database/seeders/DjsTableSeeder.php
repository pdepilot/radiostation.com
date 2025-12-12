<?php

namespace Database\Seeders;

use App\Models\Dj;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DjsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dj::create([
            'name' => 'CAPTAIN COSMOS',
            'stage_name' => 'CAPTAIN COSMOS',
            'slug' => 'captain-cosmos',
            'avatar_url' => '/assets/images/WhatsApp Image 2025-11-12 at 15.35.31_e7adcda0.jpg',
            'bio' => 'Captain Cosmos brings energy and excitement to your mornings with the best music selection.',
            'is_featured' => 1,
        ]);

        Dj::create([
            'name' => 'Soundboykiller',
            'stage_name' => 'SOUNDBOYKILL',
            'slug' => 'soundboykiller',
            'avatar_url' => '/assets/images/WhatsApp Image 2025-11-12 at 15.35.16_d35f6e83.jpg',
            'bio' => 'Soundboykiller is a master of beats and rhythms, keeping you moving all afternoon.',
            'is_featured' => 1,
        ]);

        Dj::create([
            'name' => 'Retro Queen',
            'stage_name' => 'RETRO QUEEN',
            'slug' => 'retro-queen',
            'avatar_url' => '/assets/images/OAP1.JPG',
            'bio' => 'Retro Queen takes you on a journey through the best classic hits every weekend.',
            'is_featured' => 1,
        ]);
    }
}
