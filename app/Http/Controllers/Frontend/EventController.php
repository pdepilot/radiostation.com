<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        try {
            // Check if events table exists
            if (!\Schema::hasTable('events')) {
                return view('frontend.events.index', [
                    'upcomingEvents' => collect(),
                    'pastEvents' => collect(),
                ]);
            }

            $upcomingEvents = Event::where('status', 'upcoming')
                ->where('event_date', '>=', now())
                ->orderBy('event_date')
                ->get();

            $pastEvents = Event::where(function($query) {
                    $query->where('status', 'past')
                        ->orWhere(function($q) {
                            $q->where('status', 'upcoming')
                              ->where('event_date', '<', now());
                        });
                })
                ->orderByDesc('event_date')
                ->get();
        } catch (\Exception $e) {
            // If table doesn't exist or any error, return empty collections
            \Log::error('Events page error: ' . $e->getMessage());
            $upcomingEvents = collect();
            $pastEvents = collect();
        }

        return view('frontend.events.index', [
            'upcomingEvents' => $upcomingEvents,
            'pastEvents' => $pastEvents,
        ]);
    }

    public function show(Event $event): View
    {
        // Track view (increment view count only once per session)
        if (!session()->has('viewed_event_' . $event->id)) {
            $event->increment('view_count');
            session()->put('viewed_event_' . $event->id, true);
        }
        
        $event->refresh();

        return view('frontend.events.show', [
            'event' => $event,
            'related' => Event::where('id', '!=', $event->id)
                ->where('status', 'upcoming')
                ->orderBy('event_date')
                ->take(3)
                ->get(),
        ]);
    }
}
