<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Station Settings') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('status'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('status') }}</div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.settings.store') }}" class="space-y-4">
                    @csrf
                    @foreach($settings as $setting)
                        <label class="block">
                            <span class="text-sm text-gray-700">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</span>
                            <input class="mt-1 block w-full border rounded" type="text" name="settings[{{ $loop->index }}][value]" value="{{ $setting->value }}">
                            <input type="hidden" name="settings[{{ $loop->index }}][key]" value="{{ $setting->key }}">
                        </label>
                    @endforeach
                    <div class="flex justify-end">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

