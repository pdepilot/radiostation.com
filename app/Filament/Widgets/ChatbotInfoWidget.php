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
        $totalEntries = ChatbotKnowledge::count();
        $activeEntries = ChatbotKnowledge::where('is_active', true)->count();
        $totalUsage = ChatbotKnowledge::sum('usage_count');
        $categories = ChatbotKnowledge::distinct('category')->count('category');

        return [
            Stat::make('Knowledge Entries', $totalEntries)
                ->description('Total FAQ entries')
                ->descriptionIcon('heroicon-o-book-open')
                ->color('info'),
            Stat::make('Active Entries', $activeEntries)
                ->description('Currently active')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Total Usage', $totalUsage)
                ->description('Times used')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('warning'),
            Stat::make('Categories', $categories)
                ->description('Knowledge categories')
                ->descriptionIcon('heroicon-o-tag')
                ->color('primary'),
        ];
    }
}

