<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Advertising Packages') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Create Package</h3>
                <form method="POST" action="{{ route('admin.advertising.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <input class="border rounded p-2" type="text" name="name" placeholder="Package Name" value="{{ old('name') }}" required>
                    <input class="border rounded p-2" type="number" name="duration_weeks" placeholder="Weeks" value="{{ old('duration_weeks', 4) }}" required>
                    <input class="border rounded p-2" type="text" name="reach" placeholder="Projected Reach" value="{{ old('reach') }}">
                    <input class="border rounded p-2" type="number" name="price" placeholder="Price (NGN)" value="{{ old('price') }}" required>
                    <textarea class="border rounded p-2 md:col-span-2" name="description" rows="3" placeholder="Description">{{ old('description') }}</textarea>
                    <select class="border rounded p-2" name="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <input class="border rounded p-2" type="text" name="cta" placeholder="CTA Label" value="{{ old('cta') }}">
                    <div class="md:col-span-2 text-right">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Save Package</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow sm:rounded-lg divide-y">
                @foreach($packages as $package)
                    <div class="p-6 space-y-4">
                        <form method="POST" action="{{ route('admin.advertising.update', $package) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            @csrf
                            @method('PUT')
                            <input class="border rounded p-2" type="text" name="name" value="{{ $package->name }}">
                            <input class="border rounded p-2" type="number" name="duration_weeks" value="{{ $package->duration_weeks }}">
                            <input class="border rounded p-2" type="number" name="price" value="{{ $package->price }}">
                            <select class="border rounded p-2" name="status">
                                <option value="active" @selected($package->status === 'active')>Active</option>
                                <option value="inactive" @selected($package->status === 'inactive')>Inactive</option>
                            </select>
                            <textarea class="border rounded p-2 md:col-span-4" name="description" rows="2">{{ $package->description }}</textarea>
                            <div class="md:col-span-4 flex justify-end">
                                <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Update</button>
                            </div>
                        </form>
                        <div class="text-right">
                            <form method="POST" action="{{ route('admin.advertising.destroy', $package) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 text-sm" onclick="return confirm('Delete package?')">Delete Package</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

