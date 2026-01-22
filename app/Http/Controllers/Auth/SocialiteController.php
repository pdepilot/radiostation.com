<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect to the provider's authentication page.
     */
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the provider's callback.
     */
    public function callback(string $provider): RedirectResponse
    {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Check if user exists by email
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // User exists, log them in
                Auth::login($user, true);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'slug' => Str::slug(($socialUser->getName() ?? $socialUser->getNickname() ?? 'user') . '-' . Str::random(6)),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(32)), // Random password since they use social login
                    'role' => 'user',
                    'avatar_url' => $socialUser->getAvatar(),
                ]);

                Auth::login($user, true);
            }

            // Prevent admin users from logging in through social login (user route)
            // Admin users should use Filament admin panel at /admin for login
            if ($user->isAdmin()) {
                Auth::logout();
                request()->session()->invalidate();
                request()->session()->regenerateToken();
                return redirect()->route('login', navigate: true)->withErrors([
                    'email' => 'Authentication failed. Please try again.',
                ]);
            }

            // Check if MFA is required (only for regular users)
            if ($user->hasMfaEnabled()) {
                if (!request()->session()->get('mfa_verified', false)) {
                    // Store intended destination before redirecting to MFA
                    if (!request()->session()->has('url.intended')) {
                        request()->session()->put('url.intended', route('home', absolute: false));
                    }
                    return redirect()->route('mfa.verify', navigate: true);
                }
            }

            // Redirect to intended destination or home page using SPA navigation
            $intendedUrl = request()->session()->pull('url.intended', route('home', absolute: false));
            return redirect($intendedUrl, navigate: true);
        } catch (\Exception $e) {
            \Log::error('Socialite authentication error: ' . $e->getMessage());
            return redirect()->route('login', navigate: true)->withErrors([
                'email' => 'Authentication failed. Please try again.',
            ]);
        }
    }
}

