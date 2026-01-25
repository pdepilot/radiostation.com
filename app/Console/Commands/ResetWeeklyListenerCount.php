<?php

namespace App\Console\Commands;

use App\Models\AudienceMetric;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetWeeklyListenerCount extends Command
{
    protected $signature = 'listeners:reset-weekly';
    protected $description = 'Reset listener analytics when week 4 is done and we enter week 1 (start of new month)';

    public function handle()
    {
        $now = now();
        
        // Check if we're in week 1 of the current month
        // Week 1 is when we're in the first 7 days of the month
        $monthStart = $now->copy()->startOfMonth();
        $daysFromMonthStart = $now->diffInDays($monthStart);
        $isWeek1 = $daysFromMonthStart < 7;
        
        if ($isWeek1 || $now->day <= 7) {
            // We're entering week 1, so reset the previous month's weekly data
            $previousMonth = $now->copy()->subMonth();
            $previousMonthStart = $previousMonth->copy()->startOfMonth();
            $previousMonthEnd = $previousMonth->copy()->endOfMonth();
            
            $this->info("🔄 Week 4 completed, entering Week 1 of {$now->format('F Y')}");
            $this->info("   Resetting weekly analytics from previous month: {$previousMonth->format('F Y')}");
            
            // Delete all audience metrics from the previous month (week 4 and all previous weeks)
            $deletedCount = AudienceMetric::whereBetween('captured_for', [
                $previousMonthStart->format('Y-m-d'),
                $previousMonthEnd->format('Y-m-d')
            ])->delete();
            
            if ($deletedCount > 0) {
                $this->info("✅ Weekly reset: Deleted {$deletedCount} audience metric records from previous month ({$previousMonth->format('F Y')})");
            } else {
                $this->info("ℹ️  No audience metrics to reset from previous month ({$previousMonth->format('F Y')})");
            }
            
            // Also reset live stream listener counts when entering week 1
            $liveStreams = \App\Models\LiveStream::where('status', 'live')->get();
            $resetCount = 0;
            
            foreach ($liveStreams as $liveStream) {
                $oldCount = $liveStream->listener_count;
                if ($oldCount > 0) {
                    $liveStream->update([
                        'listener_count' => 0,
                        'last_reset_at' => now(),
                    ]);
                    $resetCount++;
                    $this->info("✅ Reset listener count for '{$liveStream->title}' from {$oldCount} to 0");
                }
            }
            
            if ($liveStreams->isEmpty()) {
                $this->info("ℹ️  No active live streams to reset");
            } elseif ($resetCount === 0) {
                $this->info("ℹ️  All live streams already have 0 listeners");
            }
            
            $this->info("✅ Weekly reset completed successfully!");
        } else {
            $currentWeek = ceil($now->day / 7);
            $this->info("ℹ️  Currently in Week {$currentWeek} of {$now->format('F Y')}");
            $this->info("   Weekly reset will run automatically when Week 1 begins (1st of next month).");
        }
        
        return 0;
    }
}

