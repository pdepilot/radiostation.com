@extends('layouts.frontend', ['title' => 'Darling FM • Live Stream'])

@section('content')
    <section class="live-stream-section container">
        <h2 class="section-title">Live Control Room</h2>
        @if($liveStream)
            <div class="stream-card">
                <div>
                    <p class="live-pill {{ $liveStream->status === 'live' ? 'active' : '' }}">
                        <span class="live-dot"></span> {{ strtoupper($liveStream->status) }}
                    </p>
                    <h3>{{ $liveStream->title }}</h3>
                    <p>{{ $liveStream->description }}</p>
                    <p><strong>Current Show:</strong> {{ $liveStream->show?->title }}</p>
                    <p><strong>Listeners:</strong> {{ $liveStream->listener_count }}</p>
                    <div class="stream-buttons">
                        @if($liveStream->stream_url)
                            <a class="listen-btn" target="_blank" href="{{ $liveStream->stream_url }}">
                                Listen via Web Player
                            </a>
                        @endif
                        <a class="stream-btn secondary" href="{{ route('contact.index') }}">Send Studio Message</a>
                    </div>
                </div>
                <div class="dj-profile">
                    <img src="{{ $liveStream->dj?->avatar_url ?? asset('assets/images/face.jpg') }}" alt="Current DJ">
                    <h4>{{ $liveStream->dj?->stage_name ?? $liveStream->dj?->name }}</h4>
                    <p>{{ $liveStream->show?->tagline }}</p>
                </div>
            </div>
        @else
            <p>No stream is currently configured.</p>
        @endif
    </section>

    <section class="container">
        <h2 class="section-title">Show Lineup</h2>
        <div class="schedule-table">
            <table>
                <thead>
                    <tr>
                        <th>Show</th>
                        <th>Host</th>
                        <th>Day</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shows as $show)
                        <tr>
                            <td><a href="{{ route('shows.show', $show) }}">{{ $show->title }}</a></td>
                            <td>{{ $show->dj?->stage_name ?? $show->dj?->name }}</td>
                            <td>{{ $show->day_of_week }}</td>
                            <td>{{ $show->start_time }} - {{ $show->end_time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    <section class="container">
        <h2 class="section-title">Recently Ended Streams</h2>
        <div class="posts-grid">
            @foreach($history as $session)
                <div class="post-card">
                    <div class="post-content">
                        <p class="post-meta">{{ optional($session->ended_at)->diffForHumans() ?? 'In progress' }}</p>
                        <h3 class="post-title">{{ $session->title }}</h3>
                        <p class="post-excerpt">{{ $session->description }}</p>
                        <p><strong>Listeners:</strong> {{ $session->listener_count }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection

