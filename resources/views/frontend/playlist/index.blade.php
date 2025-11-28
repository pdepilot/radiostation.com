@extends('layouts.frontend', ['title' => 'Darling FM • Playlist'])

@section('content')
    <section class="container">
        <h2 class="section-title">Featured Rotation</h2>
        <div class="posts-grid">
            @foreach($featuredTracks as $track)
                <div class="post-card">
                    <div class="post-image" style="background-image: url('{{ $track->cover_image ?? asset('assets/images/logo1.jpg') }}')"></div>
                    <div class="post-content">
                        <h3 class="post-title">{{ $track->title }}</h3>
                        <p class="post-meta">{{ $track->artist }} • {{ $track->genre }}</p>
                        <p class="post-excerpt">Mood: {{ $track->mood }} • Duration: {{ $track->duration }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container">
        <h2 class="section-title">Full Log</h2>
        <div class="playlist-table">
            <table>
                <thead>
                <tr>
                    <th>Song</th>
                    <th>Artist</th>
                    <th>Genre</th>
                    <th>Scheduled</th>
                </tr>
                </thead>
                <tbody>
                @foreach($latestTracks as $track)
                    <tr>
                        <td>{{ $track->title }}</td>
                        <td>{{ $track->artist }}</td>
                        <td>{{ $track->genre }}</td>
                        <td>{{ optional($track->scheduled_for)->format('M d') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{ $latestTracks->links() }}
    </section>
@endsection

