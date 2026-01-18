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
    protected static ?int $sort = 1; // Section 1: Live & Real-time (Priority)
    
    protected static ?string $pollingInterval = '2s';

    protected function getStats(): array
    {
        // Get current listener count from active sessions
        $liveStream = LiveStream::where('status', 'live')->first();
        
        if ($liveStream) {
            $activeSessions = \App\Models\ListenerSession::where('live_stream_id', $liveStream->id)
                ->where('is_active', true)
                ->count();
            
            $currentListeners = $activeSessions > 0 ? $activeSessions : $liveStream->listener_count;
        } else {
            $currentListeners = 0;
        }

        return [
            Stat::make('Live Listeners', $currentListeners)
                ->description('Active now')
                ->descriptionIcon('heroicon-o-radio')
                ->color('danger')
                ->extraAttributes([
                    'class' => 'listener-count-stat-card',
                ]),
        ];
    }
}

