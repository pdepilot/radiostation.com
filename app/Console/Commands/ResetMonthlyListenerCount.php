<?php

namespace App\Console\Commands;

use App\Models\LiveStream;
use App\Models\AudienceMetric;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetMonthlyListenerCount extends Command
{
    protected $signature = 'listeners:reset-monthly';
    protected $description = 'Reset listener analytics monthly - runs after December when January begins';

    public function handle()
    {
        $now = now();
        
        // Check if we're in January (after December)
        if ($now->month === 1) {
            // We're in January, so reset the previous year's data (December and all previous months)
            $previousYear = $now->copy()->subYear();
            $previousYearStart = Carbon::create($previousYear->year, 1, 1)->startOfYear();
            $previousYearEnd = Carbon::create($previousYear->year, 12, 31)->endOfYear();
            
            $this->info("🔄 December completed, entering January {$now->year}");
            $this->info("   Resetting monthly analytics from previous year: {$previousYear->year}");
            
            // Delete all audience metrics from the previous year
            $deletedCount = AudienceMetric::whereBetween('captured_for', [
                $previousYearStart->format('Y-m-d'),
                $previousYearEnd->format('Y-m-d')
            ])->delete();
            
            if ($deletedCount > 0) {
                $this->info("✅ Monthly reset: Deleted {$deletedCount} audience metric records from previous year ({$previousYear->year})");
            } else {
                $this->info("ℹ️  No audience metrics to reset from previous year ({$previousYear->year})");
            }
            
            // Also reset live stream listener counts when entering January
            $liveStreams = LiveStream::where('status', 'live')->get();
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
            
            $this->info("✅ Monthly reset completed successfully!");
        } else {
            $this->info("ℹ️  Currently in {$now->format('F Y')}");
            $this->info("   Monthly reset will run automatically when January begins (1st of January).");
        }
        
        return 0;
    }
}

