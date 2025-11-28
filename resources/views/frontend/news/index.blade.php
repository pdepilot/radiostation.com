@extends('layouts.frontend', ['title' => 'Darling FM • News'])

@section('content')
    <section class="container">
        <h2 class="section-title">Headlines</h2>
        @if($featured)
            <article class="featured-post" style="background-image: url('{{ $featured->hero_image ?? asset('assets/images/radio1.jpg') }}')">
                <div class="overlay">
                    <p>{{ optional($featured->published_at)->format('M d, Y') }}</p>
                    <h1>{{ $featured->title }}</h1>
                    <p>{{ $featured->excerpt }}</p>
                    <a href="{{ route('news.show', $featured) }}" class="listen-btn">Full Story</a>
                </div>
            </article>
        @endif
    </section>

    <section class="container">
        <h2 class="section-title">All Stories</h2>
        <div class="posts-grid">
            @foreach($posts as $post)
                <div class="post-card">
                    <div class="post-content">
                        <div class="post-meta">
                            <span>{{ optional($post->published_at)->format('M d, Y') }}</span>
                            <span>{{ $post->author_name }}</span>
                        </div>
                        <h3 class="post-title">{{ $post->title }}</h3>
                        <p class="post-excerpt">{{ Str::limit(strip_tags($post->excerpt), 140) }}</p>
                        <a href="{{ route('news.show', $post) }}" class="action-btn">Open</a>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $posts->links() }}
    </section>
@endsection

