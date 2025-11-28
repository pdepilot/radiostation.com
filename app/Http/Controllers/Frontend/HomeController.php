<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dj;
use App\Models\LiveStream;
use App\Models\NewsPost;
use App\Models\PlaylistTrack;
use App\Models\Podcast;
use App\Models\Show;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::query()->pluck('value', 'key');

        return view('frontend.home', [
            'settings' => $settings,
            'liveStream' => LiveStream::with(['dj', 'show'])->latest('updated_at')->first(),
            'upcomingShows' => Show::with('dj')->orderBy('day_of_week')->orderBy('start_time')->take(6)->get(),
            'featuredDjs' => Dj::where('is_featured', true)->take(4)->get(),
            'newsPosts' => NewsPost::where('status', 'published')->latest('published_at')->take(3)->get(),
            'playlist' => PlaylistTrack::orderByDesc('scheduled_for')->take(8)->get(),
            'podcasts' => Podcast::latest('published_at')->take(4)->get(),
        ]);
    }
}
