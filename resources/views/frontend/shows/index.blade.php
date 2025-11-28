@extends('layouts.frontend', ['title' => 'Darling FM • Show Schedule'])

@section('content')
    <section class="container">
        <h2 class="section-title">Show Schedule</h2>
        <div class="shows-grid">
            @foreach($shows as $show)
                <div class="show-card">
                    <div class="show-header">
                        <div class="show-avatar" style="background-image: url('{{ $show->hero_image ?? asset('assets/images/studio.jpg') }}')"></div>
                        <div class="show-info">
                            <div class="show-name">{{ $show->title }}</div>
                            <div class="show-title">{{ $show->dj?->stage_name ?? $show->dj?->name }}</div>
                        </div>
                    </div>
                    <div class="show-time">
                        <i class="far fa-clock"></i> {{ $show->day_of_week }} • {{ $show->start_time }} - {{ $show->end_time }}
                    </div>
                    <p>{{ Str::limit($show->description, 150) }}</p>
                    <a href="{{ route('shows.show', $show) }}" class="remind-btn">View</a>
                </div>
            @endforeach
        </div>
        {{ $shows->links() }}
    </section>
@endsection

