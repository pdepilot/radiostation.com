<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Show;

class ShowController extends Controller
{
    public function index()
    {
        return view('frontend.shows.index', [
            'shows' => Show::with('dj')->orderBy('day_of_week')->orderBy('start_time')->paginate(12),
        ]);
    }

    public function show(Show $show)
    {
        $show->load('dj');

        return view('frontend.shows.show', [
            'show' => $show,
            'related' => Show::where('id', '!=', $show->id)->take(3)->get(),
        ]);
    }
}
