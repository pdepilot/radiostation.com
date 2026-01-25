@extends('layouts.frontend', ['title' => 'Events • Darling FM'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/index.css') }}">
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
</style>
@endpush

@section('content')
<div class="main-content" style="padding-top: 120px; padding-bottom: 80px;">
    <div class="container">
        <!-- Page Header -->
        <div style="text-align: center; margin-bottom: 60px; padding: 48px 0;">
            <h1 class="section-title" style="font-size: 3rem; margin-bottom: 0;">EVENTS</h1>
        </div>

        <!-- Top Banner Ad Slot -->
        <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; color: var(--text-secondary); font-size: 0.9rem;">
            <span>Advertisement</span>
        </div>

        <!-- Upcoming Events -->
        @if($upcomingEvents->count() > 0)
        <section style="margin-bottom: 80px;">
            <h2 class="section-title">UPCOMING EVENTS</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
                @foreach($upcomingEvents as $event)
                <a href="{{ route('events.show', $event->slug) }}" wire:navigate data-modal="event" data-id="{{ $event->id }}" data-slug="{{ $event->slug }}" class="event-card" style="background: var(--glass); backdrop-filter: blur(15px); border-radius: 20px; overflow: hidden; border: 1px solid var(--glass-border); transition: all 0.4s ease; cursor: pointer; text-decoration: none; color: inherit; display: block;" onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 40px rgba(255,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                    <div class="event-image" style="height: 250px; background-image: url('{{ $event->hero_image ?? asset('assets/images/studio.jpg') }}'); background-size: cover; background-position: center; position: relative;">
                        @if($event->is_featured)
                        <div style="position: absolute; top: 15px; right: 15px; background: var(--accent); color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; font-family: 'Orbitron', sans-serif; text-transform: uppercase; letter-spacing: 1px;">Featured</div>
                        @endif
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(transparent, rgba(0,0,0,0.8)); padding: 20px;">
                            <div style="color: white; font-size: 0.9rem; font-weight: 600;">
                                <i class="far fa-calendar-alt" style="margin-right: 8px;"></i>
                                {{ $event->event_date->format('F d, Y') }}
                            </div>
                            @if($event->event_date->format('H:i') !== '00:00')
                            <div style="color: rgba(255,255,255,0.9); font-size: 0.85rem; margin-top: 5px;">
                                <i class="far fa-clock" style="margin-right: 8px;"></i>
                                {{ $event->event_date->format('g:i A') }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="event-content" style="padding: 25px;">
                        <h3 style="color: var(--accent); font-family: 'Orbitron', sans-serif; font-size: 1.4rem; margin-bottom: 12px; font-weight: 700;">{{ $event->title }}</h3>
                        @if($event->venue)
                        <div style="color: var(--text-secondary); font-size: 0.95rem; margin-bottom: 10px;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 8px; color: var(--accent);"></i>
                            {{ $event->venue }}
                        </div>
                        @endif
                        @if($event->location)
                        <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 15px;">
                            {{ $event->location }}
                        </div>
                        @endif
                        @if($event->description)
                        <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.6; margin-bottom: 20px;">{{ Str::limit($event->description, 120) }}</p>
                        @endif
                        <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 15px; border-top: 1px solid var(--glass-border);">
                            <a href="{{ route('events.show', $event->slug) }}" wire:navigate style="color: var(--accent); font-weight: 600; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;" onmouseover="this.style.color='var(--accent-glow)'" onmouseout="this.style.color='var(--accent)'">
                                View Details <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
                            </a>
                            @if($event->ticket_url)
                            <a href="{{ $event->ticket_url }}" target="_blank" style="background: var(--accent); color: white; padding: 8px 20px; border-radius: 20px; text-decoration: none; font-weight: 600; font-size: 0.85rem; transition: all 0.3s;" onmouseover="this.style.background='var(--accent-glow)'" onmouseout="this.style.background='var(--accent)'">
                                Get Tickets
                            </a>
                            @endif
                        </div>
                    </div>
            </div>
            @endforeach
            
            {{-- Interstitial Ad Slot - Show after 3rd event --}}
            @if($upcomingEvents->count() > 3)
            <div class="ad-slot" style="grid-column: 1 / -1; background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 30px 0; color: var(--text-secondary); font-size: 0.9rem;">
                <span>Advertisement</span>
            </div>
            @endif
    </div>
    </section>
    @else
    <div style="text-align: center; padding: 80px 20px; background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; border: 1px solid var(--glass-border); margin-bottom: 80px;">
        <i class="fas fa-calendar-times" style="font-size: 4rem; color: var(--text-secondary); opacity: 0.5; margin-bottom: 20px;"></i>
        <h3 style="color: var(--light); font-size: 1.5rem; margin-bottom: 10px; font-family: 'Orbitron', sans-serif;">No Upcoming Events</h3>
        <p style="color: var(--text-secondary); font-size: 1.1rem;">Check back soon for exciting events and special broadcasts!</p>
    </div>
    @endif

    <!-- Past Events -->
    @if($pastEvents->count() > 0)
    <section>
        <h2 class="section-title" style="opacity: 0.8;">PAST EVENTS</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
            @foreach($pastEvents->take(6) as $event)
            <div class="past-event-card" style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 20px; border: 1px solid var(--glass-border); opacity: 0.7; transition: all 0.3s ease;" onmouseover="this.style.opacity='1'; this.style.transform='translateY(-5px)'" onmouseout="this.style.opacity='0.7'; this.style.transform='translateY(0)'">
                <div style="display: flex; align-items: start; gap: 15px;">
                    <div style="min-width: 80px; text-align: center; padding: 10px; background: rgba(255,0,0,0.1); border-radius: 10px; border: 1px solid var(--accent);">
                        <div style="color: var(--accent); font-weight: 700; font-size: 1.2rem; font-family: 'Orbitron', sans-serif;">{{ $event->event_date->format('M') }}</div>
                        <div style="color: var(--light); font-weight: 700; font-size: 1.8rem;">{{ $event->event_date->format('d') }}</div>
                        <div style="color: var(--text-secondary); font-size: 0.8rem;">{{ $event->event_date->format('Y') }}</div>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="color: var(--light); font-size: 1.1rem; margin-bottom: 8px; font-family: 'Orbitron', sans-serif;">{{ $event->title }}</h4>
                        @if($event->venue)
                        <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 10px;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 5px;"></i>{{ $event->venue }}
                        </p>
                        @endif
                        <a href="{{ route('events.show', $event->slug) }}" wire:navigate style="color: var(--accent); font-size: 0.85rem; text-decoration: none; font-weight: 600;">
                            View Details <i class="fas fa-arrow-right" style="margin-left: 5px; font-size: 0.7rem;"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
</div>
@endsection