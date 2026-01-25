@extends('layouts.frontend', ['title' => $post->title . ' • Darling FM News'])

@push('styles')
<style>
    .ad-slot {
        background: var(--gray-800);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-secondary);
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .ad-slot:hover {
        border-color: var(--accent);
    }

    @media (max-width: 768px) {
        .ad-slot {
            height: 80px !important;
            font-size: 0.8rem;
        }
    }

    .news-article {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .news-article .post-meta {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .news-article h1 {
        font-family: 'Orbitron', sans-serif;
        color: var(--accent);
        font-size: 2.5rem;
        margin-bottom: 30px;
        line-height: 1.2;
        font-weight: 700;
    }

    .news-article img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .news-article .post-excerpt {
        color: var(--text-secondary);
        font-size: 1.2rem;
        line-height: 1.8;
        margin-bottom: 40px;
        font-weight: 300;
    }

    .news-article .article-body {
        color: var(--light);
        font-size: 1.1rem;
        line-height: 2;
        margin-bottom: 40px;
    }

    .news-article .article-body p {
        margin-bottom: 20px;
    }

    .news-article .tags {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid var(--glass-border);
    }

    .news-article .tag {
        background: var(--glass);
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        color: var(--light);
        border: 1px solid var(--glass-border);
    }
</style>
@endpush

@section('content')
<div style="padding-top: 120px;">
    <section class="container">
        <!-- Top Banner Ad Slot -->
        <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; color: var(--text-secondary); font-size: 0.9rem;">
            <span>Advertisement</span>
        </div>

        <article class="news-article">
            <div class="post-meta">
                <span><i class="far fa-calendar"></i> {{ optional($post->published_at)->format('M d, Y') }}</span>
                @if($post->author_name)
                <span><i class="fas fa-user"></i> {{ $post->author_name }}</span>
                @endif
            </div>
            <h1 class="section-title" style="text-align: left; margin-bottom: 20px; font-size: 2.5rem; text-transform: none; letter-spacing: normal;">{{ $post->title }}</h1>
            @if($post->hero_image)
            <img src="{{ $post->hero_image }}" alt="{{ $post->title }}" style="width: 100%; height: 400px; object-fit: cover; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);" onerror="this.src='{{ asset('assets/images/studio.jpg') }}'; this.onerror=null;">
            @else
            <img src="{{ asset('assets/images/studio.jpg') }}" alt="{{ $post->title }}" style="width: 100%; height: 400px; object-fit: cover; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);">
            @endif
            @if($post->excerpt)
            <p class="post-excerpt">{{ $post->excerpt }}</p>
            @endif

            <!-- Interstitial Ad Slot - Before article body -->
            <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 30px 0; color: var(--text-secondary); font-size: 0.9rem;">
                <span>Advertisement</span>
            </div>

            <div class="article-body">
                {!! $post->body ?? '<p>Content coming soon...</p>' !!}
            </div>
            <div style="display: flex; align-items: center; justify-content: flex-start; gap: 15px; margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--glass-border);">
                <button
                    id="newsShareButton"
                    type="button"
                    data-share-type="news"
                    data-share-title="{{ $post->title }}"
                    data-share-url="{{ route('news.show', $post, true) }}"
                    style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: var(--accent); border: 1px solid rgba(255,255,255,0.2); border-radius: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s; cursor: pointer; font-weight: 600; font-size: 0.95rem;"
                    onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                    <i class="fas fa-share-alt"></i>
                    <span>Share</span>
                </button>
            </div>
            @if(!empty($post->tags) && is_array($post->tags))
            <p class="tags">
                @foreach($post->tags as $tag)
                <span class="tag">{{ $tag }}</span>
                @endforeach
            </p>
            @endif
        </article>
    </section>

    <!-- Comments Section -->
    <section class="container" id="comments" style="max-width: 900px; margin-top: 60px; padding: 0 20px;">
        <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; padding: 40px; border: 1px solid var(--glass-border);">
            <h2 class="section-title" style="text-align: left; margin-bottom: 30px; font-size: 1.8rem;">COMMENTS</h2>
            @auth
            <form method="POST" action="{{ route('comments.store', $post->slug) }}" style="margin-bottom: 30px;">
                @csrf
                <textarea name="body" rows="4" placeholder="Write a comment..." required style="width: 100%; padding: 15px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; resize: vertical; outline: none;"></textarea>
                <button type="submit" style="margin-top: 15px; padding: 12px 30px; background: var(--accent); color: white; border: none; border-radius: 25px; font-weight: 600; cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">Post Comment</button>
            </form>
            @else
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                <a href="{{ route('login') }}" wire:navigate style="color: var(--accent); text-decoration: none;">Login</a> to post a comment.
            </p>
            @endauth

            <div class="comments-list" style="margin-top: 30px;">
                @forelse($post->comments()->where('is_approved', true)->latest()->get() as $comment)
                <div style="background: rgba(255,255,255,0.03); padding: 20px; border-radius: 10px; margin-bottom: 15px; border: 1px solid var(--glass-border);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                        <div>
                            <strong style="color: var(--accent);">{{ $comment->user->name ?? 'Anonymous' }}</strong>
                            <span style="color: var(--text-secondary); font-size: 0.9rem; margin-left: 10px;">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        @auth
                        @if(auth()->id() === $comment->user_id || auth()->user()->isAdmin())
                        <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 0.9rem;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text-secondary)'">Delete</button>
                        </form>
                        @endif
                        @endauth
                    </div>
                    <p style="color: var(--light); line-height: 1.6; margin: 0;">{{ $comment->body }}</p>
                </div>
                @empty
                <p style="color: var(--text-secondary); text-align: center; padding: 40px;">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- More News Section -->
    @if($related->count() > 0)
    <section class="container" style="margin-top: 80px; padding: 0 20px;">
        <!-- Sidebar Ad Slot -->
        <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 250px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; color: var(--text-secondary); font-size: 0.9rem;">
            <span>Advertisement</span>
        </div>

        <h2 class="section-title" style="margin-bottom: 40px; font-size: 2rem;">MORE NEWS</h2>
        <div class="posts-grid">
            @foreach($related as $item)
            <div class="post-card">
                <a href="{{ route('news.show', $item) }}" wire:navigate style="text-decoration: none; color: inherit; display: block;">
                    <div class="post-image" style="background-image: url('{{ $item->hero_image ?? asset('assets/images/studio.jpg') }}'); height: 200px; background-size: cover; background-position: center; border-radius: 15px 15px 0 0;"></div>
                </a>
                <div class="post-content">
                    <div class="post-meta">
                        <span><i class="far fa-calendar"></i> {{ optional($item->published_at)->format('M d, Y') }}</span>
                    </div>
                    <a href="{{ route('news.show', $item) }}" wire:navigate style="text-decoration: none; color: inherit;">
                        <h3 class="post-title">{{ $item->title }}</h3>
                        <p class="post-excerpt">{{ Str::limit($item->excerpt, 120) }}</p>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

{{-- Share Modal --}}
@include('components.share-modal', ['shareId' => 'News'])

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shareModal = document.getElementById('shareModalNews');
        const shareButton = document.getElementById('newsShareButton');
        const closeModalBtn = shareModal?.querySelector('.close-share-modal');
        const shareOptions = shareModal?.querySelectorAll('.share-option-btn');

        if (!shareButton || !shareModal) return;

        // Open modal
        shareButton.addEventListener('click', function(e) {
            e.preventDefault();
            shareModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });

        // Close modal
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => {
                shareModal.style.display = 'none';
                document.body.style.overflow = '';
            });
        }

        // Close on backdrop
        shareModal.addEventListener('click', (e) => {
            if (e.target === shareModal) {
                shareModal.style.display = 'none';
                document.body.style.overflow = '';
            }
        });

        // Handle share
        if (shareOptions) {
            shareOptions.forEach(btn => {
                btn.addEventListener('click', function() {
                    const platform = this.getAttribute('data-platform');
                    const shareTitle = shareButton.getAttribute('data-share-title') || '';
                    const shareUrl = shareButton.getAttribute('data-share-url') || window.location.href;
                    const customMessage = document.getElementById('shareMessageNews')?.value || '';
                    const message = customMessage.trim() || `Check out "${shareTitle}" on Darling FM!`;
                    const fullText = `${message} ${shareUrl}`;

                    if (platform === 'copy') {
                        navigator.clipboard.writeText(shareUrl).then(() => {
                            const span = this.querySelector('span');
                            if (span) {
                                const original = span.textContent;
                                span.textContent = 'Copied!';
                                setTimeout(() => span.textContent = original, 2000);
                            }
                        });
                        return;
                    }

                    const encodedUrl = encodeURIComponent(shareUrl);
                    const encodedText = encodeURIComponent(fullText);
                    let shareUrlPlatform = '';

                    switch (platform) {
                        case 'facebook':
                            shareUrlPlatform = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}&quote=${encodeURIComponent(message)}`;
                            break;
                        case 'twitter':
                            shareUrlPlatform = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`;
                            break;
                        case 'whatsapp':
                            shareUrlPlatform = `https://api.whatsapp.com/send?text=${encodedText}`;
                            break;
                        case 'telegram':
                            shareUrlPlatform = `https://t.me/share/url?url=${encodedUrl}&text=${encodedText}`;
                            break;
                        case 'linkedin':
                            shareUrlPlatform = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
                            break;
                    }

                    if (shareUrlPlatform) {
                        window.open(shareUrlPlatform, 'shareWindow', 'width=600,height=400,scrollbars=yes,resizable=yes');
                        shareModal.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                });
            });
        }
    });
</script>
@endpush
@endsection