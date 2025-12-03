{{-- resources/views/frontend/home.blade.php --}}
@extends('layouts.frontend', ['title' => 'Darling FM'])

@section('content')
<div class="cyber-grid"></div>

{{-- Live OAP Display (exactly like legacy) --}}
@if($liveStream && $liveStream->status === 'live')
<div class="live-oap-display">
    <div class="live-oap-header">
        <div class="live-oap-avatar" style="background-image: url('{{ $liveStream->dj?->avatar_url ?? asset('assets/images/radio1.jpg') }}')"></div>
        <div class="live-oap-info">
            <div class="live-oap-name">
                {{ $liveStream->dj?->stage_name ?? 'DJ XTREME' }} 
                @if($liveStream->dj?->stage_name)<br>({{ $liveStream->dj->name ?? 'SoundboyKill' }})@endif
            </div>
            <div class="live-oap-show">{{ $liveStream->show?->title ?? 'Morning Show' }}</div>
            <div class="live-status">
                <div class="live-dot"></div>
                <span>LIVE NOW</span>
            </div>
            <div class="live-time">
                {{ optional($liveStream->started_at)->format('h:i A') }} - {{ optional($liveStream->ended_at)->format('h:i A') ?? 'On Air' }}
            </div>
        </div>
    </div>
    <button class="listen-btn">
        <i class="fas fa-headphones"></i>
        LISTEN LIVE
    </button>
</div>
@endif

{{-- Hero (exactly like legacy) --}}
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h2>DARLING FM <br> IS HERE</h2>
            <p>
                Immerse yourself in a revolutionary audio experience. <br> Darling FM
                brings you cutting-edge sound technology with your favorite on-air
                personalities.
            </p>
        </div>
    </div>
</section>

{{-- Upcoming Shows (exactly like legacy) --}}
<section class="upcoming-shows">
    <div class="container">
        <h2 class="section-title">UPCOMING SHOWS</h2>
        <div class="shows-grid">
            @forelse($upcomingShows as $show)
            <div class="show-card">
                <div class="show-header">
                    <div class="show-avatar" style="background-image: url('{{ $show->dj?->avatar_url ?? 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.16_d35f6e83_pv497n.jpg' }}')"></div>
                    <div class="show-info">
                        <div class="show-name">{{ strtoupper($show->dj?->stage_name ?? $show->dj?->name ?? 'DJ XTREME') }}</div>
                        <div class="show-title">{{ $show->title }}</div>
                    </div>
                </div>
                <div class="show-time">
                    <i class="far fa-clock"></i>
                    <span>
                        {{ $show->formatted_days ?? 'Today' }}, 
                        {{ \Carbon\Carbon::parse($show->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($show->end_time)->format('g:i A') }}
                    </span>
                </div>
                <button class="remind-btn">
                    <i class="far fa-bell"></i>
                    Set Reminder
                </button>
            </div>
            @empty
                <div class="show-card"><div class="show-header"><div class="show-avatar" style="background-image: url('https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.16_d35f6e83_pv497n.jpg')"></div><div class="show-info"><div class="show-name">DJ XTREME</div><div class="show-title">Night Beats</div></div></div><div class="show-time"><i class="far fa-clock"></i> <span>Today, 10:00 PM - 2:00 AM</span></div><button class="remind-btn"><i class="far fa-bell"></i> Set Reminder</button></div>
                <div class="show-card"><div class="show-header"><div class="show-avatar" style="background-image: url('https://res.cloudinary.com/dl4hjr1p2/image/upload/v1762957223/OAP1_gtmlhf.jpg')"></div><div class="show-info"><div class="show-name">CHIDERA UJAH</div><div class="show-title">Retro Rewind</div></div></div><div class="show-time"><i class="far fa-clock"></i> <span>Tomorrow, 2:00 PM - 6:00 PM</span></div><button class="remind-btn"><i class="far fa-bell"></i> Set Reminder</button></div>
                <div class="show-card"><div class="show-header"><div class="show-avatar" style="background-image: url('https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.31_e7adcda0_tehu62.jpg')"></div><div class="show-info"><div class="show-name">COSMOS CHUKWUEMEKA PUYAKA</div><div class="show-title">Afternoon Drive</div></div></div><div class="show-time"><i class="far fa-clock"></i> <span>Today, 3:00 PM - 7:00 PM</span></div><button class="remind-btn"><i class="far fa-bell"></i> Set Reminder</button></div>
            @endforelse
        </div>
    </div>
</section>

{{-- LATEST NEWS & UPDATES — NOW 100% LOADED & IDENTICAL --}}
<section class="container">
    <h2 class="section-title">LATEST NEWS & UPDATES</h2>
    <div class="posts-grid">
        @forelse($newsPosts as $post)
        <div class="post-card">
            <div class="post-image" style="background-image: url('{{ $post->hero_image ?? 'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80' }}')"></div>
            <div class="post-content">
                <div class="post-meta">
                    <span><i class="far fa-calendar"></i> {{ optional($post->published_at)->format('M d, Y') }}</span>
                    <span><i class="far fa-comment"></i> <span class="comment-count">0</span> Comments</span>
                </div>
                <h3 class="post-title">{{ $post->title }}</h3>
                <p class="post-excerpt">{{ $post->excerpt }}</p>
                <div class="post-actions">
                    <div class="like-comment">
                        <button class="action-btn like-btn"><i class="far fa-heart"></i> <span class="like-count">0</span></button>
                        <button class="action-btn comment-toggle-btn"><i class="far fa-comment"></i> Comment</button>
                        <button class="action-btn share-btn"><i class="fas fa-share-alt"></i> Share</button>
                    </div>
                </div>
            </div>
        </div>
        @empty
            {{-- fallback static cards if no news (never blank again) --}}
            <div class="post-card"><div class="post-image" style="background-image: url('https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80')"></div><div class="post-content"><div class="post-meta"><span><i class="far fa-calendar"></i> Oct 5, 2025</span><span><i class="far fa-comment"></i> 2 Comments</span></div><h3 class="post-title">New Music Festival Coming This Summer</h3><p class="post-excerpt">We're excited to announce our partnership with the City Music Festival happening this August...</p><div class="post-actions"><div class="like-comment"><button class="action-btn like-btn"><i class="far fa-heart"></i> 12</button><button class="action-btn comment-toggle-btn"><i class="far fa-comment"></i> Comment</button><button class="action-btn share-btn"><i class="fas fa-share-alt"></i> Share</button></div></div></div></div>
            <div class="post-card"><div class="post-image" style="background-image: url('https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80')"></div><div class="post-content"><div class="post-meta"><span><i class="far fa-calendar"></i> Oct 06, 2025</span><span><i class="far fa-comment"></i> 1 Comment</span></div><h3 class="post-title">Interview with Rising Star: Maya Rivers</h3><p class="post-excerpt">We sat down with the amazing Maya Rivers to talk about her new album...</p><div class="post-actions"><div class="like-comment"><button class="action-btn like-btn"><i class="far fa-heart"></i> 8</button><button class="action-btn comment-toggle-btn"><i class="far fa-comment"></i> Comment</button><button class="action-btn share-btn"><i class="fas fa-share-alt"></i> Share</button></div></div></div></div>
        @endforelse
    </div>
</section>

{{-- FEATURED PODCASTS — NOW 100% LOADED & IDENTICAL --}}
<section class="container">
    <h2 class="section-title">FEATURED PODCASTS</h2>
    <div class="podcasts-grid">
        @forelse($podcasts as $podcast)
        <div class="podcast-card">
            <div class="podcast-image" style="background-image: url('{{ $podcast->cover_image ?? 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80' }}')"></div>
            <div class="podcast-content">
                <h3 class="podcast-title">{{ $podcast->title }}</h3>
                <p class="podcast-description">{{ $podcast->description ?? 'Dive deep into the stories behind your favorite songs and artists with host DJ Alex.' }}</p>
                <div class="podcast-meta">
                    <span><i class="fas fa-video"></i> {{ $podcast->is_video ? 'Video' : 'Audio' }} Podcast</span>
                    <span><i class="far fa-calendar"></i> {{ optional($podcast->published_at)->format('M d, Y') }}</span>
                </div>
                <button class="podcast-play-btn">
                    <i class="fas fa-play"></i> {{ $podcast->is_video ? 'Watch Now' : 'Listen Now' }}
                </button>
            </div>
        </div>
        @empty
            {{-- fallback static cards (never blank) --}}
            <div class="podcast-card"><div class="podcast-image" style="background-image: url('https://images.unsplash.com/photo-1478737270239-2f02b77fc618?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80')"></div><div class="podcast-content"><h3 class="podcast-title">Behind the Music</h3><p class="podcast-description">Dive deep into the stories behind your favorite songs and artists with host DJ Alex.</p><div class="podcast-meta"><span><i class="fas fa-video"></i> Video Podcast</span><span><i class="far fa-calendar"></i> Oct 10, 2025</span></div><button class="podcast-play-btn"><i class="fas fa-play"></i> Watch Now</button></div></div>
            <div class="podcast-card"><div class="podcast-image" style="background-image: url('https://images.unsplash.com/photo-1589003077984-894e133dabab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80')"></div><div class="podcast-content"><h3 class="podcast-title">Sound Waves</h3><p class="podcast-description">Exploring the science and psychology of sound and music with expert guests.</p><div class="podcast-meta"><span><i class="fas fa-video"></i> Video Podcast</span><span><i class="far fa-calendar"></i> Oct 5, 2025</span></div><button class="podcast-play-btn"><i class="fas fa-play"></i> Watch Now</button></div></div>
        @endforelse
    </div>
</section>

{{-- OUR ON-AIR PERSONALITIES — NOW 100% LOADED & IDENTICAL --}}
<section class="container">
    <h2 class="section-title">OUR ON-AIR PERSONALITIES</h2>
    <div class="aops-grid">
        @forelse($featuredDjs as $dj)
        <div class="aop-card">
            <div class="aop-image" style="background-image: url('{{ $dj->avatar_url ?? 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.16_d35f6e83_pv497n.jpg' }}')"></div>
            <div class="aop-info">
                <h3 class="aop-name">{{ strtoupper($dj->stage_name ?? $dj->name) }}</h3>
                <div class="aop-show">{{ $dj->shows->first()?->title ?? 'Various Shows' }}</div>
                <div class="aop-schedule">
                    {{ $dj->shows->first()?->formatted_days ?? 'Weekdays' }} 
                    {{ $dj->shows->first()?->start_time ? \Carbon\Carbon::parse($dj->shows->first()->start_time)->format('gA') : '' }}
                </div>
                <button class="aop-profile-btn">View Profile</button>
            </div>
        </div>
        @empty
            <div class="aop-card"><div class="aop-image" style="background-image: url('https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.16_d35f6e83_pv497n.jpg')"></div><div class="aop-info"><h3 class="aop-name">DJ XTREME (SoundboyKiller)</h3><div class="aop-show">Morning Show</div><div class="aop-schedule">Weekdays 6AM - 10AM</div><button class="aop-profile-btn">View Profile</button></div></div>
            <div class="aop-card"><div class="aop-image" style="background-image: url('https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.31_e7adcda0_tehu62.jpg')"></div><div class="aop-info"><h3 class="aop-name">COSMAS CHUKWUEMEKA PUYAKA</h3><div class="aop-show">Afternoon Drive</div><div class="aop-schedule">Weekdays 3PM - 7PM</div><button class="aop-profile-btn">View Profile</button></div></div>
            <div class="aop-card"><div class="aop-image" style="background-image: url('https://res.cloudinary.com/dl4hjr1p2/image/upload/v1762957223/OAP1_gtmlhf.jpg')"></div><div class="aop-info"><h3 class="aop-name">CHIDERA UJAH</h3><div class="aop-show">Retro Rewind</div><div class="aop-schedule">Saturdays 2PM - 6PM</div><button class="aop-profile-btn">View Profile</button></div></div>
        @endforelse
    </div>
</section>
@endsection

@push('styles')
<style>
    .live-dot { width:12px; height:12px; border-radius:50%; background:#ff0066; display:inline-block; margin-right:8px; animation:pulse 2s infinite; }
    @keyframes pulse { 0% { box-shadow:0 0 0 0 rgba(255,0,102,0.8); } 70% { box-shadow:0 0 0 10px rgba(255,0,102,0); } 100% { box-shadow:0 0 0 0 rgba(255,0,102,0); } }
</style>
@endpush