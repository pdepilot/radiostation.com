<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertController extends Controller
{
    public function index(): View
    {
        return view('admin.adverts.index', [
            'adverts' => Advert::latest()->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'type' => ['required', 'in:banner,sidebar,popup'],
            'position' => ['nullable', 'string', 'max:50'],
            'link_url' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'min_view_duration' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        Advert::create($validated);

        return back()->with('status', 'Advert created successfully.');
    }

    public function update(Request $request, Advert $advert): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'type' => ['required', 'in:banner,sidebar,popup'],
            'position' => ['nullable', 'string', 'max:50'],
            'link_url' => ['nullable', 'url'],
            'is_active' => ['boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after:starts_at'],
            'min_view_duration' => ['required', 'integer', 'min:1', 'max:60'],
        ]);

        $advert->update($validated);

        return back()->with('status', 'Advert updated successfully.');
    }

    public function destroy(Advert $advert): RedirectResponse
    {
        $advert->delete();
        return back()->with('status', 'Advert deleted successfully.');
    }
}

