<?php

namespace App\Filament\Widgets;

use App\Models\AudienceMetric;
use Filament\Widgets\ChartWidget;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;

/**
 * Listener Analytics Chart Widget
 * Bar chart for Daily, Weekly, Monthly, Yearly listener analytics
 * Uses real data only - no dummy/fake data
 */
class ListenerAnalyticsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Listener Analytics';
    
    protected static ?int $sort = 3;
    
    protected static ?string $pollingInterval = '30s';
    
    public ?string $filter = 'month';
    
    public ?int $selectedYear = null;

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Daily',
            'week' => 'Weekly',
            'month' => 'Monthly',
            'year' => 'Yearly',
        ];
    }
    
    protected function getYearOptions(): array
    {
        $currentYear = now()->year;
        $years = [];
        
        // Get all years that have data
        $yearsWithData = \App\Models\AudienceMetric::selectRaw('YEAR(captured_for) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        // Always include current year
        if (!in_array($currentYear, $yearsWithData)) {
            $yearsWithData[] = $currentYear;
        }
        
        // Sort descending
        rsort($yearsWithData);
        
        foreach ($yearsWithData as $year) {
            $years[$year] = (string)$year;
        }
        
        return $years;
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('selectYear')
                ->form([
                    Select::make('selectedYear')
                        ->label('Select Year')
                        ->options($this->getYearOptions())
                        ->default($this->selectedYear ?? now()->year)
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->selectedYear = $data['selectedYear'];
                })
                ->icon('heroicon-o-calendar')
                ->label('Select Year')
                ->visible(fn () => $this->filter === 'month'),
        ];
    }

    protected function getData(): array
    {
        $period = $this->filter ?? 'month';
        
        // Get real data directly from database (no API call needed)
        $series = [];
        
        if ($period === 'day') {
            // Last 7 days including today - real data only
            $series = \App\Models\AudienceMetric::whereDate('captured_for', '>=', now()->subDays(6))
                ->orderBy('captured_for')
                ->get()
                ->map(function ($metric) {
                    return [
                        'value' => $metric->total_listening_sessions ?? 0,
                        'date' => $metric->captured_for->format('M d'),
                    ];
                })->toArray();
            
            // Add today if not in series (real data only)
            $todayExists = collect($series)->contains(function ($item) {
                return $item['date'] === now()->format('M d');
            });
            if (!$todayExists) {
                $dailySessions = \App\Models\AudienceMetric::whereDate('captured_for', today())
                    ->sum('total_listening_sessions') ?? 0;
                $series[] = [
                    'value' => $dailySessions > 0 ? $dailySessions : 0,
                    'date' => now()->format('M d'),
                ];
            }
        } elseif ($period === 'week') {
            // Last 7 days - real data only
            $series = \App\Models\AudienceMetric::whereDate('captured_for', '>=', now()->subDays(6))
                ->orderBy('captured_for')
                ->get()
                ->map(function ($metric) {
                    return [
                        'value' => $metric->total_listening_sessions ?? 0,
                        'date' => $metric->captured_for->format('M d'),
                    ];
                })->toArray();
        } elseif ($period === 'month') {
            // Show all 12 months (Jan-Dec) of selected year or current year
            $year = $this->selectedYear ?? now()->year;
            
            for ($month = 1; $month <= 12; $month++) {
                $monthStart = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
                $monthEnd = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
                $monthValue = \App\Models\AudienceMetric::whereBetween('captured_for', [$monthStart, $monthEnd])
                    ->sum('total_listening_sessions') ?? 0;
                
                $series[] = [
                    'value' => $monthValue,
                    'date' => $monthStart->format('M'),
                ];
            }
        } else {
            // Yearly - last 12 months - real data only
            for ($i = 11; $i >= 0; $i--) {
                $monthStart = now()->subMonths($i)->startOfMonth();
                $monthEnd = now()->subMonths($i)->endOfMonth();
                $monthValue = \App\Models\AudienceMetric::whereBetween('captured_for', [$monthStart, $monthEnd])
                    ->sum('total_listening_sessions') ?? 0;
                
                $series[] = [
                    'value' => $monthValue,
                    'date' => $monthStart->format('M Y'),
                ];
            }
        }
        
        $labels = array_column($series, 'date');
        $values = array_column($series, 'value');
        
        // Ensure all values are numbers (real data only, 0 if missing)
        $values = array_map(function($val) {
            return is_numeric($val) ? (int)$val : 0;
        }, $values);
        
        // If no data, show empty chart with 0
        if (empty($labels)) {
            $labels = ['No Data'];
            $values = [0];
        }
        
        return [
            'datasets' => [
                [
                    'label' => 'Listening Sessions',
                    'data' => $values,
                    'backgroundColor' => 'rgba(255, 0, 0, 0.5)',
                    'borderColor' => 'rgba(255, 0, 0, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    
    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
        ];
    }
}

