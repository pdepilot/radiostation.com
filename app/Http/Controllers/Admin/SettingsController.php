<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'settings' => SiteSetting::orderBy('key')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'settings' => ['required', 'array'],
            'settings.*.key' => ['required', 'string'],
            'settings.*.value' => ['nullable', 'string'],
        ]);

        foreach ($data['settings'] as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        return back()->with('status', 'Settings updated successfully.');
    }

    public function saveSocialMedia(Request $request)
    {
        try {
            $data = $request->validate([
                'facebook_url' => ['nullable', 'string', 'max:255'],
                'twitter_url' => ['nullable', 'string', 'max:255'],
                'instagram_url' => ['nullable', 'string', 'max:255'],
                'youtube_url' => ['nullable', 'string', 'max:255'],
                'tiktok_url' => ['nullable', 'string', 'max:255'],
            ]);

            foreach ($data as $key => $value) {
                $cleanValue = is_string($value) ? trim($value) : ($value ?? null);
                $cleanValue = $cleanValue === '' ? null : $cleanValue;

                SiteSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $cleanValue,
                        'type' => 'text',
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Settings saved successfully'
            ], 200, ['Content-Type' => 'application/json']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving settings: ' . $e->getMessage()
            ], 500, ['Content-Type' => 'application/json']);
        }
    }
}
