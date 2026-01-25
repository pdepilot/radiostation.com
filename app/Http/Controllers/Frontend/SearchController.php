<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dj;
use App\Models\Event;
use App\Models\NewsPost;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    /**
     * Unified search across OAPs, News, Events, and Shows
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));
        
        // Minimum 1 character required
        if (strlen($query) < 1) {
            return response()->json([]);
        }
        
        $results = [];
        
        // Search OAPs (DJs/Personalities)
        $djs = Dj::where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('stage_name', 'like', "%{$query}%")
              ->orWhere('bio', 'like', "%{$query}%");
        })->take(3)->get();
        
        foreach ($djs as $dj) {
            $results[] = [
                'type' => 'oap',
                'title' => $dj->stage_name ?? $dj->name,
                'subtitle' => 'On-Air Personality',
                'url' => route('djs.show', $dj->slug ?? $dj->id),
                'icon' => 'fas fa-microphone',
            ];
        }
        
        // Search News
        $news = NewsPost::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('author_name', 'like', "%{$query}%");
            })
            ->take(3)
            ->get();
        
        foreach ($news as $post) {
            $results[] = [
                'type' => 'news',
                'title' => $post->title,
                'subtitle' => optional($post->published_at)->format('M d, Y') . ($post->author_name ? ' • ' . $post->author_name : ''),
                'url' => route('news.show', $post->slug),
                'icon' => 'fas fa-newspaper',
            ];
        }
        
        // Search Events
        $events = Event::where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhere('venue', 'like', "%{$query}%");
        })->take(3)->get();
        
        foreach ($events as $event) {
            $results[] = [
                'type' => 'event',
                'title' => $event->title,
                'subtitle' => $event->event_date ? $event->event_date->format('M d, Y') . ($event->venue ? ' • ' . $event->venue : '') : 'Event',
                'url' => route('events.show', $event->slug),
                'icon' => 'fas fa-calendar-alt',
            ];
        }
        
        // Search Shows
        $shows = Show::where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        })->take(3)->get();
        
        foreach ($shows as $show) {
            $results[] = [
                'type' => 'show',
                'title' => $show->title,
                'subtitle' => $show->day_of_week . ' • ' . 
                    \Carbon\Carbon::parse($show->start_time)->format('g:i A') . ' - ' . 
                    \Carbon\Carbon::parse($show->end_time)->format('g:i A'),
                'url' => route('shows.show', $show->slug),
                'icon' => 'fas fa-broadcast-tower',
            ];
        }
        
        return response()->json($results);
    }
}
