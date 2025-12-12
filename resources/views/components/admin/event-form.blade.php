@props(['event' => null])

<div class="admin-form">
<div class="form-group">
    <label>
        <span>Event Title *</span>
        <input class="form-input" type="text" name="title" value="{{ old('title', $event->title ?? '') }}" placeholder="e.g., Summer Music Festival" required>
    </label>
    @error('title')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Slug</span>
        <input class="form-input" type="text" name="slug" value="{{ old('slug', $event->slug ?? '') }}" placeholder="Auto-generated from title if left empty">
        <span class="form-help-text">URL-friendly identifier (auto-generated if empty)</span>
    </label>
    @error('slug')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Description</span>
        <textarea class="form-textarea" name="description" rows="5" placeholder="Describe the event...">{{ old('description', $event->description ?? '') }}</textarea>
    </label>
    @error('description')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Venue</span>
        <input class="form-input" type="text" name="venue" value="{{ old('venue', $event->venue ?? '') }}" placeholder="e.g., Owerri Event Center">
    </label>
    @error('venue')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Location</span>
        <input class="form-input" type="text" name="location" value="{{ old('location', $event->location ?? '') }}" placeholder="e.g., Owerri, Imo State">
    </label>
    @error('location')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Event Date *</span>
        <input class="form-input" type="datetime-local" name="event_date" value="{{ old('event_date', $event ? $event->event_date->format('Y-m-d\TH:i') : '') }}" required>
    </label>
    @error('event_date')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Event End Date</span>
        <input class="form-input" type="datetime-local" name="event_end_date" value="{{ old('event_end_date', $event && $event->event_end_date ? $event->event_end_date->format('Y-m-d\TH:i') : '') }}">
        <span class="form-help-text">Optional: When the event ends</span>
    </label>
    @error('event_end_date')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Hero Image URL</span>
        <input class="form-input" type="url" name="hero_image" value="{{ old('hero_image', $event->hero_image ?? '') }}" placeholder="https://example.com/image.jpg">
        <span class="form-help-text">URL to the event's featured image</span>
    </label>
    @error('hero_image')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Ticket URL</span>
        <input class="form-input" type="url" name="ticket_url" value="{{ old('ticket_url', $event->ticket_url ?? '') }}" placeholder="https://tickets.example.com/event">
        <span class="form-help-text">Link to purchase tickets</span>
    </label>
    @error('ticket_url')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label>
        <span>Status *</span>
        <select class="form-select" name="status" required>
            <option value="upcoming" @selected(old('status', $event->status ?? 'upcoming') === 'upcoming')>Upcoming</option>
            <option value="past" @selected(old('status', $event->status ?? '') === 'past')>Past</option>
            <option value="cancelled" @selected(old('status', $event->status ?? '') === 'cancelled')>Cancelled</option>
        </select>
    </label>
    @error('status')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label class="form-checkbox-label">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $event->is_featured ?? false))>
        <span>Mark as Featured</span>
    </label>
    @error('is_featured')
        <span class="form-error">{{ $message }}</span>
    @enderror
</div>

<div class="form-actions">
    <button type="submit" class="form-submit-btn">
        <i class="fas fa-save"></i> {{ $event ? 'Update Event' : 'Create Event' }}
    </button>
    <a href="{{ route('admin.events.index') }}" class="form-cancel-btn">
        <i class="fas fa-times"></i> Cancel
    </a>
</div>
</div>

