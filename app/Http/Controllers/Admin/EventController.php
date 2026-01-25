<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        return view('admin.events.index', [
            'events' => Event::orderByDesc('event_date')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        try {
            Event::create($this->validatedData($request));
            return redirect()->route('admin.events.index')->with('status', 'Event created successfully.');
        } catch (\Exception $e) {
            \Log::error('Event creation error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to create event. Please try again.']);
        }
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', ['event' => $event]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        try {
            $event->update($this->validatedData($request, $event));
            return redirect()->route('admin.events.index')->with('status', 'Event updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Event update error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Failed to update event. Please try again.']);
        }
    }

    public function destroy(Event $event): RedirectResponse
    {
        try {
            $event->delete();
            return redirect()->route('admin.events.index')->with('status', 'Event deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Event deletion error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete event. Please try again.']);
        }
    }

    private function validatedData(Request $request, ?Event $event = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($event)],
            'description' => ['nullable', 'string'],
            'venue' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'event_date' => ['required', 'date'],
            'event_end_date' => ['nullable', 'date', 'after:event_date'],
            'hero_image' => ['nullable', 'string'],
            'ticket_url' => ['nullable', 'url'],
            'is_featured' => ['nullable', 'boolean'],
            'status' => ['required', Rule::in(['upcoming', 'past', 'cancelled'])],
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }
}

