@extends('layouts.frontend', ['title' => 'Darling FM • News'])

@section('content')
    <section class="container">
        <h2 class="section-title">LATEST NEWS & UPDATES</h2>

        <div class="posts-grid">
            @foreach($posts as $post)
                <div class="post-card" data-post-id="{{ $post->id }}">
                    <div class="post-image" style="background-image: url('{{ $post->hero_image ?? asset('assets/images/darling studio.jpg') }}')"></div>
                    <div class="post-content">
                        <div class="post-meta">
                            <span><i class="far fa-calendar"></i> {{ optional($post->published_at)->format('M d, Y') }}</span>
                            <span><i class="far fa-comment"></i> <span class="comment-count">{{ $post->comment_count ?? 0 }}</span> Comments</span>
                        </div>
                        <h3 class="post-title">{{ $post->title }}</h3>
                        <p class="post-excerpt">{{ $post->excerpt }}</p>

                        <div class="post-actions">
                            <div class="like-comment">
                                <button class="action-btn like-btn">
                                    <i class="far fa-heart"></i>
                                    <span class="like-count">{{ rand(5, 20) }}</span>
                                </button>
                                <button class="action-btn comment-toggle-btn">
                                    <i class="far fa-comment"></i> Comment
                                </button>
                                <button class="action-btn share-btn" data-post-title="{{ $post->title }}">
                                    <i class="fas fa-share-alt"></i> Share
                                </button>
                            </div>
                        </div>

                        <!-- Comments Section -->
                        <div class="comments-section" style="display: none;">
                            <div class="comment-form">
                                <textarea class="comment-input" placeholder="Add a comment..."></textarea>
                                <button class="comment-submit">Post Comment</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $posts->links() }}
    </section>
@endsection
