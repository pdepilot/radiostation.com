<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudienceMetric;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\LiveStream;
use App\Models\NewsPost;
use App\Models\RevenueRecord;
use App\Models\Show;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalNewsViews = NewsPost::sum('view_count');
        $totalShowViews = Show::sum('view_count') ?? 0;
        $totalUsers = User::where('role', 'user')->count();

        // Get current live listener count from active LiveStream (prioritize real-time data)
        $liveStream = LiveStream::where('status', 'live')->first();
        $currentLiveListeners = $liveStream ? $liveStream->listener_count : 0;

        // Calculate monthly listeners from real metrics; if missing, fall back to peak sum to avoid dummy data
        $monthlyListeners = AudienceMetric::whereYear('captured_for', now()->year)
            ->whereMonth('captured_for', now()->month)
            ->sum(DB::raw('COALESCE(average_listeners, peak_listeners)')) ?? 0;

        // Use current live count for real-time display, fall back to monthly historical data if no live stream
        $monthlyListeners = $currentLiveListeners > 0 ? $currentLiveListeners : $monthlyListeners;

        // Get real-time data
        $topNews = NewsPost::orderByDesc('view_count')->take(5)->get();
        $topShows = Show::orderByDesc('view_count')->take(5)->get();

        // Get audience data for different periods
        $audienceSeries = AudienceMetric::orderByDesc('captured_for')->take(7)->get()->reverse();
        $dailyListeners = AudienceMetric::whereDate('captured_for', today())->sum('peak_listeners') ?? 0;
        $weeklyListeners = AudienceMetric::whereBetween('captured_for', [now()->startOfWeek(), now()->endOfWeek()])->sum('peak_listeners') ?? 0;
        $monthlyListenersTotal = AudienceMetric::whereYear('captured_for', now()->year)
            ->whereMonth('captured_for', now()->month)
            ->sum('peak_listeners') ?? 0;

        // Calculate percentage changes
        $previousMonthListeners = AudienceMetric::whereYear('captured_for', now()->subMonth()->year)
            ->whereMonth('captured_for', now()->subMonth()->month)
            ->sum('average_listeners') ?? 0;
        $listenerChange = $previousMonthListeners > 0
            ? round((($monthlyListeners - $previousMonthListeners) / $previousMonthListeners) * 100, 1)
            : 0;

        return view('admin.dashboard', [
            'stats' => [
                'shows' => Show::count(),
                'news' => NewsPost::count(),
                'pendingMessages' => ContactMessage::where('status', 'new')->count(),
                'activeLiveStream' => LiveStream::where('status', 'live')->count(),
                'totalNewsViews' => $totalNewsViews,
                'totalShowViews' => $totalShowViews,
                'totalUsers' => $totalUsers,
                'monthlyListeners' => $monthlyListeners,
                'listenerChange' => $listenerChange,
            ],
            'latestMessages' => ContactMessage::latest()->take(5)->get(),
            'audienceSeries' => $audienceSeries,
            'dailyListeners' => $dailyListeners,
            'weeklyListeners' => $weeklyListeners,
            'monthlyListenersTotal' => $monthlyListenersTotal,
            'topNews' => $topNews,
            'topShows' => $topShows,
        ]);
    }

    public function getListenerAnalytics(Request $request)
    {
        $period = $request->get('period', 'month');

        // Get live listener count for real-time display
        $liveStream = LiveStream::where('status', 'live')->first();
        $currentLiveListeners = $liveStream ? $liveStream->listener_count : 0;

        // For daily count, include both historical data and current live count
        $historicalDaily = AudienceMetric::whereDate('captured_for', today())->sum('peak_listeners') ?? 0;
        // For daily count, use total listening sessions (cumulative)
        $dailySessions = AudienceMetric::whereDate('captured_for', today())->sum('total_listening_sessions') ?? 0;

        $weeklyListeners = AudienceMetric::whereBetween('captured_for', [now()->startOfWeek(), now()->endOfWeek()])->sum('total_listening_sessions') ?? 0;
        $monthlyListenersTotal = AudienceMetric::whereYear('captured_for', now()->year)
            ->whereMonth('captured_for', now()->month)
            ->sum('total_listening_sessions') ?? 0;

        $series = [];

        // Use real data only - no sample/dummy data
        // If no data exists, return empty series (will display 0)

        if ($period === 'day') {
            // Last 7 days including today - real data only
            $series = AudienceMetric::whereDate('captured_for', '>=', now()->subDays(6))
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
                $series[] = [
                    'value' => $dailySessions > 0 ? $dailySessions : 0,
                    'date' => now()->format('M d'),
                ];
            }
        } elseif ($period === 'week') {
            // Last 7 days - real data only
            $series = AudienceMetric::whereDate('captured_for', '>=', now()->subDays(6))
                ->orderBy('captured_for')
                ->get()
                ->map(function ($metric) {
                    return [
                        'value' => $metric->total_listening_sessions ?? 0,
                        'date' => $metric->captured_for->format('M d'),
                    ];
                })->toArray();
        } elseif ($period === 'month') {
            // Last 30 days - real data only
            $series = AudienceMetric::whereDate('captured_for', '>=', now()->subDays(29))
                ->orderBy('captured_for')
                ->get()
                ->map(function ($metric) {
                    return [
                        'value' => $metric->total_listening_sessions ?? 0,
                        'date' => $metric->captured_for->format('M d'),
                    ];
                })->toArray();
        } else {
            // Yearly - last 12 months - real data only
            $series = [];
            for ($i = 11; $i >= 0; $i--) {
                $monthStart = now()->subMonths($i)->startOfMonth();
                $monthEnd = now()->subMonths($i)->endOfMonth();
                $monthValue = AudienceMetric::whereBetween('captured_for', [$monthStart, $monthEnd])
                    ->sum('total_listening_sessions') ?? 0;
                
                $series[] = [
                    'value' => $monthValue,
                    'date' => $monthStart->format('M Y'),
                ];
            }
        }

        // Calculate yearly total (real data only)
        $yearlyTotal = AudienceMetric::whereYear('captured_for', now()->year)
            ->sum('total_listening_sessions') ?? 0;

        return response()->json([
            'daily' => $dailySessions,
            'weekly' => $weeklyListeners,
            'monthly' => $monthlyListenersTotal,
            'yearly' => $yearlyTotal,
            'series' => $series,
        ]);
    }

    // Sample data generation removed - using real data only
}
