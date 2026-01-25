@extends('layouts.frontend')

@section('content')
<div class="container" style="max-width: 1200px; margin: 120px auto 40px; padding: 0 20px;">
    <div style="margin-bottom: 30px;">
        <h1 style="color: var(--accent); font-family: 'Oxanium', sans-serif; font-size: 2.5rem; font-weight: 700; margin-bottom: 10px;">Profile Settings</h1>
        <p style="color: var(--text-secondary);">Manage your account information and preferences</p>
    </div>

    <div style="display: flex; flex-direction: column; gap: 30px;">
        <!-- Profile Information Section -->
        <div style="background: var(--glass); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); border-radius: 15px; padding: 30px;">
            <h2 style="color: var(--accent); font-family: 'Oxanium', sans-serif; font-size: 1.5rem; font-weight: 600; margin-bottom: 10px;">Profile Information</h2>
            <p style="color: var(--text-secondary); margin-bottom: 25px; font-size: 0.9rem;">Update your account's profile information and email address.</p>
            
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf
                @method('patch')

                <div>
                    <label for="name" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 500;">Name</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" 
                           style="width: 100%; padding: 12px 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); font-size: 1rem; outline: none; transition: border-color 0.3s;"
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--glass-border)'">
                    @error('name')
                        <p style="color: var(--accent); margin-top: 5px; font-size: 0.875rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 500;">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                           style="width: 100%; padding: 12px 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); font-size: 1rem; outline: none; transition: border-color 0.3s;"
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--glass-border)'">
                    @error('email')
                        <p style="color: var(--accent); margin-top: 5px; font-size: 0.875rem;">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div style="margin-top: 10px; padding: 10px; background: rgba(255,165,0,0.1); border: 1px solid rgba(255,165,0,0.3); border-radius: 8px;">
                            <p style="color: var(--warning); font-size: 0.9rem; margin-bottom: 5px;">
                                Your email address is unverified.
                            </p>
                            <button form="send-verification" type="button" 
                                    style="color: var(--accent); text-decoration: underline; background: none; border: none; cursor: pointer; font-size: 0.9rem;">
                                Click here to re-send the verification email.
                            </button>
                            @if (session('status') === 'verification-link-sent')
                                <p style="margin-top: 10px; color: var(--success); font-size: 0.9rem;">
                                    A new verification link has been sent to your email address.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <button type="submit" 
                            style="padding: 12px 30px; background: var(--accent); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='var(--accent-glow)'; this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)'">
                        Save Changes
                    </button>

                    @if (session('status') === 'profile-updated')
                        <p style="color: var(--success); font-size: 0.9rem; margin: 0;">Saved successfully!</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Update Password Section -->
        <div style="background: var(--glass); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); border-radius: 15px; padding: 30px;">
            <h2 style="color: var(--accent); font-family: 'Oxanium', sans-serif; font-size: 1.5rem; font-weight: 600; margin-bottom: 10px;">Update Password</h2>
            <p style="color: var(--text-secondary); margin-bottom: 25px; font-size: 0.9rem;">Ensure your account is using a long, random password to stay secure.</p>

            <form method="post" action="{{ route('password.update') }}" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf
                @method('put')

                <div>
                    <label for="update_password_current_password" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 500;">Current Password</label>
                    <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                           style="width: 100%; padding: 12px 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); font-size: 1rem; outline: none; transition: border-color 0.3s;"
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--glass-border)'">
                    @error('current_password', 'updatePassword')
                        <p style="color: var(--accent); margin-top: 5px; font-size: 0.875rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="update_password_password" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 500;">New Password</label>
                    <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                           style="width: 100%; padding: 12px 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); font-size: 1rem; outline: none; transition: border-color 0.3s;"
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--glass-border)'">
                    @error('password', 'updatePassword')
                        <p style="color: var(--accent); margin-top: 5px; font-size: 0.875rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="update_password_password_confirmation" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 500;">Confirm Password</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                           style="width: 100%; padding: 12px 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); font-size: 1rem; outline: none; transition: border-color 0.3s;"
                           onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--glass-border)'">
                    @error('password_confirmation', 'updatePassword')
                        <p style="color: var(--accent); margin-top: 5px; font-size: 0.875rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                    <button type="submit"
                            style="padding: 12px 30px; background: var(--accent); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                            onmouseover="this.style.background='var(--accent-glow)'; this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)'">
                        Update Password
                    </button>

                    @if (session('status') === 'password-updated')
                        <p style="color: var(--success); font-size: 0.9rem; margin: 0;">Password updated successfully!</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Delete Account Section -->
        <div style="background: var(--glass); backdrop-filter: blur(10px); border: 1px solid var(--glass-border); border-radius: 15px; padding: 30px;">
            <h2 style="color: var(--accent); font-family: 'Oxanium', sans-serif; font-size: 1.5rem; font-weight: 600; margin-bottom: 10px;">Delete Account</h2>
            <p style="color: var(--text-secondary); margin-bottom: 25px; font-size: 0.9rem;">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
            </p>

            <button onclick="openDeleteModal()" 
                    style="padding: 12px 30px; background: transparent; color: var(--accent); border: 2px solid var(--accent); border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                    onmouseover="this.style.background='var(--accent)'; this.style.color='white'"
                    onmouseout="this.style.background='transparent'; this.style.color='var(--accent)'">
                Delete Account
            </button>

            <!-- Delete Account Modal -->
            <div id="deleteAccountModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; align-items: center; justify-content: center;">
                <div style="background: var(--glass); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 15px; padding: 30px; max-width: 500px; width: 90%;">
                    <h3 style="color: var(--accent); font-family: 'Oxanium', sans-serif; font-size: 1.5rem; font-weight: 600; margin-bottom: 15px;">
                        Are you sure you want to delete your account?
                    </h3>
                    <p style="color: var(--text-secondary); margin-bottom: 20px; font-size: 0.9rem;">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}" style="display: flex; flex-direction: column; gap: 15px;">
                        @csrf
                        @method('delete')

                        <div>
                            <label for="password" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 500;">Password</label>
                            <input id="password" name="password" type="password" placeholder="Enter your password" required
                                   style="width: 100%; padding: 12px 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); font-size: 1rem; outline: none; transition: border-color 0.3s;"
                                   onfocus="this.style.borderColor='var(--accent)'" onblur="this.style.borderColor='var(--glass-border)'">
                            @error('password', 'userDeletion')
                                <p style="color: var(--accent); margin-top: 5px; font-size: 0.875rem;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 10px;">
                            <button type="button" onclick="closeDeleteModal()"
                                    style="padding: 12px 25px; background: transparent; color: var(--light); border: 1px solid var(--glass-border); border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                                    onmouseover="this.style.borderColor='var(--accent)'"
                                    onmouseout="this.style.borderColor='var(--glass-border)'">
                                Cancel
                            </button>
                            <button type="submit"
                                    style="padding: 12px 25px; background: var(--accent); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s;"
                                    onmouseover="this.style.background='var(--accent-glow)'"
                                    onmouseout="this.style.background='var(--accent)'">
                                Delete Account
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDeleteModal() {
    document.getElementById('deleteAccountModal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('deleteAccountModal').style.display = 'none';
}

// Close modal when clicking outside
document.getElementById('deleteAccountModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection
