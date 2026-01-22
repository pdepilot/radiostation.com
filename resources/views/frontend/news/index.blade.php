@extends('layouts.frontend', ['title' => 'Darling FM • News'])

@php
use Illuminate\Support\Str;
@endphp

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
</style>
@endpush

@section('content')
<section class="container" style="padding-top: 120px; padding-bottom: 80px;">
    <div style="text-align: center; margin-bottom: 60px; padding: 48px 0;">
        <h1 class="section-title" style="font-size: 3rem; margin-bottom: 0;">
            @if(!empty($searchQuery))
            SEARCH RESULTS: "{{ $searchQuery }}"
            @else
            LATEST NEWS & UPDATES
            @endif
        </h1>
    </div>

    @if(!empty($searchQuery))
    <div style="text-align: center; margin-bottom: 30px;">
        <a href="{{ route('news.index') }}" wire:navigate style="color: var(--accent); text-decoration: none; font-size: 0.9rem;">
            <i class="fas fa-arrow-left"></i> Back to all news
        </a>
    </div>
    @endif

    <!-- Top Banner Ad Slot -->
    <div class="ad-slot" style="background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 120px; display: flex; align-items: center; justify-content: center; margin-bottom: 40px; color: var(--text-secondary); font-size: 0.9rem;">
        <span>Advertisement</span>
    </div>

    <div class="posts-grid">
        @foreach($posts as $post)
        <a href="{{ route('news.show', $post->slug) }}" wire:navigate data-modal="news" data-id="{{ $post->id }}" data-slug="{{ $post->slug }}" style="text-decoration: none; color: inherit; display: block;">
            <div class="post-card" data-post-id="{{ $post->id }}">
                @php
                $imageUrl = $post->hero_image ?? asset('assets/images/darling studio.jpg');
                $fallbackImage = asset('assets/images/darling studio.jpg');
                @endphp
                <div class="post-image" style="background-image: url('{{ $imageUrl }}'), url('{{ $fallbackImage }}'); background-size: cover; background-position: center; height: 200px; width: 100%;" onerror="this.style.backgroundImage='url({{ $fallbackImage }})'"></div>
                <div class="post-content">
                    <div class="post-meta">
                        <span><i class="far fa-calendar"></i> {{ optional($post->published_at)->format('M d, Y') }}</span>
                    </div>
                    <h3 class="post-title">{{ $post->title }}</h3>
                    <p class="post-excerpt">{{ Str::limit($post->excerpt ?? '', 120) }}</p>
                </div>
            </div>
        </a>
        
        {{-- Interstitial Ad Slot - Show after every 3rd article --}}
        @if($loop->iteration % 3 === 0 && !$loop->last)
        <div class="ad-slot" style="grid-column: 1 / -1; background: var(--gray-800); border: 1px solid var(--glass-border); border-radius: 12px; height: 100px; display: flex; align-items: center; justify-content: center; margin: 20px 0; color: var(--text-secondary); font-size: 0.9rem;">
            <span>Advertisement</span>
        </div>
        @endif
        @endforeach
    </div>

    @if($posts->hasPages())
    <div class="pagination-wrapper" style="margin-top: 60px; display: flex; justify-content: center; align-items: center; gap: 10px; flex-wrap: wrap;">
        @if($posts->onFirstPage())
        <span style="padding: 10px 20px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--text-secondary); cursor: not-allowed;">
            <i class="fas fa-chevron-left"></i> Previous
        </span>
        @else
        <a href="{{ $posts->previousPageUrl() }}" style="padding: 10px 20px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'; this.style.borderColor='var(--accent)'" onmouseout="this.style.background='var(--glass)'; this.style.borderColor='var(--glass-border)'">
            <i class="fas fa-chevron-left"></i> Previous
        </a>
        @endif

        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
            @php
            $currentPage = $posts->currentPage();
            $lastPage = $posts->lastPage();
            $startPage = max(1, $currentPage - 2);
            $endPage = min($lastPage, $currentPage + 2);
            @endphp

            @if($startPage > 1)
            <a href="{{ $posts->url(1) }}" wire:navigate style="padding: 10px 15px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'; this.style.borderColor='var(--accent)'" onmouseout="this.style.background='var(--glass)'; this.style.borderColor='var(--glass-border)'">1</a>
            @if($startPage > 2)
            <span style="padding: 10px 5px; color: var(--text-secondary);">...</span>
            @endif
            @endif

            @for($page = $startPage; $page <= $endPage; $page++)
                @if($page==$currentPage)
                <span style="padding: 10px 15px; background: var(--accent); border: 1px solid var(--accent); border-radius: 8px; color: white; font-weight: 600;">{{ $page }}</span>
                @else
                <a href="{{ $posts->url($page) }}" wire:navigate style="padding: 10px 15px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'; this.style.borderColor='var(--accent)'" onmouseout="this.style.background='var(--glass)'; this.style.borderColor='var(--glass-border)'">{{ $page }}</a>
                @endif
                @endfor

                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                    <span style="padding: 10px 5px; color: var(--text-secondary);">...</span>
                    @endif
                    <a href="{{ $posts->url($lastPage) }}" wire:navigate style="padding: 10px 15px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'; this.style.borderColor='var(--accent)'" onmouseout="this.style.background='var(--glass)'; this.style.borderColor='var(--glass-border)'">{{ $lastPage }}</a>
                    @endif
        </div>

        @if($posts->hasMorePages())
        <a href="{{ $posts->nextPageUrl() }}" style="padding: 10px 20px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'; this.style.borderColor='var(--accent)'" onmouseout="this.style.background='var(--glass)'; this.style.borderColor='var(--glass-border)'">
            Next <i class="fas fa-chevron-right"></i>
        </a>
        @else
        <span style="padding: 10px 20px; background: rgba(255,255,255,0.05); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--text-secondary); cursor: not-allowed;">
            Next <i class="fas fa-chevron-right"></i>
        </span>
        @endif
    </div>
    @endif
</section>
@endsection