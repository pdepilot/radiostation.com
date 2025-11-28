@extends('layouts.frontend', ['title' => $post->title . ' • Darling FM News'])

@section('content')
    <section class="container">
        <article class="news-article">
            <p class="post-meta">{{ optional($post->published_at)->format('M d, Y') }} • {{ $post->author_name }}</p>
            <h1>{{ $post->title }}</h1>
            <img src="{{ $post->hero_image ?? asset('assets/images/studio.jpg') }}" alt="{{ $post->title }}">
            <p class="post-excerpt">{{ $post->excerpt }}</p>
            <div class="article-body">
                {!! $post->body !!}
            </div>
            @if($post->tags)
                <p class="tags">
                    @foreach($post->tags as $tag)
                        <span class="tag">{{ $tag }}</span>
                    @endforeach
                </p>
            @endif
        </article>
    </section>

    <section class="container">
        <h3>More News</h3>
        <div class="posts-grid">
            @foreach($related as $item)
                <div class="post-card">
                    <h4 class="post-title">{{ $item->title }}</h4>
                    <p class="post-excerpt">{{ Str::limit($item->excerpt, 100) }}</p>
                    <a href="{{ route('news.show', $item) }}" class="action-btn">Read</a>
                </div>
            @endforeach
        </div>
    </section>
@endsection

