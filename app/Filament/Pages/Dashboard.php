<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ListenerCountWidget;
use App\Models\NewsPost;
use App\Models\Show;
use App\Models\Event;
use App\Models\ContactMessage;
use Filament\Pages\Dashboard as BaseDashboard;

/**
 * Custom Dashboard
 * Shows live listener count and quick stats
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
            \Filament\Widgets\StatsOverviewWidget::make([
                \Filament\Widgets\StatsOverviewWidget\Stat::make('Total News Posts', NewsPost::count())
                    ->description('Published articles')
                    ->descriptionIcon('heroicon-o-newspaper')
                    ->color('success'),
                \Filament\Widgets\StatsOverviewWidget\Stat::make('Active Shows', Show::where('status', '!=', 'cancelled')->count())
                    ->description('Scheduled & live')
                    ->descriptionIcon('heroicon-o-radio')
                    ->color('info'),
                \Filament\Widgets\StatsOverviewWidget\Stat::make('Upcoming Events', Event::where('status', 'upcoming')->count())
                    ->description('Scheduled events')
                    ->descriptionIcon('heroicon-o-calendar')
                    ->color('warning'),
                \Filament\Widgets\StatsOverviewWidget\Stat::make('New Messages', ContactMessage::where('status', 'new')->count())
                    ->description('Unread contact messages')
                    ->descriptionIcon('heroicon-o-envelope')
                    ->color('danger'),
            ]),
        ];
    }
}

