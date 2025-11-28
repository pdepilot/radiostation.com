<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dj;
use App\Models\Show;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ShowController extends Controller
{
    public function index(): View
    {
        return view('admin.shows.index', [
            'shows' => Show::with('dj')->orderByDesc('created_at')->paginate(12),
            'djs' => Dj::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.shows.create', [
            'djs' => Dj::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Show::create($this->validatedData($request));

        return redirect()->route('admin.shows.index')->with('status', 'Show scheduled.');
    }

    public function edit(Show $show): View
    {
        return view('admin.shows.edit', [
            'show' => $show,
            'djs' => Dj::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Show $show): RedirectResponse
    {
        $show->update($this->validatedData($request, $show));

        return redirect()->route('admin.shows.index')->with('status', 'Show updated.');
    }

    public function destroy(Show $show): RedirectResponse
    {
        $show->delete();

        return redirect()->route('admin.shows.index')->with('status', 'Show removed.');
    }

    private function validatedData(Request $request, ?Show $show = null): array
    {
        $validated = $request->validate([
            'dj_id' => ['nullable', 'exists:djs,id'],
            'title' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:160', Rule::unique('shows', 'slug')->ignore($show)],
            'tagline' => ['nullable', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'genre' => ['nullable', 'string', 'max:120'],
            'day_of_week' => ['nullable', 'string', 'max:80'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'hero_image' => ['nullable', 'string'],
            'is_live' => ['nullable', 'boolean'],
            'sponsor' => ['nullable', 'string', 'max:150'],
            'listener_count' => ['nullable', 'integer'],
            'stream_url' => ['nullable', 'url'],
            'status' => ['required', Rule::in(['scheduled', 'live', 'completed', 'cancelled'])],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_live'] = $request->boolean('is_live');

        return $validated;
    }
}
