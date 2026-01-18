<?php

namespace App\Filament\Widgets;

use App\Models\SiteAnalytics;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TrafficTrendWidget extends ChartWidget
{
    protected static ?string $heading = 'Traffic Trend (Last 7 Days)';

    protected static ?int $sort = 20; // Section 3: Analytics Charts - Time Series
    
    public function getColumnSpan(): int | array
    {
        return [
            'md' => 2,
            'xl' => 2,
        ];
    }

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return today()->subDays($day);
        });

        $visits = $days->map(function ($date) {
            return SiteAnalytics::whereDate('created_at', $date)->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Visits',
                    'data' => $visits->toArray(),
                    'borderColor' => 'rgb(239, 68, 68)',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $days->map(fn($date) => $date->format('M j'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
        ];
    }
}

