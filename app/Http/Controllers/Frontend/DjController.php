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
}
