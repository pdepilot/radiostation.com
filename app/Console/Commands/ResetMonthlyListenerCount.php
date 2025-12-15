<?php

namespace App\Console\Commands;

use App\Models\LiveStream;
use Illuminate\Console\Command;

class ResetMonthlyListenerCount extends Command
{
    protected $signature = 'listeners:reset-monthly';
    protected $description = 'Reset listener count monthly for all live streams';

    public function handle()
    {
        $liveStreams = LiveStream::where('status', 'live')->get();
        
        foreach ($liveStreams as $liveStream) {
            $oldCount = $liveStream->listener_count;
            $liveStream->update([
                'listener_count' => 0,
                'last_reset_at' => now(),
            ]);
            
            $this->info("✅ Reset listener count for '{$liveStream->title}' from {$oldCount} to 0");
        }
        
        if ($liveStreams->isEmpty()) {
            $this->info("ℹ️  No active live streams to reset");
        }
        
        return 0;
    }
}

