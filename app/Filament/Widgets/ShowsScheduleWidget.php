<?php

namespace App\Filament\Widgets;

use App\Models\Show;
use App\Models\LiveStream;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Shows & Schedules Widget
 * Displays show statistics and live status
 */
class ShowsScheduleWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalShows = Show::count();
        $liveShows = Show::where('is_live', true)->count();
        $activeStreams = LiveStream::where('status', 'live')->count();
        $scheduledShows = Show::where('status', 'scheduled')->count();

        return [
            Stat::make('Total Shows', $totalShows)
                ->description('All shows')
                ->descriptionIcon('heroicon-o-radio')
                ->color('info'),
            Stat::make('Live Shows', $liveShows)
                ->description('Currently on air')
                ->descriptionIcon('heroicon-o-signal')
                ->color('danger'),
            Stat::make('Active Streams', $activeStreams)
                ->description('Live streams')
                ->descriptionIcon('heroicon-o-radio')
                ->color('success'),
            Stat::make('Scheduled', $scheduledShows)
                ->description('Upcoming shows')
                ->descriptionIcon('heroicon-o-calendar')
                ->color('warning'),
        ];
    }
}

