<?php

use App\Http\Controllers\Admin\AdvertisingController;
use App\Http\Controllers\Admin\AudienceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DjController as AdminDjController;
use App\Http\Controllers\Admin\LiveStreamController as AdminLiveStreamController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PlaylistController as AdminPlaylistController;
use App\Http\Controllers\Admin\PodcastController as AdminPodcastController;
use App\Http\Controllers\Admin\RevenueController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ShowController as AdminShowController;

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\DjController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\PlaylistController;
use App\Http\Controllers\Frontend\PodcastController;
use App\Http\Controllers\Frontend\ShowController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

// ========================
// PUBLIC ROUTES
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shows', [ShowController::class, 'index'])->name('shows.index');
Route::get('/shows/{show:slug}', [ShowController::class, 'show'])->name('shows.show');

// DJs page removed - using on-air personalities section on home page instead
// Route::get('/djs', [DjController::class, 'index'])->name('djs.index');
Route::get('/djs/{dj:slug}', [DjController::class, 'show'])->name('djs.show'); // OAP Profile Page

// Route::get('/playlist', [PlaylistController::class, 'index'])->name('playlist.index');

// Commented out for now - might be needed in the future
// Route::get('/podcasts', [PodcastController::class, 'index'])->name('podcasts.index');
// Route::get('/podcasts/{podcast:slug}', [PodcastController::class, 'show'])->name('podcasts.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{newsPost:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/api/news/search', [NewsController::class, 'search'])->name('news.search');
Route::get('/api/listener-count', [\App\Http\Controllers\Frontend\LiveStreamController::class, 'getListenerCount'])->name('api.listener-count');
Route::get('/api/active-stream', [\App\Http\Controllers\Frontend\LiveStreamController::class, 'getActiveStream'])->name('api.active-stream');
Route::post('/api/listener/track', [\App\Http\Controllers\Frontend\LiveStreamController::class, 'trackListener'])->name('api.listener.track');
Route::post('/api/listener/reset', [\App\Http\Controllers\Frontend\LiveStreamController::class, 'resetListenerCount'])->name('api.listener.reset');
Route::post('/admin/api/analytics/reset', [\App\Http\Controllers\Admin\AnalyticsController::class, 'resetAnalytics'])->name('admin.api.analytics.reset')->middleware('auth');
Route::get('/api/server-time', [\App\Http\Controllers\Api\ServerTimeController::class, 'index'])->name('api.server-time');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Comments
Route::post('/news/{newsPost:slug}/comments', [\App\Http\Controllers\Frontend\CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [\App\Http\Controllers\Frontend\CommentController::class, 'destroy'])->middleware('auth')->name('comments.destroy');

// Likes (protected with CSRF)
Route::middleware('web')->group(function () {
    Route::post('/api/news/{newsPost}/like', [\App\Http\Controllers\Frontend\LikeController::class, 'toggle'])->name('likes.toggle');
    Route::get('/api/news/{newsPost}/like/check', [\App\Http\Controllers\Frontend\LikeController::class, 'check'])->name('likes.check');
});

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Policy Pages
Route::get('/privacy', [\App\Http\Controllers\Frontend\PolicyController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\Frontend\PolicyController::class, 'terms'])->name('terms');
Route::get('/faq', [\App\Http\Controllers\Frontend\PolicyController::class, 'faq'])->name('faq');
Route::get('/feedback', function() {
    return redirect()->route('contact.index', ['category' => 'feedback']);
})->name('feedback');

// Live Chat API
Route::get('/api/live-chat', [\App\Http\Controllers\Frontend\LiveChatController::class, 'index'])->name('live-chat.index');
Route::post('/api/live-chat', [\App\Http\Controllers\Frontend\LiveChatController::class, 'store'])->name('live-chat.store');

// Adverts API
Route::get('/api/adverts', [\App\Http\Controllers\Frontend\AdvertController::class, 'getActiveAdverts'])->name('adverts.index');
Route::post('/api/adverts/{advert}/view', [\App\Http\Controllers\Frontend\AdvertController::class, 'trackView'])->name('adverts.view');
Route::post('/api/adverts/{advert}/click', [\App\Http\Controllers\Frontend\AdvertController::class, 'trackClick'])->name('adverts.click');
Route::post('/api/adverts/{advert}/close', [\App\Http\Controllers\Frontend\AdvertController::class, 'closeAdvert'])->name('adverts.close');

// ========================
// AUTHENTICATED USER ROUTES
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Email Verification Routes
    Route::get('verify-email', [\App\Http\Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', [\App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [\App\Http\Controllers\Auth\EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// ========================
// ADMIN ROUTES (protected)
// ========================
// Admin routes are now handled by Filament at /admin
// Old admin routes removed - using Filament 3 panel instead

// Admin API routes for real-time updates
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/api/realtime/stream', [\App\Http\Controllers\Admin\RealtimeController::class, 'stream'])->name('admin.api.realtime.stream');
    Route::get('/api/realtime/poll', [\App\Http\Controllers\Admin\RealtimeController::class, 'poll'])->name('admin.api.realtime.poll');
});

// ========================
// AUTH ROUTES (OTP-based authentication)
// ========================
Route::get('/login', [\App\Http\Controllers\Auth\OtpController::class, 'showLogin'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\OtpController::class, 'login'])->name('login.post');
Route::get('/register', [\App\Http\Controllers\Auth\OtpController::class, 'showRegister'])->name('register');
Route::post('/register', [\App\Http\Controllers\Auth\OtpController::class, 'register'])->name('register.post');
Route::get('/verify-otp', [\App\Http\Controllers\Auth\OtpController::class, 'showVerify'])->name('otp.verify');
Route::post('/verify-otp', [\App\Http\Controllers\Auth\OtpController::class, 'verify'])->name('otp.verify.post');
Route::post('/resend-otp', [\App\Http\Controllers\Auth\OtpController::class, 'resendOtp'])->name('otp.resend');
Route::post('/logout', [\App\Http\Controllers\Auth\OtpController::class, 'logout'])->name('logout');
