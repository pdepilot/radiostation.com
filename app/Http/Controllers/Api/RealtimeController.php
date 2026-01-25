<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Models\Show;
use App\Models\Event;
use App\Models\LiveStream;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class RealtimeController extends Controller
{
    /**
     * Get latest content updates (polling endpoint)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function poll(Request $request): JsonResponse
    {
        try {
            $lastUpdate = $request->input('last_update', 0);
            $currentTime = time();
            
            // Validate last_update parameter - ensure it's a valid number
            if (!is_numeric($lastUpdate) || $lastUpdate < 0) {
                $lastUpdate = 0;
            }
            
            // Convert to integer to ensure type safety
            $lastUpdate = (int) $lastUpdate;
            
            // Get updates since last check
            $updates = [];
            
            // Check news updates
            $lastUpdateDate = $lastUpdate > 0 ? date('Y-m-d H:i:s', $lastUpdate) : '1970-01-01 00:00:00';
        $newsUpdates = NewsPost::where('status', 'published')
            ->where(function($q) use ($lastUpdateDate) {
                $q->where('updated_at', '>', $lastUpdateDate)
                  ->orWhere('created_at', '>', $lastUpdateDate);
            })
            ->latest('updated_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'hero_image', 'excerpt', 'published_at', 'updated_at', 'created_at']);
        
        foreach ($newsUpdates as $post) {
            $createdTs = $post->created_at->timestamp;
            $updatedTs = $post->updated_at->timestamp;
            $updates[] = [
                'type' => 'news',
                'id' => $post->id,
                'action' => $createdTs > $lastUpdate ? 'created' : 'updated',
                'data' => [
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'image' => $post->hero_image,
                    'excerpt' => $post->excerpt ?? '',
                    'date' => $post->published_at?->format('M d, Y'),
                    'url' => route('news.show', $post->slug),
                ],
                'timestamp' => max($createdTs, $updatedTs),
            ];
        }
        
        // Check show updates - handle potential missing columns gracefully
        try {
            $showUpdates = Show::where(function($q) use ($lastUpdateDate) {
                    $q->where('updated_at', '>', $lastUpdateDate)
                      ->orWhere('created_at', '>', $lastUpdateDate);
                })
                ->latest('updated_at')
                ->take(3)
                ->get(['id', 'title', 'slug', 'hero_image_url', 'updated_at', 'created_at']);
        } catch (\Exception $e) {
            \Log::warning('Show updates query failed: ' . $e->getMessage());
            $showUpdates = collect([]);
        }
        
        foreach ($showUpdates as $show) {
            $createdTs = $show->created_at->timestamp;
            $updatedTs = $show->updated_at->timestamp;
            $updates[] = [
                'type' => 'show',
                'id' => $show->id,
                'action' => $createdTs > $lastUpdate ? 'created' : 'updated',
                'data' => [
                    'title' => $show->title,
                    'slug' => $show->slug,
                    'image' => $show->hero_image_url,
                    'url' => route('shows.show', $show->slug),
                ],
                'timestamp' => max($createdTs, $updatedTs),
            ];
        }
        
        // Check event updates
        $eventUpdates = Event::where(function($q) use ($lastUpdateDate) {
                $q->where('updated_at', '>', $lastUpdateDate)
                  ->orWhere('created_at', '>', $lastUpdateDate);
            })
            ->latest('updated_at')
            ->take(3)
            ->get(['id', 'title', 'slug', 'hero_image', 'event_date', 'updated_at', 'created_at']);
        
        foreach ($eventUpdates as $event) {
            $createdTs = $event->created_at->timestamp;
            $updatedTs = $event->updated_at->timestamp;
            $updates[] = [
                'type' => 'event',
                'id' => $event->id,
                'action' => $createdTs > $lastUpdate ? 'created' : 'updated',
                'data' => [
                    'title' => $event->title,
                    'slug' => $event->slug,
                    'image' => $event->hero_image,
                    'date' => $event->event_date?->format('M d, Y'),
                    'url' => route('events.show', $event->slug),
                ],
                'timestamp' => max($createdTs, $updatedTs),
            ];
        }
        
            // Sort by timestamp
            usort($updates, fn($a, $b) => $b['timestamp'] <=> $a['timestamp']);
            
            return response()->json([
                'updates' => $updates,
                'current_time' => $currentTime,
                'has_updates' => count($updates) > 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Realtime poll error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return 200 with empty updates instead of 500 to prevent polling loops
            return response()->json([
                'error' => 'Failed to fetch updates',
                'updates' => [],
                'current_time' => time(),
                'has_updates' => false,
            ], 200);
        }
    }
    
    /**
     * Get content by type and ID (for modal loading)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getContent(Request $request): JsonResponse
    {
        $type = $request->input('type'); // 'news', 'show', 'event'
        $id = $request->input('id');
        $slug = $request->input('slug');
        
        if (!$type || (!$id && !$slug)) {
            return response()->json(['error' => 'Type and ID or slug required'], 400);
        }
        
        try {
            switch ($type) {
                case 'news':
                    $content = $slug 
                        ? NewsPost::where('slug', $slug)->where('status', 'published')->first()
                        : NewsPost::where('id', $id)->where('status', 'published')->first();
                    
                    if (!$content) {
                        return response()->json(['error' => 'News post not found'], 404);
                    }
                    
                    return response()->json([
                        'type' => 'news',
                        'data' => [
                            'id' => $content->id,
                            'title' => $content->title,
                            'slug' => $content->slug,
                            'excerpt' => $content->excerpt,
                            'body' => $content->body,
                            'image' => $content->hero_image,
                            'author' => $content->author_name,
                            'date' => $content->published_at?->format('M d, Y'),
                            'tags' => $content->tags,
                            'url' => route('news.show', $content->slug),
                        ],
                    ]);
                    
                case 'show':
                    $content = $slug 
                        ? Show::where('slug', $slug)->with('dj')->first()
                        : Show::where('id', $id)->with('dj')->first();
                    
                    if (!$content) {
                        return response()->json(['error' => 'Show not found'], 404);
                    }
                    
                    return response()->json([
                        'type' => 'show',
                        'data' => [
                            'id' => $content->id,
                            'title' => $content->title,
                            'slug' => $content->slug,
                            'description' => $content->description,
                            'image' => $content->hero_image_url,
                            'dj' => $content->dj ? [
                                'name' => $content->dj->stage_name ?? $content->dj->name,
                                'avatar' => $content->dj->avatar_url,
                            ] : null,
                            'schedule' => $content->formatted_schedule ?? null,
                            'url' => route('shows.show', $content->slug),
                        ],
                    ]);
                    
                case 'event':
                    $content = $slug 
                        ? Event::where('slug', $slug)->first()
                        : Event::where('id', $id)->first();
                    
                    if (!$content) {
                        return response()->json(['error' => 'Event not found'], 404);
                    }
                    
                    return response()->json([
                        'type' => 'event',
                        'data' => [
                            'id' => $content->id,
                            'title' => $content->title,
                            'slug' => $content->slug,
                            'description' => $content->description,
                            'image' => $content->hero_image,
                            'date' => $content->event_date?->format('F d, Y'),
                            'time' => $content->event_date?->format('g:i A'),
                            'venue' => $content->venue,
                            'location' => $content->location,
                            'ticket_url' => $content->ticket_url,
                            'url' => route('events.show', $content->slug),
                        ],
                    ]);
                    
                default:
                    return response()->json(['error' => 'Invalid content type'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load content'], 500);
        }
    }
}

