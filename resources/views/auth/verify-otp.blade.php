@extends('layouts.frontend', ['title' => 'Verify Email • Darling FM'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="page-header">
                <div style="font-size: 4rem; color: var(--accent); margin-bottom: 20px; animation: pulse 2s infinite;">
                    <i class="fas fa-envelope-open-text"></i>
                </div>
                <h1 style="font-family: 'Orbitron', sans-serif; font-size: 2.5rem; margin-bottom: 15px;">VERIFY YOUR EMAIL</h1>
                <p style="font-size: 1.1rem; color: var(--text-secondary); max-width: 600px; margin: 0 auto;">We've sent a 6-digit verification code to <strong style="color: var(--accent);">{{ old('email', session('email')) }}</strong>. Please check your inbox and enter the code below.</p>
            </div>

            <div class="contact-form-section" style="max-width: 550px; margin: 0 auto;">
                <div class="form-container" style="background: var(--glass); backdrop-filter: blur(15px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.3);">
                    @if(session('status'))
                        <div class="alert success" style="background: rgba(0, 204, 102, 0.2); border: 2px solid var(--success); color: var(--success); padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="background: rgba(255, 0, 0, 0.2); border: 2px solid var(--accent); color: var(--accent); padding: 15px 20px; border-radius: 12px; margin-bottom: 25px;">
                            <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('otp.verify.post') }}" id="otpForm">
                        @csrf

                        <div class="input-group" style="margin-bottom: 25px;">
                            <input type="email" class="form-input" id="email" name="email" value="{{ old('email', session('email')) }}" placeholder=" " required autofocus style="font-size: 1.1rem; padding: 15px 20px;">
                            <label for="email" class="form-label" style="font-size: 1rem; font-weight: 600;">Email Address</label>
                            @error('email')
                                <span class="error-message" style="color: var(--accent); margin-top: 8px; display: block; font-size: 0.9rem;">
                                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="input-group" style="margin-bottom: 30px;">
                            <div style="display: flex; gap: 10px; justify-content: center; margin-bottom: 15px;">
                                @for($i = 0; $i < 6; $i++)
                                    <input type="text" 
                                           class="otp-digit" 
                                           id="otp-{{ $i }}" 
                                           maxlength="1" 
                                           pattern="[0-9]"
                                           style="width: 55px; height: 65px; text-align: center; font-size: 2rem; font-weight: 700; background: rgba(0,0,0,0.3); border: 2px solid var(--glass-border); border-radius: 12px; color: var(--light); transition: all 0.3s;"
                                           oninput="moveToNext(this, {{ $i }})"
                                           onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 20px rgba(255,0,0,0.3)'; this.style.background='rgba(0,0,0,0.5)'"
                                           onblur="this.style.borderColor='var(--glass-border)'; this.style.boxShadow='none'; this.style.background='rgba(0,0,0,0.3)'">
                                @endfor
                            </div>
                            <input type="hidden" id="otp" name="otp" required>
                            <label style="display: block; text-align: center; color: var(--text-secondary); font-size: 0.95rem; font-weight: 600; margin-top: 10px;">
                                <i class="fas fa-key" style="margin-right: 5px; color: var(--accent);"></i>
                                6-Digit Verification Code
                            </label>
                            @error('otp')
                                <span class="error-message" style="color: var(--accent); margin-top: 8px; display: block; text-align: center; font-size: 0.9rem;">
                                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>{{ $message }}
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="submit-btn" style="width: 100%; padding: 16px; font-size: 1.1rem; font-weight: 700; background: linear-gradient(135deg, var(--accent), var(--accent-glow)); box-shadow: 0 5px 20px rgba(255,0,0,0.3);">
                            <i class="fas fa-check-circle"></i> VERIFY EMAIL
                        </button>
                    </form>

                    <div style="margin-top: 25px; padding-top: 25px; border-top: 1px solid var(--glass-border);">
                        <form method="POST" action="{{ route('otp.resend') }}">
                            @csrf
                            <input type="hidden" name="email" value="{{ old('email', session('email')) }}">
                            <button type="submit" style="width: 100%; background: transparent; border: 2px solid var(--glass-border); color: var(--light); padding: 14px; border-radius: 10px; cursor: pointer; font-weight: 600; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;" onmouseover="this.style.borderColor='var(--accent)'; this.style.background='rgba(255,0,0,0.1)'" onmouseout="this.style.borderColor='var(--glass-border)'; this.style.background='transparent'">
                                <i class="fas fa-redo"></i> Resend Code
                            </button>
                        </form>
                    </div>

                    <div style="text-align: center; margin-top: 25px; padding: 15px; background: rgba(255,0,0,0.05); border-radius: 10px; border-left: 3px solid var(--accent);">
                        <p style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.6;">
                            <i class="fas fa-info-circle" style="color: var(--accent); margin-right: 8px;"></i>
                            Didn't receive the code? Check your spam folder or 
                            <a href="{{ route('register') }}" style="color: var(--accent); font-weight: 600; text-decoration: none;">register again</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function moveToNext(input, currentIndex) {
            // Only allow numbers
            input.value = input.value.replace(/[^0-9]/g, '');
            
            // Move to next input if value entered
            if (input.value && currentIndex < 5) {
                document.getElementById('otp-' + (currentIndex + 1)).focus();
            }
            
            // Update hidden input
            updateOtpValue();
        }
        
        function updateOtpValue() {
            let otpValue = '';
            for (let i = 0; i < 6; i++) {
                const digit = document.getElementById('otp-' + i).value;
                otpValue += digit || '';
            }
            document.getElementById('otp').value = otpValue;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            // Handle paste
            document.getElementById('otpForm').addEventListener('paste', function(e) {
                e.preventDefault();
                const pastedData = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '').substring(0, 6);
                for (let i = 0; i < 6; i++) {
                    const input = document.getElementById('otp-' + i);
                    input.value = pastedData[i] || '';
                }
                updateOtpValue();
                if (pastedData.length === 6) {
                    document.getElementById('otpForm').submit();
                }
            });
            
            // Handle backspace
            for (let i = 0; i < 6; i++) {
                document.getElementById('otp-' + i).addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && i > 0) {
                        document.getElementById('otp-' + (i - 1)).focus();
                    }
                });
            }
            
            // Auto-submit when all 6 digits are entered
            for (let i = 0; i < 6; i++) {
                document.getElementById('otp-' + i).addEventListener('input', function() {
                    updateOtpValue();
                    const otpValue = document.getElementById('otp').value;
                    if (otpValue.length === 6) {
                        setTimeout(() => {
                            document.getElementById('otpForm').submit();
                        }, 300);
                    }
                });
            }
        });
    </script>
    <style>
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
    </style>
@endpush

