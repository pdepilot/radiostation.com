@extends('layouts.frontend', ['title' => $show->title . ' • Darling FM'])

@section('content')
    <section class="container">
        <div class="show-detail">
            <div class="show-hero" style="background-image: url('{{ $show->hero_image ?? asset('assets/images/darling studio.jpg') }}')">
                <div class="overlay">
                    <h1>{{ $show->title }}</h1>
                    <p>{{ $show->tagline }}</p>
                    <p>{{ $show->day_of_week }} • {{ $show->start_time }} - {{ $show->end_time }}</p>
                </div>
            </div>
            <div class="show-body">
                <article>{!! nl2br(e($show->description)) !!}</article>
                <div class="show-meta">
                    <h3>Hosted by {{ $show->dj?->stage_name ?? $show->dj?->name }}</h3>
                    <p>{{ $show->dj?->bio }}</p>
                    <a href="{{ route('djs.index') }}" class="action-btn">View more OAPs</a>
                </div>
            </div>
        </div>

        <h3>Related Shows</h3>
        <div class="shows-grid">
            @foreach($related as $item)
                <div class="show-card">
                    <h4>{{ $item->title }}</h4>
                    <p>{{ Str::limit($item->description, 90) }}</p>
                    <a href="{{ route('shows.show', $item) }}">Explore</a>
                </div>
            @endforeach
        </div>
    </section>
@endsection

