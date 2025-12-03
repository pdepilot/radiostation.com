@extends('layouts.frontend', ['title' => 'Darling FM • Show Schedule'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/shows.css') }}">
@endpush

@section('content')
    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>Discover Our Shows</h1>
                <p>Explore our diverse collection of radio shows, from music mixes to talk shows and everything in between.</p>
            </div>
            
            @if($shows->count() > 0)
                <!-- Featured Show -->
                <div class="featured-show">
                    @php($featured = $shows->first())
                    <div class="featured-image" style="background-image: url('{{ $featured->hero_image ?? asset('assets/images/studio.jpg') }}')"></div>
                    <div class="featured-content">
                        <div class="featured-badge">Featured Show</div>
                        <h2 class="featured-title">{{ $featured->title }}</h2>
                        <p class="featured-description">{{ $featured->description ?? 'Join us for an amazing radio experience.' }}</p>
                        <div class="featured-meta">
                            <div class="meta-item">
                                <div class="meta-value">{{ number_format($featured->listener_count ?? 0) }}</div>
                                <div class="meta-label">Listeners</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-value">{{ $shows->count() }}</div>
                                <div class="meta-label">Shows</div>
                            </div>
                            <div class="meta-item">
                                <div class="meta-value">4.9</div>
                                <div class="meta-label">Rating</div>
                            </div>
                        </div>
                        <div class="show-actions">
                            <a href="{{ route('shows.show', $featured) }}" class="action-btn primary">
                                <i class="fas fa-play"></i>
                                View Details
                            </a>
                            <a href="{{ route('live') }}" class="action-btn follow-btn">
                                <i class="fas fa-heart"></i>
                                Listen Live
                            </a>
                            <button class="action-btn share-btn" data-show="{{ $featured->title }}">
                                <i class="fas fa-share-alt"></i>
                                Share
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Show Filters -->
                <div class="show-filters">
                    <div class="filter-group">
                        <button class="filter-btn active" data-category="all">All Shows</button>
                        <button class="filter-btn" data-category="music">Music</button>
                        <button class="filter-btn" data-category="talk">Talk</button>
                        <button class="filter-btn" data-category="news">News</button>
                        <button class="filter-btn" data-category="sports">Sports</button>
                        <button class="filter-btn" data-category="culture">Culture</button>
                    </div>
                    <div class="search-box">
                        <input type="text" placeholder="Search shows..." id="showSearch">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                
                <!-- Shows Grid -->
                <div class="shows-grid">
                    @foreach($shows as $show)
                        <div class="show-card" data-category="{{ strtolower($show->genre ?? 'music') }}">
                            <div class="show-image" style="background-image: url('{{ $show->hero_image ?? asset('assets/images/studio.jpg') }}')">
                                @if($show->is_featured ?? false)
                                    <div class="show-badge badge-popular">FEATURED</div>
                                @elseif($loop->first)
                                    <div class="show-badge badge-live">LIVE</div>
                                @else
                                    <div class="show-badge badge-new">NEW</div>
                                @endif
                            </div>
                            <div class="show-content">
                                <div class="show-title">
                                    <span>{{ $show->title }}</span>
                                    @if($loop->first)
                                        <div class="live-indicator"></div>
                                    @endif
                                </div>
                                <div class="show-host">
                                    <div class="host-avatar">{{ substr($show->dj?->name ?? 'DJ', 0, 2) }}</div>
                                    <span class="host-name">{{ $show->dj?->stage_name ?? $show->dj?->name ?? 'TBA' }}</span>
                                </div>
                                <p class="show-description">{{ Str::limit($show->description ?? 'Amazing radio show', 120) }}</p>
                                <div class="show-meta">
                                    <span>{{ $show->day_of_week }} {{ $show->start_time }} - {{ $show->end_time }}</span>
                                    <span>{{ number_format($show->listener_count ?? 0) }} listeners</span>
                                </div>
                                <div class="show-actions">
                                    <a href="{{ route('shows.show', $show) }}" class="action-btn primary">
                                        <i class="fas fa-play"></i>
                                        View Details
                                    </a>
                                    <button class="action-btn follow-btn" data-host="{{ $show->dj?->name }}">
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button class="action-btn share-btn" data-show="{{ $show->title }}">
                                        <i class="fas fa-share-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{ $shows->links() }}
            @else
                <p>No shows scheduled yet.</p>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/shows.js') }}" defer></script>
@endpush
