<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Darling FM • Nigerian Standard Radio')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">

    @php
        $routeName = optional(request()->route())->getName() ?? '';
        $pageCss = match(true) {
            str_contains($routeName, 'contact') => 'contact.css',
            str_contains($routeName, 'djs') => 'djs.css',
            str_contains($routeName, 'live') => 'live-stream.css',
            str_contains($routeName, 'shows') => 'shows.css',
            str_contains($routeName, 'playlist') => 'playlist.css',
            str_contains($routeName, 'podcast') => 'podcast.css',
            default => null
        };
        $jsFile = 'index.js';
        if(str_contains($routeName, 'contact')) $jsFile = 'contact.js';
        elseif(str_contains($routeName, 'djs')) $jsFile = 'djs.js';
        elseif(str_contains($routeName, 'live')) $jsFile = 'live-stream.js';
        elseif(str_contains($routeName, 'shows')) $jsFile = 'shows.js';
        elseif(str_contains($routeName, 'playlist')) $jsFile = 'playlist.js';
        elseif(str_contains($routeName, 'podcast')) $jsFile = 'podcast.js';
    @endphp

    @if($pageCss)
        <link rel="stylesheet" href="{{ asset('assets/css/' . $pageCss) }}">
    @endif
    @stack('styles')
</head>
<body>
    <div class="cyber-grid"></div>

    <!-- Header -->
    <header id="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="/"><img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM"></a>
                </div>
                <button class="mobile-menu" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
                <nav class="desktop-nav">
                    <ul>
                        <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
                        <li><a href="/live" class="{{ request()->is('live*') ? 'active' : '' }}">Live Stream</a></li>
                        <li><a href="/shows" class="{{ request()->is('shows*') ? 'active' : '' }}">Shows</a></li>
                        <li><a href="/djs" class="{{ request()->is('djs*') ? 'active' : '' }}">DJs</a></li>
                        <li><a href="/playlist" class="{{ request()->is('playlist*') ? 'active' : '' }}">Playlist</a></li>
                        <li><a href="/podcasts" class="{{ request()->is('podcasts*') ? 'active' : '' }}">Podcasts</a></li>
                        <li><a href="/news" class="{{ request()->is('news*') ? 'active' : '' }}">News</a></li>
                        <li><a href="/contact" class="{{ request()->is('contact*') ? 'active' : '' }}">Contact</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <nav class="mobile-nav" id="mobileNav">
        <ul>
            <li><a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Home</a></li>
            <li><a href="/live">Live Stream</a></li>
            <li><a href="/shows">Shows</a></li>
            <li><a href="/djs">DJs</a></li>
            <li><a href="/playlist">Playlist</a></li>
            <li><a href="/podcasts">Podcasts</a></li>
            <li><a href="/news">News</a></li>
            <li><a href="/contact">Contact</a></li>
        </ul>
    </nav>

    <main>@yield('content')</main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM">
                    <p>The future of radio streaming. Experience sound in a whole new dimension.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="footer-section"><h3>Quick Links</h3>
                    <div class="footer-links">
                        <a href="/">Home</a><a href="/live">Live Stream</a><a href="/shows">Shows</a><a href="/djs">DJs</a>
                        <a href="/playlist">Playlist</a><a href="/podcasts">Podcasts</a><a href="/contact">Contact</a>
                    </div>
                </div>
                <div class="footer-section"><h3>Support</h3>
                    <div class="footer-links">
                        <a href="/contact">Contact Us</a><a href="#">Privacy Policy</a><a href="#">Terms of Service</a>
                        <a href="#">FAQ</a><a href="/contact">Feedback</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Subscribe</h3>
                    <p>Get the latest updates and exclusive content.</p>
                    <form action="/subscribe" method="POST" class="footer-input">
                        @csrf
                        <input type="email" name="email" placeholder="Your email" required>
                        <button type="submit"><i class="fas fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
            <div class="copyright">© {{ date('Y') }} Darling FM. All rights reserved | ERIBS TECH</div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/' . $jsFile) }}" defer></script>
    @stack('scripts')
</body>
</html>