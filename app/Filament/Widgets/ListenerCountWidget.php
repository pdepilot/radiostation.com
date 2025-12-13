<?php

namespace App\Filament\Widgets;

use App\Models\LiveStream;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Live Listener Count Widget
 * Displays real-time listener count on the dashboard
 */
class ListenerCountWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $liveStream = LiveStream::where('status', 'live')->first();
        $currentListeners = $liveStream ? $liveStream->listener_count : 0;

        return [
            Stat::make('Live Listeners', $currentListeners)
                ->description('Active now')
                ->descriptionIcon('heroicon-o-radio')
                ->color('danger'),
        ];
    }
}

