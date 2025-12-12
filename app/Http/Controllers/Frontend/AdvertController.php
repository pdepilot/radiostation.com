<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdvertController extends Controller
{
    public function getActiveAdverts(Request $request): JsonResponse
    {
        $showToRegistered = auth()->check();
        
        $adverts = Advertisement::active()
            ->where(function($query) use ($showToRegistered) {
                if (!$showToRegistered) {
                    // Show to non-registered users
                    $query->where('show_to_registered', false);
                } else {
                    // Show to registered users if explicitly set
                    $query->where('show_to_registered', true);
                }
            })
            ->where(function($query) {
                // Only show popup and sidebar ads in the container
                $query->where('position', 'popup')
                      ->orWhere('position', 'sidebar');
            })
            ->orderBy('position')
            ->get()
            ->map(function($advert) {
                return [
                    'id' => $advert->id,
                    'title' => $advert->title,
                    'type' => $advert->type,
                    'position' => $advert->position,
                    'image_url' => $advert->image_url,
                    'link_url' => $advert->link_url,
                    'google_adsense_code' => $advert->google_adsense_code,
                ];
            });

        return response()->json($adverts);
    }

    public function trackView(Request $request, Advertisement $advert): JsonResponse
    {
        $advert->incrementViews();
        
        // Store in session that user has viewed this ad (for close functionality)
        $viewedAds = session('viewed_ads', []);
        $viewedAds[] = $advert->id;
        session(['viewed_ads' => array_unique($viewedAds)]);

        return response()->json(['success' => true]);
    }

    public function trackClick(Request $request, Advertisement $advert): JsonResponse
    {
        $advert->incrementClicks();
        return response()->json(['success' => true]);
    }

    public function closeAdvert(Request $request, Advertisement $advert): JsonResponse
    {
        // Store closed ads in session
        $closedAds = session('closed_ads', []);
        $closedAds[] = $advert->id;
        session(['closed_ads' => array_unique($closedAds)]);

        return response()->json(['success' => true]);
    }
}
