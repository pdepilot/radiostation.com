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
        $totalMessages = ContactMessage::count();
        $newMessages = ContactMessage::whereIn('status', ['new', 'pending'])->count();
        $feedbackCount = ContactMessage::where('type', 'feedback')->count();
        $unreadCount = ContactMessage::where('status', 'new')->count();

        return [
            Stat::make('Total Messages', $totalMessages)
                ->description('All contact messages')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('info'),
            Stat::make('New Messages', $newMessages)
                ->description('Unread & pending')
                ->descriptionIcon('heroicon-o-envelope-open')
                ->color('warning'),
            Stat::make('Feedback', $feedbackCount)
                ->description('Feedback submissions')
                ->descriptionIcon('heroicon-o-chat-bubble-left-right')
                ->color('success'),
            Stat::make('Unread', $unreadCount)
                ->description('Requires attention')
                ->descriptionIcon('heroicon-o-exclamation-circle')
                ->color('danger'),
        ];
    }
}
