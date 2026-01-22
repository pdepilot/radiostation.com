<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Check if MFA is required (only for regular users, admins are blocked from this route)
        if ($user && $user->hasMfaEnabled()) {
            if (!$request->session()->get('mfa_verified', false)) {
                // Store intended destination before redirecting to MFA
                if (!$request->session()->has('url.intended')) {
                    $request->session()->put('url.intended', route('home', absolute: false));
                }
                return redirect()->route('mfa.verify', navigate: true);
            }
        }

        // Get intended URL or default to home page
        $intendedUrl = $request->session()->pull('url.intended', route('home', absolute: false));
        
        // Redirect to intended destination or home page using SPA navigation
        return redirect($intendedUrl, navigate: true);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // For SPA mode, use navigate: true to preserve audio player state
        return redirect('/', navigate: true);
    }
}
