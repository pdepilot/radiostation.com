<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin.access' => \App\Http\Middleware\EnsureAdminAccess::class,
            'role' => \App\Http\Middleware\EnsureRole::class,
            'mfa' => \App\Http\Middleware\RequireMfa::class,
        ]);

        $middleware->append(\App\Http\Middleware\TrackSiteAnalytics::class);
        
        // Exclude Paystack webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'promotions/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
