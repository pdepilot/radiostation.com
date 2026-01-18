<?php

namespace App\Filament\Widgets;

use App\Models\SiteAnalytics;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class UniqueUsersWidget extends BaseWidget
{
    protected static ?int $sort = 11; // Section 2: Site Analytics Overview
    
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $uniqueLoggedInUsers = SiteAnalytics::whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        $todayUniqueUsers = SiteAnalytics::whereDate('created_at', today())
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count('user_id');

        $uniqueIPs = SiteAnalytics::distinct('ip')->count('ip');
        $todayUniqueIPs = SiteAnalytics::whereDate('created_at', today())
            ->distinct('ip')
            ->count('ip');

        return [
            Stat::make('Logged-in Users', number_format($uniqueLoggedInUsers))
                ->description('Unique registered users who visited')
                ->descriptionIcon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Today\'s Active Users', number_format($todayUniqueUsers))
                ->description('Logged-in users today')
                ->descriptionIcon('heroicon-o-user-circle')
                ->color('info'),
            Stat::make('Unique Visitors', number_format($uniqueIPs))
                ->description('Total unique IP addresses')
                ->descriptionIcon('heroicon-o-sparkles')
                ->color('warning'),
        ];
    }
}

