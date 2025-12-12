@extends('layouts.frontend', ['title' => 'Darling FM • News'])

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <section class="container" style="padding-top: 120px;">
        <h2 class="section-title" style="text-align: center; margin-bottom: 50px;">LATEST NEWS & UPDATES</h2>

        <div class="posts-grid">
            @foreach($posts as $post)
                <a href="{{ route('news.show', $post->slug) }}" style="text-decoration: none; color: inherit; display: block;">
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
                    <a href="{{ $posts->url(1) }}" style="padding: 10px 15px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'; this.style.borderColor='var(--accent)'" onmouseout="this.style.background='var(--glass)'; this.style.borderColor='var(--glass-border)'">1</a>
                    @if($startPage > 2)
                        <span style="padding: 10px 5px; color: var(--text-secondary);">...</span>
                    @endif
                @endif
                
                @for($page = $startPage; $page <= $endPage; $page++)
                    @if($page == $currentPage)
                        <span style="padding: 10px 15px; background: var(--accent); border: 1px solid var(--accent); border-radius: 8px; color: white; font-weight: 600;">{{ $page }}</span>
                    @else
                        <a href="{{ $posts->url($page) }}" style="padding: 10px 15px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'; this.style.borderColor='var(--accent)'" onmouseout="this.style.background='var(--glass)'; this.style.borderColor='var(--glass-border)'">{{ $page }}</a>
                    @endif
                @endfor
                
                @if($endPage < $lastPage)
                    @if($endPage < $lastPage - 1)
                        <span style="padding: 10px 5px; color: var(--text-secondary);">...</span>
                    @endif
                    <a href="{{ $posts->url($lastPage) }}" style="padding: 10px 15px; background: var(--glass); border: 1px solid var(--glass-border); border-radius: 8px; color: var(--light); text-decoration: none; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,0,0,0.1)'; this.style.borderColor='var(--accent)'" onmouseout="this.style.background='var(--glass)'; this.style.borderColor='var(--glass-border)'">{{ $lastPage }}</a>
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
