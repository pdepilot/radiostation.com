@extends('layouts.frontend', ['title' => 'DARLING FM • Revolutionary Contact'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
@endpush

@section('content')
    <div class="hologram-effect"></div>

    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>CONTACT THE FUTURE</h1>
                <p>
                    Connect with us through revolutionary communication channels. 
                    Experience next-level interaction with holographic interfaces and AI-powered responses.
                </p>
            </div>

            <!-- Contact Grid -->
            <div class="contact-grid">
                <!-- Contact Form -->
                <section class="contact-form-section">
                    <div class="form-container">
                        <h2 class="form-title">HOLOGRAM MESSAGE</h2>
                        @if(session('status'))
                            <div class="alert success">{{ session('status') }}</div>
                        @endif
                        <form method="POST" action="{{ route('contact.store') }}" id="contactForm">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-input" id="name" name="name" value="{{ old('name') }}" placeholder=" " required>
                                <label for="name" class="form-label">Your Name</label>
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-group">
                                <input type="email" class="form-input" id="email" name="email" value="{{ old('email') }}" placeholder=" " required>
                                <label for="email" class="form-label">Quantum Email</label>
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-input" id="phone" name="phone" value="{{ old('phone') }}" placeholder=" ">
                                <label for="phone" class="form-label">Phone (Optional)</label>
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-input" id="subject" name="subject" value="{{ old('subject') }}" placeholder=" " required>
                                <label for="subject" class="form-label">Transmission Subject</label>
                                @error('subject')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-group">
                                <select class="form-input" id="type" name="type" required style="padding-top: 20px;">
                                    @foreach(['general' => 'General', 'advertising' => 'Advertising', 'playlist' => 'Playlist Request', 'technical' => 'Technical'] as $key => $label)
                                        <option value="{{ $key }}" @selected(old('type') === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <label for="type" class="form-label">Message Category</label>
                            </div>
                            <div class="textarea-group">
                                <textarea class="form-textarea" id="message" name="message" placeholder=" " required>{{ old('message') }}</textarea>
                                <label for="message" class="form-label">Holographic Message</label>
                                @error('message')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane"></i> TRANSMIT MESSAGE
                            </button>
                        </form>
                    </div>
                </section>

                <!-- Contact Information -->
                <section class="contact-info-section">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-broadcast-tower"></i>
                        </div>
                        <h3 class="info-title">STATION HQ</h3>
                        <div class="info-content">
                            <p>Darling Broadcasting Center</p>
                            <p>Heartland-Owerri, Imo State</p>
                            <p>Phase Shift: 5.4839° N, 7.0333° E</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <h3 class="info-title">DARLING COMMUNICATION</h3>
                        <div class="info-content">
                            <p>Studio Hotline: {{ $settings['studio_hotline'] ?? '+234 700 327 5464' }}</p>
                            <p>WhatsApp: {{ $settings['whatsapp_number'] ?? '+234 806 444 4444' }}</p>
                            <p>Encrypted Channel: Available 24/7</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="info-title">DIGITAL TRANSMISSIONS</h3>
                        <div class="info-content">
                            <p>Main Hub: <a href="mailto:contact@darlingfm.ng" class="info-link">contact@darlingfm.ng</a></p>
                            <p>Music Submissions: <a href="mailto:music@darlingfm.ng" class="info-link">music@darlingfm.ng</a></p>
                            <p>Partnerships: <a href="mailto:partners@darlingfm.ng" class="info-link">partners@darlingfm.ng</a></p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Interactive Map -->
            <section class="map-section">
                <div class="map-container">
                    <div class="map-overlay">
                        <h3 class="map-title">DARLING FM LOCATION</h3>
                        <p class="map-subtitle">
                            Our headquarters exist in multiple dimensions simultaneously. 
                            Visit our main physical manifestation in the Heartland-Owerri district.
                        </p>
                        <a href="https://maps.google.com/?q=Owerri+Imo+State+Nigeria" target="_blank" class="visit-btn" id="viewLocation">
                            <i class="fas fa-map-marker-alt"></i> INITIATE TELEPORT
                        </a>
                    </div>
                </div>
            </section>

            <!-- Team Section -->
            <section class="team-section">
                <h2 class="section-title">DARLING TEAM</h2>
                <div class="team-grid">
                    <div class="team-card">
                        <div class="team-content">
                            <div class="team-avatar">
                                <i class="fas fa-user-astronaut" style="color: #0099ff;"></i>
                            </div>
                            <h3 class="team-name">JOSH</h3>
                            <p class="team-role">Darling Director</p>
                            <p class="team-contact">josh@darlingfm.ng</p>
                        </div>
                    </div>

                    <div class="team-card">
                        <div class="team-content">
                            <div class="team-avatar">
                                <i class="fas fa-robot" style="color: #ff0000;"></i>
                            </div>
                            <h3 class="team-name">KIZITO</h3>
                            <p class="team-role">AI Coordinator</p>
                            <p class="team-contact">kizito@darlingfm.ng</p>
                        </div>
                    </div>

                    <div class="team-card">
                        <div class="team-content">
                            <div class="team-avatar">
                                <i class="fas fa-headphones" style="color: #9b59b6;"></i>
                            </div>
                            <h3 class="team-name">SAMMY</h3>
                            <p class="team-role">Sound Architect</p>
                            <p class="team-contact">sammy@darlingfm.ng</p>
                        </div>
                    </div>

                    <div class="team-card">
                        <div class="team-content">
                            <div class="team-avatar">
                                <i class="fas fa-satellite" style="color: #00cc66;"></i>
                            </div>
                            <h3 class="team-name">ORBIT</h3>
                            <p class="team-role">Transmission Specialist</p>
                            <p class="team-contact">orbit@darlingfm.ng</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/contact.js') }}" defer></script>
@endpush
