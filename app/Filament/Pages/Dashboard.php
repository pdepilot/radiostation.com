<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ListenerCountWidget;
use App\Filament\Widgets\TotalVisitsWidget;
use App\Filament\Widgets\UniqueUsersWidget;
use App\Filament\Widgets\DashboardStatsWidget;
use App\Filament\Widgets\TrafficAnalyticsWidget;
use App\Filament\Widgets\TrafficTrendWidget;
use App\Filament\Widgets\TopCitiesWidget;
use App\Filament\Widgets\ListenerAnalyticsChartWidget;
use App\Filament\Widgets\ShowsScheduleWidget;
use App\Filament\Widgets\MessagesFeedbackWidget;
use App\Filament\Widgets\AdsDealsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

/**
 * Custom Dashboard
 * Professionally organized with logical sections:
 * 
 * Section 1: Live & Real-time Stats (Priority)
 * - ListenerCountWidget (Live listener count)
 * 
 * Section 2: Site Analytics Overview
 * - TotalVisitsWidget (Total & today's visits)
 * - UniqueUsersWidget (Logged-in users & unique visitors)
 * - DashboardStatsWidget (Content statistics)
 * - TrafficAnalyticsWidget (Engagement metrics)
 * 
 * Section 3: Analytics Charts
 * - TrafficTrendWidget (7-day traffic trend)
 * - TopCitiesWidget (Top 5 cities by traffic)
 * - ListenerAnalyticsChartWidget (Listener analytics over time)
 * 
 * Section 4: Content & Operations
 * - ShowsScheduleWidget (Show statistics)
 * - MessagesFeedbackWidget (Contact messages)
 * - AdsDealsWidget (Business/Sponsors)
 */
class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static ?int $navigationSort = 1;

    /**
     * Get header widgets - Section 1: Live & Real-time Stats
     * These are the most critical widgets shown at the top
     */
    protected function getHeaderWidgets(): array
    {
        return [
            ListenerCountWidget::class,
        ];
    }
    
    /**
     * Get footer widgets - All other widgets organized by sort order
     * Filament will automatically arrange them based on $sort property
     */
    protected function getFooterWidgets(): array
    {
        return [
            // Section 2: Site Analytics Overview
            TotalVisitsWidget::class,
            UniqueUsersWidget::class,
            DashboardStatsWidget::class,
            TrafficAnalyticsWidget::class,
            
            // Section 3: Analytics Charts
            TrafficTrendWidget::class,
            TopCitiesWidget::class,
            ListenerAnalyticsChartWidget::class,
            
            // Section 4: Content & Operations
            ShowsScheduleWidget::class,
            MessagesFeedbackWidget::class,
            AdsDealsWidget::class,
        ];
    }
}
