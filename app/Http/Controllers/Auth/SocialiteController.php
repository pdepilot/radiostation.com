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

            return redirect()->intended('/');
        } catch (\Exception $e) {
            \Log::error('Socialite authentication error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'email' => 'Authentication failed. Please try again.',
            ]);
        }
    }
}

