<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LikeController extends Controller
{
    public function toggle(Request $request, NewsPost $newsPost): JsonResponse
    {
        // Rate limiting
        $key = 'like:' . auth()->id() . ':' . $newsPost->id;
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'success' => false,
                'error' => 'Too many requests. Please wait a moment.',
            ], 429);
        }
        RateLimiter::hit($key, 60); // 10 attempts per minute

        // Require authentication for likes
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'error' => 'Please login or register to like posts.',
                'requires_auth' => true,
            ], 401);
        }

        // Prevent admins from liking
        if (auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'error' => 'Admins cannot engage with content. Please use a regular user account.',
            ], 403);
        }

        $userId = auth()->id();
        $userAgent = $request->userAgent();

        try {
            return DB::transaction(function () use ($newsPost, $userId, $userAgent) {
                // Check if already liked
                $like = DB::table('post_likes')
                    ->where('news_post_id', $newsPost->id)
                    ->where('user_id', $userId)
                    ->lockForUpdate()
                    ->first();

                if ($like) {
                    // Unlike
                    DB::table('post_likes')->where('id', $like->id)->delete();
                    $newsPost->decrement('like_count');
                    $liked = false;
                } else {
                    // Like
                    DB::table('post_likes')->insert([
                        'news_post_id' => $newsPost->id,
                        'user_id' => $userId,
                        'ip_address' => null,
                        'user_agent' => $userAgent,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $newsPost->increment('like_count');
                    $liked = true;
                }

                return response()->json([
                    'success' => true,
                    'liked' => $liked,
                    'like_count' => $newsPost->fresh()->like_count,
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Like toggle error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred. Please try again.',
            ], 500);
        }
    }

    public function check(Request $request, NewsPost $newsPost): JsonResponse
    {
        $userId = auth()->id();
        
        if (!$userId) {
            return response()->json([
                'liked' => false,
                'like_count' => $newsPost->like_count,
            ]);
        }

        $liked = DB::table('post_likes')
            ->where('news_post_id', $newsPost->id)
            ->where('user_id', $userId)
            ->exists();

        return response()->json([
            'liked' => $liked,
            'like_count' => $newsPost->like_count,
        ]);
    }
}
