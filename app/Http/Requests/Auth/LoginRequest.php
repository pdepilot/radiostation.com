<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Attempt authentication first to prevent role enumeration
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            $exception = ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
            $exception->status = 422;
            throw $exception;
        }

        // Check if the authenticated user is an admin
        // Admin users should use Filament admin panel at /admin for login
        // This frontend login route is only for regular users
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            // Log out the admin user immediately
            Auth::logout();
            
            // Invalidate the session
            $this->session()->invalidate();
            $this->session()->regenerateToken();
            
            // Hit rate limiter to prevent brute force
            RateLimiter::hit($this->throttleKey());
            
            // Return generic error message (same as failed login to prevent role enumeration)
            $exception = ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
            $exception->status = 422;
            throw $exception;
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $exception = ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
        $exception->status = 422;
        throw $exception;
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
