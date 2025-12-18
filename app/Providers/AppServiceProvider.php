<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

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
