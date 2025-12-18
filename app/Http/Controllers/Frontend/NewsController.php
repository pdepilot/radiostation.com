<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->get('search', '');
        $postsQuery = NewsPost::where('status', 'published');
        
        // Apply search filter if provided
        if (!empty($searchQuery)) {
            $postsQuery->where(function($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('excerpt', 'like', "%{$searchQuery}%")
                  ->orWhere('body', 'like', "%{$searchQuery}%")
                  ->orWhere('author_name', 'like', "%{$searchQuery}%")
                  ->orWhereJsonContains('tags', $searchQuery);
            });
        }
        
        return view('frontend.news.index', [
            'featured' => NewsPost::where('is_featured', true)->latest('published_at')->take(1)->first(),
            'posts' => $postsQuery->latest('published_at')->paginate(9),
            'searchQuery' => $searchQuery,
        ]);
    }

    public function show(NewsPost $newsPost)
    {
        // Increment view count when post is viewed
        // Only count views for published posts
        // Use session to ensure same session doesn't increment multiple times
        if ($newsPost->status === 'published') {
            // Get or initialize viewed posts array from session
            $viewedPosts = session('viewed_news_posts', []);
            
            // Check if this post hasn't been viewed in this session
            if (!in_array($newsPost->id, $viewedPosts)) {
                // Increment view count
                $newsPost->incrementViews();
                
                // Add this post ID to viewed posts in session
                $viewedPosts[] = $newsPost->id;
                session(['viewed_news_posts' => $viewedPosts]);
            }
        }
        
        return view('frontend.news.show', [
            'post' => $newsPost,
            'related' => NewsPost::where('id', '!=', $newsPost->id)
                ->where('status', 'published')
                ->latest('published_at')
                ->take(4)
                ->get(),
        ]);
    }

    /**
     * Intelligent News Search
     * 
     * Searches news by:
     * - Title
     * - Keywords (excerpt, body)
     * - Presenter/Author name
     * - Category (tags)
     * - Date (if query matches date format)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));
        
        // Minimum 2 characters required
        if (strlen($query) < 2) {
            return response()->json(['results' => [], 'total' => 0, 'query' => $query]);
        }
        
        // Check if query is a date (e.g., "2024", "Dec 2024", "December 12")
        $isDateQuery = $this->isDateQuery($query);
        
        // Build search query
        $searchQuery = NewsPost::where('status', 'published');
        
        if ($isDateQuery) {
            // Search by date
            $searchQuery->where(function($q) use ($query) {
                $this->applyDateSearch($q, $query);
            });
        } else {
            // Search by text fields
            $searchQuery->where(function($q) use ($query) {
                // Title search (highest priority)
                $q->where('title', 'like', "%{$query}%")
                  // Excerpt and body search
                  ->orWhere('excerpt', 'like', "%{$query}%")
                  ->orWhere('body', 'like', "%{$query}%")
                  // Author/Presenter name search
                  ->orWhere('author_name', 'like', "%{$query}%")
                  // Category/Tags search (JSON field)
                  ->orWhereJsonContains('tags', $query);
            });
        }
        
        // Get results with pagination info
        $results = $searchQuery->latest('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'excerpt', 'hero_image', 'published_at', 'author_name', 'tags']);
        
        $totalCount = $searchQuery->count();
        
        return response()->json([
            'results' => $results->map(function($post) use ($query) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'excerpt' => Str::limit($post->excerpt ?? '', 100),
                    'image' => $post->hero_image ?? asset('assets/images/darling studio.jpg'),
                    'date' => optional($post->published_at)->format('M d, Y'),
                    'dateFull' => optional($post->published_at)->format('F j, Y'),
                    'author' => $post->author_name ?? 'Darling FM',
                    'category' => $this->extractCategory($post->tags),
                    'url' => route('news.show', $post->slug),
                ];
            }),
            'total' => $totalCount,
            'query' => $query,
            'hasMore' => $totalCount > 5
        ]);
    }
    
    /**
     * Check if query looks like a date
     */
    private function isDateQuery($query)
    {
        // Check for year (4 digits)
        if (preg_match('/\b(19|20)\d{2}\b/', $query)) {
            return true;
        }
        
        // Check for month names
        $months = ['january', 'february', 'march', 'april', 'may', 'june', 
                   'july', 'august', 'september', 'october', 'november', 'december',
                   'jan', 'feb', 'mar', 'apr', 'may', 'jun',
                   'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
        
        $lowerQuery = strtolower($query);
        foreach ($months as $month) {
            if (strpos($lowerQuery, $month) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Apply date-based search
     */
    private function applyDateSearch($query, $searchTerm)
    {
        // Extract year if present
        if (preg_match('/\b(19|20)\d{2}\b/', $searchTerm, $matches)) {
            $year = $matches[0];
            $query->whereYear('published_at', $year);
        }
        
        // Extract month if present
        $monthMap = [
            'january' => 1, 'jan' => 1, 'february' => 2, 'feb' => 2,
            'march' => 3, 'mar' => 3, 'april' => 4, 'apr' => 4,
            'may' => 5, 'june' => 6, 'jun' => 6, 'july' => 7, 'jul' => 7,
            'august' => 8, 'aug' => 8, 'september' => 9, 'sep' => 9,
            'october' => 10, 'oct' => 10, 'november' => 11, 'nov' => 11,
            'december' => 12, 'dec' => 12
        ];
        
        $lowerTerm = strtolower($searchTerm);
        foreach ($monthMap as $monthName => $monthNum) {
            if (strpos($lowerTerm, $monthName) !== false) {
                $query->whereMonth('published_at', $monthNum);
                break;
            }
        }
        
        // Extract day if present (1-31)
        if (preg_match('/\b([1-9]|[12][0-9]|3[01])\b/', $searchTerm, $matches)) {
            $day = (int)$matches[1];
            if ($day >= 1 && $day <= 31) {
                $query->whereDay('published_at', $day);
            }
        }
    }
    
    /**
     * Extract category from tags array
     */
    private function extractCategory($tags)
    {
        if (empty($tags) || !is_array($tags)) {
            return null;
        }
        
        // Return first tag as category
        return is_array($tags) && count($tags) > 0 ? $tags[0] : null;
    }
}
