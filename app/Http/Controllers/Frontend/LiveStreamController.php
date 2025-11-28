<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use App\Models\Show;

class LiveStreamController extends Controller
{
    public function index()
    {
        $liveStream = LiveStream::with(['show', 'dj'])->latest('updated_at')->first();

        return view('frontend.livestream', [
            'liveStream' => $liveStream,
            'shows' => Show::with('dj')->orderBy('start_time')->get(),
            'history' => LiveStream::latest('created_at')->take(5)->get(),
        ]);
    }
}
