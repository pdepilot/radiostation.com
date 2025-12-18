<?php

namespace App\Filament\Widgets;

use App\Models\ChatbotKnowledge;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Chatbot Info Widget
 * Displays chatbot knowledge base statistics
 */
class ChatbotInfoWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        return [];
    }
}

