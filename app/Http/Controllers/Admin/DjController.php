<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dj;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DjController extends Controller
{
    public function index(): View
    {
        return view('admin.djs.index', [
            'djs' => Dj::orderByDesc('created_at')->paginate(12),
        ]);
    }

    public function create(): View
    {
        return view('admin.djs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Dj::create($this->validatedData($request));

        return redirect()->route('admin.djs.index')->with('status', 'New OAP profile created.');
    }

    public function edit(Dj $dj): View
    {
        return view('admin.djs.edit', compact('dj'));
    }

    public function update(Request $request, Dj $dj): RedirectResponse
    {
        $dj->update($this->validatedData($request, $dj));

        return redirect()->route('admin.djs.index')->with('status', 'Profile updated.');
    }

    public function destroy(Dj $dj): RedirectResponse
    {
        $dj->delete();

        return redirect()->route('admin.djs.index')->with('status', 'Profile removed.');
    }

    private function validatedData(Request $request, ?Dj $dj = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'stage_name' => ['nullable', 'string', 'max:120'],
            'slug' => ['nullable', 'string', 'max:150', Rule::unique('djs', 'slug')->ignore($dj)],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'avatar_url' => ['nullable', 'string'],
            'specialty' => ['nullable', 'string', 'max:150'],
            'bio' => ['nullable', 'string'],
            'instagram' => ['nullable', 'url'],
            'twitter' => ['nullable', 'url'],
            'facebook' => ['nullable', 'url'],
            'mixcloud' => ['nullable', 'url'],
            'booking_link' => ['nullable', 'url'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['stage_name'] ?? $validated['name']);
        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }
}
