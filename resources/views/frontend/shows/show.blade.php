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
                    <h1>{{ $show->title }}</h1>
                    @if($show->tagline)
                        <p class="show-tagline">{{ $show->tagline }}</p>
                    @endif
                    <div class="show-schedule">
                        <i class="far fa-calendar"></i>
                        <span>{{ $show->day_of_week }} • {{ \Carbon\Carbon::parse($show->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($show->end_time)->format('g:i A') }}</span>
                    </div>
                </div>
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
@endsection
