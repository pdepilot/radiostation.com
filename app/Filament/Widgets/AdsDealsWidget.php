<?php

namespace App\Filament\Widgets;

use App\Models\Sponsor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Ads & Deals Overview Widget
 * Displays advertising and sponsorship statistics
 */
class AdsDealsWidget extends BaseWidget
{
    protected static ?int $sort = 32; // Section 4: Content & Operations (Business)

    protected function getStats(): array
    {
        $featuredSponsors = Sponsor::where('is_featured', true)->count();

        return [
            Stat::make('Featured Sponsors', $featuredSponsors)
                ->description('Homepage featured')
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),
        ];
    }
}

