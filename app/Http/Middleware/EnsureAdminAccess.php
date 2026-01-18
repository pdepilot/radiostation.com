<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Enforce HTTPS
        if (!$request->secure() && app()->environment('production')) {
            return redirect()->secure($request->getRequestUri());
        }

        // 2. IP Whitelist Check
        $allowedIps = $this->getAllowedIps();
        if (!empty($allowedIps)) {
            $clientIp = $request->ip();
            
            // Check if IP is in whitelist
            $isAllowed = false;
            foreach ($allowedIps as $allowedIp) {
                // Support CIDR notation
                if ($this->ipInRange($clientIp, $allowedIp)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                \Log::warning('Admin access denied for IP: ' . $clientIp);
                abort(403, 'Access denied. Your IP address is not authorized.');
            }
        }

        // 3. Throttling - Limit admin requests per minute
        $key = 'admin-access:' . $request->ip();
        $maxAttempts = 60; // 60 requests per minute
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            \Log::warning('Admin access throttled for IP: ' . $request->ip());
            abort(429, 'Too many requests. Please try again in ' . ceil($seconds / 60) . ' minute(s).');
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        return $next($request);
    }

    /**
     * Get allowed IP addresses from config or env
     */
    private function getAllowedIps(): array
    {
        $ips = env('ADMIN_ALLOWED_IPS', '');
        
        if (empty($ips)) {
            return [];
        }

        return array_map('trim', explode(',', $ips));
    }

    /**
     * Check if an IP is in a range (supports CIDR notation)
     */
    private function ipInRange(string $ip, string $range): bool
    {
        // If no CIDR notation, do exact match
        if (strpos($range, '/') === false) {
            return $ip === $range;
        }

        // Handle CIDR notation
        list($subnet, $mask) = explode('/', $range);
        
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $this->ipv4InRange($ip, $subnet, (int)$mask);
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $this->ipv6InRange($ip, $subnet, (int)$mask);
        }

        return false;
    }

    /**
     * Check if IPv4 is in CIDR range
     */
    private function ipv4InRange(string $ip, string $subnet, int $mask): bool
    {
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << (32 - $mask);
        
        return ($ipLong & $maskLong) === ($subnetLong & $maskLong);
    }

    /**
     * Check if IPv6 is in CIDR range
     */
    private function ipv6InRange(string $ip, string $subnet, int $mask): bool
    {
        $ipBin = inet_pton($ip);
        $subnetBin = inet_pton($subnet);
        
        $bytes = intval($mask / 8);
        $bits = $mask % 8;
        
        // Compare full bytes
        if (substr($ipBin, 0, $bytes) !== substr($subnetBin, 0, $bytes)) {
            return false;
        }
        
        // Compare partial byte if needed
        if ($bits > 0) {
            $ipByte = ord(substr($ipBin, $bytes, 1));
            $subnetByte = ord(substr($subnetBin, $bytes, 1));
            $maskByte = 0xFF << (8 - $bits);
            
            return ($ipByte & $maskByte) === ($subnetByte & $maskByte);
        }
        
        return true;
    }
}

