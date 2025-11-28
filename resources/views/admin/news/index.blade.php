<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Newsroom') }}
            </h2>
            <a href="{{ route('admin.news.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">
                New Article
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
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Published</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($posts as $post)
                            <tr>
                                <td class="px-4 py-2">{{ $post->title }}</td>
                                <td class="px-4 py-2 capitalize">{{ $post->status }}</td>
                                <td class="px-4 py-2">{{ optional($post->published_at)->format('M d, Y') }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <a href="{{ route('admin.news.edit', $post) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('admin.news.destroy', $post) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" onclick="return confirm('Delete article?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $posts->links() }}
        </div>
    </div>
</x-app-layout>

