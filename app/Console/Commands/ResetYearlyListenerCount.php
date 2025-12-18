<?php

namespace App\Console\Commands;

use App\Models\LiveStream;
use App\Models\AudienceMetric;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ResetYearlyListenerCount extends Command
{
    protected $signature = 'listeners:reset-yearly';
    protected $description = 'Reset listener analytics yearly - runs after previous year when new year begins (e.g., after 2025 when 2026 starts)';

    public function handle()
    {
        $now = now();
        $currentYear = $now->year;
        
        // Check if we're in a new year (January 1st)
        // This automatically works for any year transition (2025->2026, 2026->2027, etc.)
        if ($now->month === 1 && $now->day === 1) {
            $previousYear = $currentYear - 1;
            $previousYearStart = Carbon::create($previousYear, 1, 1)->startOfYear();
            $previousYearEnd = Carbon::create($previousYear, 12, 31)->endOfYear();
            
            $this->info("🔄 Year {$previousYear} completed, entering Year {$currentYear}");
            $this->info("   Resetting yearly analytics from previous year: {$previousYear}");
            
            // Delete all audience metrics from the previous year
            $deletedCount = AudienceMetric::whereBetween('captured_for', [
                $previousYearStart->format('Y-m-d'),
                $previousYearEnd->format('Y-m-d')
            ])->delete();
            
            if ($deletedCount > 0) {
                $this->info("✅ Yearly reset: Deleted {$deletedCount} audience metric records from year {$previousYear}");
            } else {
                $this->info("ℹ️  No audience metrics to reset from year {$previousYear}");
            }
            
            // Also reset live stream listener counts when entering new year
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
            
            $this->info("✅ Yearly reset completed successfully for year transition: {$previousYear} -> {$currentYear}!");
        } else {
            $this->info("ℹ️  Currently in {$now->format('F d, Y')}");
            $this->info("   Yearly reset will run automatically on January 1st when new year begins.");
        }
        
        return 0;
    }
}

