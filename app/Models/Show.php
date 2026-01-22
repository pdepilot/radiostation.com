<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Show extends Model
{
    use HasFactory;
    protected $fillable = [
        'dj_id',
        'title',
        'slug',
        'tagline',
        'description',
        'genre',
        'day_of_week',
        'start_time',
        'end_time',
        'hero_image',
        'is_live',
        'sponsor',
        'listener_count',
        'stream_url',
        'status',
    ];

    protected $casts = [
        'is_live' => 'boolean',
    ];

    public function dj()
    {
        return $this->belongsTo(Dj::class);
    }

    public function liveStreams()
    {
        return $this->hasMany(LiveStream::class);
    }

    /**
     * Get the currently active show based on current day and time
     */
    public static function getCurrentActiveShow()
    {
        $now = Carbon::now();
        $currentDay = strtolower($now->format('l')); // e.g., 'monday', 'tuesday'
        $currentDayFull = $now->format('l'); // e.g., 'Monday'
        $currentDayShort = strtolower(substr($currentDayFull, 0, 3)); // e.g., 'mon'
        $currentTime = $now->format('H:i:s');

        // Determine if it's a weekday or weekend
        $isWeekday = in_array($currentDay, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
        $isWeekend = in_array($currentDay, ['saturday', 'sunday']);

        // Get all shows for current day (exclude cancelled and completed)
        // Match exact day name (case-insensitive), short day name, or keywords like "weekdays"/"weekends"
        $shows = static::where(function ($query) use ($currentDay, $currentDayFull, $currentDayShort, $isWeekday, $isWeekend) {
            // Match exact day name (case-insensitive)
            $query->whereRaw('LOWER(day_of_week) = ?', [$currentDay])
                // Match day name within string (case-insensitive)
                ->orWhereRaw('LOWER(day_of_week) LIKE ?', ["%{$currentDay}%"])
                // Match short day name (e.g., "wed" for "wednesday")
                ->orWhereRaw('LOWER(day_of_week) LIKE ?', ["%{$currentDayShort}%"])
                // Match full day name
                ->orWhereRaw('LOWER(day_of_week) LIKE ?', ["%" . strtolower($currentDayFull) . "%"]);

            // Match "weekdays" if it's a weekday (Monday-Friday)
            if ($isWeekday) {
                $query->orWhereRaw('LOWER(day_of_week) LIKE ?', ['%weekday%'])
                    ->orWhereRaw('LOWER(day_of_week) LIKE ?', ['%weekdays%'])
                    ->orWhereRaw('LOWER(day_of_week) LIKE ?', ['%week days%'])
                    ->orWhereRaw('LOWER(day_of_week) LIKE ?', ['%week-days%']);
            }

            // Match "weekends" if it's a weekend (Saturday-Sunday)
            if ($isWeekend) {
                $query->orWhereRaw('LOWER(day_of_week) LIKE ?', ['%weekend%'])
                    ->orWhereRaw('LOWER(day_of_week) LIKE ?', ['%weekends%'])
                    ->orWhereRaw('LOWER(day_of_week) LIKE ?', ['%week ends%'])
                    ->orWhereRaw('LOWER(day_of_week) LIKE ?', ['%week-ends%']);
            }
        })
            ->whereNotNull('start_time')
            ->whereNotNull('end_time')
            ->where('status', '!=', 'cancelled')
            ->with('dj')
            ->orderBy('start_time')
            ->get();

        // Find all shows that match current time, then pick the one that started most recently
        $activeShows = [];
        foreach ($shows as $show) {
            // start_time and end_time are TIME type, so they're strings like "14:00:00"
            $startTime = $show->start_time ? (is_string($show->start_time) ? $show->start_time : $show->start_time->format('H:i:s')) : null;
            $endTime = $show->end_time ? (is_string($show->end_time) ? $show->end_time : $show->end_time->format('H:i:s')) : null;

            if (!$startTime || !$endTime) {
                continue;
            }

            $isActive = false;
            // Handle shows that span midnight (e.g., 15:00 - 05:55 means 3:00 PM to 5:55 AM next day)
            if ($startTime > $endTime) {
                // Show spans midnight - end time is on the NEXT day
                // Active if:
                // 1. Current time >= start_time: Show started today, running until end_time tomorrow
                // 2. Current time <= end_time: Show started yesterday, still running until end_time today
                // NOT active if: end_time < current_time < start_time (dead zone between yesterday's end and today's start)

                if ($currentTime >= $startTime) {
                    // Show has started today - active until end_time tomorrow
                    $isActive = true;
                } elseif ($currentTime <= $endTime) {
                    // Current time is <= end_time (e.g., 05:00 when end is 05:55)
                    // This means show started yesterday and is still running today
                    $isActive = true;
                }
                // If currentTime > endTime AND currentTime < startTime, we're in the dead zone
                // (e.g., 06:00 when show is 15:00-05:55) - show is NOT active
            } else {
                // Normal show (same day) - active if current time is between start and end (inclusive)
                // e.g., 14:00-18:00: active if current time is between 14:00 and 18:00
                $isActive = ($currentTime >= $startTime && $currentTime <= $endTime);
            }

            if ($isActive) {
                // If show is marked as completed but is still within its time range, unmark it as completed
                // This handles cases where shows were incorrectly marked as completed
                if ($show->status === 'completed') {
                    $show->update(['status' => 'scheduled']);
                }

                // If show is scheduled but has reached its start time, mark it as live
                // This ensures immediate detection even if command hasn't run yet
                // Note: This only affects the returned object, not the database
                // The command will still update the database status

                $activeShows[] = [
                    'show' => $show,
                    'start_time' => $startTime
                ];
            }
        }

        // If multiple shows are active, return the one that started most recently (latest start_time)
        if (count($activeShows) > 0) {
            // Sort by start_time descending to get the most recently started show
            usort($activeShows, function ($a, $b) {
                // Compare times as strings (HH:MM:SS format compares correctly as strings)
                if ($b['start_time'] == $a['start_time']) {
                    return 0;
                }
                return ($b['start_time'] > $a['start_time']) ? 1 : -1; // Descending order
            });
            return $activeShows[0]['show'];
        }

        return null;
    }

    /**
     * Get the total count of all listeners who have tuned into this show
     * This counts all listener sessions from all live streams of this show
     */
    public function getTotalListenersCountAttribute()
    {
        $totalListeners = 0;
        foreach ($this->liveStreams as $liveStream) {
            $totalListeners += $liveStream->listenerSessions()->count();
        }
        return $totalListeners;
    }

    /**
     * Check if this show is currently live based on current day and time
     */
    public function isCurrentlyLive(): bool
    {
        $now = Carbon::now();
        $currentDay = strtolower($now->format('l')); // e.g., 'monday', 'tuesday'
        $currentDayFull = $now->format('l'); // e.g., 'Monday'
        $currentDayShort = strtolower(substr($currentDayFull, 0, 3)); // e.g., 'mon'
        $currentTime = $now->format('H:i:s');

        // Check if show is cancelled
        if ($this->status === 'cancelled') {
            return false;
        }

        // Check if show matches current day
        $dayOfWeek = strtolower($this->day_of_week ?? '');
        $matchesDay = false;

        // Determine if it's a weekday or weekend
        $isWeekday = in_array($currentDay, ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
        $isWeekend = in_array($currentDay, ['saturday', 'sunday']);

        // Check if day matches
        if (empty($dayOfWeek)) {
            return false; // No day specified
        }

        // Match exact day name (case-insensitive)
        if (strtolower($dayOfWeek) === $currentDay) {
            $matchesDay = true;
        }
        // Match day name within string (case-insensitive)
        elseif (str_contains(strtolower($dayOfWeek), $currentDay)) {
            $matchesDay = true;
        }
        // Match short day name (e.g., "wed" for "wednesday")
        elseif (str_contains(strtolower($dayOfWeek), $currentDayShort)) {
            $matchesDay = true;
        }
        // Match full day name
        elseif (str_contains(strtolower($dayOfWeek), strtolower($currentDayFull))) {
            $matchesDay = true;
        }
        // Match "weekdays" if it's a weekday
        elseif ($isWeekday && (
            str_contains(strtolower($dayOfWeek), 'weekday') ||
            str_contains(strtolower($dayOfWeek), 'weekdays') ||
            str_contains(strtolower($dayOfWeek), 'week days') ||
            str_contains(strtolower($dayOfWeek), 'week-days')
        )) {
            $matchesDay = true;
        }
        // Match "weekends" if it's a weekend
        elseif ($isWeekend && (
            str_contains(strtolower($dayOfWeek), 'weekend') ||
            str_contains(strtolower($dayOfWeek), 'weekends') ||
            str_contains(strtolower($dayOfWeek), 'week ends') ||
            str_contains(strtolower($dayOfWeek), 'week-ends')
        )) {
            $matchesDay = true;
        }

        if (!$matchesDay) {
            return false; // Not the right day
        }

        // Check if times are set
        if (!$this->start_time || !$this->end_time) {
            return false;
        }

        // Get times as strings
        $startTime = is_string($this->start_time) ? $this->start_time : $this->start_time->format('H:i:s');
        $endTime = is_string($this->end_time) ? $this->end_time : $this->end_time->format('H:i:s');

        // Handle shows that span midnight (e.g., 15:00 - 05:55 means 3:00 PM to 5:55 AM next day)
        if ($startTime > $endTime) {
            // Show spans midnight - end time is on the NEXT day
            // Active if:
            // 1. Current time >= start_time: Show started today, running until end_time tomorrow
            // 2. Current time <= end_time: Show started yesterday, still running until end_time today
            if ($currentTime >= $startTime) {
                return true; // Show has started today
            } elseif ($currentTime <= $endTime) {
                return true; // Show started yesterday and is still running
            }
            return false; // In the dead zone between yesterday's end and today's start
        } else {
            // Normal show (same day) - active if current time is between start and end (inclusive)
            return ($currentTime >= $startTime && $currentTime <= $endTime);
        }
    }

    /**
     * Get the full URL for the hero image
     * Use this in frontend views to display images properly
     */
    public function getHeroImageUrlAttribute()
    {
        // Get raw value from database (bypass any accessor)
        $value = $this->getRawOriginal('hero_image') ?? $this->attributes['hero_image'] ?? null;

        if (!$value) {
            return asset('assets/images/studio.jpg'); // Fallback image
        }

        // If it's already a full URL, return as-is
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        // Handle public assets paths (e.g., /assets/images/...)
        if (str_starts_with($value, '/assets/') || str_starts_with($value, 'assets/')) {
            return asset(ltrim($value, '/'));
        }

        // For storage paths, construct the URL manually
        // Storage paths are relative to storage/app/public
        // So we need to use asset('storage/...')
        $imagePath = $value;

        // Remove 'storage/' prefix if present (it will be added by asset())
        if (str_starts_with($imagePath, 'storage/')) {
            $imagePath = substr($imagePath, 8);
        }
        if (str_starts_with($imagePath, '/storage/')) {
            $imagePath = substr($imagePath, 9);
        }

        // Use asset() to generate the URL
        return asset('storage/' . ltrim($imagePath, '/'));
    }
}
