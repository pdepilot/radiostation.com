<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LiveStreamController extends Controller
{
    public function index(): View
    {
        return view('admin.livestream.index', [
            'streams' => LiveStream::with(['dj', 'show'])->orderByDesc('updated_at')->paginate(10),
        ]);
    }

    public function update(Request $request, LiveStream $livestream): RedirectResponse
    {
        try {
            $data = $request->validate([
                'title' => ['required', 'string', 'max:150'],
                'description' => ['nullable', 'string'],
                'status' => ['required', Rule::in(['scheduled', 'live', 'offline'])],
                'stream_url' => ['nullable', 'url'],
                'listener_count' => ['nullable', 'integer', 'min:0'],
            ]);

            $livestream->update($data);

            return back()->with('status', 'Stream updated.');
        } catch (\Exception $e) {
            \Log::error('Live stream update error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to update stream. Please try again.']);
        }
    }
}
