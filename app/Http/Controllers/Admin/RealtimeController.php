<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use App\Models\ContactMessage;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RealtimeController extends Controller
{
    /**
     * Server-Sent Events endpoint for real-time dashboard updates
     */
    public function stream(Request $request)
    {
        return response()->stream(function () {
            $lastId = 0;
            
            while (true) {
                // Check if client is still connected
                if (connection_aborted()) {
                    break;
                }

                // Get latest data
                $liveStream = LiveStream::where('status', 'live')->first();
                $newMessages = ContactMessage::where('status', 'new')->count();
                $liveShows = Show::where('is_live', true)->count();
                
                // Create event data
                $data = [
                    'timestamp' => now()->toIso8601String(),
                    'listeners' => $liveStream ? $liveStream->listener_count : 0,
                    'new_messages' => $newMessages,
                    'live_shows' => $liveShows,
                    'stream_status' => $liveStream ? $liveStream->status : 'offline',
                ];
                
                // Send SSE event
                echo "id: " . ++$lastId . "\n";
                echo "event: update\n";
                echo "data: " . json_encode($data) . "\n\n";
                
                // Flush output
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
                
                // Wait 5 seconds before next update
                sleep(5);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }
    
    /**
     * API endpoint for polling-based real-time updates
     */
    public function poll(Request $request)
    {
        $liveStream = LiveStream::where('status', 'live')->first();
        $newMessages = ContactMessage::where('status', 'new')->count();
        $liveShows = Show::where('is_live', true)->count();
        $activeStreams = LiveStream::where('status', 'live')->count();
        
        return response()->json([
            'timestamp' => now()->toIso8601String(),
            'listeners' => $liveStream ? $liveStream->listener_count : 0,
            'new_messages' => $newMessages,
            'live_shows' => $liveShows,
            'active_streams' => $activeStreams,
            'stream_status' => $liveStream ? $liveStream->status : 'offline',
            'stream_title' => $liveStream ? $liveStream->title : null,
        ]);
    }
}

