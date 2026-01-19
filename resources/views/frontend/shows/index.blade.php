@extends('layouts.frontend', ['title' => 'Darling FM • Show Schedule'])

@push('styles')
<style>
    .shows-page {
        min-height: 100vh;
    }

    .shows-simple-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
        padding: 0 20px;
        margin-bottom: 60px;
    }

    .show-simple-card {
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

    .show-simple-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(255, 0, 0, 0.2);
    }

    .show-simple-image {
        height: 200px;
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .show-simple-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        background: var(--accent);
        color: white;
    }

    .show-simple-content {
        padding: 25px;
    }

    .show-simple-title {
        font-family: 'Orbitron', sans-serif;
        color: var(--accent);
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .show-simple-host {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .show-simple-description {
        color: var(--light);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .show-simple-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px solid var(--glass-border);
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .show-simple-schedule {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    @media (max-width: 768px) {
        .shows-simple-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="shows-page">
    <div class="container" style="padding-top: 120px; padding-bottom: 80px;">
        <div style="text-align: center; margin-bottom: 60px; padding: 48px 0;">
            <h1 class="section-title" style="font-size: 3rem; margin-bottom: 0;">DISCOVER OUR SHOWS</h1>
        </div>

        <!-- Top Banner Ad Slot -->
        <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; color: var(--text-secondary); font-size: 0.9rem; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--glass-border)'">
            <span>Advertisement</span>
        </div>

        @if($shows->count() > 0)
        <div class="shows-simple-grid">
            @foreach($shows as $show)
            <a href="{{ route('shows.show', $show) }}" data-modal="show" data-id="{{ $show->id }}" data-slug="{{ $show->slug }}" class="show-simple-card">
                @php
                $imageUrl = $show->hero_image_url;
                @endphp
                <div class="show-simple-image" style="background-image: url('{{ $imageUrl }}')">
                    @if($show->is_featured ?? false)
                    <div class="show-simple-badge">FEATURED</div>
                    @elseif($loop->first)
                    <div class="show-simple-badge" style="background: #00ff00; color: #000;">LIVE</div>
                    @endif
                </div>
                <div class="show-simple-content">
                    <h3 class="show-simple-title">{{ $show->title }}</h3>
                    <div class="show-simple-host">
                        <i class="fas fa-microphone"></i>
                        <span>{{ $show->dj?->stage_name ?? $show->dj?->name ?? 'TBA' }}</span>
                    </div>
                    <p class="show-simple-description">{{ $show->description ?? 'Amazing radio show experience.' }}</p>
                    <div class="show-simple-meta">
                        <div class="show-simple-schedule">
                            <i class="far fa-calendar"></i>
                            <span>{{ $show->day_of_week }}</span>
                        </div>
                        <div>
                            <i class="far fa-clock"></i>
                            <span>{{ \Carbon\Carbon::parse($show->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($show->end_time)->format('g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div style="padding: 0 20px; margin-bottom: 60px;">
            {{ $shows->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 60px 20px;">
            <p style="color: var(--text-secondary); font-size: 1.1rem;">No shows scheduled yet.</p>
        </div>
        @endif
    </div>
</div>
@endsection