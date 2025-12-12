@extends('layouts.frontend', ['title' => 'Darling FM • Revolutionary DJs'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/djs.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header" style="text-align: center; margin-bottom: 60px;">
                <h1 class="section-title">REVOLUTIONARY DJS</h1>
            </div>

            @if($djs->count() > 0)
                @php($featuredDj = $djs->where('is_featured', true)->first() ?? $djs->first())
                
                <!-- DJ Spotlight -->
                <div class="dj-spotlight">
                    <div class="spotlight-bg"></div>
                    <div class="spotlight-content">
                        <div class="spotlight-image">
                            <div class="dj-avatar-hologram floating">
                                <img src="{{ $featuredDj->avatar_url ?? asset('assets/images/face.jpg') }}" alt="{{ $featuredDj->stage_name ?? $featuredDj->name }}">
                            </div>
                        </div>
                        <div class="spotlight-info">
                            <h2 class="dj-name">{{ strtoupper($featuredDj->stage_name ?? $featuredDj->name) }}</h2>
                            <p class="dj-tagline">{{ $featuredDj->specialty ?? 'Radio Personality' }}</p>
                            <div class="dj-stats">
                                <div class="stat">
                                    <div class="stat-value">{{ number_format(rand(10000, 50000)) }}</div>
                                    <div class="stat-label">Followers</div>
                                </div>
                                <div class="stat">
                                    <div class="stat-value">{{ $featuredDj->shows->count() ?? 0 }}</div>
                                    <div class="stat-label">Shows</div>
                                </div>
                            </div>
                            <p class="dj-bio">
                                {{ $featuredDj->bio ?? 'On-air personality that keeps Owerri moving with amazing content and great music.' }}
                            </p>
                            <div class="dj-actions">
                                <button class="action-btn primary play-btn" data-audio="{{ $featuredDj->shows->first()->stream_url ?? route('live') }}">
                                    <i class="fas fa-play"></i>
                                    Play Latest Mix
                                </button>
                                @if($featuredDj->instagram || $featuredDj->twitter)
                                    <a href="{{ $featuredDj->instagram ?? $featuredDj->twitter ?? '#' }}" class="action-btn follow-btn" target="_blank">
                                        <i class="fas fa-heart"></i>
                                        Follow
                                    </a>
                                @endif
                                <button class="action-btn share-btn" data-dj="{{ $featuredDj->stage_name ?? $featuredDj->name }}">
                                    <i class="fas fa-share-alt"></i>
                                    Share
                                </button>
                            </div>
                            <!-- Audio Duration Display -->
                            <div class="audio-duration" id="featuredDuration">
                                <span class="current-time">0:00</span> /
                                <span class="total-time">0:00</span>
                            </div>
                            <!-- Audio Visualizer for Featured DJ -->
                            <div class="audio-visualizer" id="featuredVisualizer">
                                <div class="visualizer-bar"></div>
                                <div class="visualizer-bar"></div>
                                <div class="visualizer-bar"></div>
                                <div class="visualizer-bar"></div>
                                <div class="visualizer-bar"></div>
                                <div class="visualizer-bar"></div>
                                <div class="visualizer-bar"></div>
                                <div class="visualizer-bar"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DJ Grid Section -->
                <div class="dj-grid-section">
                    <div class="section-header">
                        <h2 class="section-title">MEET THE ARTISTS</h2>
                    </div>

                    <div class="dj-filters">
                        <button class="filter-btn active" data-genre="all">All DJs</button>
                        <button class="filter-btn" data-genre="electronic">Electronic</button>
                        <button class="filter-btn" data-genre="hip-hop">Hip-Hop</button>
                        <button class="filter-btn" data-genre="house">House</button>
                        <button class="filter-btn" data-genre="techno">Techno</button>
                        <button class="filter-btn" data-genre="experimental">Experimental</button>
                    </div>

                    <div class="dj-grid">
                        @foreach($djs as $dj)
                            <div class="dj-card" data-genre="electronic">
                                <div class="dj-card-image" style="background-image: url('{{ $dj->avatar_url ?? asset('assets/images/face.jpg') }}')">
                                    @if($dj->is_featured)
                                        <div class="dj-card-badge badge-live">FEATURED</div>
                                    @elseif($loop->first)
                                        <div class="dj-card-badge badge-popular">POPULAR</div>
                                    @else
                                        <div class="dj-card-badge badge-new">NEW</div>
                                    @endif
                                </div>
                                <div class="dj-card-content">
                                    <h3 class="dj-card-name">{{ strtoupper($dj->stage_name ?? $dj->name) }}</h3>
                                    <p class="dj-card-role">{{ $dj->specialty ?? 'Radio Host' }}</p>
                                    <p class="dj-card-bio">
                                        {{ Str::limit($dj->bio ?? 'On-air personality bringing great content.', 100) }}
                                    </p>
                                    <div class="dj-card-stats">
                                        <div class="dj-card-stat">
                                            <div class="dj-card-stat-value">{{ number_format(rand(5000, 30000)) }}</div>
                                            <div class="dj-card-stat-label">Followers</div>
                                        </div>
                                        <div class="dj-card-stat">
                                            <div class="dj-card-stat-value">{{ $dj->shows->count() ?? 0 }}</div>
                                            <div class="dj-card-stat-label">Shows</div>
                                        </div>
                                    </div>
                                    <div class="dj-card-actions">
                                        <button class="action-btn play-btn" data-audio="{{ $dj->shows->first()->stream_url ?? route('live') }}">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        @if($dj->instagram || $dj->twitter)
                                            <a href="{{ $dj->instagram ?? $dj->twitter ?? '#' }}" class="action-btn follow-btn" target="_blank">
                                                <i class="fas fa-heart"></i>
                                            </a>
                                        @endif
                                        <button class="action-btn share-btn" data-dj="{{ $dj->stage_name ?? $dj->name }}">
                                            <i class="fas fa-share-alt"></i>
                                        </button>
                                    </div>
                                    <!-- Audio Duration Display -->
                                    <div class="audio-duration">
                                        <span class="current-time">0:00</span> /
                                        <span class="total-time">0:00</span>
                                    </div>
                                    <!-- Audio Visualizer -->
                                    <div class="audio-visualizer">
                                        <div class="visualizer-bar"></div>
                                        <div class="visualizer-bar"></div>
                                        <div class="visualizer-bar"></div>
                                        <div class="visualizer-bar"></div>
                                        <div class="visualizer-bar"></div>
                                        <div class="visualizer-bar"></div>
                                        <div class="visualizer-bar"></div>
                                        <div class="visualizer-bar"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{ $djs->links() }}

                <!-- Latest Mixes -->
                <div class="mixes-section">
                    <div class="section-header">
                        <h2 class="section-title">LATEST MIXES</h2>
                        <a href="{{ route('playlist.index') }}" class="view-all">
                            View All Mixes
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="mixes-grid">
                        @foreach($djs->take(6) as $dj)
                            <div class="mix-card">
                                <div class="mix-image" style="background-image: url('{{ $dj->avatar_url ?? asset('assets/images/face.jpg') }}')">
                                    <div class="mix-overlay">
                                        <div class="play-mix" data-audio="{{ $dj->shows->first()->stream_url ?? route('live') }}">
                                            <i class="fas fa-play"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mix-content">
                                    <h3 class="mix-title">{{ $dj->stage_name ?? $dj->name }} Mix</h3>
                                    <p class="mix-dj">{{ $dj->stage_name ?? $dj->name }}</p>
                                    <div class="mix-meta">
                                        <span>{{ number_format(rand(500, 5000)) }} plays</span>
                                        <span>{{ $dj->shows->first()->start_time ?? 'N/A' }}</span>
                                    </div>
                                    <!-- Audio Duration Display -->
                                    <div class="audio-duration">
                                        <span class="current-time">0:00</span> /
                                        <span class="total-time">0:00</span>
                                    </div>
                                    <!-- Audio Visualizer for Mix Card -->
                                    <div class="mix-visualizer">
                                        <div class="mix-visualizer-bar"></div>
                                        <div class="mix-visualizer-bar"></div>
                                        <div class="mix-visualizer-bar"></div>
                                        <div class="mix-visualizer-bar"></div>
                                        <div class="mix-visualizer-bar"></div>
                                        <div class="mix-visualizer-bar"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p>No DJs available yet.</p>
            @endif
        </div>
    </div>

    <!-- Social Share Modal -->
    <div class="modal" id="shareModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Share DJ</h3>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p id="shareDJText">Share this amazing DJ with your friends!</p>
                <div class="social-share-buttons">
                    <button class="social-share-btn facebook" data-platform="facebook">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </button>
                    <button class="social-share-btn twitter" data-platform="twitter">
                        <i class="fab fa-twitter"></i> Twitter
                    </button>
                    <button class="social-share-btn whatsapp" data-platform="whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </button>
                    <button class="social-share-btn telegram" data-platform="telegram">
                        <i class="fab fa-telegram"></i> Telegram
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Audio Player -->
    <audio id="audioPlayer" style="display: none;"></audio>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/djs.js') }}" defer></script>
@endpush
