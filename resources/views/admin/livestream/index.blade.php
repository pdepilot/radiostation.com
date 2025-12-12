@extends('layouts.admin', ['title' => 'Stream Management'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
@endpush

@section('content')
    @if(session('status'))
        <div style="background: rgba(0, 204, 102, 0.2); border: 1px solid var(--success); color: var(--success); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            {{ session('status') }}
        </div>
    @endif

    @foreach($streams as $stream)
        <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border); margin-bottom: 20px;">
            <form method="POST" action="{{ route('admin.livestreams.update', $stream) }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                @csrf
                @method('PUT')
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Title</label>
                    <input type="text" name="title" value="{{ old('title', $stream->title) }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Stream URL</label>
                    <input type="text" name="stream_url" value="{{ old('stream_url', $stream->stream_url) }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Description</label>
                    <textarea name="description" rows="3" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); resize: vertical;">{{ old('description', $stream->description) }}</textarea>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Status</label>
                    <select name="status" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                        @foreach(['scheduled','live','offline'] as $status)
                            <option value="{{ $status }}" @selected($stream->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; margin-bottom: 8px; color: var(--accent); font-weight: 600;">Listeners</label>
                    <input type="number" name="listener_count" value="{{ old('listener_count', $stream->listener_count) }}" style="width: 100%; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light);">
                </div>
                <div style="grid-column: 1 / -1; text-align: right;">
                    <button type="submit" style="background: var(--accent); color: white; padding: 12px 30px; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Update Stream</button>
                </div>
            </form>
        </div>
    @endforeach
@endsection
