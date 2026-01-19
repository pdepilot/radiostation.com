@extends('layouts.frontend', ['title' => ($dj->stage_name ?? $dj->name) . ' • Darling FM'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/djs.css') }}">
@endpush

@section('content')
    <div class="main-content" style="padding-top: 120px;">
        <div class="container" style="max-width: 900px;">
            <!-- OAP Profile Header - Inspired by thebeat99.com -->
            <div style="text-align: center; margin-bottom: 60px;">
                <div style="width: 200px; height: 200px; border-radius: 50%; background-image: url('{{ $dj->avatar_url ?? asset('assets/images/face.jpg') }}'); background-size: cover; background-position: center; margin: 0 auto 30px; border: 3px solid var(--accent); box-shadow: 0 0 40px rgba(255, 0, 0, 0.3);"></div>
                <h1 class="section-title" style="text-align: center; margin-bottom: 15px; font-size: 3rem; text-transform: none; letter-spacing: normal;">{{ strtoupper($dj->stage_name ?? $dj->name) }}</h1>
                @if($dj->specialty)
                    <p style="color: var(--light); font-size: 1.3rem; margin-bottom: 30px; font-weight: 300;">{{ $dj->specialty }}</p>
                @endif
            </div>

            <!-- About Section -->
            <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; padding: 50px; margin-bottom: 40px; border: 1px solid var(--glass-border); text-align: left;">
                <p style="color: var(--light); line-height: 2; font-size: 1.1rem; margin: 0;">{{ $dj->bio ?? 'On-air personality that keeps Owerri moving with amazing content and great music. Known for engaging shows and connecting with listeners.' }}</p>
            </div>

            <!-- Social Handles -->
            @if($dj->instagram || $dj->twitter || $dj->facebook || $dj->mixcloud || $dj->youtube || $dj->spotify)
            <div style="display: flex; justify-content: center; gap: 20px; margin-bottom: 40px; flex-wrap: wrap;">
                @if($dj->instagram)
                    <a href="{{ $dj->instagram }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; background: linear-gradient(45deg, #E4405F, #F77737); color: white; border-radius: 30px; text-decoration: none; font-weight: 600; transition: transform 0.2s; font-size: 1rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                @endif
                @if($dj->twitter)
                    <a href="{{ $dj->twitter }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; background: #1DA1F2; color: white; border-radius: 30px; text-decoration: none; font-weight: 600; transition: transform 0.2s; font-size: 1rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                @endif
                @if($dj->facebook)
                    <a href="{{ $dj->facebook }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; background: #1877F2; color: white; border-radius: 30px; text-decoration: none; font-weight: 600; transition: transform 0.2s; font-size: 1rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                @endif
                @if($dj->mixcloud)
                    <a href="{{ $dj->mixcloud }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; background: #52AAD8; color: white; border-radius: 30px; text-decoration: none; font-weight: 600; transition: transform 0.2s; font-size: 1rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-mixcloud"></i> Mixcloud
                    </a>
                @endif
                @if($dj->youtube)
                    <a href="{{ $dj->youtube }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; background: #c8102e; color: white; border-radius: 30px; text-decoration: none; font-weight: 600; transition: transform 0.2s; font-size: 1rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-youtube"></i> YouTube
                    </a>
                @endif
                @if($dj->spotify)
                    <a href="{{ $dj->spotify }}" target="_blank" style="display: inline-flex; align-items: center; gap: 10px; padding: 12px 24px; background: #1DB954; color: white; border-radius: 30px; text-decoration: none; font-weight: 600; transition: transform 0.2s; font-size: 1rem;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fab fa-spotify"></i> Spotify
                    </a>
                @endif
            </div>
            @endif

            <!-- Related DJs -->
            @if($related->count() > 0)
                <div style="margin-top: 60px; padding-top: 40px; border-top: 1px solid var(--glass-border);">
                    <h2 class="section-title" style="margin-bottom: 30px; font-size: 2rem;">PRESENTERS</h2>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 30px;">
                        @foreach($related as $relatedDj)
                            <a href="{{ route('djs.show', $relatedDj) }}" style="text-decoration: none; color: inherit;">
                                <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 15px; padding: 20px; border: 1px solid var(--glass-border); text-align: center; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <div style="width: 120px; height: 120px; border-radius: 50%; background-image: url('{{ $relatedDj->avatar_url ?? asset('assets/images/face.jpg') }}'); background-size: cover; background-position: center; margin: 0 auto 15px; border: 3px solid var(--accent);"></div>
                                    <h3 style="color: var(--accent); margin-bottom: 5px; font-size: 1rem;">{{ strtoupper($relatedDj->stage_name ?? $relatedDj->name) }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
