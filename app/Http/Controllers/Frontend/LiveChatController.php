<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LiveChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveChatController extends Controller
{
    public function index(): JsonResponse
    {
        $messages = LiveChatMessage::with('user')
            ->where('is_moderated', true)
            ->recent(50)
            ->get()
            ->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'name' => $msg->name ?? $msg->user->name ?? 'Guest',
                    'message' => $msg->message,
                    'time' => $msg->created_at->format('g:i A'),
                    'is_verified' => $msg->user_id !== null,
                ];
            });

        return response()->json($messages);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
            'name' => ['nullable', 'string', 'max:120'],
        ]);

        // Rate limiting: max 5 messages per minute per IP
        $recentCount = LiveChatMessage::where('ip_address', $request->ip())
            ->where('created_at', '>', now()->subMinute())
            ->count();

        if ($recentCount >= 5) {
            return response()->json(['error' => 'Too many messages. Please wait a moment.'], 429);
        }

        $message = LiveChatMessage::create([
            'user_id' => auth()->id(),
            'name' => auth()->check() ? null : ($validated['name'] ?? 'Guest'),
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'is_moderated' => auth()->check(), // Auto-moderate authenticated users
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'name' => $message->name ?? auth()->user()->name ?? 'Guest',
                'message' => $message->message,
                'time' => $message->created_at->format('g:i A'),
                'is_verified' => $message->user_id !== null,
            ],
        ]);
    }
}
