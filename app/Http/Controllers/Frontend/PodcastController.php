<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Podcast;

class PodcastController extends Controller
{
    public function index()
    {
        return view('frontend.podcasts.index', [
            'featured' => Podcast::orderByDesc('listen_count')->take(3)->get(),
            'episodes' => Podcast::orderByDesc('published_at')->paginate(12),
        ]);
    }

    public function show(Podcast $podcast)
    {
        return view('frontend.podcasts.show', [
            'podcast' => $podcast,
            'recommendations' => Podcast::where('id', '!=', $podcast->id)->take(4)->get(),
        ]);
    }
}
