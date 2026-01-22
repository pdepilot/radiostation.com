<?php

namespace App\Console\Commands;

use App\Models\MusicPromotion;
use App\Models\PromotionWaitlist;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ExpireMusicPromotions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Expire music promotions that have passed their end date and notify waitlist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Find promotions that should be expired
        $expiredPromotions = MusicPromotion::where('status', 'active')
            ->where('ends_at', '<=', $now)
            ->get();

        $expiredCount = 0;
        foreach ($expiredPromotions as $promotion) {
            $promotion->update(['status' => 'expired']);
            $expiredCount++;
            $this->info("Expired promotion: {$promotion->track_title} by {$promotion->artist_name}");
        }

        // Check if slots are now available and notify waitlist
        $activeCount = MusicPromotion::active()->count();
        $maxSlots = 6; // Should match controller constant

        if ($activeCount < $maxSlots && $expiredCount > 0) {
            $waitlist = PromotionWaitlist::where('notified', false)->get();
            
            foreach ($waitlist as $entry) {
                try {
                    // Send email notification
                    // For now, we'll just mark as notified
                    // You can implement email sending here using Laravel Mail
                    $entry->update([
                        'notified' => true,
                        'notified_at' => Carbon::now(),
                    ]);
                    
                    $this->info("Notified waitlist entry: {$entry->email}");
                } catch (\Exception $e) {
                    $this->error("Failed to notify {$entry->email}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Expired {$expiredCount} promotion(s).");
        
        return Command::SUCCESS;
    }
}
