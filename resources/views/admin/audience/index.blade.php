<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audience Analytics') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Weekly Trend</h3>
                <ul class="grid grid-cols-2 gap-4 text-sm">
                    @foreach($trend as $metric)
                        <li class="border rounded p-3">
                            <p class="font-semibold">{{ $metric->captured_for->format('M d') }}</p>
                            <p class="text-gray-600">{{ number_format($metric->peak_listeners) }} peak listeners</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Peak</th>
                            <th class="px-4 py-2 text-left">Average</th>
                            <th class="px-4 py-2 text-left">New Followers</th>
                            <th class="px-4 py-2 text-left">SMS Votes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($metrics as $metric)
                            <tr>
                                <td class="px-4 py-2">{{ $metric->captured_for->format('M d, Y') }}</td>
                                <td class="px-4 py-2">{{ number_format($metric->peak_listeners) }}</td>
                                <td class="px-4 py-2">{{ number_format($metric->average_listeners) }}</td>
                                <td class="px-4 py-2">{{ number_format($metric->new_followers) }}</td>
                                <td class="px-4 py-2">{{ number_format($metric->sms_votes) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $metrics->links() }}
        </div>
    </div>
</x-app-layout>

