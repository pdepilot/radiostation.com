@extends('layouts.frontend', ['title' => 'Darling FM • Podcasts'])

@section('content')
    <section class="container">
        <h2 class="section-title">Spotlight</h2>
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

    <section class="container">
        <h2 class="section-title">All Episodes</h2>
        <div class="posts-grid">
            @foreach($episodes as $episode)
                <div class="post-card">
                    <div class="post-content">
                        <div class="post-meta">
                            <span>{{ optional($episode->published_at)->format('M d, Y') }}</span>
                            <span>{{ $episode->duration }}</span>
                        </div>
                        <h3 class="post-title">{{ $episode->title }}</h3>
                        <p class="post-excerpt">{{ Str::limit($episode->description, 140) }}</p>
                        <a class="action-btn" href="{{ route('podcasts.show', $episode) }}">Open</a>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $episodes->links() }}
    </section>
@endsection

