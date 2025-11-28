<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AudienceMetric;
use App\Models\ContactMessage;
use App\Models\LiveStream;
use App\Models\NewsPost;
use App\Models\Podcast;
use App\Models\RevenueRecord;
use App\Models\Show;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'stats' => [
                'shows' => Show::count(),
                'news' => NewsPost::count(),
                'podcasts' => Podcast::count(),
                'pendingMessages' => ContactMessage::where('status', 'new')->count(),
                'activeLiveStream' => LiveStream::where('status', 'live')->count(),
                'revenueYtd' => RevenueRecord::whereYear('created_at', now()->year)->sum('amount'),
            ],
            'latestMessages' => ContactMessage::latest()->take(5)->get(),
            'audienceSeries' => AudienceMetric::orderByDesc('captured_for')->take(7)->get()->reverse(),
            'recentInvoices' => RevenueRecord::orderByDesc('created_at')->take(5)->get(),
            'team' => User::admins()->take(4)->get(),
        ]);
    }
}
