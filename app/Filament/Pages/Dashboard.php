<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ListenerCountWidget;
use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\ListenerAnalyticsChartWidget;
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
            ListenerCountWidget::class,
        ];
    }
    
    protected function getFooterWidgets(): array
    {
        return [
            DashboardStatsWidget::class,
            ListenerAnalyticsChartWidget::class,
        ];
    }
}

