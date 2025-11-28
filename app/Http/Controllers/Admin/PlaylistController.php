<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlaylistTrack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlaylistController extends Controller
{
    public function index(): View
    {
        return view('admin.playlist.index', [
            'tracks' => PlaylistTrack::orderByDesc('created_at')->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'artist' => ['required', 'string', 'max:150'],
            'genre' => ['nullable', 'string', 'max:80'],
            'mood' => ['nullable', 'string', 'max:80'],
            'duration' => ['nullable', 'string', 'max:10'],
            'cover_image' => ['nullable', 'string'],
            'audio_url' => ['nullable', 'string'],
            'scheduled_for' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
        $data['is_featured'] = $request->boolean('is_featured');

        PlaylistTrack::create($data);

        return back()->with('status', 'Track queued.');
    }

    public function destroy(PlaylistTrack $playlist): RedirectResponse
    {
        $playlist->delete();

        return back()->with('status', 'Track removed.');
    }
}
<?php

