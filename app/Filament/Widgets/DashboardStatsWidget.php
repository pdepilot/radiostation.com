<?php

namespace App\Filament\Widgets;

use App\Models\NewsPost;
use App\Models\Show;
use App\Models\Event;
use App\Models\ContactMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Dashboard Stats Widget
 * Displays overview statistics for the admin dashboard
 */
class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Total News Posts', NewsPost::count())
                ->description('Published articles')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('success'),
            Stat::make('Active Shows', Show::where('status', '!=', 'cancelled')->count())
                ->description('Scheduled & live')
                ->descriptionIcon('heroicon-o-radio')
                ->color('info'),
            Stat::make('Upcoming Events', Event::where('status', 'upcoming')->count())
                ->description('Scheduled events')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('warning'),
            Stat::make('New Messages', ContactMessage::where('status', 'new')->count())
                ->description('Unread contact messages')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('danger'),
        ];
    }
}

