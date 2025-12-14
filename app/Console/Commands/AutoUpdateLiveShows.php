<?php

namespace App\Console\Commands;

use App\Models\Show;
use App\Models\LiveStream;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AutoUpdateLiveShows extends Command
{
    protected $signature = 'shows:auto-update';
    protected $description = 'Automatically update livestream based on show schedule';

    public function handle()
    {
        $now = Carbon::now();
        $currentDay = strtolower($now->format('l')); // monday, tuesday, etc.
        $currentTime = $now->format('H:i:s');

        // Find shows that should be live based on schedule
        $scheduledShows = Show::where('day_of_week', $currentDay)
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->whereNotNull('stream_url')
            ->get();

        $activeShow = null;

        foreach ($scheduledShows as $show) {
            $startTime = Carbon::parse($show->start_time)->format('H:i:s');
            $endTime = Carbon::parse($show->end_time)->format('H:i:s');

            // Check if current time is within show time range
            if ($currentTime >= $startTime && $currentTime <= $endTime) {
                $activeShow = $show;
                break;
            }
        }

        // Update or create livestream
        if ($activeShow) {
            $liveStream = LiveStream::where('status', 'live')->first();

            if ($liveStream) {
                // Update existing livestream
                $liveStream->update([
                    'show_id' => $activeShow->id,
                    'dj_id' => $activeShow->dj_id,
                    'title' => $activeShow->title,
                    'slug' => Str::slug($activeShow->title . '-' . now()->timestamp),
                    'description' => $activeShow->description ?? $activeShow->tagline,
                    'status' => 'live',
                    'stream_url' => $activeShow->stream_url,
                    'started_at' => $liveStream->started_at ?? now(),
                ]);

                $this->info("✅ Updated livestream: {$activeShow->title}");
            } else {
                // Create new livestream
                LiveStream::create([
                    'show_id' => $activeShow->id,
                    'dj_id' => $activeShow->dj_id,
                    'title' => $activeShow->title,
                    'slug' => Str::slug($activeShow->title . '-' . now()->timestamp),
                    'description' => $activeShow->description ?? $activeShow->tagline,
                    'status' => 'live',
                    'stream_url' => $activeShow->stream_url,
                    'started_at' => now(),
                ]);

                $this->info("✅ Created livestream: {$activeShow->title}");
            }

            // Mark show as live
            $activeShow->update(['is_live' => true]);
        } else {
            // No show scheduled - set all shows to not live
            Show::where('is_live', true)->update(['is_live' => false]);

            // Optionally set livestream to offline if no show is scheduled
            $liveStream = LiveStream::where('status', 'live')->first();
            if ($liveStream && !$liveStream->show) {
                $liveStream->update([
                    'status' => 'offline',
                    'ended_at' => now(),
                ]);
                $this->info("ℹ️  No active show, set livestream to offline");
            }
        }

        return 0;
    }
}

