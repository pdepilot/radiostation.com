<?php

namespace App\Filament\Widgets;

use App\Models\NewsPost;
use App\Models\Show;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Traffic Analytics Widget
 * Displays basic traffic and engagement statistics
 */
class TrafficAnalyticsWidget extends BaseWidget
{
    protected static ?int $sort = 6;
    
    protected static ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        $totalNewsViews = NewsPost::sum('view_count');
        $totalShowViews = Show::sum('view_count') ?? 0;
        $totalUsers = User::where('role', 'user')->count();

        return [
            Stat::make('News Views', number_format($totalNewsViews))
                ->description('Total article views')
                ->descriptionIcon('heroicon-o-eye')
                ->color('info'),
            Stat::make('Show Views', number_format($totalShowViews))
                ->description('Total show page views')
                ->descriptionIcon('heroicon-o-eye')
                ->color('success'),
            Stat::make('Registered Users', number_format($totalUsers))
                ->description('Total user accounts')
                ->descriptionIcon('heroicon-o-users')
                ->color('warning'),
        ];
    }
}

