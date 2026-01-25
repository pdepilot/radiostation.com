<?php

namespace App\Filament\Widgets;

use App\Models\SiteAnalytics;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class TotalVisitsWidget extends BaseWidget
{
    protected static ?int $sort = 10; // Section 2: Site Analytics Overview
    
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalVisits = SiteAnalytics::count();
        $todayVisits = SiteAnalytics::whereDate('created_at', today())->count();
        $yesterdayVisits = SiteAnalytics::whereDate('created_at', today()->subDay())->count();
        $change = $yesterdayVisits > 0 
            ? round((($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100, 1)
            : ($todayVisits > 0 ? 100 : 0);

        return [
            Stat::make('Total Visits', number_format($totalVisits))
                ->description('All-time site visits (users + guests)')
                ->descriptionIcon('heroicon-o-globe-alt')
                ->color('success'),
            Stat::make('Today\'s Visits', number_format($todayVisits))
                ->description($change >= 0 ? "↑ {$change}% from yesterday" : "↓ " . abs($change) . "% from yesterday")
                ->descriptionIcon($change >= 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                ->color($change >= 0 ? 'success' : 'danger'),
        ];
    }
}

