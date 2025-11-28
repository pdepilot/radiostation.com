<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;

class NewsController extends Controller
{
    public function index()
    {
        return view('frontend.news.index', [
            'featured' => NewsPost::where('is_featured', true)->latest('published_at')->take(1)->first(),
            'posts' => NewsPost::where('status', 'published')->latest('published_at')->paginate(9),
        ]);
    }

    public function show(NewsPost $newsPost)
    {
        return view('frontend.news.show', [
            'post' => $newsPost,
            'related' => NewsPost::where('id', '!=', $newsPost->id)->latest('published_at')->take(4)->get(),
        ]);
    }
}
