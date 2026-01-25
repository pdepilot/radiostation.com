<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudienceMetric;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function resetAnalytics(Request $request)
    {
        try {
            $period = $request->input('period', 'all'); // 'all', 'day', 'week', 'month', 'year'
            
            if ($period === 'all') {
                // Reset all analytics data
                AudienceMetric::truncate();
                $message = 'All listener analytics data has been reset to 0';
            } else {
                // Reset based on period
                $now = now();
                
                switch ($period) {
                    case 'day':
                        AudienceMetric::whereDate('captured_for', today())->delete();
                        $message = "Today's analytics data has been reset";
                        break;
                    case 'week':
                        AudienceMetric::whereBetween('captured_for', [
                            $now->startOfWeek(),
                            $now->endOfWeek()
                        ])->delete();
                        $message = "This week's analytics data has been reset";
                        break;
                    case 'month':
                        AudienceMetric::whereYear('captured_for', $now->year)
                            ->whereMonth('captured_for', $now->month)
                            ->delete();
                        $message = "This month's analytics data has been reset";
                        break;
                    case 'year':
                        AudienceMetric::whereYear('captured_for', $now->year)->delete();
                        $message = "This year's analytics data has been reset";
                        break;
                    default:
                        return response()->json(['success' => false, 'message' => 'Invalid period'], 400);
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Analytics reset error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Reset failed'], 500);
        }
    }
}

