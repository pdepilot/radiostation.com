<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use App\Models\Podcast;
use App\Models\Dj;
use App\Models\Show;
use App\Models\LiveStream;
use App\Models\Sponsor;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home', [
            'liveStream'     => LiveStream::latest()->first(),
            'upcomingShows'  => Show::with('dj')->orderBy('start_time')->limit(6)->get(),
            'newsPosts'      => NewsPost::where('status', 'published')
                                    ->latest('published_at')
                                    ->take(3)
                                    ->get(),
            'featuredDjs'    => Dj::where('is_featured', 1)
                                    ->with('shows')
                                    ->take(4)
                                    ->get(),
            'featuredSponsors' => Sponsor::where('status', 'active')
                                    ->where('is_featured', true)
                                    ->orderBy('order')
                                    ->get(),
        ]);
    }
}