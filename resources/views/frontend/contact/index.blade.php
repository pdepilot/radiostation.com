@extends('layouts.frontend', ['title' => 'Darling FM • Contact'])

@section('content')
    <section class="container contact-grid">
        <div>
            <h2 class="section-title">Talk To The Studio</h2>
            <p>Send programming requests, advertising briefs or technical reports using the secure form. The newsroom responds within 24 hours.</p>

            <ul class="contact-list">
                <li><i class="fas fa-phone"></i> {{ $settings['studio_hotline'] ?? '+234 700 327 5464' }}</li>
                <li><i class="fab fa-whatsapp"></i> {{ $settings['whatsapp_number'] ?? '+234 806 444 4444' }}</li>
                <li><i class="fas fa-map-marker-alt"></i> Owerri, Imo State</li>
            </ul>
        </div>

        <div class="contact-form">
            @if(session('status'))
                <div class="alert success">{{ session('status') }}</div>
            @endif
            <form method="POST" action="{{ route('contact.store') }}">
                @csrf
                <label>Name
                    <input type="text" name="name" value="{{ old('name') }}" required>
                </label>
                <label>Email
                    <input type="email" name="email" value="{{ old('email') }}" required>
                </label>
                <label>Phone
                    <input type="text" name="phone" value="{{ old('phone') }}">
                </label>
                <label>Subject
                    <input type="text" name="subject" value="{{ old('subject') }}" required>
                </label>
                <label>Category
                    <select name="type" required>
                        @foreach(['general' => 'General', 'advertising' => 'Advertising', 'playlist' => 'Playlist Request', 'technical' => 'Technical'] as $key => $label)
                            <option value="{{ $key }}" @selected(old('type') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Message
                    <textarea name="message" rows="5" required>{{ old('message') }}</textarea>
                </label>
                <button type="submit" class="listen-btn">Send Message</button>
            </form>
        </div>
    </section>
@endsection

