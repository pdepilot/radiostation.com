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
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\LiveStreamController;
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

Route::get('/live', [LiveStreamController::class, 'index'])->name('live');

Route::get('/shows', [ShowController::class, 'index'])->name('shows.index');
Route::get('/shows/{show:slug}', [ShowController::class, 'show'])->name('shows.show');

Route::get('/djs', [DjController::class, 'index'])->name('djs.index');
Route::get('/djs/{dj:slug}', [DjController::class, 'show'])->name('djs.show');

Route::get('/playlist', [PlaylistController::class, 'index'])->name('playlist.index');

Route::get('/podcasts', [PodcastController::class, 'index'])->name('podcasts.index');
Route::get('/podcasts/{podcast:slug}', [PodcastController::class, 'show'])->name('podcasts.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{newsPost:slug}', [NewsController::class, 'show'])->name('news.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// ========================
// AUTHENTICATED USER ROUTES
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ========================
// ADMIN ROUTES (protected)
// ========================
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('shows', AdminShowController::class)->except(['show']);
    Route::resource('djs', AdminDjController::class)->except(['show']);
    Route::resource('news', AdminNewsController::class)->except(['show']);
    Route::resource('podcasts', AdminPodcastController::class)->except(['show']);

    Route::resource('playlist', AdminPlaylistController::class)->only(['index', 'store', 'destroy']);
    Route::resource('livestreams', AdminLiveStreamController::class)->only(['index', 'update']);

    Route::resource('audience', AudienceController::class)->only(['index']);
    Route::resource('advertising', AdvertisingController::class)->except(['show', 'edit', 'create']);
    Route::resource('revenue', RevenueController::class)->only(['index', 'store', 'update']);

    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'store'])->name('settings.store');
});

// ========================
// AUTH ROUTES (login, register, etc)
// ========================
require __DIR__.'/auth.php';