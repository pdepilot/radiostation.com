<?php

use App\Http\Controllers\Admin\SettingsController;

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\DjController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\NewsController;
use App\Http\Controllers\Frontend\ShowController;
use App\Http\Controllers\ProfileController;

use Illuminate\Support\Facades\Route;

// ========================
// PUBLIC ROUTES
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO Routes
Route::get('/sitemap.xml', [\App\Http\Controllers\Frontend\SitemapController::class, 'index'])->name('sitemap');

Route::get('/shows', [ShowController::class, 'index'])->name('shows.index');
Route::get('/shows/{show:slug}', [ShowController::class, 'show'])->name('shows.show');

// DJs page removed - using on-air personalities section on home page instead
// Route::get('/djs', [DjController::class, 'index'])->name('djs.index');
Route::get('/djs/{dj:slug}', [DjController::class, 'show'])->name('djs.show'); // OAP Profile Page

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{newsPost:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/api/news/search', [NewsController::class, 'search'])->name('news.search');
Route::get('/api/search', [\App\Http\Controllers\Frontend\SearchController::class, 'search'])->name('search');
Route::get('/live', [\App\Http\Controllers\Frontend\LiveStreamController::class, 'index'])->name('live');
Route::get('/api/listener-count', [\App\Http\Controllers\Frontend\LiveStreamController::class, 'getListenerCount'])->name('api.listener-count');
Route::get('/api/active-stream', [\App\Http\Controllers\Frontend\LiveStreamController::class, 'getActiveStream'])->name('api.active-stream');
Route::post('/api/listener/track', [\App\Http\Controllers\Frontend\LiveStreamController::class, 'trackListener'])->name('api.listener.track');
Route::post('/admin/api/analytics/reset', [\App\Http\Controllers\Admin\AnalyticsController::class, 'resetAnalytics'])->name('admin.api.analytics.reset')->middleware(['auth', 'admin.access']);
Route::get('/api/server-time', [\App\Http\Controllers\Api\ServerTimeController::class, 'index'])->name('api.server-time');

// Real-time content updates
Route::get('/api/realtime/poll', [\App\Http\Controllers\Api\RealtimeController::class, 'poll'])->name('api.realtime.poll');
Route::get('/api/realtime/content', [\App\Http\Controllers\Api\RealtimeController::class, 'getContent'])->name('api.realtime.content');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Comments
Route::post('/news/{newsPost:slug}/comments', [\App\Http\Controllers\Frontend\CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [\App\Http\Controllers\Frontend\CommentController::class, 'destroy'])->middleware('auth')->name('comments.destroy');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Music Promotion Routes
Route::prefix('promotions')->name('promotions.')->group(function () {
    Route::get('/check-availability', [\App\Http\Controllers\Frontend\MusicPromotionController::class, 'checkAvailability'])->name('check-availability');
    Route::get('/pricing', [\App\Http\Controllers\Frontend\MusicPromotionController::class, 'getPricing'])->name('pricing');
    Route::post('/submit', [\App\Http\Controllers\Frontend\MusicPromotionController::class, 'submit'])->name('submit');
    Route::get('/callback', [\App\Http\Controllers\Frontend\MusicPromotionController::class, 'callback'])->name('callback');
    Route::post('/webhook', [\App\Http\Controllers\Frontend\MusicPromotionController::class, 'webhook'])->name('webhook');
    Route::post('/waitlist', [\App\Http\Controllers\Frontend\MusicPromotionController::class, 'joinWaitlist'])->name('waitlist');
    Route::post('/{id}/track-click', [\App\Http\Controllers\Frontend\MusicPromotionController::class, 'trackClick'])->name('track-click');
});

// Policy Pages
Route::get('/privacy', [\App\Http\Controllers\Frontend\PolicyController::class, 'privacy'])->name('privacy');
Route::get('/terms', [\App\Http\Controllers\Frontend\PolicyController::class, 'terms'])->name('terms');
Route::get('/faq', [\App\Http\Controllers\Frontend\PolicyController::class, 'faq'])->name('faq');
Route::get('/feedback', function () {
    return redirect()->route('contact.index', ['category' => 'feedback']);
})->name('feedback');


// Adverts API
Route::get('/api/adverts', [\App\Http\Controllers\Frontend\AdvertController::class, 'getActiveAdverts'])->name('adverts.index');
Route::post('/api/adverts/{advert}/view', [\App\Http\Controllers\Frontend\AdvertController::class, 'trackView'])->name('adverts.view');
Route::post('/api/adverts/{advert}/click', [\App\Http\Controllers\Frontend\AdvertController::class, 'trackClick'])->name('adverts.click');
Route::post('/api/adverts/{advert}/close', [\App\Http\Controllers\Frontend\AdvertController::class, 'closeAdvert'])->name('adverts.close');

// ========================
// AUTHENTICATED USER ROUTES
// ========================
Route::middleware('auth')->group(function () {
    // Profile routes (accessible to all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========================
// ADMIN ROUTES (protected)
// ========================
// Admin routes are now handled by Filament at /admin
// Old admin routes removed - using Filament 3 panel instead

// Admin API routes (requires admin role + MFA)
Route::prefix('admin')->middleware(['auth', 'role:admin', 'mfa', 'admin.access'])->group(function () {
    Route::get('/api/realtime/stream', [\App\Http\Controllers\Admin\RealtimeController::class, 'stream'])->name('admin.api.realtime.stream');
    Route::get('/api/realtime/poll', [\App\Http\Controllers\Admin\RealtimeController::class, 'poll'])->name('admin.api.realtime.poll');
    // Hybrid: API endpoint for social media settings
    Route::post('/api/social-media-settings', [\App\Http\Controllers\Admin\SettingsController::class, 'saveSocialMedia'])->name('admin.api.social-media-settings');
});

// ========================
// AUTH ROUTES (Laravel Breeze + Socialite)
// ========================
// Include Breeze auth routes
require __DIR__ . '/auth.php';

// Socialite OAuth routes
Route::middleware('guest')->group(function () {
    Route::get('/auth/{provider}', [\App\Http\Controllers\Auth\SocialiteController::class, 'redirect'])
        ->where('provider', 'google|facebook|twitter')
        ->name('socialite.redirect');

    Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Auth\SocialiteController::class, 'callback'])
        ->where('provider', 'google|facebook|twitter')
        ->name('socialite.callback');
});
