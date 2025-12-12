<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Rate limiting
        $key = 'register:' . ($request->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => ['Too many registration attempts. Please wait before trying again.'],
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:40'],
            'state' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name'] . '-' . Str::random(6)),
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'state' => $validated['state'] ?? null,
                'city' => $validated['city'] ?? null,
                'password' => Hash::make($validated['password']),
                'role' => 'user',
            ]);

            // Generate and send OTP
            $otp = $user->generateOtp();
            
            try {
                Mail::raw("Your Darling FM verification code is: {$otp}. This code expires in 10 minutes.", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Darling FM - Email Verification Code');
                });
            } catch (\Exception $e) {
                \Log::error('OTP Email failed: ' . $e->getMessage());
            }

            RateLimiter::clear($key);
            return redirect()->route('otp.verify')->with('email', $user->email)->with('status', 'Registration successful! Please check your email for the verification code.');
        } catch (\Exception $e) {
            RateLimiter::hit($key, 3600);
            \Log::error('Registration error: ' . $e->getMessage());
            throw ValidationException::withMessages([
                'email' => ['Registration failed. Please try again.'],
            ]);
        }
    }

    public function showVerify()
    {
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        // Rate limiting
        $key = 'verify:' . ($request->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 10)) {
            throw ValidationException::withMessages([
                'otp' => ['Too many verification attempts. Please wait before trying again.'],
            ]);
        }

        $validated = $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ]);
        
        // Normalize OTP (remove spaces, ensure 6 digits)
        $validated['otp'] = preg_replace('/[^0-9]/', '', $validated['otp']);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            RateLimiter::hit($key, 60);
            throw ValidationException::withMessages([
                'email' => ['User not found.'],
            ]);
        }

        if ($user->verifyOtp($validated['otp'])) {
            RateLimiter::clear($key);
            auth()->login($user);
            $request->session()->regenerate();
            return redirect()->intended('/')->with('status', 'Email verified successfully! Welcome to Darling FM.');
        }

        RateLimiter::hit($key, 60);
        throw ValidationException::withMessages([
            'otp' => ['Invalid or expired verification code.'],
        ]);
    }

    public function resendOtp(Request $request)
    {
        // Rate limiting
        $key = 'resend-otp:' . ($request->ip() ?? 'unknown');
        if (RateLimiter::tooManyAttempts($key, 3)) {
            return back()->withErrors(['email' => 'Too many resend attempts. Please wait before trying again.']);
        }

        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        try {
            $otp = $user->generateOtp();
            
            try {
                Mail::raw("Your Darling FM verification code is: {$otp}. This code expires in 10 minutes.", function ($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Darling FM - Email Verification Code');
                });
            } catch (\Exception $e) {
                \Log::error('OTP Email failed: ' . $e->getMessage());
                RateLimiter::hit($key, 300);
                return back()->withErrors(['email' => 'Failed to send email. Please try again.']);
            }

            RateLimiter::hit($key, 300); // 3 resends per 5 minutes
            return back()->with('status', 'Verification code resent to your email.');
        } catch (\Exception $e) {
            \Log::error('Resend OTP error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to resend code. Please try again.']);
        }
    }

    public function login(Request $request)
    {
        // Rate limiting
        $key = 'login:' . ($request->ip() ?? 'unknown') . ':' . $request->email;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => ['Too many login attempts. Please wait before trying again.'],
            ]);
        }

        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            RateLimiter::hit($key, 3600); // Lock for 1 hour after 5 failed attempts
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_verified) {
            // Resend OTP if not verified
            try {
                $otp = $user->generateOtp();
                try {
                    Mail::raw("Your Darling FM verification code is: {$otp}. This code expires in 10 minutes.", function ($message) use ($user) {
                        $message->to($user->email)
                                ->subject('Darling FM - Email Verification Code');
                    });
                } catch (\Exception $e) {
                    \Log::error('OTP Email failed: ' . $e->getMessage());
                }
                RateLimiter::clear($key);
                return redirect()->route('otp.verify')->with('email', $user->email)->with('status', 'Please verify your email. A new code has been sent.');
            } catch (\Exception $e) {
                \Log::error('Login OTP generation error: ' . $e->getMessage());
                return back()->withErrors(['email' => 'Failed to send verification code. Please contact support.']);
            }
        }

        RateLimiter::clear($key);
        auth()->login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended('/')->with('status', 'Welcome back!');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Logged out successfully.');
    }
}

