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

        // If no data exists, create sample data for demonstration
        $existingData = AudienceMetric::whereYear('captured_for', now()->year)->count();
        if ($existingData === 0) {
            Log::info('No audience metrics found, creating sample data');
            $this->createSampleAudienceData();
        }

        if ($period === 'day') {
            // Last 7 days + include today's live data
            $series = AudienceMetric::whereDate('captured_for', '>=', now()->subDays(6))
                ->whereDate('captured_for', '<', today()) // Exclude today from historical data
                ->orderBy('captured_for')
                ->get()
                ->map(function ($metric) {
                    return [
                        'value' => $metric->total_listening_sessions,
                        'date' => $metric->captured_for->format('M d'),
                    ];
                })->toArray();

            // Add today's live data as the most recent point
            $series[] = [
                'value' => $currentLiveListeners,
                'date' => now()->format('M d'),
                'is_live' => true // Mark as live data
            ];
        } elseif ($period === 'week') {
            // Last 6 days + today
            $series = AudienceMetric::whereDate('captured_for', '>=', now()->subDays(6))
                ->whereDate('captured_for', '<', today())
                ->orderBy('captured_for')
                ->get()
                ->map(function ($metric) {
                    return [
                        'value' => $metric->total_listening_sessions,
                        'date' => $metric->captured_for->format('M d'),
                    ];
                })->toArray();

            // Add today's live data
            $series[] = [
                'value' => $currentLiveListeners,
                'date' => now()->format('M d'),
                'is_live' => true
            ];
        } else {
            // Last 29 days + today
            $series = AudienceMetric::whereDate('captured_for', '>=', now()->subDays(29))
                ->whereDate('captured_for', '<', today())
                ->orderBy('captured_for')
                ->get()
                ->map(function ($metric) {
                    return [
                        'value' => $metric->total_listening_sessions,
                        'date' => $metric->captured_for->format('M d'),
                    ];
                })->toArray();

            // Add today's live data
            $series[] = [
                'value' => $currentLiveListeners,
                'date' => now()->format('M d'),
                'is_live' => true
            ];
        }

        return response()->json([
            'daily' => $dailySessions,
            'weekly' => $weeklyListeners,
            'monthly' => $monthlyListenersTotal,
            'series' => $series,
        ]);
    }

    private function createSampleAudienceData()
    {
        try {
            $sampleData = [];

            // Create data for the past 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();

                // Generate realistic listener numbers (higher on weekends)
                $isWeekend = now()->subDays($i)->isWeekend();
                $baseListeners = $isWeekend ? rand(50, 150) : rand(20, 80);
                $peakListeners = $baseListeners + rand(10, 30);
                $averageListeners = round(($baseListeners + $peakListeners) / 2);

                $sampleData[] = [
                    'captured_for' => $date,
                    'peak_listeners' => $peakListeners,
                    'average_listeners' => $averageListeners,
                    'total_listening_time' => rand(1000, 5000), // in minutes
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            AudienceMetric::insert($sampleData);
            Log::info('Created ' . count($sampleData) . ' sample audience metrics');
        } catch (\Exception $e) {
            Log::error('Failed to create sample audience data: ' . $e->getMessage());
        }
    }

    public function generateSampleData(Request $request)
    {
        try {
            // Clear existing sample data first
            AudienceMetric::where('captured_for', '>=', now()->subDays(30))->delete();

            $this->createSampleAudienceData();

            return response()->json([
                'success' => true,
                'message' => 'Sample audience data generated for the past 30 days'
            ]);
        } catch (\Exception $e) {
            Log::error('Generate sample data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate sample data: ' . $e->getMessage()
            ], 500);
        }
    }
}
