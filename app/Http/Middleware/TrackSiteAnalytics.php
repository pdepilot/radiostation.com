<?php

namespace App\Http\Middleware;

use App\Models\SiteAnalytics;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackSiteAnalytics
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests to avoid duplicate tracking
        if ($request->isMethod('GET') && !$request->is('admin/*') && !$request->is('api/*')) {
            try {
                $this->trackVisit($request);
            } catch (\Exception $e) {
                // Log error but don't break the request
                Log::error('Site analytics tracking failed: ' . $e->getMessage());
            }
        }

        return $response;
    }

    /**
     * Track the visit
     */
    protected function trackVisit(Request $request): void
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $page = $request->path();
        
        // Get user ID if authenticated
        $userId = auth()->id();

        // Get location data from IP
        $location = $this->getLocationFromIp($ip);

        // Use cursor-based insert for better performance with large datasets
        SiteAnalytics::create([
            'user_id' => $userId,
            'ip' => $ip,
            'city' => $location['city'] ?? null,
            'state' => $location['state'] ?? null,
            'country' => $location['country'] ?? null,
            'page' => $page,
            'user_agent' => $userAgent,
            'created_at' => now(),
        ]);
    }

    /**
     * Get location data from IP address
     * Using a simple HTTP API (ipapi.co - free tier available)
     * You can replace this with any IP geolocation service
     */
    protected function getLocationFromIp(string $ip): array
    {
        // Skip private/local IPs
        if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return [];
        }

        try {
            // Using ipapi.co (free tier: 1000 requests/day)
            // You can also use: ip-api.com, ipgeolocation.io, etc.
            $url = "http://ip-api.com/json/{$ip}?fields=status,message,city,regionName,country,countryCode";
            
            $context = stream_context_create([
                'http' => [
                    'timeout' => 2, // 2 second timeout
                    'method' => 'GET',
                ]
            ]);

            $response = @file_get_contents($url, false, $context);
            
            if ($response) {
                $data = json_decode($response, true);
                
                if (isset($data['status']) && $data['status'] === 'success') {
                    return [
                        'city' => $data['city'] ?? null,
                        'state' => $data['regionName'] ?? null,
                        'country' => $data['country'] ?? null,
                    ];
                }
            }
        } catch (\Exception $e) {
            // Silently fail - location is optional
            Log::debug('IP geolocation failed: ' . $e->getMessage());
        }

        return [];
    }
}
