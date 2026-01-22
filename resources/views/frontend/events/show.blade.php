@extends('layouts.frontend', ['title' => $event->title . ' • Darling FM'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
@endpush

@section('content')
<div class="main-content" style="padding-top: 120px;">
    <div class="container">
        <!-- Event Hero -->
        <div class="event-hero" style="position: relative; border-radius: 20px; overflow: hidden; margin-bottom: 40px; height: 400px; background-image: url('{{ $event->hero_image ?? asset('assets/images/studio.jpg') }}'); background-size: cover; background-position: center;">
            <div style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(255,0,0,0.3)); display: flex; align-items: flex-end; padding: 40px;">
                <div style="color: white; width: 100%;">
                    @if($event->is_featured)
                    <div style="background: var(--accent); color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; font-family: 'Orbitron', sans-serif; text-transform: uppercase; letter-spacing: 1px; display: inline-block; margin-bottom: 15px;">Featured Event</div>
                    @endif
                    <h1 class="section-title" style="text-align: left; margin-bottom: 15px; font-size: 3rem; text-transform: none; letter-spacing: normal;">{{ $event->title }}</h1>
                    <div style="display: flex; gap: 30px; flex-wrap: wrap; font-size: 1.1rem;">
                        <div><i class="far fa-calendar-alt" style="margin-right: 8px;"></i>{{ $event->event_date->format('F d, Y') }}</div>
                        @if($event->event_date->format('H:i') !== '00:00')
                        <div><i class="far fa-clock" style="margin-right: 8px;"></i>{{ $event->event_date->format('g:i A') }}</div>
                        @endif
                        @if($event->venue)
                        <div><i class="fas fa-map-marker-alt" style="margin-right: 8px;"></i>{{ $event->venue }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Banner Ad Slot -->
        <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; color: var(--text-secondary); font-size: 0.9rem; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--glass-border)'">
            <span>Advertisement</span>
        </div>

        <!-- Event Content -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-bottom: 60px;">
            <div>
                <h2 class="section-title" style="text-align: left; margin-bottom: 20px; font-size: 1.8rem;">About This Event</h2>
                @if($event->description)
                <div style="color: var(--light); line-height: 1.8; font-size: 1.05rem; margin-bottom: 30px;">
                    {!! nl2br(e($event->description)) !!}
                </div>
                @else
                <p style="color: var(--text-secondary); font-size: 1.05rem;">More details coming soon...</p>
                @endif
                
                <div style="display: flex; align-items: center; justify-content: flex-start; gap: 15px; margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--glass-border);">
                    <button
                        id="eventShareButton"
                        type="button"
                        data-share-type="event"
                        data-share-title="{{ $event->title }}"
                        data-share-url="{{ route('events.show', $event, true) }}"
                        style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: var(--accent); border: 1px solid rgba(255,255,255,0.2); border-radius: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); transition: all 0.3s; cursor: pointer; font-weight: 600; font-size: 0.95rem;"
                        onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-share-alt"></i>
                        <span>Share</span>
                    </button>
                </div>

                @if($event->location)
                <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 25px; border: 1px solid var(--glass-border); margin-top: 30px;">
                    <h3 style="font-family: 'Orbitron', sans-serif; color: var(--accent); margin-bottom: 15px; font-size: 1.3rem;">
                        <i class="fas fa-map-marker-alt" style="margin-right: 10px;"></i>Location
                    </h3>
                    <p style="color: var(--light); font-size: 1.05rem; line-height: 1.6;">{{ $event->location }}</p>
                </div>
                @endif
            </div>

            <div>
                <div style="background: var(--glass); backdrop-filter: blur(15px); border-radius: 20px; padding: 30px; border: 1px solid var(--glass-border); position: sticky; top: 140px;">
                    <h3 style="font-family: 'Orbitron', sans-serif; color: var(--accent); margin-bottom: 20px; font-size: 1.3rem;">Event Details</h3>
                    
                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--glass-border);">
                        <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 5px;">Date</div>
                        <div style="color: var(--light); font-size: 1.1rem; font-weight: 600;">{{ $event->event_date->format('F d, Y') }}</div>
                    </div>

                    @if($event->event_date->format('H:i') !== '00:00')
                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--glass-border);">
                        <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 5px;">Time</div>
                        <div style="color: var(--light); font-size: 1.1rem; font-weight: 600;">{{ $event->event_date->format('g:i A') }}</div>
                    </div>
                    @endif

                    @if($event->venue)
                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--glass-border);">
                        <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 5px;">Venue</div>
                        <div style="color: var(--light); font-size: 1.1rem; font-weight: 600;">{{ $event->venue }}</div>
                    </div>
                    @endif

                    @if($event->ticket_url)
                    <a href="{{ $event->ticket_url }}" target="_blank" style="display: block; background: var(--accent); color: white; padding: 15px 25px; border-radius: 10px; text-decoration: none; font-weight: 700; text-align: center; font-family: 'Orbitron', sans-serif; text-transform: uppercase; letter-spacing: 1px; transition: all 0.3s; margin-top: 20px;" onmouseover="this.style.background='var(--accent-glow)'" onmouseout="this.style.background='var(--accent)'">
                        <i class="fas fa-ticket-alt" style="margin-right: 10px;"></i>Get Tickets
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Events -->
        @if($related->count() > 0)
        <section>
            <h2 class="section-title" style="margin-bottom: 30px; font-size: 2rem;">Other Events</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
                @foreach($related as $relatedEvent)
                <a href="{{ route('events.show', $relatedEvent->slug) }}" wire:navigate style="display: block; background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; overflow: hidden; border: 1px solid var(--glass-border); transition: all 0.3s ease; cursor: pointer; text-decoration: none; color: inherit;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="height: 200px; background-image: url('{{ $relatedEvent->hero_image ?? asset('assets/images/studio.jpg') }}'); background-size: cover; background-position: center;"></div>
                    <div style="padding: 20px;">
                        <h4 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin-bottom: 10px; font-size: 1.2rem;">{{ $relatedEvent->title }}</h4>
                        <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 15px;">
                            <i class="far fa-calendar-alt" style="margin-right: 5px;"></i>{{ $relatedEvent->event_date->format('M d, Y') }}
                        </div>
                        <span style="color: var(--accent); font-weight: 600; font-size: 0.9rem;">
                            View Details <i class="fas fa-arrow-right" style="margin-left: 5px; font-size: 0.8rem;"></i>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>

{{-- Share Modal --}}
@include('components.share-modal', ['shareId' => 'Event'])

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const shareModal = document.getElementById('shareModalEvent');
        const shareButton = document.getElementById('eventShareButton');
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
                    const customMessage = document.getElementById('shareMessageEvent')?.value || '';
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

