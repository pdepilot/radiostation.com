<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvertisingPackage;
use App\Models\RevenueRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RevenueController extends Controller
{
    public function index(): View
    {
        return view('admin.revenue.index', [
            'records' => RevenueRecord::with('package')->orderByDesc('created_at')->paginate(15),
            'packages' => AdvertisingPackage::orderBy('name')->get(),
            'totals' => [
                'pending' => RevenueRecord::where('status', 'pending')->sum('amount'),
                'paid' => RevenueRecord::where('status', 'paid')->sum('amount'),
                'overdue' => RevenueRecord::where('status', 'overdue')->sum('amount'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        RevenueRecord::create($this->validatedData($request));

        return back()->with('status', 'Invoice captured.');
    }

    public function update(Request $request, RevenueRecord $revenue): RedirectResponse
    {
        $revenue->update($this->validatedData($request, $revenue));

        return back()->with('status', 'Invoice updated.');
    }

    private function validatedData(Request $request, ?RevenueRecord $record = null): array
    {
        return $request->validate([
            'advertising_package_id' => ['nullable', 'exists:advertising_packages,id'],
            'sponsor_name' => ['required', 'string', 'max:150'],
            'contact_email' => ['nullable', 'email'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'status' => ['required', Rule::in(['pending', 'paid', 'overdue'])],
            'invoice_number' => ['required', 'string', Rule::unique('revenue_records', 'invoice_number')->ignore($record)],
            'due_date' => ['nullable', 'date'],
            'paid_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:800'],
        ]);
    }
}
