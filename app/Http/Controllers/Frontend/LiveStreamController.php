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
use Illuminate\Support\Facades\Broadcast;

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
            // Get the active live stream (status = 'live') or latest stream
            $liveStream = LiveStream::where('status', 'live')
                ->latest('updated_at')
                ->with(['show', 'dj'])
                ->first();

            // If no live stream, get the latest one as fallback
            if (!$liveStream) {
                $liveStream = LiveStream::latest('updated_at')
                    ->with(['show', 'dj'])
                    ->first();
            }

            // Default fallback values
            $defaultStreamUrl = 'https://phoebe.streamerr.co:7572/stream';
            $defaultTitle = '107.3 FM';

            if ($liveStream) {
                return response()->json([
                    'stream_url' => $liveStream->stream_url ?? $defaultStreamUrl,
                    'title' => $liveStream->title ?? $defaultTitle,
                    'status' => $liveStream->status ?? 'offline',
                    'show' => $liveStream->show?->title,
                    'dj' => $liveStream->dj?->stage_name,
                    'listener_count' => $liveStream->listener_count ?? 0,
                ]);
            }

            // Return defaults if no stream exists
            return response()->json([
                'stream_url' => $defaultStreamUrl,
                'title' => $defaultTitle,
                'status' => 'offline',
                'show' => null,
                'dj' => null,
                'listener_count' => 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Active stream API error: ' . $e->getMessage());
            return response()->json([
                'stream_url' => 'https://phoebe.streamerr.co:7572/stream',
                'title' => '107.3 FM',
                'status' => 'offline',
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

            // Broadcast real-time update to admin dashboard
            Broadcast::channel('listener-count-updates', function () {
                return true;
            });
            
            // Trigger Livewire update event
            event(new \App\Events\ListenerCountUpdated($liveStream->fresh()->listener_count));

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
