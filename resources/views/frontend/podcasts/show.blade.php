@extends('layouts.frontend', ['title' => $podcast->title . ' • Darling FM'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/podcast.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <div class="podcast-detail-page">
                <div class="podcast-hero">
                    <div class="podcast-cover-large">
                        <img src="{{ $podcast->cover_image ?? asset('assets/images/logo1.jpg') }}" alt="{{ $podcast->title }}">
                        @if($podcast->video_url)
                            <div class="play-overlay">
                                <button class="play-hero-btn" id="playPodcastBtn">
                                    <i class="fas fa-play"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                    <div class="podcast-info-large">
                        <span class="podcast-type-badge">PODCAST EPISODE</span>
                        <h1>{{ $podcast->title }}</h1>
                        <p class="podcast-host-large">Host: {{ $podcast->host }}</p>
                        <p class="podcast-description-large">{{ $podcast->description }}</p>
                        <div class="podcast-meta-large">
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ $podcast->duration }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ optional($podcast->published_at)->format('M d, Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-headphones"></i>
                                <span>{{ number_format($podcast->listen_count ?? 0) }} listens</span>
                            </div>
                        </div>
                        <div class="podcast-actions-large">
                            @if($podcast->audio_url || $podcast->video_url)
                                <button class="action-btn primary-btn" id="playEpisodeBtn">
                                    <i class="fas fa-play"></i> Play Episode
                                </button>
                            @endif
                            <button class="action-btn secondary-btn" id="likePodcastBtn">
                                <i class="fas fa-heart"></i> <span>{{ rand(50, 500) }}</span>
                            </button>
                            <button class="action-btn secondary-btn" id="sharePodcastBtn" data-podcast="{{ $podcast->slug }}">
                                <i class="fas fa-share"></i> Share
                            </button>
                        </div>
                    </div>
                </div>

                @if($podcast->video_url)
                    <div class="video-player-container" id="videoPlayerContainer" style="display: none;">
                        <video id="podcastVideoPlayer" controls style="width: 100%; border-radius: 10px;">
                            <source src="{{ $podcast->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @endif

                @if($podcast->audio_url)
                    <div class="audio-player-container" id="audioPlayerContainer" style="display: none;">
                        <div class="audio-visualizer-large" id="audioVisualizerLarge">
                            @for($i = 0; $i < 20; $i++)
                                <div class="audio-bar-large"></div>
                            @endfor
                        </div>
                        <audio id="podcastAudioPlayer" controls style="width: 100%;">
                            <source src="{{ $podcast->audio_url }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                @endif

                <div class="podcast-description-full">
                    <h2>About This Episode</h2>
                    <div class="description-content">
                        {!! nl2br(e($podcast->description)) !!}
                    </div>
                </div>
            </div>

            <section class="container" style="margin-top: 40px;">
                <h3 class="section-title">More Episodes</h3>
                <div class="posts-grid">
                    @foreach($recommendations as $episode)
                        <div class="post-card">
                            <div class="post-image" style="background-image: url('{{ $episode->cover_image ?? asset('assets/images/logo1.jpg') }}')"></div>
                            <div class="post-content">
                                <h4 class="post-title">{{ $episode->title }}</h4>
                                <p class="post-meta">{{ optional($episode->published_at)->format('M d') }} • {{ $episode->duration }}</p>
                                <a href="{{ route('podcasts.show', $episode) }}" class="action-btn">Play</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/podcast.js') }}" defer></script>
@endpush
