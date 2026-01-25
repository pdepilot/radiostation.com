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

        // Use the same logic as getCurrentActiveShow() to find active shows
        $activeShow = Show::getCurrentActiveShow();

        // Mark all shows that are past their end time as completed
        // Only check shows that haven't been cancelled or already completed
        $allShows = Show::where(function($query) use ($currentDay) {
            $query->where('day_of_week', $currentDay)
                  ->orWhere('day_of_week', 'like', "%{$currentDay}%");
        })
        ->whereNotNull('start_time')
        ->whereNotNull('end_time')
        ->where('status', '!=', 'cancelled')
        ->where('status', '!=', 'completed')
        ->get();

        foreach ($allShows as $show) {
            $startTime = $show->start_time ? (is_string($show->start_time) ? $show->start_time : $show->start_time->format('H:i:s')) : null;
            $endTime = $show->end_time ? (is_string($show->end_time) ? $show->end_time : $show->end_time->format('H:i:s')) : null;

            if (!$startTime || !$endTime) {
                continue;
            }

            // Check if show has passed its end time (handle midnight spanning)
            // CRITICAL: Only mark as completed if:
            // 1. The show status is 'live' (it was actually running)
            // 2. The current time has CONFIRMED passed the end time
            // Never mark as completed if show is 'scheduled' (hasn't started yet)
            
            $shouldMarkAsCompleted = false;
            
            // Only process shows that are currently 'live' (were actually running)
            if ($show->status !== 'live') {
                // Show is not live, so it hasn't started or was already completed/cancelled
                // Skip completion check
                continue;
            }
            
            if ($startTime > $endTime) {
                // Show spans midnight (e.g., 15:00 - 05:55 means 3:00 PM to 5:55 AM next day)
                // For shows that span midnight, the end time is on the NEXT day
                // 
                // Logic for completion:
                // - Show runs from start_time (today) to end_time (tomorrow)
                // - Dead zone: end_time < current_time < start_time
                // - Only mark as completed if we're in the dead zone AND show was running (status = 'live')
                // - This means yesterday's show run has ended
                
                $isInDeadZone = ($currentTime > $endTime && $currentTime < $startTime);
                
                if ($isInDeadZone) {
                    // We're in the dead zone (after end time, before start time)
                    // This confirms the show has ended
                    $shouldMarkAsCompleted = true;
                }
            } else {
                // Normal show (same day, e.g., 14:00 - 18:00)
                // Mark as completed ONLY if:
                // 1. Current time is STRICTLY AFTER end time (confirmed past end)
                // 2. Show status is 'live' (was running)
                if ($currentTime > $endTime) {
                    // Confirmed: current time has passed the end time
                    $shouldMarkAsCompleted = true;
                }
            }

            // Only mark as completed if we've confirmed end time has been reached
            if ($shouldMarkAsCompleted) {
                $show->update([
                    'status' => 'completed',
                    'is_live' => false,
                ]);
                $this->info("✅ Marked show as completed: {$show->title} (end time confirmed: {$endTime})");
            }
        }

        // Update or create livestream for active show
        if ($activeShow) {
            // Use show's stream_url or default
            $streamUrl = $activeShow->stream_url ?? 'https://phoebe.streamerr.co:7572/stream';
            
            // First, try to find an existing live stream for this specific show
            $liveStream = LiveStream::where('show_id', $activeShow->id)
                ->where('status', 'live')
                ->first();

            // If no stream for this show, find any existing live stream (we'll update it)
            if (!$liveStream) {
                $liveStream = LiveStream::where('status', 'live')->first();
            }

            if ($liveStream) {
                // Update existing livestream - always update to the current active show
                // This ensures that when a new show starts, it takes over the live stream
                $liveStream->update([
                    'show_id' => $activeShow->id,
                    'dj_id' => $activeShow->dj_id,
                    'title' => $activeShow->title,
                    'slug' => Str::slug($activeShow->title . '-' . now()->timestamp),
                    'description' => $activeShow->description ?? $activeShow->tagline ?? '',
                    'status' => 'live',
                    'stream_url' => $streamUrl,
                    'started_at' => $liveStream->started_at ?? now(),
                    'updated_at' => now(),
                ]);

                $this->info("✅ Updated livestream to: {$activeShow->title}");
            } else {
                // Create new livestream if none exists
                LiveStream::create([
                    'show_id' => $activeShow->id,
                    'dj_id' => $activeShow->dj_id,
                    'title' => $activeShow->title,
                    'slug' => Str::slug($activeShow->title . '-' . now()->timestamp),
                    'description' => $activeShow->description ?? $activeShow->tagline ?? '',
                    'status' => 'live',
                    'stream_url' => $streamUrl,
                    'started_at' => now(),
                ]);

                $this->info("✅ Created livestream: {$activeShow->title}");
            }

            // Mark show as live and update status
            $activeShow->update([
                'is_live' => true,
                'status' => 'live',
            ]);

            $this->info("✅ Marked show as live: {$activeShow->title}");
            
            // Set any other live streams (like default "Darling FM Live") to offline since a show is now live
            $otherLiveStreams = LiveStream::where('status', 'live')
                ->where('id', '!=', $liveStream->id)
                ->get();
            
            foreach ($otherLiveStreams as $stream) {
                // If it's the default "Darling FM Live" stream, set it offline
                if ($stream->title === 'Darling FM Live' && !$stream->show_id) {
                    $stream->update([
                        'status' => 'offline',
                        'ended_at' => now(),
                    ]);
                    $this->info("ℹ️  Set default livestream to offline: {$stream->title}");
                } elseif (!$stream->show || $stream->show->status !== 'live') {
                    // Set any other streams without live shows to offline
                    $stream->update([
                        'status' => 'offline',
                        'ended_at' => now(),
                    ]);
                    $this->info("ℹ️  Set livestream to offline: {$stream->title}");
                }
            }
        } else {
            // No active show - mark all shows as not live (except completed ones)
            Show::where('is_live', true)->where('status', '!=', 'completed')->update(['is_live' => false]);
            Show::where('status', 'live')->where('is_live', false)->update(['status' => 'scheduled']);

            // Find or create default "Darling FM Live" stream
            $defaultStream = LiveStream::where('title', 'Darling FM Live')
                ->whereNull('show_id')
                ->first();

            // Set any show-specific livestreams to offline
            $showStreams = LiveStream::where('status', 'live')
                ->whereNotNull('show_id')
                ->get();
            
            foreach ($showStreams as $stream) {
                $stream->update([
                    'status' => 'offline',
                    'ended_at' => now(),
                ]);
                $this->info("ℹ️  Set show livestream to offline: {$stream->title}");
            }
            
            // Create or reactivate default "Darling FM Live" stream
            if ($defaultStream) {
                if ($defaultStream->status !== 'live') {
                    $defaultStream->update([
                        'status' => 'live',
                        'started_at' => now(),
                        'ended_at' => null,
                        'updated_at' => now(),
                    ]);
                    $this->info("✅ Reactivated default livestream: Darling FM Live");
                } else {
                    // Just update timestamp
                    $defaultStream->update(['updated_at' => now()]);
                }
            } else {
                LiveStream::create([
                    'title' => 'Darling FM Live',
                    'slug' => Str::slug('darling-fm-live-' . now()->timestamp),
                    'description' => 'Live radio streaming from Darling FM',
                    'status' => 'live',
                    'stream_url' => 'https://phoebe.streamerr.co:7572/stream',
                    'started_at' => now(),
                ]);
                $this->info("✅ Created default livestream: Darling FM Live");
            }
        }

        return 0;
    }
}

