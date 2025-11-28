<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Shows') }}
            </h2>
            <a href="{{ route('admin.shows.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">
                New Show
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Title</th>
                            <th class="px-4 py-2 text-left">Host</th>
                            <th class="px-4 py-2 text-left">Schedule</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($shows as $show)
                            <tr>
                                <td class="px-4 py-2">{{ $show->title }}</td>
                                <td class="px-4 py-2">{{ $show->dj?->stage_name ?? $show->dj?->name }}</td>
                                <td class="px-4 py-2">{{ $show->day_of_week }} • {{ $show->start_time }} - {{ $show->end_time }}</td>
                                <td class="px-4 py-2 capitalize">{{ $show->status }}</td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <a href="{{ route('admin.shows.edit', $show) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('admin.shows.destroy', $show) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" onclick="return confirm('Delete show?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $shows->links() }}
        </div>
    </div>
</x-app-layout>

