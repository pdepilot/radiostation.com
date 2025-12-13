@extends('layouts.frontend', ['title' => 'Register • Darling FM'])

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
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i> Phone Number <span class="optional">(Optional)</span>
                        </label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input" 
                            value="{{ old('phone') }}" 
                            autocomplete="tel"
                            placeholder="Enter your phone number">
                        @error('phone')
                            <span class="error-message">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="state" class="form-label">
                                <i class="fas fa-map-marker-alt"></i> State <span class="optional">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                id="state" 
                                name="state" 
                                class="form-input" 
                                value="{{ old('state') }}" 
                                placeholder="State">
                            @error('state')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="city" class="form-label">
                                <i class="fas fa-city"></i> City <span class="optional">(Optional)</span>
                            </label>
                            <input 
                                type="text" 
                                id="city" 
                                name="city" 
                                class="form-input" 
                                value="{{ old('city') }}" 
                                placeholder="City">
                            @error('city')
                                <span class="error-message">
                                    <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>
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
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
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

                {{-- Login Link --}}
                <div class="auth-footer">
                    <p>Already have an account? 
                        <a href="{{ route('login') }}" class="auth-link">Sign In</a>
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
