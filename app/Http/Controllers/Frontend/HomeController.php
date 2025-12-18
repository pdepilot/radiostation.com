<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Models\Podcast;
use App\Models\Dj;
use App\Models\Show;
use App\Models\LiveStream;
use App\Models\Sponsor;

class HomeController extends Controller
{
    public function index()
    {
        // Get current active show for display (time-based check)
        $currentShow = Show::getCurrentActiveShow();
        
        // Only use currentShow if it's not completed and is within time range
        if ($currentShow && $currentShow->status === 'completed') {
            $currentShow = null;
        }
        
        // Double-check time range for server-side rendering
        if ($currentShow) {
            $now = \Carbon\Carbon::now();
            $currentTime = $now->format('H:i:s');
            
            // Handle start_time - it's a TIME column, so it's already a string
            $startTime = $currentShow->start_time;
            if ($startTime && !is_string($startTime)) {
                $startTime = is_object($startTime) ? $startTime->format('H:i:s') : (string)$startTime;
            }
            
            // Handle end_time - it's a TIME column, so it's already a string
            $endTime = $currentShow->end_time;
            if ($endTime && !is_string($endTime)) {
                $endTime = is_object($endTime) ? $endTime->format('H:i:s') : (string)$endTime;
            }
            
            if ($startTime && $endTime) {
                $isWithinTime = false;
                if ($startTime > $endTime) {
                    // Show spans midnight (e.g., 15:00 - 05:55 means 3:00 PM to 5:55 AM next day)
                    // Active if current time >= start_time (started today) OR <= end_time (still running from yesterday)
                    $isWithinTime = ($currentTime >= $startTime || $currentTime <= $endTime);
                } else {
                    // Normal show (same day) - active if between start and end
                    $isWithinTime = ($currentTime >= $startTime && $currentTime <= $endTime);
                }
                
                if (!$isWithinTime) {
                    $currentShow = null;
                }
            }
        }

        // Get the latest live stream with a live show
        $liveStream = LiveStream::where('status', 'live')
            ->whereHas('show', function($query) {
                $query->where('status', 'live');
            })
            ->latest('updated_at')
            ->first();

        // If no live stream with live show, check for any live stream
        if (!$liveStream) {
            $liveStream = LiveStream::where('status', 'live')
                ->latest('updated_at')
                ->first();
        }
        
        // If live stream exists but show is not live or no show, set to null to default to "Darling FM Live"
        if ($liveStream && (!$liveStream->show || $liveStream->show->status !== 'live')) {
            // Only keep liveStream if we want to show "Darling FM Live" with listener count
            // Otherwise set to null to use default
        }
        
        return view('frontend.home', [
            'liveStream'     => $liveStream,
            'currentShow'    => $currentShow,
            'upcomingShows'  => Show::with('dj')->orderBy('start_time')->limit(6)->get(),
            'newsPosts'      => NewsPost::where('status', 'published')
                                    ->latest('published_at')
                                    ->take(3)
                                    ->get(),
            'featuredDjs'    => Dj::where('is_featured', 1)
                                    ->with('shows')
                                    ->take(4)
                                    ->get(),
            'featuredSponsors' => Sponsor::where('status', 'active')
                                    ->where('is_featured', true)
                                    ->orderBy('order')
                                    ->get(),
        ]);
    }
}