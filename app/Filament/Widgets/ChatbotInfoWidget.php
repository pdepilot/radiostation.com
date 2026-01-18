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
    protected static ?int $sort = 40; // Section 5: Additional Tools (Optional - empty for now)

    protected function getStats(): array
    {
        return [];
    }
}

