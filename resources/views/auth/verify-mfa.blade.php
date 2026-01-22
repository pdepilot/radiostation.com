@extends('layouts.frontend', ['title' => 'Verify MFA • Darling FM'])

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
                <h1 class="auth-title">TWO-FACTOR AUTHENTICATION</h1>
                <p class="auth-subtitle">Enter the 6-digit code from your authenticator app</p>
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

            {{-- MFA Form --}}
            <form method="POST" action="{{ route('mfa.verify.post') }}" class="auth-form" id="mfaForm">
                @csrf

                <div class="form-group">
                    <label for="code" class="form-label">
                        <i class="fas fa-shield-alt"></i> Authentication Code
                    </label>
                    <div style="display: flex; gap: 10px; justify-content: center; margin-bottom: 15px;">
                        @for($i = 0; $i < 6; $i++)
                            <input 
                                type="text" 
                                maxlength="1" 
                                class="form-input otp-digit" 
                                id="code-{{ $i }}" 
                                name="code[]" 
                                autocomplete="off"
                                style="width: 50px; height: 60px; text-align: center; font-size: 1.5rem; font-weight: bold;"
                                required>
                        @endfor
                    </div>
                    <input type="hidden" id="code" name="code" required>
                    @error('code')
                        <span class="error-message">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="auth-submit-btn">
                    <i class="fas fa-check"></i>
                    <span>Verify Code</span>
                </button>
            </form>

            <div class="auth-footer">
                <p style="text-align: center; color: var(--text-secondary); font-size: 0.9rem;">
                    <i class="fas fa-info-circle"></i> 
                    Use your authenticator app (Google Authenticator, Authy, etc.) to get your verification code.
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus and move between OTP inputs
        const inputs = document.querySelectorAll('.otp-digit');
        let currentIndex = 0;

        inputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
                updateCodeValue();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').slice(0, 6);
                pastedData.split('').forEach((char, i) => {
                    if (inputs[i]) {
                        inputs[i].value = char;
                    }
                });
                updateCodeValue();
                if (pastedData.length === 6) {
                    document.getElementById('mfaForm').submit();
                }
            });
        });

        function updateCodeValue() {
            let codeValue = '';
            inputs.forEach(input => {
                codeValue += input.value || '';
            });
            document.getElementById('code').value = codeValue;
        }

        // Focus first input on load
        if (inputs[0]) {
            inputs[0].focus();
        }
    </script>
@endsection
