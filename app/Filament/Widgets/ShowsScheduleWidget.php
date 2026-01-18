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
    protected static ?int $sort = 30; // Section 4: Content & Operations
    
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [];
    }
}

