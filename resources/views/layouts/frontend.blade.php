<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Darling FM' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    @stack('styles')
</head>
<body>
    @php($settings = ($settings ?? \App\Models\SiteSetting::query()->pluck('value', 'key')))
    <div class="cyber-grid"></div>
    <header id="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM Logo">
                    </a>
                </div>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
                <nav class="desktop-nav">
                    <ul>
                        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                        <li><a href="{{ route('live') }}" class="{{ request()->routeIs('live') ? 'active' : '' }}">Live Stream</a></li>
                        <li><a href="{{ route('shows.index') }}" class="{{ request()->routeIs('shows.*') ? 'active' : '' }}">Shows</a></li>
                        <li><a href="{{ route('djs.index') }}" class="{{ request()->routeIs('djs.*') ? 'active' : '' }}">DJs</a></li>
                        <li><a href="{{ route('playlist.index') }}" class="{{ request()->routeIs('playlist.*') ? 'active' : '' }}">Playlist</a></li>
                        <li><a href="{{ route('podcasts.index') }}" class="{{ request()->routeIs('podcasts.*') ? 'active' : '' }}">Podcasts</a></li>
                        <li><a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'active' : '' }}">News</a></li>
                        <li><a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.index') ? 'active' : '' }}">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4>DARLING FM</h4>
                    <p>Owerri's most futuristic radio station. 24/7 programming with culture, music and real talk.</p>
                </div>
                <div>
                    <h4>Studio Lines</h4>
                    <p>Phone: {{ $settings['studio_hotline'] ?? '+234 700 327 5464' }}</p>
                    <p>WhatsApp: {{ $settings['whatsapp_number'] ?? '+234 806 444 4444' }}</p>
                </div>
                <div>
                    <h4>Links</h4>
                    <ul>
                        <li><a href="{{ route('live') }}">Listen Live</a></li>
                        <li><a href="{{ route('shows.index') }}">Show Schedule</a></li>
                        <li><a href="{{ route('podcasts.index') }}">Podcasts</a></li>
                        <li><a href="{{ route('contact.index') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
            <p class="footer-copy">&copy; {{ date('Y') }} Darling FM 107.3 Owerri. All rights reserved.</p>
        </div>
    </footer>
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
    @stack('scripts')
</body>
</html>

