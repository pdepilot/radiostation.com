@extends('layouts.frontend', ['title' => 'Register • Darling FM'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endpush

@section('content')
    <div class="auth-page">
        <div class="auth-container">
                {{-- Logo Section --}}
                <div class="auth-header">
                    <a href="{{ route('home') }}" class="auth-logo">
                        <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM Logo">
                    </a>
                    <h1 class="auth-title">JOIN DARLING FM</h1>
                    <p class="auth-subtitle">Create your account to get started</p>
                </div>

                {{-- Status Messages --}}
                @if(session('status'))
                    <div class="auth-alert success">
                        <i class="fas fa-check-circle"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="auth-alert error">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                {{-- Register Form --}}
                <form method="POST" action="{{ route('register.post') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i> Full Name
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                            placeholder="Enter your full name">
                        @error('name')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="username"
                            placeholder="Enter your email">
                        @error('email')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Password
                        </label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                class="form-input" 
                                required 
                                autocomplete="new-password"
                                placeholder="Create a password (min. 8 characters)">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock"></i> Confirm Password
                        </label>
                        <div class="password-input-wrapper">
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                class="form-input" 
                                required 
                                autocomplete="new-password"
                                placeholder="Confirm your password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="password_confirmation-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="auth-submit-btn">
                        <i class="fas fa-user-plus"></i>
                        <span>Create Account</span>
                    </button>
                </form>

                {{-- Social Login Divider --}}
                <div class="auth-divider">
                    <span>OR</span>
                </div>

                {{-- Social Login Buttons --}}
                <div class="social-login">
                    <a href="{{ route('socialite.redirect', 'google') }}" class="social-btn google">
                        <i class="fab fa-google"></i>
                        <span>Continue with Google</span>
                    </a>
                    <a href="{{ route('socialite.redirect', 'facebook') }}" class="social-btn facebook">
                        <i class="fab fa-facebook-f"></i>
                        <span>Continue with Facebook</span>
                    </a>
                    <a href="{{ route('socialite.redirect', 'twitter') }}" class="social-btn twitter">
                        <i class="fab fa-twitter"></i>
                        <span>Continue with Twitter</span>
                    </a>
                </div>

                {{-- Login Link --}}
                <div class="auth-footer">
                    <p>Already have an account? 
                        <a href="{{ route('login') }}" class="auth-link">Sign In</a>
                    </p>
                </div>
            </div>
    </div>

    <script>
        /**
         * Toggle password visibility
         * Shows/hides password in real-time
         */
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            
            if (!input || !eye) return;
            
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
                eye.setAttribute('aria-label', 'Hide password');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
                eye.setAttribute('aria-label', 'Show password');
            }
        }
    </script>
@endsection
