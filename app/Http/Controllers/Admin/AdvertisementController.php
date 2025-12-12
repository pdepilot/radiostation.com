<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdvertisementController extends Controller
{
    public function index(): View
    {
        return view('admin.advertisements.index', [
            'adverts' => Advertisement::latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.advertisements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'type' => ['required', 'in:image,banner,popup,google_adsense'],
                'position' => ['required', 'in:sidebar,header,footer,content,popup'],
                'image_url' => ['nullable', 'url'],
                'link_url' => ['nullable', 'url'],
                'google_adsense_code' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],
                'show_to_registered' => ['nullable', 'boolean'],
                'starts_at' => ['nullable', 'date'],
                'ends_at' => ['nullable', 'date', 'after:starts_at'],
            ]);

            $validated['is_active'] = $request->boolean('is_active');
            $validated['show_to_registered'] = $request->boolean('show_to_registered');

            Advertisement::create($validated);

            return redirect()->route('admin.adverts.index')
                ->with('status', 'Advertisement created successfully.');
        } catch (\Exception $e) {
            \Log::error('Advertisement creation error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create advertisement. Please try again.']);
        }
    }

    public function edit(Advertisement $advert): View
    {
        return view('admin.advertisements.edit', [
            'advert' => $advert,
        ]);
    }

    public function update(Request $request, Advertisement $advert): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'type' => ['required', 'in:image,banner,popup,google_adsense'],
                'position' => ['required', 'in:sidebar,header,footer,content,popup'],
                'image_url' => ['nullable', 'url'],
                'link_url' => ['nullable', 'url'],
                'google_adsense_code' => ['nullable', 'string'],
                'is_active' => ['nullable', 'boolean'],
                'show_to_registered' => ['nullable', 'boolean'],
                'starts_at' => ['nullable', 'date'],
                'ends_at' => ['nullable', 'date', 'after:starts_at'],
            ]);

            $validated['is_active'] = $request->boolean('is_active');
            $validated['show_to_registered'] = $request->boolean('show_to_registered');
            $advert->update($validated);

            return redirect()->route('admin.adverts.index')
                ->with('status', 'Advertisement updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Advertisement update error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to update advertisement. Please try again.']);
        }
    }

    public function destroy(Advertisement $advert): RedirectResponse
    {
        try {
            $advert->delete();
            return redirect()->route('admin.adverts.index')
                ->with('status', 'Advertisement deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Advertisement deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete advertisement. Please try again.']);
        }
    }
}
