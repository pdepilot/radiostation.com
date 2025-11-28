<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Playlist Rotation') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Add Track</h3>
                <form method="POST" action="{{ route('admin.playlist.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <input class="border rounded p-2" type="text" name="title" placeholder="Song Title" value="{{ old('title') }}" required>
                    <input class="border rounded p-2" type="text" name="artist" placeholder="Artist" value="{{ old('artist') }}" required>
                    <input class="border rounded p-2" type="text" name="genre" placeholder="Genre" value="{{ old('genre') }}">
                    <input class="border rounded p-2" type="text" name="mood" placeholder="Mood" value="{{ old('mood') }}">
                    <input class="border rounded p-2" type="text" name="duration" placeholder="Duration" value="{{ old('duration') }}">
                    <input class="border rounded p-2" type="date" name="scheduled_for" value="{{ old('scheduled_for') }}">
                    <input class="border rounded p-2 md:col-span-2" type="text" name="audio_url" placeholder="Audio URL" value="{{ old('audio_url') }}">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured'))>
                        <span>Mark as featured</span>
                    </label>
                    <div class="md:col-span-2 text-right">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Save Track</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Song</th>
                            <th class="px-4 py-2 text-left">Artist</th>
                            <th class="px-4 py-2 text-left">Mood</th>
                            <th class="px-4 py-2 text-left">Scheduled</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($tracks as $track)
                            <tr>
                                <td class="px-4 py-2">{{ $track->title }}</td>
                                <td class="px-4 py-2">{{ $track->artist }}</td>
                                <td class="px-4 py-2">{{ $track->mood }}</td>
                                <td class="px-4 py-2">{{ optional($track->scheduled_for)->format('M d') }}</td>
                                <td class="px-4 py-2 text-right">
                                    <form method="POST" action="{{ route('admin.playlist.destroy', $track) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" onclick="return confirm('Delete track?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $tracks->links() }}
        </div>
    </div>
</x-app-layout>

