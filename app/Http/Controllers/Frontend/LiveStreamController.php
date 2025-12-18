<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use App\Models\Show;
use App\Models\PlaylistTrack;
use App\Models\AudienceMetric;
use App\Models\ListenerSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LiveStreamController extends Controller
{
    public function index()
    {
        try {
            $liveStream = LiveStream::with(['show', 'dj'])->latest('updated_at')->first();

            return view('frontend.livestream', [
                'liveStream' => $liveStream,
                'playlistTracks' => PlaylistTrack::where(function($query) {
                        $query->whereNull('sponsor_end_date')
                            ->orWhere('sponsor_end_date', '>=', now());
                    })
                    ->orderByRaw('CASE WHEN spot_position IS NOT NULL THEN spot_position ELSE 999 END')
                    ->orderByDesc('scheduled_for')
                    ->orderByDesc('is_featured')
                    ->take(20)
                    ->get(),
                'history' => LiveStream::latest('created_at')->take(5)->get(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Live stream page error: ' . $e->getMessage());
            return view('frontend.livestream', [
                'liveStream' => null,
                'playlistTracks' => collect(),
                'history' => collect(),
            ]);
        }
    }

    public function getListenerCount()
    {
        try {
            $liveStream = LiveStream::where('status', 'live')->latest('updated_at')->first();
            
            if ($liveStream && $liveStream->listener_count) {
                return response()->json([
                    'count' => $liveStream->listener_count,
                    'status' => 'live'
                ]);
            }
            
            // Fallback: calculate from audience metrics if available
            $currentListeners = \App\Models\AudienceMetric::whereDate('captured_for', today())
                ->latest('captured_for')
                ->value('peak_listeners') ?? 0;
            
            return response()->json([
                'count' => $currentListeners > 0 ? $currentListeners : 0, // Real data only
                'status' => $liveStream ? $liveStream->status : 'offline'
            ]);
        } catch (\Exception $e) {
            \Log::error('Listener count API error: ' . $e->getMessage());
            return response()->json([
                'count' => 0, // Real data only - no dummy data
                'status' => 'offline'
            ]);
        }
    }

    public function getActiveStream()
    {
        try {
            // Check for currently scheduled show (based on day/time) FIRST
            // This ensures immediate update when show starts, even if command hasn't run yet
            $currentShow = Show::getCurrentActiveShow();
            
            // Default fallback values
            $defaultStreamUrl = 'https://phoebe.streamerr.co:7572/stream';
            $defaultTitle = '107.3 FM';
            
            // Priority 1: If we have a current show (based on time), use it if not completed
            // This ensures immediate update when show starts, even if command hasn't run yet
            if ($currentShow && $currentShow->status !== 'completed') {
                // Double-check if show has actually reached its start time and hasn't passed end time
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
                
                $isWithinTime = false;
                if ($startTime && $endTime) {
                    if ($startTime > $endTime) {
                        // Show spans midnight (e.g., 22:00 - 02:00)
                        $isWithinTime = ($currentTime >= $startTime || $currentTime <= $endTime);
                    } else {
                        // Normal show - must be after start AND before or equal to end
                        $isWithinTime = ($currentTime >= $startTime && $currentTime <= $endTime);
                    }
                }
                
                // If show is within its scheduled time and not completed, use it
                if ($isWithinTime) {
                    return response()->json([
                        'stream_url' => $currentShow->stream_url ?? $defaultStreamUrl,
                        'title' => $currentShow->title ?? $defaultTitle,
                        'status' => 'live',
                        'show' => $currentShow->title,
                        'dj' => $currentShow->dj?->stage_name,
                        'listener_count' => $currentShow->listener_count ?? 0,
                    ], 200, [], JSON_UNESCAPED_UNICODE);
                }
            }

            // Priority 2: Get the active live stream with status = 'live' and a live show (not completed)
            $liveStream = LiveStream::where('status', 'live')
                ->whereHas('show', function($query) {
                    $query->where('status', 'live')
                          ->where('status', '!=', 'completed');
                })
                ->latest('updated_at')
                ->with(['show', 'dj'])
                ->first();

            // Priority 3: Get any live stream with status = 'live' (not from completed show)
            if (!$liveStream) {
                $liveStream = LiveStream::where('status', 'live')
                    ->whereDoesntHave('show', function($query) {
                        $query->where('status', 'completed');
                    })
                    ->latest('updated_at')
                    ->with(['show', 'dj'])
                    ->first();
            }

            // If we have a live stream with a live show (not completed), use it
            if ($liveStream && $liveStream->show && $liveStream->show->status === 'live' && $liveStream->show->status !== 'completed') {
                return response()->json([
                    'stream_url' => $liveStream->stream_url ?? $defaultStreamUrl,
                    'title' => $liveStream->title ?? $defaultTitle,
                    'status' => 'live',
                    'show' => $liveStream->show->title,
                    'dj' => $liveStream->dj?->stage_name ?? $liveStream->show->dj?->stage_name,
                    'listener_count' => $liveStream->listener_count ?? 0,
                ]);
            }

            // Priority 4: Check if live stream is the default "Darling FM Live" stream
            if ($liveStream && $liveStream->status === 'live' && $liveStream->title === 'Darling FM Live' && !$liveStream->show_id) {
                return response()->json([
                    'stream_url' => $liveStream->stream_url ?? $defaultStreamUrl,
                    'title' => 'Darling FM Live',
                    'status' => 'live',
                    'show' => null,
                    'dj' => null,
                    'listener_count' => $liveStream->listener_count ?? 0,
                ]);
            }

            // Default to "Darling FM Live" when no active show
            return response()->json([
                'stream_url' => $defaultStreamUrl,
                'title' => 'Darling FM Live',
                'status' => 'live',
                'show' => null,
                'dj' => null,
                'listener_count' => $liveStream->listener_count ?? 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Active stream API error: ' . $e->getMessage());
            return response()->json([
                'stream_url' => 'https://phoebe.streamerr.co:7572/stream',
                'title' => 'Darling FM Live',
                'status' => 'live',
                'show' => null,
                'dj' => null,
                'listener_count' => 0,
            ]);
        }
    }

    public function trackListener(Request $request)
    {
        try {
            $action = $request->input('action'); // 'start' or 'stop'
            $sessionId = $request->input('session_id'); // Browser session ID
            $userId = auth()->id(); // Authenticated user ID if available

            // Validate action
            if (!in_array($action, ['start', 'stop'])) {
                return response()->json(['success' => false, 'message' => 'Invalid action'], 400);
            }

            // Validate session_id
            if (!$sessionId) {
                return response()->json(['success' => false, 'message' => 'Session ID required'], 400);
            }

            // Find the active live stream
            $liveStream = LiveStream::where('status', 'live')->first();

            if (!$liveStream) {
                // Create a live stream if none exists
                $liveStream = LiveStream::create([
                    'title' => 'Darling FM Live',
                    'slug' => 'darling-fm-live-' . now()->timestamp,
                    'description' => 'Live radio stream',
                    'status' => 'live',
                    'stream_url' => 'https://phoebe.streamerr.co:7572/stream',
                    'listener_count' => 0,
                    'server_host' => 'phoebe.streamerr.co',
                    'bitrate' => 192,
                    'started_at' => now(),
                ]);
            }

            if ($action === 'start') {
                // Check if this session already exists (active or inactive)
                $existingSession = ListenerSession::where('session_id', $sessionId)
                    ->where('live_stream_id', $liveStream->id)
                    ->first();

                if (!$existingSession) {
                    // Create new session - this is a new listener
                    ListenerSession::create([
                        'session_id' => $sessionId,
                        'live_stream_id' => $liveStream->id,
                        'user_id' => $userId,
                        'ip_address' => $request->ip(),
                        'started_at' => now(),
                        'last_activity_at' => now(),
                        'is_active' => true,
                    ]);

                    // Increment listener count only if this is a new session
                    $liveStream->increment('listener_count');
                    
                    // Increment total listening sessions for today
                    $this->incrementDailyListeningSession();
                } else {
                    // Session exists - check if it's inactive (user paused and is resuming)
                    if (!$existingSession->is_active) {
                        // Reactivate the session (user resumed listening)
                        $existingSession->update([
                            'is_active' => true,
                            'last_activity_at' => now()
                        ]);
                        
                        // Increment listener count (they were counted before, but paused)
                        $liveStream->increment('listener_count');
                    } else {
                        // Session is already active, just update last activity
                        $existingSession->update(['last_activity_at' => now()]);
                    }
                }
            } elseif ($action === 'stop') {
                // Mark session as inactive
                $session = ListenerSession::where('session_id', $sessionId)
                    ->where('live_stream_id', $liveStream->id)
                    ->where('is_active', true)
                    ->first();

                if ($session) {
                    $session->update(['is_active' => false]);
                    
                    // Decrement listener count
                    $liveStream->decrement('listener_count');
                    
                    // Ensure it doesn't go below 0
                    if ($liveStream->listener_count < 0) {
                        $liveStream->update(['listener_count' => 0]);
                    }
                }
            }

            // Record in audience metrics for historical tracking
            $this->recordAudienceMetric($liveStream->fresh()->listener_count);

            return response()->json([
                'success' => true,
                'count' => $liveStream->fresh()->listener_count
            ]);

        } catch (\Exception $e) {
            \Log::error('Listener tracking error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Tracking failed'], 500);
        }
    }

    public function resetListenerCount(Request $request)
    {
        try {
            $liveStream = LiveStream::where('status', 'live')->first();

            if (!$liveStream) {
                return response()->json(['success' => false, 'message' => 'No active live stream'], 404);
            }

            $oldCount = $liveStream->listener_count;
            $liveStream->update(['listener_count' => 0]);

            \Log::info("🔄 Listener count reset from {$oldCount} to 0");

            return response()->json([
                'success' => true,
                'message' => 'Listener count reset to 0',
                'old_count' => $oldCount,
                'new_count' => 0
            ]);
        } catch (\Exception $e) {
            \Log::error('Reset listener count error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Reset failed'], 500);
        }
    }

    private function recordAudienceMetric($currentListeners)
    {
        try {
            // Update or create today's audience metric
            AudienceMetric::updateOrCreate(
                ['captured_for' => now()->toDateString()],
                [
                    'peak_listeners' => max($currentListeners, AudienceMetric::whereDate('captured_for', today())->value('peak_listeners') ?? 0),
                    'average_listeners' => $currentListeners,
                    'total_listening_time' => AudienceMetric::whereDate('captured_for', today())->value('total_listening_time') ?? 0,
                    'total_listening_sessions' => AudienceMetric::whereDate('captured_for', today())->value('total_listening_sessions') ?? 0,
                    'unique_listeners' => AudienceMetric::whereDate('captured_for', today())->value('unique_listeners') ?? 0
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Audience metric recording error: ' . $e->getMessage());
        }
    }

    private function incrementDailyListeningSession()
    {
        try {
            // Increment total listening sessions for today
            AudienceMetric::updateOrCreate(
                ['captured_for' => now()->toDateString()],
                [
                    'total_listening_sessions' => \DB::raw('total_listening_sessions + 1'),
                    'unique_listeners' => \DB::raw('unique_listeners + 1') // For now, assume each session is a unique listener
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Increment daily listening session error: ' . $e->getMessage());
        }
    }
}
