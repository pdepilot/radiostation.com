<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('DJs & OAPs') }}
            </h2>
            <a href="{{ route('admin.djs.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded">
                Add Profile
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Specialty</th>
                            <th class="px-4 py-2 text-left">Featured</th>
                            <th class="px-4 py-2"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($djs as $dj)
                            <tr>
                                <td class="px-4 py-2">
                                    <p class="font-semibold">{{ $dj->stage_name ?? $dj->name }}</p>
                                    <p class="text-gray-500 text-xs">{{ $dj->email }}</p>
                                </td>
                                <td class="px-4 py-2">{{ $dj->specialty }}</td>
                                <td class="px-4 py-2">
                                    @if($dj->is_featured)
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Featured</span>
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-right space-x-2">
                                    <a href="{{ route('admin.djs.edit', $dj) }}" class="text-indigo-600">Edit</a>
                                    <form action="{{ route('admin.djs.destroy', $dj) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600" onclick="return confirm('Delete profile?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $djs->links() }}
        </div>
    </div>
</x-app-layout>

