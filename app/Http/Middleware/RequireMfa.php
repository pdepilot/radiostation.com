<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireMfa
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow unauthenticated requests to pass through (for login pages)
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // Only require MFA for admin users
        if ($user->isAdmin() && $user->hasMfaEnabled()) {
            // Check if MFA is verified in session
            if (!$request->session()->get('mfa_verified', false)) {
                // If this is a Filament route, redirect to Filament login
                if ($request->is('admin*')) {
                    return redirect('/admin/login')
                        ->with('error', 'MFA verification required.');
                }
                return redirect()->route('mfa.verify');
            }
        }

        return $next($request);
    }
}
