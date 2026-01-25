<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Revenue Desk') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-admin.stat-card label="Pending" :value="'₦' . number_format($totals['pending'], 2)"/>
                <x-admin.stat-card label="Paid" :value="'₦' . number_format($totals['paid'], 2)"/>
                <x-admin.stat-card label="Overdue" :value="'₦' . number_format($totals['overdue'], 2)"/>
            </div>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Log Invoice</h3>
                <form method="POST" action="{{ route('admin.revenue.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <input class="border rounded p-2" type="text" name="sponsor_name" placeholder="Sponsor" value="{{ old('sponsor_name') }}" required>
                    <input class="border rounded p-2" type="email" name="contact_email" placeholder="Email" value="{{ old('contact_email') }}">
                    <select class="border rounded p-2" name="advertising_package_id">
                        <option value="">Package</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" @selected(old('advertising_package_id') == $package->id)>{{ $package->name }}</option>
                        @endforeach
                    </select>
                    <input class="border rounded p-2" type="text" name="invoice_number" placeholder="Invoice #" value="{{ old('invoice_number') }}" required>
                    <input class="border rounded p-2" type="number" name="amount" placeholder="Amount" value="{{ old('amount') }}" required>
                    <input class="border rounded p-2" type="text" name="currency" value="{{ old('currency', 'NGN') }}" required>
                    <select class="border rounded p-2" name="status">
                        @foreach(['pending','paid','overdue'] as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <input class="border rounded p-2" type="date" name="due_date" value="{{ old('due_date') }}">
                    <textarea class="border rounded p-2 md:col-span-2" name="notes" rows="2" placeholder="Notes">{{ old('notes') }}</textarea>
                    <div class="md:col-span-2 text-right">
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded" type="submit">Save Invoice</button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Sponsor</th>
                            <th class="px-4 py-2 text-left">Package</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Amount</th>
                            <th class="px-4 py-2 text-left">Due</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($records as $record)
                            <tr>
                                <td class="px-4 py-2">
                                    <p class="font-semibold">{{ $record->sponsor_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $record->invoice_number }}</p>
                                </td>
                                <td class="px-4 py-2">{{ $record->package?->name }}</td>
                                <td class="px-4 py-2 capitalize">{{ $record->status }}</td>
                                <td class="px-4 py-2">₦{{ number_format($record->amount, 2) }}</td>
                                <td class="px-4 py-2">{{ optional($record->due_date)->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $records->links() }}
        </div>
    </div>
</x-app-layout>

