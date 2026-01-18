@extends('layouts.frontend', ['title' => 'Darling FM • Revolutionary Podcasts'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/podcast.css') }}">
@endpush

@section('content')
    <!-- Audio Players (hidden) -->
    <audio id="audio-player" class="audio-player"></audio>

    <!-- Social Share Modal -->
    <div class="share-modal" id="shareModal">
        <div class="share-content">
            <div class="share-header">
                <h3 class="share-title">Share This Podcast</h3>
                <button class="close-share" id="closeShare">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="share-platforms">
                <div class="share-platform" data-platform="facebook">
                    <i class="fab fa-facebook-f platform-icon" style="color: #1877f2;"></i>
                    <span class="platform-name">Facebook</span>
                </div>
                <div class="share-platform" data-platform="twitter">
                    <i class="fab fa-twitter platform-icon" style="color: #1da1f2;"></i>
                    <span class="platform-name">Twitter</span>
                </div>
                <div class="share-platform" data-platform="whatsapp">
                    <i class="fab fa-whatsapp platform-icon" style="color: #25d366;"></i>
                    <span class="platform-name">WhatsApp</span>
                </div>
            </div>
            <div class="share-link">
                <input type="text" id="shareUrl" value="{{ route('podcasts.index') }}" readonly>
                <button class="copy-link" id="copyLink">Copy</button>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>DARLING FM PODCASTS</h1>
                <p>
                    Immerse yourself in cutting-edge conversations, interviews, and stories. 
                    Experience both audio and video podcasts in a revolutionary way.
                </p>
            </div>

            @if($featured->count() > 0)
                @php($heroPodcast = $featured->first())
                <!-- Hero Section -->
                <section class="hero-section">
                    <div class="hero-toggle">
                        <button class="toggle-btn active" id="videoToggle">
                            <i class="fas fa-video"></i> Video Podcast
                        </button>
                        <button class="toggle-btn" id="audioToggle">
                            <i class="fas fa-headphones"></i> Audio Podcast
                        </button>
                    </div>
                    
                    <div class="hero-content">
                        <div class="hero-media">
                            @if($heroPodcast->video_url)
                                <video id="heroVideo" poster="{{ $heroPodcast->cover_image ?? asset('assets/images/logo1.jpg') }}" controls>
                                    <source src="{{ $heroPodcast->video_url }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @else
                                <div class="video-placeholder" style="background-image: url('{{ $heroPodcast->cover_image ?? asset('assets/images/logo1.jpg') }}')">
                                    <i class="fas fa-play"></i>
                                </div>
                            @endif
                            
                            <!-- Audio Player (hidden by default) -->
                            <div class="hero-audio" id="heroAudio" style="display: none;">
                                <div class="audio-visualizer" id="audioVisualizer">
                                    @for($i = 0; $i < 10; $i++)
                                        <div class="audio-bar"></div>
                                    @endfor
                                </div>
                                <h3>Audio Podcast Playing</h3>
                                <p>Listen to the full episode with immersive sound</p>
                            </div>
                            
                            <div class="media-overlay">
                                <div class="play-hero-btn" id="playHeroBtn">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="hero-info">
                            <span class="podcast-type" id="heroType">FEATURED PODCAST</span>
                            <h2 class="hero-title">{{ $heroPodcast->title }}</h2>
                            <p class="hero-description">
                                {{ Str::limit($heroPodcast->description, 200) }}
                            </p>
                            <div class="hero-meta">
                                <div class="meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $heroPodcast->duration }}</span>
                                </div>
                                <div class="meta-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ optional($heroPodcast->published_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="hero-actions">
                                <a href="{{ route('podcasts.show', $heroPodcast) }}" class="action-btn primary-btn">
                                    <i class="fas fa-play"></i> Play Now
                                </a>
                                <button class="action-btn secondary-btn" id="heroShareBtn" data-podcast="{{ $heroPodcast->slug }}">
                                    <i class="fas fa-share"></i> Share
                                </button>
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            <!-- Podcast Categories -->
            <section class="podcast-categories">
                <h2 class="section-title">BROWSE CATEGORIES</h2>
                <div class="categories-grid">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <h3 class="category-title">Music</h3>
                        <div class="category-count">{{ $episodes->where('category', 'music')->count() }} podcasts</div>
                    </div>
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-microphone"></i>
                        </div>
                        <h3 class="category-title">Interviews</h3>
                        <div class="category-count">{{ $episodes->where('category', 'interview')->count() }} podcasts</div>
                    </div>
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h3 class="category-title">Technology</h3>
                        <div class="category-count">{{ $episodes->where('category', 'technology')->count() }} podcasts</div>
                    </div>
                </div>
            </section>

            <!-- Featured Episodes -->
            @if($featured->count() > 0)
                <section class="container">
                    <h2 class="section-title">SPOTLIGHT</h2>
                    <div class="posts-grid">
                        @foreach($featured as $episode)
                            <div class="post-card">
                                <div class="post-image" style="background-image: url('{{ $episode->cover_image ?? asset('assets/images/logo1.jpg') }}')"></div>
                                <div class="post-content">
                                    <h3 class="post-title">{{ $episode->title }}</h3>
                                    <p class="post-meta">{{ $episode->host }} • {{ $episode->duration }}</p>
                                    <p class="post-excerpt">{{ Str::limit($episode->description, 120) }}</p>
                                    <a class="action-btn" href="{{ route('podcasts.show', $episode) }}">Play</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            <!-- All Episodes -->
            <section class="container">
                <h2 class="section-title">ALL EPISODES</h2>
                <div class="posts-grid">
                    @foreach($episodes as $episode)
                        <div class="post-card">
                            <div class="post-image" style="background-image: url('{{ $episode->cover_image ?? asset('assets/images/logo1.jpg') }}')"></div>
                            <div class="post-content">
                                <div class="post-meta">
                                    <span><i class="far fa-calendar"></i> {{ optional($episode->published_at)->format('M d, Y') }}</span>
                                    <span><i class="far fa-clock"></i> {{ $episode->duration }}</span>
                                </div>
                                <h3 class="post-title">{{ $episode->title }}</h3>
                                <p class="post-excerpt">{{ Str::limit($episode->description, 140) }}</p>
                                <div class="post-actions">
                                    <a href="{{ route('podcasts.show', $episode) }}" class="action-btn">
                                        <i class="fas fa-play"></i> Open
                                    </a>
                                    <button class="action-btn share-btn" data-podcast="{{ $episode->slug }}">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $episodes->links() }}
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/podcast.js') }}" defer></script>
@endpush
