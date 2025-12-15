<?php

namespace App\Filament\Widgets;

use App\Models\LiveStream;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Actions\Action;

/**
 * Live Listener Count Widget
 * Displays real-time listener count on the dashboard
 */
class ListenerCountWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    
    protected static ?string $pollingInterval = '30s';

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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reset')
                ->label('Reset')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Reset Listener Count')
                ->modalDescription('Are you sure you want to reset the listener count to 0? This action cannot be undone.')
                ->modalSubmitActionLabel('Reset')
                ->action(function () {
                    $liveStream = LiveStream::where('status', 'live')->first();
                    if ($liveStream) {
                        $liveStream->update(['listener_count' => 0]);
                    }
                }),
        ];
    }
}

