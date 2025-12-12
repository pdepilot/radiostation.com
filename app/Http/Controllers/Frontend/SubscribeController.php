<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function store(Request $request)
    {
        // Rate limiting
        $key = 'subscribe:' . ($request->ip() ?? 'unknown');
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, 10)) {
            $message = 'Too many subscription attempts. Please wait before trying again.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 429);
            }
            return back()->withErrors(['email' => $message]);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        try {
            // Save to database if you have a subscriptions table
            // For now, we'll just log it and return success
            \Log::info('New subscription: ' . $validated['email']);
            
            \Illuminate\Support\Facades\RateLimiter::hit($key, 3600); // 10 per hour
            
            $message = 'Thank you for subscribing! You will receive our latest updates.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            return back()->with('status', $message);
        } catch (\Exception $e) {
            \Log::error('Subscription error: ' . $e->getMessage());
            $error = 'Failed to subscribe. Please try again.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $error], 500);
            }
            return back()->withErrors(['email' => $error]);
        }
    }
}
