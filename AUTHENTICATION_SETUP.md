# Authentication System Setup

## ✅ Completed

1. **Spatie Laravel Permission** - Installed and configured
2. **User Model** - Updated with HasRoles trait and MFA fields
3. **Social Login Buttons** - Added to login/register pages (Google, Facebook, Twitter)
4. **Role-Based Middleware** - Created `EnsureRole` middleware
5. **MFA Middleware** - Created `RequireMfa` middleware
6. **MFA Controller** - Created with verification logic
7. **MFA View** - Created verification form
8. **Routes** - Updated with role-based protection and MFA routes
9. **Filament Integration** - Added MFA middleware to admin panel
10. **Seeder** - Created RolePermissionSeeder
11. **CSS** - Added styles for social login buttons

## 📋 Next Steps (Run These Commands)

### 1. Install Google2FA Package
```bash
composer require pragmarx/google2fa
```

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Seed Roles and Permissions
```bash
php artisan db:seed --class=RolePermissionSeeder
```

### 4. Clear Caches
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan permission:cache-reset
```

## 🔧 Configuration Required

### Environment Variables (.env)
Add these for social login:
```
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://your-domain.com/auth/google/callback

FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://your-domain.com/auth/facebook/callback

TWITTER_CLIENT_ID=your_twitter_client_id
TWITTER_CLIENT_SECRET=your_twitter_client_secret
TWITTER_REDIRECT_URI=http://your-domain.com/auth/twitter/callback
```

## 🎯 How It Works

### User Authentication (Frontend)
- **Breeze** - Email/password login
- **Socialite** - OAuth login (Google, Facebook, Twitter)
- **Roles**: `user` can use frontend login
- **Admin users** are redirected to use Filament admin panel

### Admin Authentication (Filament)
- **Filament** - Admin panel at `/admin`
- **MFA Required** - Admins with MFA enabled must verify
- **Role Check** - Only users with `admin` role can access
- **IP Whitelist** - Optional IP restriction via `ADMIN_ALLOWED_IPS` env var

### Role-Based Access Control
- **Spatie Permissions** - Manages roles and permissions
- **Roles**: `admin`, `user`
  - **Admin**: Full access to admin panel, all permissions, MFA required
  - **User**: Basic authenticated user, read-only permissions
  - **Guest**: Not a role - unauthenticated users (handled by `guest` middleware)
- **Middleware**: `role:admin`, `role:user`
- **Guest routes** - Public access (no auth required, uses `guest` middleware)

### MFA Implementation
- **Google2FA** - TOTP-based authentication
- **Admin Only** - MFA required for admin users
- **Session-based** - MFA verification stored in session
- **Filament Integration** - Automatically enforced in admin panel

## 📝 Usage Examples

### Assign Role to User
```php
$user->assignRole('admin');
```

### Check Permission
```php
$user->hasPermissionTo('edit news');
```

### Protect Route with Role
```php
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin only routes
});
```

### Enable MFA for Admin
```php
$user->mfa_enabled = true;
$user->mfa_secret = $google2fa->generateSecretKey();
$user->save();
```

## 🔐 Security Features

1. **Rate Limiting** - Login attempts throttled
2. **IP Whitelist** - Optional admin IP restriction
3. **HTTPS Enforcement** - Required in production
4. **MFA** - Two-factor authentication for admins
5. **Role-Based Access** - Granular permission control
6. **Session Security** - Regenerated on login
