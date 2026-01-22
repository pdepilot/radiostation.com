@props(['mode' => 'login']) {{-- 'login' or 'register' --}}

<div id="authModal" class="auth-modal-overlay" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px); align-items: center; justify-content: center;">
    <div class="auth-modal-container" style="background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); max-width: 420px; width: 90%; max-height: 90vh; overflow-y: auto; position: relative; animation: modalSlideIn 0.3s ease-out;">
        {{-- Close Button --}}
        <button onclick="closeAuthModal()" class="auth-modal-close" style="position: absolute; top: 16px; right: 16px; background: transparent; border: none; color: #64748b; cursor: pointer; padding: 8px; border-radius: 8px; transition: all 0.2s; z-index: 10;" onmouseover="this.style.background='#f1f5f9'; this.style.color='#1e293b'" onmouseout="this.style.background='transparent'; this.style.color='#64748b'">
            <i class="fas fa-times" style="font-size: 1.25rem;"></i>
        </button>

        {{-- Modal Content --}}
        <div class="auth-modal-content" style="padding: 32px;">
            {{-- Logo --}}
            <div style="text-align: center; margin-bottom: 24px;">
                <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM" style="height: 60px; width: auto; margin-bottom: 16px;">
                <h2 style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0 0 8px 0;" id="authModalTitle">Sign In</h2>
                <p style="font-size: 0.875rem; color: #64748b; margin: 0;" id="authModalSubtitle">Welcome back to Darling FM</p>
            </div>

            {{-- Status Messages --}}
            <div id="authModalAlert" style="display: none; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.875rem;"></div>

            {{-- Login Form --}}
            <form id="loginForm" method="POST" action="{{ route('login.post') }}" style="display: {{ $mode === 'login' ? 'block' : 'none' }};">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" required autocomplete="username" value="{{ old('email') }}" 
                        style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; transition: border-color 0.2s; outline: none;"
                        onfocus="this.style.borderColor='#c8102e'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('email')
                        <p style="color: #dc2626; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="loginPassword" required autocomplete="current-password"
                            style="width: 100%; padding: 10px 12px; padding-right: 40px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; transition: border-color 0.2s; outline: none;"
                            onfocus="this.style.borderColor='#c8102e'" onblur="this.style.borderColor='#e2e8f0'">
                        <button type="button" onclick="togglePassword('loginPassword', 'loginPasswordToggle')" 
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #64748b; cursor: pointer; padding: 4px;">
                            <i id="loginPasswordToggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p style="color: #dc2626; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; font-size: 0.875rem;">
                    <label style="display: flex; align-items: center; gap: 8px; color: #64748b; cursor: pointer;">
                        <input type="checkbox" name="remember" style="cursor: pointer;">
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" style="color: #c8102e; text-decoration: none; font-weight: 500;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Forgot password?</a>
                </div>

                <button type="submit" style="width: 100%; padding: 12px; background: #c8102e; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#a00d24'" onmouseout="this.style.background='#c8102e'">
                    Sign In
                </button>
            </form>

            {{-- Register Form --}}
            <form id="registerForm" method="POST" action="{{ route('register.post') }}" style="display: {{ $mode === 'register' ? 'block' : 'none' }};">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Full Name</label>
                    <input type="text" name="name" required autocomplete="name" value="{{ old('name') }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; transition: border-color 0.2s; outline: none;"
                        onfocus="this.style.borderColor='#c8102e'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('name')
                        <p style="color: #dc2626; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" required autocomplete="username" value="{{ old('email') }}"
                        style="width: 100%; padding: 10px 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; transition: border-color 0.2s; outline: none;"
                        onfocus="this.style.borderColor='#c8102e'" onblur="this.style.borderColor='#e2e8f0'">
                    @error('email')
                        <p style="color: #dc2626; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password" id="registerPassword" required autocomplete="new-password"
                            style="width: 100%; padding: 10px 12px; padding-right: 40px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; transition: border-color 0.2s; outline: none;"
                            onfocus="this.style.borderColor='#c8102e'" onblur="this.style.borderColor='#e2e8f0'">
                        <button type="button" onclick="togglePassword('registerPassword', 'registerPasswordToggle')"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #64748b; cursor: pointer; padding: 4px;">
                            <i id="registerPasswordToggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <p style="color: #dc2626; font-size: 0.75rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #1e293b; margin-bottom: 8px;">Confirm Password</label>
                    <div style="position: relative;">
                        <input type="password" name="password_confirmation" id="registerPasswordConfirm" required autocomplete="new-password"
                            style="width: 100%; padding: 10px 12px; padding-right: 40px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.875rem; transition: border-color 0.2s; outline: none;"
                            onfocus="this.style.borderColor='#c8102e'" onblur="this.style.borderColor='#e2e8f0'">
                        <button type="button" onclick="togglePassword('registerPasswordConfirm', 'registerPasswordConfirmToggle')"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #64748b; cursor: pointer; padding: 4px;">
                            <i id="registerPasswordConfirmToggle" class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" style="width: 100%; padding: 12px; background: #c8102e; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 0.875rem; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#a00d24'" onmouseout="this.style.background='#c8102e'">
                    Create Account
                </button>
            </form>

            {{-- Social Login Divider --}}
            <div style="display: flex; align-items: center; margin: 24px 0; color: #94a3b8;">
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
                <span style="padding: 0 12px; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">OR</span>
                <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            </div>

            {{-- Social Login Buttons --}}
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="{{ route('socialite.redirect', 'google') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; color: #1e293b; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.borderColor='#db4437'; this.style.background='#fef2f2'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='transparent'">
                    <i class="fab fa-google" style="color: #db4437;"></i>
                    <span>Continue with Google</span>
                </a>
                <a href="{{ route('socialite.redirect', 'facebook') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; color: #1e293b; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.borderColor='#3b5998'; this.style.background='#f0f4ff'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='transparent'">
                    <i class="fab fa-facebook-f" style="color: #3b5998;"></i>
                    <span>Continue with Facebook</span>
                </a>
                <a href="{{ route('socialite.redirect', 'twitter') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 10px; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; color: #1e293b; font-size: 0.875rem; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.borderColor='#1da1f2'; this.style.background='#f0f9ff'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='transparent'">
                    <i class="fab fa-twitter" style="color: #1da1f2;"></i>
                    <span>Continue with Twitter</span>
                </a>
            </div>

            {{-- Toggle Login/Register --}}
            <div style="text-align: center; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0; font-size: 0.875rem; color: #64748b;">
                <span id="authModalToggleText">Don't have an account? </span>
                <a href="#" id="authModalToggleLink" onclick="toggleAuthMode(); return false;" style="color: #c8102e; font-weight: 600; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Sign up</a>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.auth-modal-overlay {
    animation: fadeIn 0.2s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<script>
// Use window property to avoid redeclaration during Livewire navigation
if (typeof window.authModalMode === 'undefined') {
    window.authModalMode = '{{ $mode }}';
}

function openAuthModal(mode = 'login') {
    window.authModalMode = mode;
    const modal = document.getElementById('authModal');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const title = document.getElementById('authModalTitle');
    const subtitle = document.getElementById('authModalSubtitle');
    const toggleText = document.getElementById('authModalToggleText');
    const toggleLink = document.getElementById('authModalToggleLink');
    
    if (mode === 'login') {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        title.textContent = 'Sign In';
        subtitle.textContent = 'Welcome back to Darling FM';
        toggleText.textContent = "Don't have an account? ";
        toggleLink.textContent = 'Sign up';
    } else {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        title.textContent = 'Create Account';
        subtitle.textContent = 'Join Darling FM community';
        toggleText.textContent = 'Already have an account? ';
        toggleLink.textContent = 'Sign in';
    }
    
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAuthModal() {
    const modal = document.getElementById('authModal');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

function toggleAuthMode() {
    openAuthModal(window.authModalMode === 'login' ? 'register' : 'login');
}

function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Close modal on overlay click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('authModal');
    if (e.target === modal) {
        closeAuthModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAuthModal();
    }
});

// Handle form errors from server
@if($errors->any())
    const alertDiv = document.getElementById('authModalAlert');
    alertDiv.style.display = 'block';
    alertDiv.style.background = '#fee2e2';
    alertDiv.style.border = '1px solid #dc2626';
    alertDiv.style.color = '#991b1b';
    alertDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}';
    openAuthModal('{{ old("_token") ? "register" : "login" }}');
@endif

@if(session('status'))
    const alertDiv = document.getElementById('authModalAlert');
    alertDiv.style.display = 'block';
    alertDiv.style.background = '#d1fae5';
    alertDiv.style.border = '1px solid #10b981';
    alertDiv.style.color = '#065f46';
    alertDiv.innerHTML = '<i class="fas fa-check-circle"></i> {{ session("status") }}';
@endif
</script>
