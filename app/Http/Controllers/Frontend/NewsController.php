<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }
        
        $results = NewsPost::where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('body', 'like', "%{$query}%");
            })
            ->latest('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'excerpt', 'hero_image', 'published_at']);
        
        return response()->json([
            'results' => $results->map(function($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => Str::limit($post->excerpt, 80),
                    'image' => $post->hero_image ?? asset('assets/images/studio.jpg'),
                    'date' => optional($post->published_at)->format('M d, Y'),
                    'url' => route('news.show', $post->slug),
                ];
            })
        ]);
    }
}
