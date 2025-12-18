<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Messages & Feedback Widget
 * Displays contact messages and feedback statistics
 */
class MessagesFeedbackWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected static ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        return [];
    }
}
