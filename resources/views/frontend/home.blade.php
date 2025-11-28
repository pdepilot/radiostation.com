@extends('layouts.frontend', ['title' => 'Darling FM • Nigerian Standard Radio'])

@section('content')
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h2>Darling FM<br> Broadcasting Nigeria's Pulse</h2>
                <p>Broadcasting from Owerri with a mix of premium talk-shows, live mixes and immersive cultural storytelling.</p>
                <a class="listen-btn" href="{{ route('live') }}">
                    <i class="fas fa-headphones"></i>
                    Listen Live
                </a>
            </div>
        </div>
    </section>

    @if($liveStream)
        <section class="live-oap-display">
            <div class="live-oap-header">
                <div class="live-oap-avatar" style="background-image: url('{{ $liveStream->dj?->avatar_url ?? asset('assets/images/radio1.jpg') }}')"></div>
                <div class="live-oap-info">
                    <div class="live-oap-name">{{ $liveStream->dj?->stage_name ?? $liveStream->dj?->name }}</div>
                    <div class="live-oap-show">{{ $liveStream->show?->title ?? 'Live Session' }}</div>
                    <div class="live-status">
                        <div class="live-dot {{ $liveStream->status === 'live' ? 'active' : '' }}"></div>
                        <span>{{ strtoupper($liveStream->status) }}</span>
                    </div>
                    <div class="live-time">{{ optional($liveStream->started_at)->format('h:i A') }} • {{ $liveStream->listener_count }} listeners</div>
                </div>
            </div>
            <div class="live-actions">
                <a href="{{ route('live') }}" class="listen-btn">
                    Tune In
                </a>
                <a href="{{ route('contact.index') }}" class="stream-btn secondary">Book Studio</a>
            </div>
        </section>
    @endif

    <section class="upcoming-shows">
        <div class="container">
            <h2 class="section-title">Upcoming Shows</h2>
            <div class="shows-grid">
                @forelse($upcomingShows as $show)
                    <div class="show-card">
                        <div class="show-header">
                            <div class="show-avatar" style="background-image: url('{{ $show->hero_image ?? asset('assets/images/studio.jpg') }}')"></div>
                            <div class="show-info">
                                <div class="show-name">{{ $show->title }}</div>
                                <div class="show-title">{{ $show->dj?->stage_name ?? $show->dj?->name }}</div>
                            </div>
                        </div>
                        <div class="show-time">
                            <i class="far fa-clock"></i>
                            <span>{{ $show->day_of_week }} • {{ $show->start_time }} - {{ $show->end_time }}</span>
                        </div>
                        <a href="{{ route('shows.show', $show) }}" class="remind-btn">
                            <i class="far fa-bell"></i> Details
                        </a>
                    </div>
                @empty
                    <p>No shows scheduled yet.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="container">
        <h2 class="section-title">Featured DJs</h2>
        <div class="posts-grid">
            @foreach($featuredDjs as $dj)
                <div class="post-card">
                    <div class="post-image" style="background-image: url('{{ $dj->avatar_url ?? asset('assets/images/face.jpg') }}')"></div>
                    <div class="post-content">
                        <h3 class="post-title">{{ $dj->stage_name ?? $dj->name }}</h3>
                        <p class="post-excerpt">{{ $dj->bio }}</p>
                        <div class="post-actions">
                            <a class="action-btn share-btn" href="{{ $dj->instagram }}" target="_blank">
                                <i class="fab fa-instagram"></i> Follow
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container">
        <h2 class="section-title">Latest News</h2>
        <div class="posts-grid">
            @foreach($newsPosts as $post)
                <div class="post-card">
                    <div class="post-image" style="background-image: url('{{ $post->hero_image ?? asset('assets/images/darling studio.jpg') }}')"></div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span><i class="far fa-calendar"></i> {{ optional($post->published_at)->format('M d, Y') }}</span>
                            <span><i class="far fa-user"></i> {{ $post->author_name }}</span>
                        </div>
                        <h3 class="post-title">{{ $post->title }}</h3>
                        <p class="post-excerpt">{{ $post->excerpt }}</p>
                        <div class="post-actions">
                            <a href="{{ route('news.show', $post) }}" class="action-btn">
                                Read Story
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container">
        <h2 class="section-title">This Week's Playlist</h2>
        <div class="playlist-table">
            <table>
                <thead>
                    <tr>
                        <th>Song</th>
                        <th>Artist</th>
                        <th>Mood</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($playlist as $track)
                    <tr>
                        <td>{{ $track->title }}</td>
                        <td>{{ $track->artist }}</td>
                        <td>{{ $track->mood }}</td>
                        <td>{{ $track->duration }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="container">
        <h2 class="section-title">Fresh Podcasts</h2>
        <div class="posts-grid">
            @foreach($podcasts as $podcast)
                <div class="post-card">
                    <div class="post-image" style="background-image: url('{{ $podcast->cover_image ?? asset('assets/images/logo1.jpg') }}')"></div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span><i class="far fa-calendar"></i> {{ optional($podcast->published_at)->format('M d, Y') }}</span>
                            <span><i class="far fa-clock"></i> {{ $podcast->duration }}</span>
                        </div>
                        <h3 class="post-title">{{ $podcast->title }}</h3>
                        <p class="post-excerpt">{{ Str::limit($podcast->description, 120) }}</p>
                        <div class="post-actions">
                            <a href="{{ route('podcasts.show', $podcast) }}" class="action-btn">
                                Listen Episode
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/index.js') }}" defer></script>
@endpush

