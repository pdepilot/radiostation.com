<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ListenerCountWidget;
use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\ListenerAnalyticsChartWidget;
use App\Filament\Widgets\MessagesFeedbackWidget;
use App\Filament\Widgets\ShowsScheduleWidget;
// use App\Filament\Widgets\ChatbotInfoWidget;
use App\Filament\Widgets\TrafficAnalyticsWidget;
use App\Filament\Widgets\AdsDealsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

/**
 * Custom Dashboard
 * Shows live listener count, quick stats, and analytics charts
 */
class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?int $navigationSort = 1;

    protected function getHeaderWidgets(): array
    {
        return [
            // Widgets auto-discovered by Filament - no explicit registration needed
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            // Widgets auto-discovered by Filament - no explicit registration needed
        ];
    }
}

