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
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['scheduled', 'live', 'offline'])],
            'stream_url' => ['nullable', 'url'],
            'chat_enabled' => ['nullable', 'boolean'],
            'listener_count' => ['nullable', 'integer'],
        ]);
        $data['chat_enabled'] = $request->boolean('chat_enabled');

        $livestream->update($data);

        return back()->with('status', 'Stream updated.');
    }
}
<?php

