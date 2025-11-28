<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Control Room') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto space-y-6 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <x-admin.stat-card label="Shows" :value="$stats['shows']"/>
                <x-admin.stat-card label="Published News" :value="$stats['news']"/>
                <x-admin.stat-card label="Podcasts" :value="$stats['podcasts']"/>
                <x-admin.stat-card label="Pending Messages" :value="$stats['pendingMessages']"/>
                <x-admin.stat-card label="Live Streams" :value="$stats['activeLiveStream']"/>
                <x-admin.stat-card label="Revenue (YTD)" :value="'₦' . number_format($stats['revenueYtd'], 2)"/>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Audience Trend</h3>
                    <ul class="space-y-2">
                        @foreach($audienceSeries as $metric)
                            <li class="flex justify-between text-sm">
                                <span>{{ $metric->captured_for->format('M d') }}</span>
                                <span>{{ number_format($metric->peak_listeners) }} listeners</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Recent Messages</h3>
                    <ul class="space-y-3">
                        @foreach($latestMessages as $message)
                            <li>
                                <p class="font-semibold">{{ $message->subject }}</p>
                                <p class="text-sm text-gray-600">{{ $message->name }} • {{ ucfirst($message->type) }}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Invoices</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                        <tr>
                            <th class="text-left py-2">Sponsor</th>
                            <th>Status</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentInvoices as $invoice)
                            <tr>
                                <td class="py-2">{{ $invoice->sponsor_name }}</td>
                                <td class="capitalize">{{ $invoice->status }}</td>
                                <td>₦{{ number_format($invoice->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

