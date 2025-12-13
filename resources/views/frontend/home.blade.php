{{-- resources/views/frontend/home.blade.php --}}
@extends('layouts.frontend', ['title' => 'Darling FM'])

@section('content')
<div class="cyber-grid"></div>
<div style="padding-top: 100px;">


    {{-- Hero Section --}}
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 style="font-family: 'Orbitron', sans-serif; font-size: 5rem; font-weight: 900; margin-bottom: 10px; line-height: 1.1; letter-spacing: 5px; color: #ff0000;">DARLING FM</h1>
                <h2 style="font-family: 'Orbitron', sans-serif; font-size: 2.5rem; font-weight: 400; margin-bottom: 30px; letter-spacing: 3px; color: #ffffff;">OWERRI</h2>
            </div>
        </div>
    </section>

    {{-- Live Stream CTA --}}
    <section style="padding: 40px 20px; text-align: center; background: var(--glass); backdrop-filter: blur(16px); border-radius: 24px; max-width: 560px; margin: 40px auto; border: 1px solid rgba(255,255,255,0.1);">
        <div id="liveNowBadge" style="display: none; background: #ff0000; color: #fff; font-size: 0.82rem; font-weight: 700; padding: 6px 16px; border-radius: 30px; margin-bottom: 16px;">
            ● LIVE NOW
        </div>
        <h2 id="streamTitle" style="font-size: 2.4rem; margin: 12px 0; color: var(--light); font-weight: 800; letter-spacing: -0.5px;">
            Morning Charge
        </h2>
        <p style="color: var(--text-secondary); margin: 8px 0 32px; font-size: 1.1rem; font-weight: 600;">
            107.3 FM
        </p>
        <p style="color: var(--text-secondary); margin: 0 0 32px; font-size: 1.05rem;">
            Tap the button to listen live
        </p>
        <button
           id="homePlayButton"
           type="button"
           style="display: inline-flex; align-items: center; justify-content: center; width: 100px; height: 100px; background: #ff0000; color: white; font-size: 3rem; border-radius: 50%; box-shadow: 0 12px 40px rgba(255,0,0,0.45); transition: transform 0.2s; border: none; cursor: pointer;">
            <i class="fas fa-play" style="margin-left: 6px;"></i>
        </button>
    </section>


    {{-- LATEST NEWS & UPDATES — NOW 100% LOADED & IDENTICAL --}}
    <section class="container" style="margin-top: 80px;">
        <h2 class="section-title">LATEST NEWS & UPDATES</h2>
        <div class="posts-grid">
            @forelse($newsPosts as $post)
            @php
            $postImageUrl = $post->hero_image ?? 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80';
            @endphp
            <div class="post-card" data-post-id="{{ $post->id }}">
                <a href="{{ route('news.show', $post->slug) }}" style="text-decoration: none; color: inherit; display: block;">
                <div class="post-image" style="background-image: url('{{ $postImageUrl }}'); background-size: cover; background-position: center; height: 200px;"></div>
                </a>
                <div class="post-content">
                    <div class="post-meta">
                        <span><i class="far fa-calendar"></i> {{ optional($post->published_at)->format('M d, Y') }}</span>
                    </div>
                    <a href="{{ route('news.show', $post->slug) }}" style="text-decoration: none; color: inherit;">
                    <h3 class="post-title">{{ $post->title }}</h3>
                    <p class="post-excerpt">{{ $post->excerpt }}</p>
                    </a>
                </div>
            </div>
            @empty
            {{-- fallback static cards if no news (never blank again) --}}
            <div class="post-card">
                <div class="post-image" style="background-image: url('https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; height: 200px;"></div>
                <div class="post-content">
                    <div class="post-meta"><span><i class="far fa-calendar"></i> Oct 5, 2025</span></div>
                    <h3 class="post-title">New Music Festival Coming This Summer</h3>
                    <p class="post-excerpt">We're excited to announce our partnership with the City Music Festival happening this August...</p>
                </div>
            </div>
            <div class="post-card">
                <div class="post-image" style="background-image: url('https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80'); background-size: cover; background-position: center; height: 200px;"></div>
                <div class="post-content">
                    <div class="post-meta"><span><i class="far fa-calendar"></i> Oct 06, 2025</span></div>
                    <h3 class="post-title">Interview with Rising Star: Maya Rivers</h3>
                    <p class="post-excerpt">We sat down with the amazing Maya Rivers to talk about her new album...</p>
                </div>
            </div>
            @endforelse
        </div>
    </section>


    {{-- OUR ON-AIR PERSONALITIES — Horizontal Scroll --}}
    <section class="container" id="on-air-personalities" style="margin: 80px 0; padding: 0; width: 100%; max-width: 100%;">
        <h2 class="section-title">OUR ON-AIR PERSONALITIES</h2>
        <div class="aops-carousel-wrapper" style="position: relative; margin: 60px 0; overflow: hidden; padding: 80px 100px; width: 100%;">
            <button class="aops-nav-btn aops-nav-prev" id="aopsPrevBtn" style="position: absolute; left: 20px; top: 50%; transform: translateY(-50%); z-index: 10; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); color: var(--light); width: 50px; height: 50px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: all 0.3s; opacity: 1;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(-50%) scale(1)'">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="aops-nav-btn aops-nav-next" id="aopsNextBtn" style="position: absolute; right: 20px; top: 50%; transform: translateY(-50%); z-index: 10; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); color: var(--light); width: 50px; height: 50px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; transition: all 0.3s; opacity: 1;" onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(-50%) scale(1)'">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="aops-carousel" id="aopsCarousel" style="display: flex; gap: 40px; padding: 20px 0; min-width: max-content; width: max-content; margin: 0 auto; transition: transform 0.5s ease; overflow: visible;">
                @php
                    $allDjs = $featuredDjs->count() > 0 ? $featuredDjs : collect([
                        (object)['id' => 1, 'stage_name' => 'DJ XTREME', 'name' => 'SoundboyKiller', 'slug' => 'dj-xtreme', 'avatar_url' => 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.16_d35f6e83_pv497n.jpg', 'shows' => collect([(object)['title' => 'Morning Show', 'formatted_days' => 'Weekdays', 'start_time' => '06:00:00']])],
                        (object)['id' => 2, 'stage_name' => 'COSMAS CHUKWUEMEKA PUYAKA', 'name' => 'Cosmas', 'slug' => 'cosmas-chukwuemeka-puyaka', 'avatar_url' => 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.31_e7adcda0_tehu62.jpg', 'shows' => collect([(object)['title' => 'Afternoon Drive', 'formatted_days' => 'Weekdays', 'start_time' => '15:00:00']])],
                        (object)['id' => 3, 'stage_name' => 'CHIDERA UJAH', 'name' => 'Chidera', 'slug' => 'chidera-ujah', 'avatar_url' => 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1762957223/OAP1_gtmlhf.jpg', 'shows' => collect([(object)['title' => 'Retro Rewind', 'formatted_days' => 'Saturdays', 'start_time' => '14:00:00']])],
                    ]);
                @endphp
                @foreach($allDjs as $dj)
            @php
            $djAvatarUrl = $dj->avatar_url ?? 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.16_d35f6e83_pv497n.jpg';
                $djSlug = isset($dj->slug) ? $dj->slug : (isset($dj->id) ? 'dj-' . $dj->id : 'dj-' . $loop->index);
            @endphp
                <div class="aop-card" style="min-width: 300px; max-width: 300px; flex-shrink: 0; width: 300px; --index: {{ $loop->index }}; cursor: pointer;">
                    <a href="{{ route('djs.show', $djSlug) }}" style="text-decoration: none; color: inherit; display: block; height: 100%;">
                <div class="aop-image" style="background-image: url('{{ $djAvatarUrl }}')"></div>
                <div class="aop-info">
                    <h3 class="aop-name">{{ strtoupper($dj->stage_name ?? $dj->name) }}</h3>
                            @php
                                $firstShow = $dj->shows->first();
                            @endphp
                            <div class="aop-show">{{ $firstShow->title ?? 'Various Shows' }}</div>
                    <div class="aop-schedule">
                                {{ $firstShow->formatted_days ?? ($firstShow->day_of_week ?? 'Weekdays') }}
                                @if($firstShow && $firstShow->start_time)
                                    {{ \Carbon\Carbon::parse($firstShow->start_time)->format('gA') }}
                                @endif
                    </div>
                </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    {{-- Featured Sponsors Section --}}
    <section class="featured-sponsors-section" style="margin: 60px 0; width: 100%;">
        <div class="container" style="width: 90%; max-width: 1400px; margin: 0 auto; padding: 0 20px;">
            <div style="text-align: center; margin-bottom: 40px;">
                <h2 class="section-title">FEATURED SPONSORS</h2>
                            </div>
            <div class="sponsors-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px; width: 100%;">
            @forelse($featuredSponsors as $sponsor)
                <div class="sponsor-card" style="background: var(--glass); backdrop-filter: blur(15px); border-radius: 20px; overflow: hidden; border: 1px solid var(--glass-border); transition: all 0.4s ease; position: relative; height: 100%; display: flex; flex-direction: column;" onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 20px 40px rgba(255,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <a href="{{ $sponsor->website_url ?? '#' }}" target="_blank" style="text-decoration: none; color: inherit; display: block; flex: 1;">
                        <div class="sponsor-image-container" style="position: relative; height: 250px; overflow: hidden; background: linear-gradient(135deg, rgba(255,0,0,0.1), rgba(0,0,0,0.3));">
                            @if($sponsor->images && count($sponsor->images) > 0)
                                <div class="image-slider-ad" id="slider{{ $sponsor->id }}" style="height: 100%; position: relative;">
                                    <div class="image-slides" style="display: flex; height: 100%; transition: transform 0.5s ease;">
                                        @foreach($sponsor->images as $image)
                                            <div class="image-slide" style="min-width: 100%; height: 100%; background-size: cover; background-position: center; background-image: url('{{ asset('storage/' . $image['image']) }}')"></div>
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($sponsor->logo)
                                <div style="height: 100%; background-size: cover; background-position: center; background-image: url('{{ asset('storage/' . $sponsor->logo) }}')"></div>
                            @else
                                <div style="height: 100%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-building" style="font-size: 5rem; color: rgba(255,255,255,0.3);"></i>
                                </div>
                            @endif
                            @if($sponsor->badge)
                                <div class="sponsor-badge" style="position: absolute; top: 15px; right: 15px; background: var(--accent); color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; font-family: 'Orbitron', sans-serif; text-transform: uppercase; letter-spacing: 1px;">{{ $sponsor->badge }}</div>
                            @endif
                        </div>
                        <div class="sponsor-content" style="padding: 25px; flex: 1; display: flex; flex-direction: column;">
                            <h3 style="color: var(--accent); font-family: 'Orbitron', sans-serif; font-size: 1.4rem; margin-bottom: 10px; font-weight: 700;">{{ $sponsor->name }}</h3>
                            <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px; flex: 1;">{{ $sponsor->description ?? 'Premium partner of Darling FM.' }}</p>
                            <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 15px; border-top: 1px solid var(--glass-border);">
                                <span style="color: var(--light); font-size: 0.9rem; font-weight: 600;">Learn More</span>
                                <i class="fas fa-arrow-right" style="color: var(--accent); transition: transform 0.3s;"></i>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                {{-- Fallback: Show message if no sponsors --}}
                <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--text-secondary);">
                    <p>No featured sponsors at the moment. Check back soon!</p>
                </div>
            @endforelse
            </div>
        </div>
    </section>

    {{-- OAP Profile Modal --}}
    <div class="profile-modal" id="profileModal">
        <div class="profile-modal-content">
            <button class="close-modal">&times;</button>
            <div class="profile-header">
                <div class="profile-image" id="modalProfileImage"></div>
                <div class="profile-info">
                    <h2 class="profile-name" id="modalProfileName">DJ Name</h2>
                    <div class="profile-show" id="modalProfileShow">Show Name</div>
                    <div class="profile-schedule" id="modalProfileSchedule">Schedule</div>
                    <div class="profile-social" id="modalProfileSocial"></div>
                </div>
            </div>
            <div class="profile-bio">
                <h3>About</h3>
                <p id="modalProfileBio">Bio content will be loaded here...</p>
            </div>
        </div>
    </div>


    {{-- Share Modal --}}
    <div class="share-modal" id="shareModal">
        <div class="share-modal-content">
            <h3>Share This News</h3>
            <div class="share-options">
                <div class="share-option" data-platform="facebook">
                    <div class="share-icon facebook"><i class="fab fa-facebook-f"></i></div>
                    <span>Facebook</span>
                </div>
                <div class="share-option" data-platform="twitter">
                    <div class="share-icon twitter"><i class="fab fa-twitter"></i></div>
                    <span>Twitter</span>
                </div>
                <div class="share-option" data-platform="instagram">
                    <div class="share-icon instagram"><i class="fab fa-instagram"></i></div>
                    <span>Instagram</span>
                </div>
                <div class="share-option" data-platform="whatsapp">
                    <div class="share-icon whatsapp"><i class="fab fa-whatsapp"></i></div>
                    <span>WhatsApp</span>
                </div>
            </div>
            <button class="close-modal">Close</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .live-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #ff0066;
        display: inline-block;
        margin-right: 8px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(255, 0, 102, 0.8);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(255, 0, 102, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(255, 0, 102, 0);
        }
    }
    
    .aops-carousel-wrapper {
        position: relative;
        padding: 80px 100px;
        overflow: visible;
        width: 100%;
        margin-left: 0;
        margin-right: 0;
    }
    
    .aops-carousel {
        display: flex;
        gap: 40px;
        padding: 60px 0;
        min-width: max-content;
        width: max-content;
        margin: 0 auto;
    }
    
    /* Equal padding on both sides - responsive */
    @media (min-width: 1400px) {
        .aops-carousel {
            padding-left: calc((100vw - 1400px) / 2);
            padding-right: calc((100vw - 1400px) / 2);
        }
    }
    
    @media (max-width: 1399px) and (min-width: 769px) {
        .aops-carousel {
            padding-left: 80px;
            padding-right: 80px;
        }
    }
    
    .aop-card {
        transition: transform 0.4s ease, opacity 0.4s ease, box-shadow 0.4s ease;
        transform-origin: center;
    }
    
    .aop-card {
        transition: all 0.4s ease !important;
    }
    
    .aop-card:hover {
        transform: translateY(-10px) !important;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.4) !important;
        z-index: 10;
    }
    
    @media (max-width: 768px) {
        .aops-carousel-wrapper {
            padding: 40px 20px !important;
            overflow-x: auto !important;
        }
        
        .aops-carousel {
            padding: 0 !important;
            gap: 20px !important;
        }
        
        .aop-card {
            min-width: 250px !important;
            max-width: 250px !important;
        }
        
        .sponsors-grid {
            grid-template-columns: 1fr !important;
        }
        
        .posts-grid {
            grid-template-columns: 1fr !important;
        }
    }
    
    @media (max-width: 480px) {
        .aops-carousel-wrapper {
            padding: 30px 15px !important;
        }
        
        .aops-carousel {
            gap: 15px !important;
        }
        
        .aop-card {
            min-width: 220px !important;
            max-width: 220px !important;
        }
        
        .hero h1 {
            font-size: 3rem !important;
        }
        
        .hero h2 {
            font-size: 1.8rem !important;
        }
    }
    
    .carousel-nav-btn:hover {
        background: var(--accent-glow) !important;
        transform: translateY(-50%) scale(1.15) !important;
    }
    
    /* Featured Sponsors Styles */
    .featured-sponsors-section {
        margin: 60px 0;
    }
    
    .sponsors-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 30px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .sponsor-card {
        background: var(--glass);
        backdrop-filter: blur(15px);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--glass-border);
        transition: all 0.4s ease;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .sponsor-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(255,0,0,0.2);
    }
    
    .sponsor-card:hover .fa-arrow-right {
        transform: translateX(5px);
    }
    
    .sponsor-image-container {
        position: relative;
        height: 250px;
        overflow: hidden;
    }
    
    .sponsor-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
        z-index: 5;
    }
    
    .sponsor-content {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .aops-carousel-wrapper {
        position: relative;
        overflow: hidden;
    }
    
    .aops-carousel {
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        will-change: transform;
    }
    
    .aop-card {
        width: 300px !important;
        min-width: 300px !important;
        max-width: 300px !important;
        animation: slideInUp 0.6s ease-out forwards;
        animation-delay: calc(var(--index, 0) * 0.1s);
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @media (max-width: 768px) {
        .aops-carousel-wrapper {
            padding: 0 60px !important;
        }
        .aops-nav-btn {
            width: 40px !important;
            height: 40px !important;
            font-size: 1.2rem !important;
        }
        .aop-card {
            min-width: 250px !important;
            max-width: 250px !important;
            width: 250px !important;
        }
        .sponsors-grid {
            grid-template-columns: 1fr !important;
            gap: 20px !important;
        }
    }
</style>
@endpush

@push('scripts')
    <script>
        // Hero play/pause button -> toggle global audio playback
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('homePlayButton');
            const icon = btn?.querySelector('i');

            if (!btn || !icon) return;

            // Function to update button state
            function updateButtonState(isPlaying) {
                if (isPlaying) {
                    icon.className = 'fas fa-pause';
                    icon.style.marginLeft = '0';
                    btn.setAttribute('aria-label', 'Pause live stream');
                } else {
                    icon.className = 'fas fa-play';
                    icon.style.marginLeft = '6px';
                    btn.setAttribute('aria-label', 'Play live stream');
                }
            }

            // Function to check and update button state
            function checkAndUpdateState() {
                if (window.DarlingFMAudio && window.DarlingFMAudio.player) {
                    const audioElement = window.DarlingFMAudio.player;
                    const isPlaying = audioElement && !audioElement.paused && !audioElement.ended;
                    updateButtonState(isPlaying);
                    console.log('Button state updated - isPlaying:', isPlaying);
                }
            }

            // Listen for global audio state changes
            if (window.DarlingFMAudio) {
                window.DarlingFMAudio.listeners.play.push(function(isPlaying) {
                    updateButtonState(isPlaying);
                });

                // Initial state check with a small delay to ensure audio is loaded
                setTimeout(checkAndUpdateState, 100);
            }

            // Handle button clicks
            btn.addEventListener('click', async function() {
                if (!window.DarlingFMAudio) {
                    // Fallback: open stream in new window
                    window.open('https://phoebe.streamerr.co:7572/stream', 'darlingfm-stream');
                    return;
                }

                // Check actual audio element state instead of global variable
                const audioElement = window.DarlingFMAudio.player;
                const isCurrentlyPlaying = audioElement && !audioElement.paused;

                console.log('Button clicked - isCurrentlyPlaying:', isCurrentlyPlaying);

                if (isCurrentlyPlaying) {
                    // Currently playing, so pause
                    console.log('Pausing audio...');
                    try {
                        audioElement.pause();
                        // Force state update
                        window.DarlingFMAudio.isPlaying = false;
                        updateButtonState(false);
                        console.log('Audio paused successfully');
                    } catch (err) {
                        console.error('Failed to pause:', err);
                    }
                } else {
                    // Currently paused/stopped, so play
                    console.log('Starting audio...');
                    try {
                        await window.DarlingFMAudio.switchStream('main');
                        await window.DarlingFMAudio.play();
                        updateButtonState(true);
                        console.log('Audio started successfully');
                    } catch (err) {
                        console.error('Primary play failed, trying backup:', err);
                        try {
                            await window.DarlingFMAudio.switchStream('backup');
                            await window.DarlingFMAudio.play();
                            updateButtonState(true);
                        } catch (err2) {
                            console.error('Backup play failed:', err2);
                            // Final fallback: open in new window
                            window.open('https://phoebe.streamerr.co:7567/stream', 'darlingfm-stream-backup');
                        }
                    }
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // OAP Carousel Navigation
            const carousel = document.getElementById('aopsCarousel');
            const prevBtn = document.getElementById('aopsPrevBtn');
            const nextBtn = document.getElementById('aopsNextBtn');
            
            if (carousel && prevBtn && nextBtn) {
                const cardWidth = 300; // min-width of aop-card
                const gap = 40; // gap between cards
                const scrollAmount = cardWidth + gap;
                let scrollPosition = 0;
                const maxScroll = carousel.scrollWidth - carousel.parentElement.offsetWidth;
                
                nextBtn.addEventListener('click', () => {
                    scrollPosition = Math.min(scrollPosition + scrollAmount, maxScroll);
                    carousel.style.transform = `translateX(-${scrollPosition}px)`;
                    updateButtonStates();
                });
                
                prevBtn.addEventListener('click', () => {
                    scrollPosition = Math.max(scrollPosition - scrollAmount, 0);
                    carousel.style.transform = `translateX(-${scrollPosition}px)`;
                    updateButtonStates();
                });
                
                function updateButtonStates() {
                    prevBtn.style.opacity = scrollPosition <= 0 ? '0.3' : '1';
                    nextBtn.style.opacity = scrollPosition >= maxScroll ? '0.3' : '1';
                }
                
                // Initial state
                updateButtonStates();
            }
            
            // Auto-rotate image sliders for sponsor cards
            const slider1 = document.getElementById('slider1');
            if (slider1) {
                const slides = slider1.querySelector('.image-slides');
                if (slides) {
                    let currentSlide = 0;
                    const totalSlides = slides.children.length;
                    
                    setInterval(() => {
                        currentSlide = (currentSlide + 1) % totalSlides;
                        slides.style.transform = `translateX(-${currentSlide * 100}%)`;
                    }, 4000);
                }
            }
        });
    </script>
@endpush