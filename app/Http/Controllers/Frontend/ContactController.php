<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('frontend.contact.index', [
            'settings' => SiteSetting::query()->pluck('value', 'key'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:general,advertising,shoutout,technical'],
            'message' => ['required', 'string'],
        ]);

        ContactMessage::create($validated);

        return back()->with('status', 'Message received. Someone from Darling FM will reach out shortly.');
    }
}
