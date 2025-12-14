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
    protected static ?int $sort = 7;

    protected function getStats(): array
    {
        $totalSponsors = Sponsor::count();
        $featuredSponsors = Sponsor::where('is_featured', true)->count();
        $activeSponsors = Sponsor::where('status', 'active')->count();

        return [
            Stat::make('Total Sponsors', $totalSponsors)
                ->description('All sponsors')
                ->descriptionIcon('heroicon-o-building-office')
                ->color('info'),
            Stat::make('Featured Sponsors', $featuredSponsors)
                ->description('Homepage featured')
                ->descriptionIcon('heroicon-o-star')
                ->color('warning'),
            Stat::make('Active Sponsors', $activeSponsors)
                ->description('Currently active')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
        ];
    }
}

