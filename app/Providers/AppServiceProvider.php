<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;
use App\Models\NewsPost;
use App\Models\Show;
use App\Models\Event;
use App\Observers\NewsPostObserver;
use App\Observers\ShowObserver;
use App\Observers\EventObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for content updates
        NewsPost::observe(NewsPostObserver::class);
        Show::observe(ShowObserver::class);
        Event::observe(EventObserver::class);
        
        // Share social media links with all views
        View::composer('layouts.frontend', function ($view) {
            try {
                $view->with([
                    'twitterUrl' => SiteSetting::value('twitter_url'),
                    'instagramUrl' => SiteSetting::value('instagram_url'),
                    'facebookUrl' => SiteSetting::value('facebook_url'),
                    'youtubeUrl' => SiteSetting::value('youtube_url'),
                    'tiktokUrl' => SiteSetting::value('tiktok_url'),
                ]);
            } catch (\Exception $e) {
                $view->with([
                    'twitterUrl' => null,
                    'instagramUrl' => null,
                    'facebookUrl' => null,
                    'youtubeUrl' => null,
                    'tiktokUrl' => null,
                ]);
            }
        });

        // Override the public disk URL to dynamically use the request's base URL
        // This ensures storage URLs work regardless of how Laravel is accessed
        if ($this->app->runningInConsole() === false) {
            // Only override when handling HTTP requests
            $baseUrl = request()->getSchemeAndHttpHost() . request()->getBasePath();
            config([
                'filesystems.disks.public.url' => rtrim($baseUrl, '/') . '/storage',
            ]);
        }

        // Schedule the auto-update live shows command to run every minute
        // Note: Homepage updates immediately via JavaScript (every 10 seconds) which checks time-based detection
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('shows:auto-update')->everyMinute();
            // Schedule weekly listener count reset - runs at the start of each month (when week 1 begins)
            // This resets data when week 4 is done and we enter week 1
            $schedule->command('listeners:reset-weekly')->monthlyOn(1, '00:00');
            // Schedule monthly listener count reset - runs on January 1st (after December)
            // This resets data when December is done and we enter January
            $schedule->command('listeners:reset-monthly')->yearlyOn(1, 1, '00:00');
            // Schedule yearly listener count reset - runs on January 1st at midnight
            $schedule->command('listeners:reset-yearly')->yearly();
        });
    }
}
