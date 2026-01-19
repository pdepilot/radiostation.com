@extends('layouts.frontend', ['title' => $show->title . ' • Darling FM'])

@push('styles')
<style>
    .show-detail-page {
        padding-top: 120px;
        min-height: 100vh;
    }
    
    .show-hero-section {
        position: relative;
        height: 400px;
        background-size: cover;
        background-position: center;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }
    
    .show-hero-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.9));
        padding: 40px;
        color: white;
    }
    
    .show-hero-overlay h1 {
        font-family: 'Orbitron', sans-serif;
        font-size: 3rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 10px;
    }
    
    .show-hero-overlay .show-tagline {
        font-size: 1.3rem;
        color: var(--light);
        margin-bottom: 15px;
        font-weight: 300;
    }
    
    .show-hero-overlay .show-schedule {
        font-size: 1rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .show-content-section {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .show-description-card {
        background: var(--glass);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 40px;
        border: 1px solid var(--glass-border);
    }
    
    .show-description-card h2 {
        font-family: 'Orbitron', sans-serif;
        color: var(--accent);
        font-size: 1.8rem;
        margin-bottom: 20px;
    }
    
    .show-description-card p {
        color: var(--light);
        line-height: 2;
        font-size: 1.1rem;
        margin-bottom: 15px;
    }
    
    .show-host-card {
        background: var(--glass);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 40px;
        border: 1px solid var(--glass-border);
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 15px;
        align-items: start;
    }
    
    .show-host-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-size: cover;
        background-position: center;
        border: 3px solid var(--accent);
        grid-row: 1 / 3;
    }
    
    .show-host-info {
        grid-column: 2;
    }
    
    .show-host-info h3 {
        font-family: 'Orbitron', sans-serif;
        color: var(--accent);
        font-size: 1.2rem;
        margin-bottom: 8px;
        line-height: 1.3;
    }
    
    .show-host-info p {
        color: var(--text-secondary);
        line-height: 1.5;
        font-size: 0.9rem;
        margin: 0;
    }
    
    .related-shows-section {
        margin-top: 60px;
        padding: 0 20px;
    }
    
    .related-shows-section h2 {
        font-family: 'Orbitron', sans-serif;
        color: var(--accent);
        font-size: 2rem;
        margin-bottom: 30px;
        text-align: center;
    }
    
    .related-shows-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    @media (max-width: 768px) {
        .related-shows-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .related-show-card {
        background: var(--glass);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        overflow: hidden;
        border: 1px solid var(--glass-border);
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
        display: block;
    }
    
    .related-show-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(255, 0, 0, 0.2);
    }
    
    .related-show-image {
        height: 180px;
        background-size: cover;
        background-position: center;
    }
    
    .related-show-content {
        padding: 20px;
    }
    
    .related-show-content h3 {
        color: var(--accent);
        font-size: 1.2rem;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .related-show-content p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    @media (max-width: 768px) {
        .show-hero-section {
            height: 300px;
        }
        
        .show-hero-overlay {
            padding: 25px;
        }
        
        .show-hero-overlay h1 {
            font-size: 2rem;
        }
        
        .show-hero-overlay .show-tagline {
            font-size: 1.1rem;
        }
        
        .show-host-card {
            grid-template-columns: 1fr;
            text-align: center;
        }
        
        .show-host-avatar {
            grid-row: 1;
            margin: 0 auto 15px;
        }
        
        .show-host-info {
            grid-column: 1;
        }
        
        .related-shows-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
    <div class="show-detail-page">
        <div class="container">
            @php
                $heroImageUrl = $show->hero_image_url;
            @endphp
            <div class="show-hero-section" style="background-image: url('{{ $heroImageUrl }}')">
                <div class="show-hero-overlay">
                    <h1 class="section-title" style="text-align: left; margin-bottom: 10px; font-size: 3rem; text-transform: none; letter-spacing: normal;">{{ $show->title }}</h1>
                    @if($show->tagline)
                        <p class="show-tagline">{{ $show->tagline }}</p>
                    @endif
                    <div class="show-schedule">
                        <i class="far fa-calendar"></i>
                        <span>{{ $show->day_of_week }} • {{ \Carbon\Carbon::parse($show->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($show->end_time)->format('g:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Top Banner Ad Slot -->
            <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; color: var(--text-secondary); font-size: 0.9rem; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                <span>Advertisement</span>
            </div>
            
            <div class="show-content-section">
                @if($show->description)
                <div class="show-description-card">
                    <h2>About This Show</h2>
                    <div>{!! nl2br(e($show->description)) !!}</div>
                </div>
                @endif
                
                @if($show->dj)
                <div class="show-host-card">
                    <div class="show-host-avatar" style="background-image: url('{{ $show->dj->avatar_url ?? asset('assets/images/face.jpg') }}')"></div>
                    <div class="show-host-info">
                        <h3>Hosted by {{ $show->dj->stage_name ?? $show->dj->name }}</h3>
                        @if($show->dj->bio)
                            <p>{{ Str::limit($show->dj->bio, 150) }}</p>
                        @endif
                    </div>
                </div>
                @endif
                
                <div style="display: flex; align-items: center; justify-content: flex-start; gap: 15px; margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--glass-border);">
                    <button
                        id="showShareButton"
                        type="button"
                        data-share-type="show"
                        data-share-title="{{ $show->title }}"
                        data-share-url="{{ route('shows.show', $show, true) }}"
                        style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: var(--accent); border: 1px solid rgba(255,255,255,0.2); border-radius: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s; cursor: pointer; font-weight: 600; font-size: 0.95rem;"
                        onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-share-alt"></i>
                        <span>Share</span>
                    </button>
                </div>
            </div>
            
            @if($related->count() > 0)
            <div class="related-shows-section">
                <h2>Related Shows</h2>
                <div class="related-shows-grid">
                    @foreach($related as $item)
                        <a href="{{ route('shows.show', $item) }}" class="related-show-card">
                            @php
                                $relatedImageUrl = $item->hero_image_url;
                            @endphp
                            <div class="related-show-image" style="background-image: url('{{ $relatedImageUrl }}')"></div>
                            <div class="related-show-content">
                                <h3>{{ $item->title }}</h3>
                                <p>{{ Str::limit($item->description ?? 'Amazing radio show', 100) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Share Modal --}}
    @include('components.share-modal', ['shareId' => 'Show'])

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shareModal = document.getElementById('shareModalShow');
            const shareButton = document.getElementById('showShareButton');
            const closeModalBtn = shareModal?.querySelector('.close-share-modal');
            const shareOptions = shareModal?.querySelectorAll('.share-option-btn');

            if (!shareButton || !shareModal) return;

            shareButton.addEventListener('click', function(e) {
                e.preventDefault();
                shareModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', () => {
                    shareModal.style.display = 'none';
                    document.body.style.overflow = '';
                });
            }

            shareModal.addEventListener('click', (e) => {
                if (e.target === shareModal) {
                    shareModal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            });

            if (shareOptions) {
                shareOptions.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const platform = this.getAttribute('data-platform');
                        const shareTitle = shareButton.getAttribute('data-share-title') || '';
                        const shareUrl = shareButton.getAttribute('data-share-url') || window.location.href;
                        const customMessage = document.getElementById('shareMessageShow')?.value || '';
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
