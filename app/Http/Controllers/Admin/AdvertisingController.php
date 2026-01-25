<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvertisingPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdvertisingController extends Controller
{
    public function index(): View
    {
        return view('admin.advertising.index', [
            'packages' => AdvertisingPackage::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        AdvertisingPackage::create($this->validatedData($request));

        return back()->with('status', 'Package added.');
    }

    public function update(Request $request, AdvertisingPackage $advertising): RedirectResponse
    {
        $advertising->update($this->validatedData($request, $advertising));

        return back()->with('status', 'Package updated.');
    }

    public function destroy(AdvertisingPackage $advertising): RedirectResponse
    {
        $advertising->delete();

        return back()->with('status', 'Package removed.');
    }

    private function validatedData(Request $request, ?AdvertisingPackage $package = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['nullable', 'string', 'max:180', Rule::unique('advertising_packages', 'slug')->ignore($package)],
            'description' => ['nullable', 'string', 'max:800'],
            'reach' => ['nullable', 'string', 'max:120'],
            'duration_weeks' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'cta' => ['nullable', 'string', 'max:120'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        return $validated;
    }
}

