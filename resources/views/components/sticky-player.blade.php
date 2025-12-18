{{-- Sticky Mini Player Component - HLS Live Stream --}}
<div id="stickyPlayer" class="sticky-player" style="display: none;">
    <div class="sticky-player-content">
        {{-- Play/Pause Button --}}
        <button id="stickyPlayBtn" class="sticky-player-btn" aria-label="Play/Pause" style="position: relative; z-index: 10;">
            <svg class="play-icon" style="width: 1.2rem; height: 1.2rem; fill: white; display: block;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 5v14l11-7z"/>
            </svg>
            <svg class="pause-icon" style="width: 1.2rem; height: 1.2rem; fill: white; display: none;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
            </svg>
        </button>
        
        {{-- Stream Info --}}
        <div class="sticky-player-info">
            <div class="sticky-player-title" id="stickyPlayerTitle">Darling FM Live</div>
            <div class="sticky-player-status" id="stickyPlayerStatus">Tap to play</div>
        </div>
    </div>
</div>

{{-- Hidden Video Element for HLS - Single Global Instance --}}
{{-- HLS.js will use this element for playback --}}
<video id="hlsLivePlayer" preload="auto" style="display: none;" crossorigin="anonymous"></video>
