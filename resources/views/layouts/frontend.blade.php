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
    
    <link rel="icon" type="image/png" href="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sticky-player.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/news-search.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @stack('styles')
</head>
<body>
    @php($settings = ($settings ?? \App\Models\SiteSetting::query()->pluck('value', 'key')))
    <div class="cyber-grid"></div>
    <header id="main-header">
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <a href="{{ route('home') }}" style="display: flex; align-items: center;">
                        <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM Logo" style="height: 80px; width: auto; max-width: 250px; filter: drop-shadow(0 0 15px var(--accent)); object-fit: contain;">
                    </a>
                </div>
                <div class="mobile-menu">
                    <i class="fas fa-bars"></i>
                </div>
                <nav class="desktop-nav">
                    <ul style="display: flex; align-items: center; list-style: none; gap: 30px; margin: 0; padding: 0;">
                        <li style="display: flex; align-items: center;"><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                        <li style="display: flex; align-items: center;"><a href="{{ route('shows.index') }}" class="{{ request()->routeIs('shows.*') ? 'active' : '' }}">Shows</a></li>
                        <li style="display: flex; align-items: center;"><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">Events</a></li>
                        <li style="display: flex; align-items: center;"><a href="{{ route('news.index') }}" class="{{ request()->routeIs('news.*') ? 'active' : '' }}">News</a></li>
                        <li style="display: flex; align-items: center;"><a href="{{ route('contact.index') }}" class="{{ request()->routeIs('contact.index') ? 'active' : '' }}">Contact</a></li>
                        <li style="margin-left: 40px; position: relative; display: flex; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <!-- Intelligent News Search -->
                                <div class="nav-search-container" style="position: relative;">
                                    <input type="text" 
                                           id="navNewsSearch" 
                                           placeholder="Search news, presenters, dates..." 
                                           autocomplete="off"
                                           style="padding: 10px 40px 10px 15px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 25px; color: var(--light); outline: none; width: 200px; font-size: 0.9rem; transition: width 0.3s, border-color 0.3s;" 
                                           onfocus="this.style.width='280px'; this.style.borderColor='var(--accent)'" 
                                           onblur="this.style.width='200px'; this.style.borderColor='var(--glass-border)'">
                                    <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: var(--text-secondary); pointer-events: none;"></i>
                                    <div id="navSearchResults" class="nav-search-results" style="display: none; position: absolute; top: calc(100% + 8px); left: 0; right: 0; min-width: 350px; background: var(--glass); backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 12px; max-height: 500px; overflow-y: auto; z-index: 10000; box-shadow: 0 15px 40px rgba(0,0,0,0.6); padding: 0; opacity: 0; transform: translateY(-10px); transition: opacity 0.2s ease, transform 0.2s ease;"></div>
                                </div>
                                @guest
                                    <a href="{{ route('login') }}" style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--glass); border: 1px solid var(--glass-border); color: var(--light); text-decoration: none; transition: all 0.3s; font-size: 1.1rem;" title="Login or Register" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--glass)'">
                                        <i class="fas fa-user"></i>
                                    </a>
                                @else
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--glass); border: 1px solid var(--glass-border); color: var(--light); text-decoration: none; transition: all 0.3s; font-size: 1.1rem;" title="Admin Dashboard" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--glass)'">
                                            <i class="fas fa-tachometer-alt"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('profile.edit') }}" style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--glass); border: 1px solid var(--glass-border); color: var(--light); text-decoration: none; transition: all 0.3s; font-size: 1.1rem;" title="Profile" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--glass)'">
                                            <i class="fas fa-user-circle"></i>
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="display: flex; align-items: center; justify-content: center; width: 45px; height: 45px; border-radius: 50%; background: var(--glass); border: 1px solid var(--glass-border); color: var(--light); cursor: pointer; transition: all 0.3s; font-size: 1.1rem;" title="Logout" onmouseover="this.style.background='var(--accent)'" onmouseout="this.style.background='var(--glass)'">
                                            <i class="fas fa-sign-out-alt"></i>
                                        </button>
                                    </form>
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
                    <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM">
                    <p>The future of radio streaming. Experience sound in a whole new dimension.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="#" aria-label="TikTok"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <div class="footer-links">
                        <a href="{{ route('home') }}">Home</a>
                        <a href="{{ route('shows.index') }}">Shows</a>
                        <a href="{{ route('events.index') }}">Events</a>
                        <a href="{{ route('news.index') }}">News</a>
                        <a href="{{ route('contact.index') }}">Contact</a>
                    </div>
                </div>
                <div class="footer-section">
                    <h3>Support</h3>
                    <div class="footer-links">
                        <a href="{{ route('contact.index') }}">Contact Us</a>
                        <a href="{{ route('privacy') }}">Privacy Policy</a>
                        <a href="{{ route('terms') }}">Terms of Service</a>
                        <a href="{{ route('faq') }}">FAQ</a>
                        <a href="{{ route('contact.index', ['type' => 'feedback']) }}">Feedback</a>
                    </div>
                </div>
            </div>
            <div class="copyright">© {{ date('Y') }} Darling FM. All rights reserved.</div>
        </div>
    </footer>
    <!-- Floating Action Buttons -->
    <div class="floating-buttons" style="position: fixed; bottom: 90px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 15px; transition: bottom 0.3s;">
        <!-- WhatsApp Button -->
        <a href="https://wa.me/2348064444444" target="_blank" class="floating-btn whatsapp-btn" style="width: 50px; height: 50px; border-radius: 50%; background: #25D366; color: white; display: flex; align-items: center; justify-content: center; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: transform 0.3s; font-size: 1.5rem;" title="Contact us on WhatsApp">
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
            <h3 style="color: var(--accent); margin: 0; font-family: 'Orbitron', sans-serif;">Ask Darling</h3>
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

    <script src="{{ asset('assets/js/notifications.js') }}"></script>
    <script src="{{ asset('assets/js/form-confirm.js') }}"></script>
    <script src="{{ asset('assets/js/global-audio.js') }}"></script>
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
        
        // News search functionality - Real-time search
        const navNewsSearch = document.getElementById('navNewsSearch');
        const navSearchResults = document.getElementById('navSearchResults');
        
        if (navNewsSearch && navSearchResults) {
            let searchTimeout;
            let currentSearchTerm = '';
            
            navNewsSearch.addEventListener('input', function() {
                const searchTerm = this.value.trim();
                currentSearchTerm = searchTerm;
                
                clearTimeout(searchTimeout);
                
                if (searchTerm.length < 1) {
                    navSearchResults.style.display = 'none';
                    navSearchResults.innerHTML = '';
                    return;
                }
                
                // Show loading state immediately
                navSearchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';
                navSearchResults.style.display = 'block';
                
                // Debounce for API calls (only if search term is 2+ chars), but show results immediately for single char
                const searchDelay = searchTerm.length >= 2 ? 100 : 0;
                
                searchTimeout = setTimeout(() => {
                    // Only proceed if search term hasn't changed
                    if (currentSearchTerm !== searchTerm) return;
                    
                    // Fetch news results
                    fetch(`{{ route('news.search') }}?q=${encodeURIComponent(searchTerm)}`)
                        .then(response => response.json())
                        .then(data => {
                            // Check if search term changed during fetch
                            if (currentSearchTerm !== searchTerm) return;
                            
                            if (data.length === 0) {
                                navSearchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);"><i class="fas fa-search"></i> No results found</div>';
                            } else {
                                let html = '';
                                data.forEach(news => {
                                    // Highlight matching text
                                    const title = news.title || '';
                                    const excerpt = news.excerpt || '';
                                    const highlightedTitle = highlightText(title, searchTerm);
                                    const highlightedExcerpt = highlightText(excerpt, searchTerm);
                                    
                                    html += `
                                        <a href="/news/${news.slug}" style="display: block; padding: 15px; border-bottom: 1px solid var(--glass-border); text-decoration: none; color: var(--light); transition: background 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'" onmouseout="this.style.background='transparent'">
                                            <div style="font-weight: 600; margin-bottom: 5px;">${highlightedTitle}</div>
                                            <div style="font-size: 0.85rem; color: var(--text-secondary);">${highlightedExcerpt}</div>
                                        </a>
                                    `;
                                });
                                navSearchResults.innerHTML = html;
                            }
                            navSearchResults.style.display = 'block';
                        })
                        .catch(() => {
                            // Fallback: search in current page
                            if (currentSearchTerm !== searchTerm) return;
                            
                            const allNews = document.querySelectorAll('.post-card');
                            let matches = [];
                            const searchLower = searchTerm.toLowerCase();
                            
                            allNews.forEach(card => {
                                const title = card.querySelector('.post-title')?.textContent || '';
                                const excerpt = card.querySelector('.post-excerpt')?.textContent || '';
                                if (title.toLowerCase().includes(searchLower) || excerpt.toLowerCase().includes(searchLower)) {
                                    const link = card.querySelector('a[href*="/news/"]');
                                    if (link) {
                                        matches.push({
                                            title: title,
                                            excerpt: excerpt,
                                            url: link.getAttribute('href')
                                        });
                                    }
                                }
                            });
                            
                            if (matches.length === 0) {
                                navSearchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);"><i class="fas fa-search"></i> No results found</div>';
                            } else {
                                let html = '';
                                matches.forEach(news => {
                                    const highlightedTitle = highlightText(news.title, searchTerm);
                                    const highlightedExcerpt = highlightText(news.excerpt.substring(0, 80), searchTerm);
                                    
                                    html += `
                                        <a href="${news.url}" style="display: block; padding: 15px; border-bottom: 1px solid var(--glass-border); text-decoration: none; color: var(--light); transition: background 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'" onmouseout="this.style.background='transparent'">
                                            <div style="font-weight: 600; margin-bottom: 5px;">${highlightedTitle}</div>
                                            <div style="font-size: 0.85rem; color: var(--text-secondary);">${highlightedExcerpt}...</div>
                                        </a>
                                    `;
                                });
                                navSearchResults.innerHTML = html;
                            }
                            navSearchResults.style.display = 'block';
                        });
                }, searchDelay);
            });
            
            // Helper function to highlight matching text
            function highlightText(text, searchTerm) {
                if (!searchTerm || !text) return text;
                const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                return text.replace(regex, '<mark style="background: rgba(255,0,0,0.3); color: var(--accent); padding: 2px 4px; border-radius: 3px;">$1</mark>');
            }
            
            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (!navNewsSearch.contains(e.target) && !navSearchResults.contains(e.target)) {
                    navSearchResults.style.display = 'none';
                }
            });
        }
        
        // Like functionality
        window.toggleLike = function(postId) {
            const likeBtn = document.querySelector(`.like-btn[data-post-id="${postId}"]`);
            const likeCount = likeBtn?.querySelector('.like-count');
            const icon = likeBtn?.querySelector('i');
            
            if (likeBtn && likeCount) {
                const currentCount = parseInt(likeCount.textContent) || 0;
                const isLiked = icon?.classList.contains('fas');
                
                if (isLiked) {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    likeCount.textContent = currentCount - 1;
                    likeBtn.style.color = '';
                } else {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    likeCount.textContent = currentCount + 1;
                    likeBtn.style.color = 'var(--accent)';
                }
            }
        };
        
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
    
    {{-- Persistent Sticky Player Component --}}
    @include('components.sticky-player')
    
    {{-- Persistent Audio Player Script - Must load before other scripts --}}
    <script src="{{ asset('assets/js/hls-live-player.js') }}" defer></script>
</body>
</html>

