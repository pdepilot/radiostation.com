@extends('layouts.frontend', ['title' => 'Login • Darling FM'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
@endpush

@section('content')
    <div class="auth-page">
        <div class="container">
            <div class="auth-container">
                {{-- Logo Section --}}
                <div class="auth-header">
                    <a href="{{ route('home') }}" class="auth-logo">
                        <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM Logo">
                    </a>
                    <h1 class="auth-title">WELCOME BACK</h1>
                    <p class="auth-subtitle">Sign in to your Darling FM account</p>
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

                {{-- Login Form --}}
                <form method="POST" action="{{ route('login.post') }}" class="auth-form">
                    @csrf

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
                            autofocus 
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
                                autocomplete="current-password"
                                placeholder="Enter your password">
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-options">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remember" id="remember_me">
                            <span>Remember me</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="auth-submit-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In</span>
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="auth-footer">
                    <p>Don't have an account? 
                        <a href="{{ route('register') }}" class="auth-link">Create Account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(inputId + '-eye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.classList.remove('fa-eye');
                eye.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eye.classList.remove('fa-eye-slash');
                eye.classList.add('fa-eye');
            }
        }
    </script>
@endsection
