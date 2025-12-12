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
                    <h1 style="font-family: 'Orbitron', sans-serif; font-size: 3rem; margin-bottom: 15px; font-weight: 700;">{{ $event->title }}</h1>
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

        <!-- Event Content -->
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; margin-bottom: 60px;">
            <div>
                <h2 style="font-family: 'Orbitron', sans-serif; color: var(--accent); margin-bottom: 20px; font-size: 1.8rem;">About This Event</h2>
                @if($event->description)
                <div style="color: var(--light); line-height: 1.8; font-size: 1.05rem; margin-bottom: 30px;">
                    {!! nl2br(e($event->description)) !!}
                </div>
                @else
                <p style="color: var(--text-secondary); font-size: 1.05rem;">More details coming soon...</p>
                @endif

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
            <h2 style="font-family: 'Orbitron', sans-serif; color: var(--accent); margin-bottom: 30px; font-size: 2rem; text-align: center;">Other Events</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
                @foreach($related as $relatedEvent)
                <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; overflow: hidden; border: 1px solid var(--glass-border); transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'" onclick="window.location.href='{{ route('events.show', $relatedEvent->slug) }}'">
                    <div style="height: 200px; background-image: url('{{ $relatedEvent->hero_image ?? asset('assets/images/studio.jpg') }}'); background-size: cover; background-position: center;"></div>
                    <div style="padding: 20px;">
                        <h4 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin-bottom: 10px; font-size: 1.2rem;">{{ $relatedEvent->title }}</h4>
                        <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 15px;">
                            <i class="far fa-calendar-alt" style="margin-right: 5px;"></i>{{ $relatedEvent->event_date->format('M d, Y') }}
                        </div>
                        <a href="{{ route('events.show', $relatedEvent->slug) }}" style="color: var(--accent); font-weight: 600; text-decoration: none; font-size: 0.9rem;">
                            View Details <i class="fas fa-arrow-right" style="margin-left: 5px; font-size: 0.8rem;"></i>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif
    </div>
</div>
@endsection

