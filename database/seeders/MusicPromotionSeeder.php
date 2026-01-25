<?php

namespace Database\Seeders;

use App\Models\MusicPromotion;
use App\Models\PromotionPayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MusicPromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Dummy Promotion 1
        $promotion1 = MusicPromotion::create([
            'user_id' => null,
            'artist_name' => 'Ace Beatz',
            'track_title' => 'Owerri Vibes',
            'description' => 'A fresh afrobeats track celebrating the vibrant culture of Owerri. Perfect for your daily playlist!',
            'audio_embed_url' => 'https://soundcloud.com/acebeatz/owerri-vibes',
            'cover_image' => null, // You can add a cover image path if needed
            'cta_url' => 'https://open.spotify.com/track/example1',
            'duration_days' => 14,
            'price_paid' => 9000.00,
            'starts_at' => $now->copy()->subDays(2),
            'ends_at' => $now->copy()->addDays(12),
            'status' => 'active',
            'impressions' => 1250,
            'clicks' => 89,
        ]);

        // Create payment record for promotion 1
        PromotionPayment::create([
            'music_promotion_id' => $promotion1->id,
            'paystack_reference' => 'PROMO_' . $promotion1->id . '_' . time() . '_1',
            'amount' => 9000.00,
            'currency' => 'NGN',
            'status' => 'success',
            'paystack_response' => ['status' => true, 'message' => 'Payment successful'],
        ]);

        // Dummy Promotion 2
        $promotion2 = MusicPromotion::create([
            'user_id' => null,
            'artist_name' => 'Luna Star',
            'track_title' => 'Midnight Dreams',
            'description' => 'Smooth R&B vibes perfect for late night listening. Get lost in the melody!',
            'audio_embed_url' => 'https://soundcloud.com/lunastar/midnight-dreams',
            'cover_image' => null,
            'cta_url' => 'https://open.spotify.com/track/example2',
            'duration_days' => 7,
            'price_paid' => 5000.00,
            'starts_at' => $now->copy()->subDays(1),
            'ends_at' => $now->copy()->addDays(6),
            'status' => 'active',
            'impressions' => 890,
            'clicks' => 67,
        ]);

        // Create payment record for promotion 2
        PromotionPayment::create([
            'music_promotion_id' => $promotion2->id,
            'paystack_reference' => 'PROMO_' . $promotion2->id . '_' . time() . '_2',
            'amount' => 5000.00,
            'currency' => 'NGN',
            'status' => 'success',
            'paystack_response' => ['status' => true, 'message' => 'Payment successful'],
        ]);

        $this->command->info('Created 2 dummy music promotions successfully!');
    }
}
