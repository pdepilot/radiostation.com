<?php

namespace Database\Seeders;

use App\Models\AdvertisingPackage;
use App\Models\AudienceMetric;
use App\Models\ContactMessage;
use App\Models\Dj;
use App\Models\LiveStream;
use App\Models\NewsPost;
use App\Models\RevenueRecord;
use App\Models\Show;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Darling FM Admin',
            'slug' => 'darling-fm-admin',
            'email' => 'admin@darlingfm.ng',
            'phone' => '+234 800 000 0000',
            'role' => 'admin',
            'password' => Hash::make('Password123!'),
        ]);

        $djProfiles = [
            [
                'name' => 'Cosmos Chukwuemeka Puyaka',
                'stage_name' => 'Captain Cosmos',
                'specialty' => 'Afternoon Drive',
                'avatar_url' => '/assets/images/WhatsApp Image 2025-11-12 at 15.35.31_e7adcda0.jpg',
            ],
            [
                'name' => 'DJ Xtreme',
                'stage_name' => 'SoundboyKill',
                'specialty' => 'Night Beats',
                'avatar_url' => '/assets/images/WhatsApp Image 2025-11-12 at 15.35.16_d35f6e83.jpg',
            ],
            [
                'name' => 'Chidera Ujah',
                'stage_name' => 'Retro Queen',
                'specialty' => 'Retro Rewind',
                'avatar_url' => '/assets/images/OAP1.JPG',
            ],
        ];

        $djs = collect($djProfiles)->map(function (array $profile) {
            $slug = Str::slug($profile['stage_name'] ?? $profile['name']);

            return Dj::create(array_merge([
                'slug' => $slug,
                'email' => $slug . '@darlingfm.ng',
                'stage_name' => $profile['stage_name'] ?? null,
                'instagram' => "https://instagram.com/{$slug}",
                'twitter' => "https://twitter.com/{$slug}",
                'specialty' => $profile['specialty'] ?? null,
                'bio' => 'On-air personality that keeps Owerri moving.',
                'is_featured' => true,
            ], $profile));
        });

        $shows = collect([
            [
                'title' => 'Morning Charge',
                'tagline' => 'Energy shots for the southeast',
                'genre' => 'Urban Breakfast',
                'day_of_week' => 'Weekdays',
                'start_time' => '05:00',
                'end_time' => '09:00',
                'hero_image' => '/assets/images/darling studio.jpg',
            ],
            [
                'title' => 'Afternoon Drive',
                'tagline' => 'Big stories and highlife',
                'genre' => 'Drive Time',
                'day_of_week' => 'Weekdays',
                'start_time' => '15:00',
                'end_time' => '19:00',
                'hero_image' => '/assets/images/WhatsApp Image 2025-11-12 at 11.20.54_77ff1fa8.jpg',
            ],
            [
                'title' => 'Night Beats',
                'tagline' => 'Late night Owerri club simulcast',
                'genre' => 'Club Mix',
                'day_of_week' => 'Fri - Sun',
                'start_time' => '22:00',
                'end_time' => '02:00',
                'hero_image' => '/assets/images/radio1.jpg',
            ],
        ])->map(function (array $show, int $index) use ($djs) {
            return Show::create(array_merge($show, [
                'dj_id' => $djs[$index % $djs->count()]->id,
                'slug' => Str::slug($show['title']),
                'description' => 'Programming inspired by the prototype site.',
                'sponsor' => 'Darling Communications',
                'stream_url' => 'https://stream.darlingfm.ng/live',
                'listener_count' => rand(500, 4000),
            ]));
        });

        LiveStream::create([
            'title' => 'Darling FM Live',
            'slug' => 'darling-fm-live',
            'description' => 'High fidelity live stream for Darling FM Owerri.',
            'status' => 'live',
            'stream_url' => 'https://stream.darlingfm.ng/live',
            'dj_id' => $djs->first()->id,
            'show_id' => $shows->first()->id,
            'listener_count' => 3200,
            'chat_enabled' => true,
            'server_host' => 'live.darlingfm.ng',
            'bitrate' => 192,
            'started_at' => now()->subMinutes(30),
        ]);

        SiteSetting::insert([
            ['key' => 'station_name', 'value' => 'Darling FM 107.3', 'type' => 'text'],
            ['key' => 'station_city', 'value' => 'Owerri, Imo State', 'type' => 'text'],
            ['key' => 'studio_hotline', 'value' => '+234 700 327 5464', 'type' => 'text'],
            ['key' => 'stream_url', 'value' => 'https://stream.darlingfm.ng/live', 'type' => 'text'],
            ['key' => 'whatsapp_number', 'value' => '+234 806 444 4444', 'type' => 'text'],
        ]);

        $this->call([
            NewsPostsTableSeeder::class,
            DjsTableSeeder::class,
            ShowsTableSeeder::class,
        ]);
        AdvertisingPackage::factory(4)->create();
        RevenueRecord::factory(6)->create();
        AudienceMetric::factory(10)->create();
        ContactMessage::factory(5)->create();

        // Additional DJs and shows via factories for breadth
        Dj::factory(4)->create();
        Show::factory(6)->create();
        LiveStream::factory(2)->create();
    }
}
