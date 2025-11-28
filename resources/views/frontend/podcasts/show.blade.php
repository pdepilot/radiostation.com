@extends('layouts.frontend', ['title' => $podcast->title . ' • Darling FM'])

@section('content')
    <section class="container">
        <div class="podcast-detail">
            <div class="podcast-cover">
                <img src="{{ $podcast->cover_image ?? asset('assets/images/logo1.jpg') }}" alt="{{ $podcast->title }}">
            </div>
            <div class="podcast-body">
                <p class="post-meta">{{ optional($podcast->published_at)->format('M d, Y') }} • {{ $podcast->duration }}</p>
                <h1>{{ $podcast->title }}</h1>
                <p class="post-meta">Host: {{ $podcast->host }}</p>
                <article>{!! nl2br(e($podcast->description)) !!}</article>
                @if($podcast->audio_url)
                    <audio controls style="width:100%;">
                        <source src="{{ $podcast->audio_url }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                @endif
            </div>
        </div>
    </section>

    <section class="container">
        <h3 class="section-title">More Episodes</h3>
        <div class="posts-grid">
            @foreach($recommendations as $episode)
                <div class="post-card">
                    <h4 class="post-title">{{ $episode->title }}</h4>
                    <p class="post-meta">{{ optional($episode->published_at)->format('M d') }}</p>
                    <a href="{{ route('podcasts.show', $episode) }}" class="action-btn">Play</a>
                </div>
            @endforeach
        </div>
    </section>
@endsection

