@extends('layouts.frontend', ['title' => 'Darling FM • Playlist'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/playlist.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>DARLING FM 107.3 PLAYLIST</h1>
                <p>
                    Experience the future of music curation. AI-powered playlists that
                    adapt to your mood, environment, and listening habits in real-time.
                </p>
            </div>

            <!-- Search Section -->
            <div class="search-section">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="search-input" placeholder="Search for tracks, artists, or albums...">
                </div>
            </div>

            <!-- Music Library -->
            <div class="music-library">
                <div class="library-header">
                    <h2 class="library-title">Music Library</h2>
                    <div class="library-count" id="track-count">{{ $latestTracks->total() }} tracks</div>
                </div>
                <div class="tracks-grid" id="tracks-grid">
                    @foreach($latestTracks as $track)
                        <div class="track-card" data-track-title="{{ strtolower($track->title) }}" data-track-artist="{{ strtolower($track->artist) }}">
                            <div class="track-image" style="background-image: url('{{ $track->cover_image ?? asset('assets/images/logo1.jpg') }}')">
                                <div class="track-overlay">
                                    <button class="play-track-btn" data-track="{{ $track->id }}">
                                        <i class="fas fa-play"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="track-info">
                                <h3 class="track-title">{{ $track->title }}</h3>
                                <p class="track-artist">{{ $track->artist }}</p>
                                <p class="track-meta">{{ $track->genre }} • {{ $track->duration }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $latestTracks->links() }}
            </div>

            <!-- Now Playing Section -->
            <div class="now-playing-section">
                <h2 class="section-title">NOW PLAYING</h2>
                <div class="current-track">
                    <div class="track-image-large" id="now-playing-image" style="background-image: url('{{ asset('assets/images/studio.jpg') }}')"></div>
                    <div class="track-info-large">
                        <h3 class="track-title-large" id="now-playing-title">Select a track to play</h3>
                        <p class="track-artist-large" id="now-playing-artist">Artist</p>
                        <p class="track-album-large" id="now-playing-album">Album</p>
                    </div>
                </div>
                
                <!-- Equalizer in Now Playing -->
                <div class="now-playing-equalizer" id="now-playing-equalizer">
                    <div class="equalizer-bar" style="height: 5px;"></div>
                    <div class="equalizer-bar" style="height: 15px;"></div>
                    <div class="equalizer-bar" style="height: 10px;"></div>
                    <div class="equalizer-bar" style="height: 20px;"></div>
                    <div class="equalizer-bar" style="height: 8px;"></div>
                    <div class="equalizer-bar" style="height: 18px;"></div>
                    <div class="equalizer-bar" style="height: 12px;"></div>
                    <div class="equalizer-bar" style="height: 7px;"></div>
                </div>
                
                <div class="player-controls">
                    <button class="control-btn" id="shuffle-btn">
                        <i class="fas fa-random"></i>
                    </button>
                    <button class="control-btn" id="prev-btn">
                        <i class="fas fa-step-backward"></i>
                    </button>
                    <button class="play-pause-btn" id="play-pause-btn">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="control-btn" id="next-btn">
                        <i class="fas fa-step-forward"></i>
                    </button>
                    <button class="control-btn" id="repeat-btn">
                        <i class="fas fa-redo"></i>
                    </button>
                </div>
                <div class="progress-container">
                    <div class="progress-bar" id="progress-bar">
                        <div class="progress" id="progress"></div>
                    </div>
                    <div class="time-display">
                        <span id="current-time">0:00</span>
                        <span id="total-time">0:00</span>
                    </div>
                </div>
            </div>

            <!-- Queue Section -->
            <div class="queue-section">
                <h2 class="section-title">PLAYLIST QUEUE</h2>
                <div class="queue-list">
                    <div class="queue-header">
                        <h3 class="queue-title">Up Next</h3>
                        <div class="queue-actions">
                            <button class="queue-btn" id="clear-queue-btn">Clear Queue</button>
                        </div>
                    </div>
                    <div class="queue-items" id="queue-items">
                        @foreach($latestTracks->take(5) as $track)
                            <div class="queue-item" data-track-id="{{ $track->id }}">
                                <div class="queue-item-image" style="background-image: url('{{ $track->cover_image ?? asset('assets/images/logo1.jpg') }}')"></div>
                                <div class="queue-item-info">
                                    <h4>{{ $track->title }}</h4>
                                    <p>{{ $track->artist }}</p>
                                </div>
                                <div class="queue-item-duration">{{ $track->duration }}</div>
                                <button class="queue-item-remove" data-track-id="{{ $track->id }}">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Players (hidden) -->
    <audio id="audio-player" class="audio-player"></audio>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/playlist.js') }}" defer></script>
@endpush
