<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        return view('admin.news.index', [
            'posts' => NewsPost::orderByDesc('published_at')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        NewsPost::create($this->validatedData($request));

        return redirect()->route('admin.news.index')->with('status', 'News item published.');
    }

    public function edit(NewsPost $news): View
    {
        return view('admin.news.edit', ['post' => $news]);
    }

    public function update(Request $request, NewsPost $news): RedirectResponse
    {
        $news->update($this->validatedData($request, $news));

        return redirect()->route('admin.news.index')->with('status', 'News item updated.');
    }

    public function destroy(NewsPost $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('status', 'News item removed.');
    }

    private function validatedData(Request $request, ?NewsPost $post = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('news_posts', 'slug')->ignore($post)],
            'excerpt' => ['nullable', 'string', 'max:800'],
            'body' => ['required', 'string'],
            'hero_image' => ['nullable', 'string'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'reading_time' => ['nullable', 'string', 'max:20'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['string'],
            'status' => ['required', Rule::in(['draft', 'scheduled', 'published'])],
            'published_at' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['tags'] = $validated['tags'] ?? $this->explodeTags($request->input('tags_string'));

        return $validated;
    }

    private function explodeTags(?string $tags): ?array
    {
        if (! $tags) {
            return null;
        }

        return collect(explode(',', $tags))
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->values()
            ->all();
    }
}
