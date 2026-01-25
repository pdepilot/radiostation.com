<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        $comments = Comment::with(['newsPost', 'user', 'parent'])
            ->latest()
            ->paginate(20);

        return view('admin.comments.index', [
            'comments' => $comments,
        ]);
    }

    public function approve(Comment $comment): RedirectResponse
    {
        try {
            DB::transaction(function () use ($comment) {
                if (!$comment->is_approved) {
                    $comment->update(['is_approved' => true]);
                    if ($comment->newsPost) {
                        $comment->newsPost->increment('comment_count');
                    }
                }
            });

            return back()->with('status', 'Comment approved.');
        } catch (\Exception $e) {
            \Log::error('Comment approval error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to approve comment. Please try again.']);
        }
    }

    public function reject(Comment $comment): RedirectResponse
    {
        try {
            DB::transaction(function () use ($comment) {
                $newsPost = $comment->newsPost;
                if ($comment->is_approved && $newsPost) {
                    $newsPost->decrement('comment_count');
                }
                $comment->delete();
            });

            return back()->with('status', 'Comment rejected and deleted.');
        } catch (\Exception $e) {
            \Log::error('Comment rejection error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to reject comment. Please try again.']);
        }
    }

    public function destroy(Comment $comment): RedirectResponse
    {
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

