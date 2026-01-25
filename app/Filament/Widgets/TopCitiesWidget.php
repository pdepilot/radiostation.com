<?php

namespace App\Filament\Widgets;

use App\Models\SiteAnalytics;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TopCitiesWidget extends ChartWidget
{
    protected static ?string $heading = 'Top 5 Cities by Traffic';

    protected static ?int $sort = 21; // Section 3: Analytics Charts - Geographic
    
    public function getColumnSpan(): int | array
    {
        return [
            'md' => 2,
            'xl' => 1,
        ];
    }

    protected function getData(): array
    {
        $topCities = SiteAnalytics::select('city', DB::raw('count(*) as visits'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderByDesc('visits')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Visits',
                    'data' => $topCities->pluck('visits')->toArray(),
                    'backgroundColor' => [
                        'rgba(239, 68, 68, 0.8)', // red
                        'rgba(249, 115, 22, 0.8)', // orange
                        'rgba(234, 179, 8, 0.8)',  // yellow
                        'rgba(34, 197, 94, 0.8)',  // green
                        'rgba(59, 130, 246, 0.8)', // blue
                    ],
                    'borderColor' => [
                        'rgb(239, 68, 68)',
                        'rgb(249, 115, 22)',
                        'rgb(234, 179, 8)',
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)',
                    ],
                ],
            ],
            'labels' => $topCities->pluck('city')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => false,
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
