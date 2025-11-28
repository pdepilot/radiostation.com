<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Live Streams') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
            @endif

            @foreach($streams as $stream)
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <form method="POST" action="{{ route('admin.livestreams.update', $stream) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="block">
                                <span class="text-sm text-gray-700">Title</span>
                                <input class="mt-1 block w-full border rounded" type="text" name="title" value="{{ old('title', $stream->title) }}">
                            </label>
                            <label class="block">
                                <span class="text-sm text-gray-700">Stream URL</span>
                                <input class="mt-1 block w-full border rounded" type="text" name="stream_url" value="{{ old('stream_url', $stream->stream_url) }}">
                            </label>
                        </div>
                        <label class="block">
                            <span class="text-sm text-gray-700">Description</span>
                            <textarea class="mt-1 block w-full border rounded" name="description" rows="3">{{ old('description', $stream->description) }}</textarea>
                        </label>
                        <div class="grid grid-cols-3 gap-4">
                            <label class="block">
                                <span class="text-sm text-gray-700">Status</span>
                                <select class="mt-1 block w-full border rounded" name="status">
                                    @foreach(['scheduled','live','offline'] as $status)
                                        <option value="{{ $status }}" @selected($stream->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <label class="block">
                                <span class="text-sm text-gray-700">Listeners</span>
                                <input class="mt-1 block w-full border rounded" type="number" name="listener_count" value="{{ old('listener_count', $stream->listener_count) }}">
                            </label>
                            <label class="flex items-center space-x-2 mt-6">
                                <input type="checkbox" name="chat_enabled" value="1" @checked($stream->chat_enabled)>
                                <span>Chat Enabled</span>
                            </label>
                        </div>
                        <div class="text-right">
                            <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Update Stream</button>
                        </div>
                    </form>
                </div>
            @endforeach

            {{ $streams->links() }}
        </div>
    </div>
</x-app-layout>

