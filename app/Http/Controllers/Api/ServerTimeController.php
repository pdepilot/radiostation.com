<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Server Time API Controller
 * Returns server timestamp for live stream position sync
 */
class ServerTimeController extends Controller
{
    /**
     * Get server time in Unix timestamp (seconds)
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'timestamp' => now()->timestamp,
            'iso' => now()->toIso8601String(),
        ]);
    }
}
