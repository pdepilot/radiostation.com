{{-- resources/views/frontend/home.blade.php --}}
@extends('layouts.frontend', ['title' => 'Darling FM'])

@section('content')
<div class="cyber-grid"></div>
<div style="padding-top: 100px;">


    {{-- Hero Section --}}
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 style="font-family: 'Oxanium', sans-serif; font-size: 5rem; font-weight: 900; margin-bottom: 10px; line-height: 1.1; letter-spacing: 5px; color: #c8102e;">DARLING FM</h1>
                <h2 style="font-family: 'Oxanium', sans-serif; font-size: 2.5rem; font-weight: 400; margin-bottom: 30px; letter-spacing: 3px; color: #ffffff;">OWERRI</h2>
            </div>
        </div>
    </section>

    {{-- Live Stream CTA --}}
    <section style="padding: 40px 20px; text-align: center; background: var(--glass); backdrop-filter: blur(16px); border-radius: 24px; max-width: 560px; margin: 40px auto; border: 1px solid rgba(255,255,255,0.1);">
        <div id="liveNowBadge" style="display: {{ ($currentShow && $currentShow->status !== 'completed') ? 'inline-block' : 'none' }}; background: #c8102e; color: #fff; font-size: 0.82rem; font-weight: 700; padding: 6px 16px; border-radius: 30px; margin-bottom: 16px;">
            <span class="live-dot-pulse">●</span> LIVE NOW
        </div>
        <h2 id="streamTitle" style="font-size: 2.4rem; margin: 12px 0; color: var(--light); font-weight: 800; letter-spacing: -0.5px;">
            @if($currentShow && $currentShow->status !== 'completed')
            {{ $currentShow->title }}
            @else
            Darling FM Live
            @endif
        </h2>
        <p style="color: var(--text-secondary); margin: 8px 0 32px; font-size: 1.1rem; font-weight: 600;">
            107.3 FM
        </p>
        <p style="color: var(--text-secondary); margin: 0 0 32px; font-size: 1.05rem;">
            Tap the button to listen live
        </p>
        <div style="display: flex; align-items: center; justify-content: center; gap: 20px; position: relative;">
            <button
                id="homePlayButton"
                type="button"
                style="display: inline-flex; align-items: center; justify-content: center; width: 100px; height: 100px; background: #c8102e; color: white; border-radius: 50%; box-shadow: 0 12px 40px rgba(200,16,46,0.45); transition: transform 0.2s; border: none; cursor: pointer; position: relative;">
                <svg class="home-play-icon" style="width: 2.5rem; height: 2.5rem; fill: white; display: block; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 5v14l11-7z" />
                </svg>
                <svg class="home-pause-icon" style="width: 2.5rem; height: 2.5rem; fill: white; display: none; position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z" />
                </svg>
            </button>
            <button
                id="homeShareButton"
                type="button"
                data-share-type="home"
                data-share-title="Darling FM 107.3"
                data-share-url="{{ route('home', [], true) }}"
                style="display: inline-flex; align-items: center; justify-content: center; width: 45px; height: 45px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: var(--accent); border: 1px solid rgba(255,255,255,0.2); border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s; border: none; cursor: pointer; position: relative;">
                <i class="fas fa-share-alt" style="font-size: 1.1rem;"></i>
            </button>
        </div>
    </section>

    {{-- Ad Placeholders Under Hero Section --}}
    <div class="container" style="margin: 40px auto; max-width: 1200px; padding: 0 20px;">
        <div class="homepage-ads-grid" style="display: grid; grid-template-columns: 1fr; gap: 20px;">
            <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 250px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-size: 0.9rem; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                <span>Advertisement</span>
            </div>
            <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 250px; display: flex; align-items: center; justify-content: center; color: var(--text-secondary); font-size: 0.9rem; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                <span>Advertisement</span>
            </div>
        </div>
    </div>

    {{-- Share Modal --}}
    <div id="shareModal" class="share-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 10000; align-items: center; justify-content: center;">
        <div class="share-modal-content" style="background: var(--glass); backdrop-filter: blur(15px); border-radius: 20px; padding: 30px; max-width: 500px; width: 90%; border: 1px solid var(--glass-border); box-shadow: 0 20px 60px rgba(0,0,0,0.5); position: relative;">
            <button class="close-share-modal" style="position: absolute; top: 15px; right: 15px; background: transparent; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='var(--accent)'" onmouseout="this.style.background='transparent'; this.style.color='var(--text-secondary)'">&times;</button>
            <h3 style="color: var(--accent); font-family: 'Orbitron', sans-serif; font-size: 1.5rem; margin-bottom: 20px; font-weight: 700;">Share</h3>
            <div class="form-group" style="margin-bottom: 25px;">
                <label for="shareMessage" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Custom Message (Optional)</label>
                <textarea id="shareMessage" rows="3" placeholder="Add your personal message here..." style="width: 100%; padding: 12px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 0.95rem; resize: vertical; font-family: inherit;"></textarea>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; color: var(--light); margin-bottom: 15px; font-weight: 600;">Share to:</label>
                <div class="share-options-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                    <button class="share-option-btn" data-platform="facebook" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(24,119,242,0.2); border: 1px solid rgba(24,119,242,0.4); border-radius: 12px; color: #1877f2; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(24,119,242,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(24,119,242,0.2)'; this.style.transform='translateY(0)'">
                        <i class="fab fa-facebook-f" style="font-size: 1.3rem;"></i>
                        <span>Facebook</span>
                    </button>
                    <button class="share-option-btn" data-platform="twitter" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(29,161,242,0.2); border: 1px solid rgba(29,161,242,0.4); border-radius: 12px; color: #1da1f2; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(29,161,242,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(29,161,242,0.2)'; this.style.transform='translateY(0)'">
                        <i class="fab fa-twitter" style="font-size: 1.3rem;"></i>
                        <span>Twitter</span>
                    </button>
                    <button class="share-option-btn" data-platform="whatsapp" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(37,211,102,0.2); border: 1px solid rgba(37,211,102,0.4); border-radius: 12px; color: #25d366; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(37,211,102,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(37,211,102,0.2)'; this.style.transform='translateY(0)'">
                        <i class="fab fa-whatsapp" style="font-size: 1.3rem;"></i>
                        <span>WhatsApp</span>
                    </button>
                    <button class="share-option-btn" data-platform="telegram" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(37,150,190,0.2); border: 1px solid rgba(37,150,190,0.4); border-radius: 12px; color: #2596be; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(37,150,190,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(37,150,190,0.2)'; this.style.transform='translateY(0)'">
                        <i class="fab fa-telegram" style="font-size: 1.3rem;"></i>
                        <span>Telegram</span>
                    </button>
                    <button class="share-option-btn" data-platform="linkedin" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(0,119,181,0.2); border: 1px solid rgba(0,119,181,0.4); border-radius: 12px; color: #0077b5; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(0,119,181,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(0,119,181,0.2)'; this.style.transform='translateY(0)'">
                        <i class="fab fa-linkedin-in" style="font-size: 1.3rem;"></i>
                        <span>LinkedIn</span>
                    </button>
                    <button class="share-option-btn" data-platform="copy" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(255,255,255,0.1); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--light); cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-link" style="font-size: 1.3rem;"></i>
                        <span>Copy Link</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


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
        <div class="aops-carousel-wrapper" style="position: relative !important; margin: 20px 0; overflow: hidden !important; padding: 40px 80px; width: 100%; transform: none !important;">
            <button class="aops-nav-btn aops-nav-prev" id="aopsPrevBtn" style="position: absolute !important; left: 20px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 10000 !important;">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="aops-nav-btn aops-nav-next" id="aopsNextBtn" style="position: absolute !important; right: 20px !important; top: 50% !important; transform: translateY(-50%) !important; z-index: 10000 !important;">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="aops-carousel" id="aopsCarousel" style="display: flex; gap: 40px; padding: 20px 0; min-width: max-content; width: max-content; margin: 0 auto; transition: transform 0.5s ease; overflow: visible; position: relative; z-index: 1;">
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
    .live-dot-pulse {
        display: inline-block;
        animation: livePulse 1.5s ease-in-out infinite;
    }

    @keyframes livePulse {
        0%, 100% {
            opacity: 1;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.8), 0 0 10px rgba(255, 255, 255, 0.6);
        }
        50% {
            opacity: 0.5;
            text-shadow: 0 0 10px rgba(255, 255, 255, 1), 0 0 20px rgba(255, 255, 255, 0.8), 0 0 30px rgba(255, 0, 0, 0.6);
        }
    }

    @media (min-width: 768px) {
        .homepage-ads-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 767px) {
        .homepage-ads-grid .ad-slot {
            height: 150px !important;
        }
    }

    .aops-carousel-wrapper {
        position: relative;
        padding: 60px 80px;
        overflow: visible;
        width: 100%;
        margin-left: 0;
        margin-right: 0;
    }

    .aops-carousel {
        display: flex;
        gap: 40px;
        padding: 20px 0;
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
        /* Transformations removed for cleaner look */
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
        box-shadow: 0 20px 40px rgba(255, 0, 0, 0.2);
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
    // Hero play/pause button -> uses global togglePlayback function from hls-live-player.js
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('homePlayButton');
        if (!btn) return;

        // Initial icon state - ensure only play icon is visible initially
        const playIcon = btn.querySelector('.home-play-icon');
        const pauseIcon = btn.querySelector('.home-pause-icon');
        if (playIcon) playIcon.style.display = 'block';
        if (pauseIcon) pauseIcon.style.display = 'none';

        // Handle button clicks - use global toggle function
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Wait a moment to ensure audio system is ready
            if (window.DarlingFMAudio && window.DarlingFMAudio.toggle) {
                window.DarlingFMAudio.toggle();
            } else {
                // Wait for audio system to initialize
                setTimeout(function() {
                    if (window.DarlingFMAudio && window.DarlingFMAudio.toggle) {
                        window.DarlingFMAudio.toggle();
                    } else {
                        // Final fallback: open stream in new window
                        window.open('https://phoebe.streamerr.co:7572/stream', 'darlingfm-stream');
                    }
                }, 100);
            }
        });

        // Initial state update - wait for audio system to be ready
        setTimeout(function() {
            if (window.DarlingFMAudio && window.DarlingFMAudio.updateHomeButton) {
                window.DarlingFMAudio.updateHomeButton();
            }
        }, 500);
    });

    // Share Modal Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const shareModal = document.getElementById('shareModal');
        const shareButton = document.getElementById('homeShareButton');
        const closeModalBtn = shareModal?.querySelector('.close-share-modal');
        const shareOptions = shareModal?.querySelectorAll('.share-option-btn');

        // Open modal
        if (shareButton) {
            shareButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (shareModal) {
                    shareModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                }
            });
        }

        // Close modal
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', function() {
                if (shareModal) {
                    shareModal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        }

        // Close on backdrop click
        if (shareModal) {
            shareModal.addEventListener('click', function(e) {
                if (e.target === shareModal) {
                    shareModal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });
        }

        // Handle share options
        if (shareOptions) {
            shareOptions.forEach(btn => {
                btn.addEventListener('click', function() {
                    const platform = this.getAttribute('data-platform');
                    const shareType = shareButton?.getAttribute('data-share-type') || 'home';
                    const shareTitle = shareButton?.getAttribute('data-share-title') || 'Darling FM 107.3';
                    const shareUrl = shareButton?.getAttribute('data-share-url') || window.location.href;
                    const customMessage = document.getElementById('shareMessage')?.value || '';

                    const defaultMessage = shareType === 'home' ?
                        'Listen to Darling FM 107.3 - Nigeria\'s leading lifestyle and edutainment radio station!' :
                        `Check out "${shareTitle}" on Darling FM!`;

                    const message = customMessage.trim() || defaultMessage;
                    const fullText = `${message} ${shareUrl}`;

                    if (platform === 'copy') {
                        // Copy to clipboard
                        navigator.clipboard.writeText(shareUrl).then(() => {
                            const originalText = this.querySelector('span')?.textContent;
                            if (this.querySelector('span')) {
                                this.querySelector('span').textContent = 'Copied!';
                                setTimeout(() => {
                                    if (this.querySelector('span')) {
                                        this.querySelector('span').textContent = originalText;
                                    }
                                }, 2000);
                            }
                        }).catch(() => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Copy Failed',
                                text: 'Failed to copy link. Please copy manually: ' + shareUrl,
                                confirmButtonColor: '#c8102e',
                                background: 'var(--glass)',
                                color: 'var(--text-primary)',
                                backdrop: 'rgba(0,0,0,0.8)'
                            });
                        });
                        return;
                    }

                    let shareUrlPlatform = '';
                    const encodedUrl = encodeURIComponent(shareUrl);
                    const encodedText = encodeURIComponent(fullText);

                    switch (platform) {
                        case 'facebook':
                            shareUrlPlatform = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}&quote=${encodeURIComponent(message)}`;
                            break;
                        case 'twitter':
                            shareUrlPlatform = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`;
                            break;
                        case 'whatsapp':
                            shareUrlPlatform = `https://api.whatsapp.com/send?text=${encodedText}`;
                            break;
                        case 'telegram':
                            shareUrlPlatform = `https://t.me/share/url?url=${encodedUrl}&text=${encodedText}`;
                            break;
                        case 'linkedin':
                            shareUrlPlatform = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
                            break;
                    }

                    if (shareUrlPlatform) {
                        window.open(shareUrlPlatform, 'shareWindow', 'width=600,height=400,scrollbars=yes,resizable=yes');
                        if (shareModal) {
                            shareModal.style.display = 'none';
                            document.body.style.overflow = '';
                        }
                    }
                });
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // OAP Carousel Navigation
        const carousel = document.getElementById('aopsCarousel');
        const prevBtn = document.getElementById('aopsPrevBtn');
        const nextBtn = document.getElementById('aopsNextBtn');
        const wrapper = carousel?.parentElement;

        if (carousel && prevBtn && nextBtn && wrapper) {
            const cardWidth = 300; // min-width of aop-card
            const gap = 40; // gap between cards
            const scrollAmount = cardWidth + gap;
            let scrollPosition = 0;

            function getMaxScroll() {
                const carouselWidth = carousel.scrollWidth;
                const wrapperWidth = wrapper.offsetWidth;
                return Math.max(0, carouselWidth - wrapperWidth);
            }

            function updateButtonStates() {
                const maxScroll = getMaxScroll();
                prevBtn.style.opacity = scrollPosition <= 0 ? '0.3' : '1';
                prevBtn.style.pointerEvents = scrollPosition <= 0 ? 'none' : 'auto';
                nextBtn.style.opacity = scrollPosition >= maxScroll ? '0.3' : '1';
                nextBtn.style.pointerEvents = scrollPosition >= maxScroll ? 'none' : 'auto';
            }

            nextBtn.addEventListener('click', () => {
                const maxScroll = getMaxScroll();
                scrollPosition = Math.min(scrollPosition + scrollAmount, maxScroll);
                carousel.style.transform = `translateX(-${scrollPosition}px)`;
                updateButtonStates();
            });

            prevBtn.addEventListener('click', () => {
                scrollPosition = Math.max(scrollPosition - scrollAmount, 0);
                carousel.style.transform = `translateX(-${scrollPosition}px)`;
                updateButtonStates();
            });

            // Update on window resize
            let resizeTimeout;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    const maxScroll = getMaxScroll();
                    scrollPosition = Math.min(scrollPosition, maxScroll);
                    carousel.style.transform = `translateX(-${scrollPosition}px)`;
                    updateButtonStates();
                }, 250);
            });

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