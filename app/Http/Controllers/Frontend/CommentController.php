<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\NewsPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(Request $request, NewsPost $newsPost): RedirectResponse
    {
        // Require authentication for comments
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login or register to post comments.');
        }

        // Prevent admins from commenting
        if (auth()->user()->isAdmin()) {
            return back()->with('error', 'Admins cannot engage with content. Please use a regular user account.');
        }

        // Rate limiting
        $key = 'comment:' . auth()->id();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            return back()->withErrors(['body' => 'Too many comments. Please wait a moment before posting again.']);
        }

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        $validated['user_id'] = auth()->id();
        $validated['name'] = auth()->user()->name;
        $validated['email'] = auth()->user()->email;
        $validated['news_post_id'] = $newsPost->id;
        $validated['is_approved'] = true; // Auto-approve authenticated users

        try {
            DB::transaction(function () use ($validated, $newsPost) {
                Comment::create($validated);
                $newsPost->increment('comment_count');
            });

            RateLimiter::hit($key, 60); // 10 comments per minute
            return back()->with('status', 'Comment posted successfully!');
        } catch (\Exception $e) {
            \Log::error('Comment creation error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to post comment. Please try again.']);
        }
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        // Check if user owns the comment or is admin
        if (!auth()->check() || (auth()->id() !== $comment->user_id && !auth()->user()->isAdmin())) {
            abort(403);
        }

        try {
            DB::transaction(function () use ($comment) {
                $newsPost = $comment->newsPost;
                if ($comment->is_approved && $newsPost) {
                    $newsPost->decrement('comment_count');
                }
                $comment->delete();
            });

            return back()->with('status', 'Comment deleted.');
        } catch (\Exception $e) {
            \Log::error('Comment deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete comment. Please try again.']);
        }
    }
}

