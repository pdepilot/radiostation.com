<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Darling FM 107.3 - Owerri\'s Premier Radio Station' }}</title>
    <meta name="description" content="{{ $description ?? 'Darling FM 107.3 - Owerri\'s premier radio station. Listen live, discover shows, news, events, and connect with your favorite on-air personalities.' }}">
    <meta name="keywords" content="Darling FM, Radio Owerri, 107.3 FM, Owerri Radio, Live Radio, Music, News, Events, On-Air Personalities">
    <meta name="author" content="Darling FM">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'Darling FM 107.3 - Owerri\'s Premier Radio Station' }}">
    <meta property="og:description" content="{{ $description ?? 'Darling FM 107.3 - Owerri\'s premier radio station. Listen live, discover shows, news, events, and connect with your favorite on-air personalities.' }}">
    <meta property="og:image" content="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $title ?? 'Darling FM 107.3 - Owerri\'s Premier Radio Station' }}">
    <meta property="twitter:description" content="{{ $description ?? 'Darling FM 107.3 - Owerri\'s premier radio station. Listen live, discover shows, news, events, and connect with your favorite on-air personalities.' }}">
    <meta property="twitter:image" content="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Oxanium:wght@400;500;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sticky-player.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/news-search.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- AEO & AI Optimization Meta Tags -->
    <meta name="format-detection" content="telephone=yes">
    <meta name="theme-color" content="#c8102e">
    <link rel="sitemap" type="application/xml" href="{{ route('sitemap') }}">

    @php
    $settings = $settings ?? \App\Models\SiteSetting::query()->pluck('value', 'key')->toArray();
    $socialLinks = [];
    if (!empty($settings['facebook_url'] ?? null)) {
        $socialLinks[] = $settings['facebook_url'];
    }
    if (!empty($settings['twitter_url'] ?? null)) {
        $socialLinks[] = $settings['twitter_url'];
    }
    if (!empty($settings['instagram_url'] ?? null)) {
        $socialLinks[] = $settings['instagram_url'];
    }
    if (!empty($settings['youtube_url'] ?? null)) {
        $socialLinks[] = $settings['youtube_url'];
    }
    $socialLinksJson = json_encode($socialLinks, JSON_UNESCAPED_SLASHES);
    @endphp

    <!-- Schema.org JSON-LD - Radio Station -->
    @php
    $jsonLd = [
        '@context' => 'https://schema.org',
        '@type' => 'RadioStation',
        'name' => 'Darling FM 107.3',
        'alternateName' => 'Darling FM',
        'description' => "Darling FM 107.3 is Owerri's premier radio station, broadcasting lifestyle and edutainment content 24/7. Listen live, discover shows, news, events, and connect with on-air personalities.",
        'url' => url('/'),
        'logo' => asset('assets/images/REAL_LOGO-removebg-preview.png'),
        'image' => asset('assets/images/REAL_LOGO-removebg-preview.png'),
        'foundingDate' => '2020',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Owerri',
            'addressRegion' => 'Imo State',
            'addressCountry' => 'NG'
        ],
        'contactPoint' => [
            '@type' => 'ContactPoint',
            'telephone' => '+234-809-444-1073',
            'contactType' => 'Customer Service',
            'availableLanguage' => 'English'
        ],
        'sameAs' => $socialLinks,
        'broadcastFrequency' => [
            '@type' => 'BroadcastFrequencySpecification',
            'broadcastFrequencyValue' => '107.3',
            'broadcastSignalModulation' => 'FM'
        ]
    ];
    $jsonLdString = json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    @endphp
    <script type="application/ld+json">
{!! $jsonLdString !!}
    </script>

    @stack('styles')
    
    @livewireStyles

    <!-- Real-time Updates & Modal System -->
    <script src="{{ asset('assets/js/realtime-updates.js') }}" defer></script>
    <script src="{{ asset('assets/js/modal-system.js') }}" defer></script>
</head>

<body>
    @php
    if (!isset($settings)) {
    $settings = \App\Models\SiteSetting::query()->pluck('value', 'key')->toArray();
    }
    @endphp
    <div class="cyber-grid"></div>
    <header id="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ route('home') }}" wire:navigate style="display: flex; align-items: center;">
                        <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM Logo" style="height: 80px; width: auto; max-width: 250px; filter: drop-shadow(0 0 15px var(--accent)); object-fit: contain;">
                    </a>
                </div>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
                <nav class="desktop-nav">
                    <ul style="display: flex; align-items: center; list-style: none; gap: 30px; margin: 0; padding: 0;">
                        <li style="display: flex; align-items: center;"><a href="{{ route('home') }}" wire:navigate class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                        <li style="display: flex; align-items: center;"><a href="{{ route('shows.index') }}" wire:navigate class="{{ request()->routeIs('shows.*') ? 'active' : '' }}">Shows</a></li>
                        <li style="display: flex; align-items: center;"><a href="{{ route('events.index') }}" wire:navigate class="{{ request()->routeIs('events.*') ? 'active' : '' }}">Events</a></li>
                        <li style="display: flex; align-items: center;"><a href="{{ route('news.index') }}" wire:navigate class="{{ request()->routeIs('news.*') ? 'active' : '' }}">News</a></li>
                        <li style="display: flex; align-items: center;"><a href="{{ route('contact.index') }}" wire:navigate class="{{ request()->routeIs('contact.index') ? 'active' : '' }}">Contact</a></li>
                        <li style="margin-left: 40px; position: relative; display: flex; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <!-- Intelligent News Search -->
                                <div class="nav-search-container" style="position: relative;">
                                    <input type="text"
                                        id="navNewsSearch"
                                        placeholder="Search OAPs, news, events, shows..."
                                        autocomplete="off"
                                        class="nav-search-input"
                                        style="padding: 10px 40px 10px 15px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 25px; color: var(--light); outline: none; width: 200px; font-size: 0.9rem; transition: width 0.3s, border-color 0.3s;"
                                        onfocus="this.style.width='280px'; this.style.borderColor='var(--accent)'"
                                        onblur="this.style.width='200px'; this.style.borderColor='var(--glass-border)'">
                                    <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none;"></i>
                                    <div id="navSearchResults" class="nav-search-results" style="display: none; position: absolute; top: calc(100% + 8px); left: 0; right: 0; min-width: 350px; background: rgba(15, 15, 20, 0.98); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 12px; max-height: 500px; overflow-y: auto; z-index: 10000; box-shadow: 0 15px 40px rgba(0,0,0,0.6); padding: 0; opacity: 1; transform: translateY(0); transition: opacity 0.2s ease, transform 0.2s ease;"></div>
                                </div>
                                @guest
                                <button onclick="openAuthModal('login'); return false;" style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--glass); border: 1px solid var(--glass-border); color: var(--light); text-decoration: none; transition: all 0.3s; font-size: 1.1rem; cursor: pointer;" title="Login or Register" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--glass)'">
                                    <i class="fas fa-user"></i>
                                </button>
                                @else
                                    @php
                                        $isAdmin = auth()->check() && auth()->user() && method_exists(auth()->user(), 'isAdmin') && auth()->user()->isAdmin();
                                    @endphp
                                    @if($isAdmin)
                                    <a href="{{ url('/admin') }}" wire:navigate style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--glass); border: 1px solid var(--glass-border); color: var(--light); text-decoration: none; transition: all 0.3s; font-size: 1.1rem;" title="Admin Dashboard" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--glass)'">
                                        <i class="fas fa-tachometer-alt"></i>
                                    </a>
                                    @else
                                    <a href="{{ route('profile.edit') }}" wire:navigate style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--glass); border: 1px solid var(--glass-border); color: var(--light); text-decoration: none; transition: all 0.3s; font-size: 1.1rem;" title="Profile" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--glass)'">
                                        <i class="fas fa-user-circle"></i>
                                    </a>
                                    @endif
                                <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: inline;">
                                    @csrf
                                    <button type="submit" style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--glass); border: 1px solid var(--glass-border); color: var(--light); cursor: pointer; transition: all 0.3s; font-size: 1.1rem;" title="Logout" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--glass)'">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </button>
                                </form>
                                <script>
                                    // Handle logout with SPA navigation to preserve audio player
                                    function attachLogoutHandler() {
                                        const logoutForm = document.getElementById('logoutForm');
                                        if (logoutForm && !logoutForm.dataset.listenerAttached) {
                                            logoutForm.dataset.listenerAttached = 'true';
                                            logoutForm.addEventListener('submit', function(e) {
                                                e.preventDefault();
                                                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                                                if (!csrfToken || !csrfToken.content) {
                                                    // CSRF token missing - use Livewire navigate if available
                                                    if (window.Livewire && window.Livewire.navigate) {
                                                        window.Livewire.navigate('/');
                                                    } else if (window.location) {
                                                        window.location.replace('/');
                                                    }
                                                    return;
                                                }
                                                // Submit logout via fetch
                                                fetch('{{ route('logout') }}', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/x-www-form-urlencoded',
                                                        'X-CSRF-TOKEN': csrfToken.content,
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    },
                                                    body: new URLSearchParams(new FormData(logoutForm))
                                                }).then(() => {
                                                    // Navigate to home using Livewire's wire:navigate (preserves audio player)
                                                    if (window.Livewire && window.Livewire.navigate) {
                                                        window.Livewire.navigate('/');
                                                    } else if (window.location) {
                                                        // Fallback: use location.replace to avoid full page reload
                                                        window.location.replace('/');
                                                    }
                                                }).catch((error) => {
                                                    console.error('Logout error:', error);
                                                    // Fallback: use location.replace to avoid full page reload
                                                    if (window.Livewire && window.Livewire.navigate) {
                                                        window.Livewire.navigate('/');
                                                    } else if (window.location) {
                                                        window.location.replace('/');
                                                    }
                                                });
                                            });
                                        }
                                    }
                                    
                                    // Initialize on DOM ready
                                    if (document.readyState === 'loading') {
                                        document.addEventListener('DOMContentLoaded', attachLogoutHandler);
                                    } else {
                                        attachLogoutHandler();
                                    }
                                    
                                    // Re-initialize on Livewire navigation
                                    document.addEventListener('livewire:navigated', attachLogoutHandler);
                                </script>
                                @endguest
                            </div>
                        </li>
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
            <div class="footer-content">
                <div class="footer-section">
                    <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM" style="height: 80px; width: auto; max-width: 250px; object-fit: contain; margin-bottom: 20px;">
                    <p>Darling 107.3 FM is Nigeria’s leading healthy lifestyle and edutainment radio station, blending urban and contemporary music with news, talk, and engaging conversations that inform, inspire, and entertain.</p>
                    <div class="social-links">
                        @if(!empty($twitterUrl))
                        <a href="{{ $twitterUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if(!empty($instagramUrl))
                        <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if(!empty($facebookUrl))
                        <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if(!empty($youtubeUrl))
                        <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        @endif
                        @if(!empty($tiktokUrl))
                        <a href="{{ $tiktokUrl }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                        @endif
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <div class="footer-links">
                        <a href="{{ route('home') }}" wire:navigate>Home</a>
                        <a href="{{ route('shows.index') }}" wire:navigate>Shows</a>
                        <a href="{{ route('events.index') }}" wire:navigate>Events</a>
                        <a href="{{ route('news.index') }}" wire:navigate>News</a>
                        <a href="{{ route('contact.index') }}" wire:navigate>Contact</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <div class="footer-links">
                        <a href="{{ route('contact.index') }}" wire:navigate>Contact Us</a>
                        <a href="{{ route('privacy') }}" wire:navigate>Privacy Policy</a>
                        <a href="{{ route('terms') }}" wire:navigate>Terms of Service</a>
                        <a href="{{ route('faq') }}" wire:navigate>FAQ</a>
                        <a href="{{ route('contact.index', ['type' => 'feedback']) }}" wire:navigate>Feedback</a>
                        <a href="javascript:void(0);" onclick="window.openMusicPromotionModal()" style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-music"></i> Promote Your Music
                        </a>
                    </div>
                </div>
            </div>
            <div class="copyright">© {{ date('Y') }} Darling FM. All rights reserved.</div>
        </div>
    </footer>
    <!-- Floating Action Buttons -->
    <div class="floating-buttons" style="position: fixed; bottom: 90px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 15px; transition: bottom 0.3s;">
        <!-- WhatsApp Button -->
        <a href="https://wa.me/{{ str_replace([' ', '+'], '', ($settings['whatsapp_number'] ?? '+2348064444444')) }}" target="_blank" class="floating-btn whatsapp-btn" style="width: 50px; height: 50px; border-radius: 50%; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: transform 0.3s; font-size: 1.5rem;" title="Contact us on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
        <!-- Chatbot Button -->
        <button class="floating-btn chatbot-btn" id="chatbotBtn" style="width: 50px; height: 50px; border-radius: 50%; background: var(--accent); color: white; border: none; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 15px rgba(0,0,0,0.3); cursor: pointer; transition: transform 0.3s; font-size: 1.5rem;" title="Ask Darling">
            <i class="fas fa-comments"></i>
        </button>
    </div>

    <!-- Chatbot Modal -->
    <div id="chatbotModal" style="display: none; position: fixed; bottom: 90px; right: 20px; width: 350px; max-width: 90vw; height: 500px; max-height: calc(100vh - 180px); background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid var(--glass-border); box-shadow: 0 10px 40px rgba(0,0,0,0.5); z-index: 10000; flex-direction: column; transition: bottom 0.3s;">
        <div style="padding: 20px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="color: var(--accent); margin: 0; font-family: 'Oxanium', sans-serif; font-weight: 600; letter-spacing: 0.5px;">Ask Darling</h3>
            <button id="closeChatbot" style="background: none; border: none; color: var(--light); font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <div id="chatbotMessages" style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 15px;">
            <!-- Messages will be added dynamically -->
        </div>
        <div style="padding: 20px; border-top: 1px solid var(--glass-border); display: flex; gap: 10px;">
            <input type="text" id="chatbotInput" placeholder="Type your message..." style="flex: 1; padding: 12px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 25px; color: var(--light); outline: none;">
            <button id="chatbotSend" style="background: var(--accent); color: white; border: none; padding: 12px 20px; border-radius: 25px; cursor: pointer;">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <script src="{{ asset('assets/js/form-confirm.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
    @stack('scripts')
    <script src="{{ asset('assets/js/news-search.js') }}" defer></script>
    <script src="{{ asset('assets/js/askdarling-chatbot.js') }}" defer></script>
    <script>
        // Floating buttons hover effect
        document.querySelectorAll('.floating-btn').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1)';
            });
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        });

        // Chatbot functionality is handled by askdarling-chatbot.js

        // Unified search functionality - Always shows categorized results (Type A)
        function initSearch() {
            const navNewsSearch = document.getElementById('navNewsSearch');
            const navSearchResults = document.getElementById('navSearchResults');

            if (!navNewsSearch || !navSearchResults) {
                // Retry initialization if elements not found (for dynamic content)
                setTimeout(initSearch, 100);
                return;
            }

            let searchTimeout;
            let hideTimeout;
            let currentSearchTerm = '';
            let isHoveringResults = false;
            let isSearching = false;
            let abortController = null;

            // Helper function to highlight matching text
            function highlightText(text, searchTerm) {
                if (!searchTerm || !text) return text;
                const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                return text.replace(regex, '<mark style="background: rgba(255,0,0,0.3); color: var(--accent); padding: 2px 4px; border-radius: 3px;">$1</mark>');
            }

            // Function to display categorized results (Type A - always)
            function displayCategorizedResults(data, searchTerm) {
                if (!data || data.length === 0) {
                    navSearchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);"><i class="fas fa-search"></i> No results found</div>';
                    navSearchResults.style.display = 'block';
                    return;
                }

                let html = '';
                // Group results by type - ALWAYS show categories
                const grouped = {};
                data.forEach(item => {
                    if (item && item.type) {
                        if (!grouped[item.type]) {
                            grouped[item.type] = [];
                        }
                        grouped[item.type].push(item);
                    }
                });

                // Display grouped results with category headers
                const typeLabels = {
                    'oap': 'On-Air Personalities',
                    'news': 'News',
                    'event': 'Events',
                    'show': 'Shows'
                };

                // Sort types in consistent order
                const typeOrder = ['oap', 'news', 'event', 'show'];
                typeOrder.forEach(type => {
                    if (grouped[type] && grouped[type].length > 0) {
                        html += `<div style="padding: 10px 15px; background: rgba(255,0,0,0.05); border-bottom: 1px solid var(--glass-border); font-size: 0.75rem; font-weight: 700; color: var(--accent); text-transform: uppercase; letter-spacing: 1px;">${typeLabels[type] || type}</div>`;
                        grouped[type].forEach((item, index) => {
                            const highlightedTitle = highlightText(item.title || '', searchTerm);
                            html += `
                                <a href="${item.url || '#'}" 
                                   wire:navigate
                                   style="display: flex; align-items: center; gap: 12px; padding: 12px 15px; border-bottom: 1px solid var(--glass-border); text-decoration: none; color: var(--light); transition: background 0.3s;" 
                                   onmouseover="this.style.background='rgba(255,0,0,0.1)'" 
                                   onmouseout="this.style.background='transparent'"
                                   data-search-result="${type}-${index}">
                                    <i class="${item.icon || 'fas fa-circle'}" style="color: var(--accent); font-size: 1.1rem; width: 20px; text-align: center; flex-shrink: 0;"></i>
                                    <div style="flex: 1; min-width: 0;">
                                        <div style="font-weight: 600; margin-bottom: 3px; font-size: 0.9rem; word-wrap: break-word;">${highlightedTitle}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-secondary); word-wrap: break-word;">${item.subtitle || ''}</div>
                                    </div>
                                </a>
                            `;
                        });
                    }
                });

                navSearchResults.innerHTML = html;
                navSearchResults.style.display = 'block';
                // Force reflow to ensure display is set before animation
                navSearchResults.offsetHeight;
                navSearchResults.style.opacity = '1';
                navSearchResults.style.transform = 'translateY(0)';
            }

            // Function to perform search
            function performSearch(searchTerm) {
                // Cancel any pending request
                if (abortController) {
                    abortController.abort();
                }
                abortController = new AbortController();

                isSearching = true;

                // Show loading state
                navSearchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
                navSearchResults.style.display = 'block';

                // Fetch unified search results (OAPs, News, Events, Shows) - ALWAYS use API
                fetch(`{{ route('search') }}?q=${encodeURIComponent(searchTerm)}`, {
                    signal: abortController.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // Check if search term changed during fetch
                    if (currentSearchTerm !== searchTerm) {
                        isSearching = false;
                        return;
                    }

                    isSearching = false;
                    displayCategorizedResults(data, searchTerm);
                })
                .catch(error => {
                    // Only handle abort errors silently, show others
                    if (error.name === 'AbortError') {
                        return;
                    }

                    // Check if search term changed during fetch
                    if (currentSearchTerm !== searchTerm) {
                        isSearching = false;
                        return;
                    }

                    isSearching = false;
                    console.error('Search error:', error);
                    
                    // Show error message instead of fallback
                    navSearchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);"><i class="fas fa-exclamation-circle"></i> Search temporarily unavailable. Please try again.</div>';
                    navSearchResults.style.display = 'block';
                });
            }

            // Input event handler
            navNewsSearch.addEventListener('input', function() {
                const searchTerm = this.value.trim();
                currentSearchTerm = searchTerm;

                // Clear any pending hide timeout
                clearTimeout(hideTimeout);

                // Clear previous search timeout
                clearTimeout(searchTimeout);

                if (searchTerm.length < 1) {
                    // Only hide if not hovering over results
                    if (!isHoveringResults) {
                        hideTimeout = setTimeout(() => {
                            if (!isHoveringResults) {
                                navSearchResults.style.display = 'none';
                                navSearchResults.innerHTML = '';
                            }
                        }, 200);
                    }
                    return;
                }

                // Debounce: minimum 300ms delay for all searches
                searchTimeout = setTimeout(() => {
                    // Only proceed if search term hasn't changed
                    if (currentSearchTerm !== searchTerm) return;
                    performSearch(searchTerm);
                }, 300);
            });

            // Track when user hovers over results
            navSearchResults.addEventListener('mouseenter', function() {
                isHoveringResults = true;
                clearTimeout(hideTimeout);
            });

            navSearchResults.addEventListener('mouseleave', function() {
                isHoveringResults = false;
            });

            // Close search results when clicking outside (with delay to allow clicking results)
            document.addEventListener('click', function(e) {
                if (!navNewsSearch.contains(e.target) && !navSearchResults.contains(e.target)) {
                    // Don't hide if searching or hovering
                    if (isSearching || isHoveringResults) return;
                    
                    // Add delay before hiding to allow time to click results
                    hideTimeout = setTimeout(() => {
                        if (!isHoveringResults && !isSearching) {
                            navSearchResults.style.display = 'none';
                        }
                    }, 150);
                }
            });

            // Handle focus to show results if there's a search term
            navNewsSearch.addEventListener('focus', function() {
                if (this.value.trim().length >= 1 && navSearchResults.innerHTML && navSearchResults.innerHTML.trim() !== '') {
                    navSearchResults.style.display = 'block';
                }
            });
        }
        
        // Initialize search on page load and after Livewire navigation
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSearch);
        } else {
            initSearch();
        }
        
        // Re-initialize search after Livewire navigation (SPA mode)
        document.addEventListener('livewire:navigated', initSearch);


        // Share functionality
        window.sharePost = function(title, url) {
            const shareUrl = encodeURIComponent(url);
            const shareText = encodeURIComponent(`Check out: ${title}`);

            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: shareText,
                    url: url
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(url).then(() => {
                    showSuccess('Link copied to clipboard!');
                });
            }
        };
    </script>

    {{-- Persistent Audio Player - Must be direct child of body, outside main content --}}
    {{-- Wrapped in @persist to survive Livewire navigation --}}
    @persist('radio-player')
        <div wire:key="global-audio-wrapper">
            <audio id="main-radio-player" preload="none" crossorigin="anonymous" style="display: none;"></audio>
            {{-- Sticky Player UI Component --}}
            @include('components.sticky-player')
        </div>
    @endpersist

    {{-- Persistent Video Element for HLS Streaming - Separate persist block --}}
    {{-- This is used by hls-live-player.js for HLS stream playback --}}
    @persist('station-player-video')
        <video id="station-player" preload="none" style="display: none; position: fixed; pointer-events: none; z-index: -1;" crossorigin="anonymous"></video>
    @endpersist

    {{-- Music Promotion Modal - Available on all pages --}}
    @include('components.music-promotion-modal')

    {{-- Persistent Audio Player Script - Must load before other scripts --}}
    {{-- global-audio.js handles initialization and reconnection on livewire:navigated --}}
    <script src="{{ asset('assets/js/global-audio.js') }}"></script>
    <script src="{{ asset('assets/js/hls-live-player.js') }}" defer></script>
    <script src="{{ asset('assets/js/music-promotion.js') }}" defer></script>
    
    @livewireScripts
    
    {{-- Global Error Handler for Console Debugging --}}
    <script>
        // Catch all JavaScript errors
        window.addEventListener('error', function(e) {
        }, true);
        
        // Catch unhandled promise rejections
        window.addEventListener('unhandledrejection', function(e) {
        });
    </script>
    
    {{-- Unified Notification System --}}
    <x-notification-system />
    
    {{-- Auth Modal --}}
    <x-auth-modal mode="login" />
</body>

</html>