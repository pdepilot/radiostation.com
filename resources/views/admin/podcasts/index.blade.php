<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Podcasts') }}
            </h2>
            <a href="{{ route('admin.podcasts.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">
                New Episode
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Title</th>
                            <th class="px-4 py-2 text-left">Host</th>
                            <th class="px-4 py-2 text-left">Published</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($episodes as $episode)
                            <tr>
                                <td class="px-4 py-2">{{ $episode->title }}</td>
                                <td class="px-4 py-2">{{ $episode->host }}</td>
                                <td class="px-4 py-2">{{ optional($episode->published_at)->format('M d, Y') }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <a href="{{ route('admin.podcasts.edit', $episode) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('admin.podcasts.destroy', $episode) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" onclick="return confirm('Delete episode?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $episodes->links() }}
        </div>
    </div>
</x-app-layout>

