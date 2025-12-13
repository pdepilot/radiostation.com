{{-- Sticky Mini Player Component - HLS Live Stream --}}
<div id="stickyPlayer" class="sticky-player" style="display: none;">
    <div class="sticky-player-content">
        {{-- Play/Pause Button --}}
        <button id="stickyPlayBtn" class="sticky-player-btn" aria-label="Play/Pause">
            <i class="fas fa-play"></i>
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
