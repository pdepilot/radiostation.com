<?php

namespace Database\Seeders;

use App\Models\Show;
use App\Models\Dj;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShowsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $captainCosmos = Dj::where('stage_name', 'CAPTAIN COSMOS')->first();
        $soundboykiller = Dj::where('stage_name', 'SOUNDBOYKILL')->first();
        $retroQueen = Dj::where('stage_name', 'RETRO QUEEN')->first();

        if ($captainCosmos) {
            Show::create([
                'title' => 'Morning Charge',
                'slug' => 'morning-charge',
                'description' => 'Start your day with high energy music and the latest hits.',
                'dj_id' => $captainCosmos->id,
                'start_time' => '05:00:00',
                'end_time' => '09:00:00',
                'hero_image' => '/assets/images/studio.jpg',
                'formatted_days' => 'Weekdays',
            ]);
        }

        if ($soundboykiller) {
            Show::create([
                'title' => 'Afternoon Drive',
                'slug' => 'afternoon-drive',
                'description' => 'Keep the momentum going with the best afternoon tracks.',
                'dj_id' => $soundboykiller->id,
                'start_time' => '15:00:00',
                'end_time' => '19:00:00',
                'hero_image' => '/assets/images/studio.jpg',
                'formatted_days' => 'Weekdays',
            ]);
        }

        if ($retroQueen) {
            Show::create([
                'title' => 'Night Beats',
                'slug' => 'night-beats',
                'description' => 'Classic hits and retro vibes for your evening entertainment.',
                'dj_id' => $retroQueen->id,
                'start_time' => '22:00:00',
                'end_time' => '02:00:00',
                'hero_image' => '/assets/images/studio.jpg',
                'formatted_days' => 'Fri - Sun',
            ]);
        }
    }
}
