<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PlaylistTrack;

class PlaylistController extends Controller
{
    public function index()
    {
        return view('frontend.playlist.index', [
            'featuredTracks' => PlaylistTrack::where('is_featured', true)->orderByDesc('scheduled_for')->take(6)->get(),
            'latestTracks' => PlaylistTrack::orderByDesc('created_at')->paginate(20),
        ]);
    }
}
