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
    
    public ?int $selectedMonth = null;
    
    public ?int $selectedYearForYearly = null;
    
    public ?string $selectedWeek = null; // Format: YYYY-WW (e.g., 2024-01)

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
    
    protected function getWeekOptions(): array
    {
        $weeks = [];
        $currentYear = now()->year;
        
        // Get all weeks that have data
        $weeksWithData = \App\Models\AudienceMetric::selectRaw('YEAR(captured_for) as year, WEEK(captured_for, 1) as week')
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('week', 'desc')
            ->get();
        
        foreach ($weeksWithData as $weekData) {
            $weekKey = $weekData->year . '-' . str_pad($weekData->week, 2, '0', STR_PAD_LEFT);
            $weekStart = \Carbon\Carbon::now()->setISODate($weekData->year, $weekData->week)->startOfWeek();
            $weekEnd = $weekStart->copy()->endOfWeek();
            $weeks[$weekKey] = "Week {$weekData->week}, {$weekData->year} ({$weekStart->format('M d')} - {$weekEnd->format('M d')})";
        }
        
        // Always include current week
        $currentWeek = now()->format('o-\WW');
        if (!isset($weeks[$currentWeek])) {
            $weekStart = now()->startOfWeek();
            $weekEnd = now()->endOfWeek();
            $weeks[$currentWeek] = "Current Week ({$weekStart->format('M d')} - {$weekEnd->format('M d')})";
        }
        
        return $weeks;
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Action::make('selectWeek')
                ->form([
                    Select::make('selectedWeek')
                        ->label('Select Week')
                        ->options($this->getWeekOptions())
                        ->default($this->selectedWeek ?? now()->format('o-\WW'))
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->selectedWeek = $data['selectedWeek'];
                })
                ->icon('heroicon-o-calendar')
                ->label('Select Week')
                ->visible(fn () => $this->filter === 'day'),
            
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
            
            Action::make('selectMonthYear')
                ->form([
                    Select::make('selectedYear')
                        ->label('Select Year')
                        ->options($this->getYearOptions())
                        ->default($this->selectedYear ?? now()->year)
                        ->searchable()
                        ->required(),
                    Select::make('selectedMonth')
                        ->label('Select Month')
                        ->options([
                            1 => 'January',
                            2 => 'February',
                            3 => 'March',
                            4 => 'April',
                            5 => 'May',
                            6 => 'June',
                            7 => 'July',
                            8 => 'August',
                            9 => 'September',
                            10 => 'October',
                            11 => 'November',
                            12 => 'December',
                        ])
                        ->default($this->selectedMonth ?? now()->month)
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->selectedYear = $data['selectedYear'];
                    $this->selectedMonth = $data['selectedMonth'];
                })
                ->icon('heroicon-o-calendar')
                ->label('Select Month/Year')
                ->visible(fn () => $this->filter === 'week'),
            
            Action::make('selectYearForYearly')
                ->form([
                    Select::make('selectedYearForYearly')
                        ->label('Select Starting Year')
                        ->options($this->getYearOptions())
                        ->default($this->selectedYearForYearly ?? now()->year)
                        ->searchable()
                        ->required()
                        ->helperText('Shows this year and 3 previous years'),
                ])
                ->action(function (array $data) {
                    $this->selectedYearForYearly = $data['selectedYearForYearly'];
                })
                ->icon('heroicon-o-calendar')
                ->label('Select Year Range')
                ->visible(fn () => $this->filter === 'year'),
        ];
    }

    protected function getData(): array
    {
        $period = $this->filter ?? 'month';
        
        // Get real data directly from database (no API call needed)
        $series = [];
        
        if ($period === 'day') {
            // Show Mon-Sun of selected week or current week
            if ($this->selectedWeek) {
                // Parse week string (format: YYYY-WW)
                [$year, $week] = explode('-', $this->selectedWeek);
                $weekStart = \Carbon\Carbon::now()->setISODate($year, $week)->startOfWeek();
            } else {
                // Default to current week
                $weekStart = now()->startOfWeek();
            }
            
            $weekEnd = $weekStart->copy()->endOfWeek();
            
            // Get all 7 days (Mon-Sun)
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $series = [];
            
            for ($i = 0; $i < 7; $i++) {
                $dayDate = $weekStart->copy()->addDays($i);
                $dayValue = \App\Models\AudienceMetric::whereDate('captured_for', $dayDate)
                    ->sum('total_listening_sessions') ?? 0;
                
                $series[] = [
                    'value' => $dayValue,
                    'date' => $days[$i] . ' ' . $dayDate->format('M d'),
                ];
            }
        } elseif ($period === 'week') {
            // Show 4 weeks of selected month/year or current month
            $year = $this->selectedYear ?? now()->year;
            $month = $this->selectedMonth ?? now()->month;
            
            $monthStart = \Carbon\Carbon::create($year, $month, 1)->startOfMonth();
            $monthEnd = \Carbon\Carbon::create($year, $month, 1)->endOfMonth();
            
            // Calculate 4 weeks within the month
            $weeks = [];
            $currentWeekStart = $monthStart->copy();
            
            for ($weekNum = 1; $weekNum <= 4; $weekNum++) {
                $weekEnd = $currentWeekStart->copy()->endOfWeek();
                
                // Don't go beyond month end
                if ($weekEnd->gt($monthEnd)) {
                    $weekEnd = $monthEnd->copy();
                }
                
                $weekValue = \App\Models\AudienceMetric::whereBetween('captured_for', [$currentWeekStart, $weekEnd])
                    ->sum('total_listening_sessions') ?? 0;
                
                $weeks[] = [
                    'value' => $weekValue,
                    'date' => "Week {$weekNum}",
                ];
                
                // Move to next week
                $currentWeekStart = $weekEnd->copy()->addDay()->startOfWeek();
                
                // Break if we've passed the month end
                if ($currentWeekStart->gt($monthEnd)) {
                    break;
                }
            }
            
            // Ensure we always have 4 weeks (fill with 0 if needed)
            while (count($weeks) < 4) {
                $weeks[] = [
                    'value' => 0,
                    'date' => 'Week ' . (count($weeks) + 1),
                ];
            }
            
            $series = $weeks;
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
            // Yearly - show 4 years (current year and 3 previous years)
            $startYear = $this->selectedYearForYearly ?? now()->year;
            
            for ($i = 3; $i >= 0; $i--) {
                $year = $startYear - $i;
                $yearStart = \Carbon\Carbon::create($year, 1, 1)->startOfYear();
                $yearEnd = \Carbon\Carbon::create($year, 12, 31)->endOfYear();
                
                $yearValue = \App\Models\AudienceMetric::whereBetween('captured_for', [$yearStart, $yearEnd])
                    ->sum('total_listening_sessions') ?? 0;
                
                $series[] = [
                    'value' => $yearValue,
                    'date' => (string)$year,
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

