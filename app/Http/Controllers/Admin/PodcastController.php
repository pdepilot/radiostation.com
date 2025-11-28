<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Podcast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PodcastController extends Controller
{
    public function index(): View
    {
        return view('admin.podcasts.index', [
            'episodes' => Podcast::orderByDesc('published_at')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.podcasts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Podcast::create($this->validatedData($request));

        return redirect()->route('admin.podcasts.index')->with('status', 'Podcast episode published.');
    }

    public function edit(Podcast $podcast): View
    {
        return view('admin.podcasts.edit', compact('podcast'));
    }

    public function update(Request $request, Podcast $podcast): RedirectResponse
    {
        $podcast->update($this->validatedData($request, $podcast));

        return redirect()->route('admin.podcasts.index')->with('status', 'Episode updated.');
    }

    public function destroy(Podcast $podcast): RedirectResponse
    {
        $podcast->delete();

        return redirect()->route('admin.podcasts.index')->with('status', 'Episode deleted.');
    }

    private function validatedData(Request $request, ?Podcast $podcast = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:220', Rule::unique('podcasts', 'slug')->ignore($podcast)],
            'host' => ['nullable', 'string', 'max:120'],
            'sponsor' => ['nullable', 'string', 'max:120'],
            'cover_image' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'audio_url' => ['required', 'url'],
            'duration' => ['nullable', 'string', 'max:40'],
            'listen_count' => ['nullable', 'integer'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);

        return $validated;
    }
}
<?php

