@extends('layouts.frontend', ['title' => 'Darling FM • On Air Personalities'])

@section('content')
    <section class="container">
        <h2 class="section-title">Meet the OAPs</h2>
        <div class="posts-grid">
            @foreach($djs as $dj)
                <div class="post-card">
                    <div class="post-image" style="background-image: url('{{ $dj->avatar_url ?? asset('assets/images/face.jpg') }}')"></div>
                    <div class="post-content">
                        <h3 class="post-title">{{ $dj->stage_name ?? $dj->name }}</h3>
                        <p class="post-meta">{{ $dj->specialty }}</p>
                        <p class="post-excerpt">{{ Str::limit($dj->bio, 160) }}</p>
                        <div class="post-actions">
                            @if($dj->instagram)<a class="action-btn" target="_blank" href="{{ $dj->instagram }}"><i class="fab fa-instagram"></i></a>@endif
                            @if($dj->twitter)<a class="action-btn" target="_blank" href="{{ $dj->twitter }}"><i class="fab fa-twitter"></i></a>@endif
                            @if($dj->mixcloud)<a class="action-btn" target="_blank" href="{{ $dj->mixcloud }}"><i class="fab fa-soundcloud"></i></a>@endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $djs->links() }}
    </section>
@endsection

