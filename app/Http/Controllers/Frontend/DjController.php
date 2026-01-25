<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dj;

class DjController extends Controller
{
    public function index()
    {
        return view('frontend.djs.index', [
            'djs' => Dj::orderByDesc('is_featured')->paginate(12),
        ]);
    }

    public function show(Dj $dj)
    {
        return view('frontend.djs.show', [
            'dj' => $dj->load('shows'),
            'related' => Dj::where('id', '!=', $dj->id)
                ->where('is_featured', 1)
                ->take(4)
                ->get(),
        ]);
    }
}
