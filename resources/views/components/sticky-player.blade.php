{{-- Sticky Mini Player Component - HLS Live Stream --}}
<div id="stickyPlayer" class="sticky-player" style="display: none;">
    <div class="sticky-player-content">
        {{-- Play/Pause Button --}}
        <button id="stickyPlayBtn" class="sticky-player-btn" aria-label="Play/Pause">
            <i class="fas fa-play"></i>
        </button>
        
        {{-- Stream Info --}}
        <div class="sticky-player-info">
            <div class="sticky-player-title" id="stickyPlayerTitle">Darling FM 107.3</div>
            <div class="sticky-player-status" id="stickyPlayerStatus">Tap to play</div>
        </div>
        
        {{-- Expand Button (optional - opens full player) --}}
        <button id="stickyExpandBtn" class="sticky-player-expand" aria-label="Expand player">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>
</div>

{{-- Hidden Video Element for HLS - Single Global Instance --}}
{{-- HLS.js will use this element for playback - persists across page navigations --}}
<video id="hlsLivePlayer" preload="auto" style="display: none;" crossorigin="anonymous" data-persistent="true"></video>
