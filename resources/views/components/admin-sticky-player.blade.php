{{-- Admin Panel Floating Player Button --}}
<div id="stickyPlayer" class="sticky-player admin-sticky-player" style="display: none;">
    <button id="stickyPlayBtn" class="sticky-player-btn admin-sticky-player-btn" aria-label="Play/Pause" title="Darling FM Live">
        <svg class="play-icon" style="width: 1.2rem; height: 1.2rem; fill: white; display: block;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 5v14l11-7z"/>
        </svg>
        <svg class="pause-icon" style="width: 1.2rem; height: 1.2rem; fill: white; display: none;" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
        </svg>
    </button>
    
    {{-- Tooltip on hover --}}
    <div class="admin-sticky-player-tooltip">
        <div class="sticky-player-title admin-sticky-player-title" id="stickyPlayerTitle">Darling FM Live</div>
        <div class="sticky-player-status admin-sticky-player-status" id="stickyPlayerStatus">Tap to play</div>
    </div>
</div>

{{-- Hidden Video Element for HLS - Single Global Instance --}}
<video id="hlsLivePlayer" preload="auto" style="display: none;" crossorigin="anonymous"></video>

<style>
/* Override sticky player styles for admin panel - make it a floating button */
.admin-sticky-player {
    position: fixed !important;
    bottom: 20px !important;
    right: 20px !important;
    left: auto !important;
    width: auto !important;
    min-height: auto !important;
    padding: 0 !important;
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    display: flex !important;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.admin-sticky-player.hidden {
    opacity: 0;
    pointer-events: none;
    transform: scale(0.8);
}

.admin-sticky-player .sticky-player-content {
    display: none !important;
}

.admin-sticky-player-btn {
    width: 56px !important;
    height: 56px !important;
    border-radius: 50% !important;
    background: var(--accent, #ff0000) !important;
    color: white !important;
    border: none !important;
    cursor: pointer;
    display: flex !important;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(255, 0, 0, 0.4);
    position: relative;
    z-index: 10000;
    flex-shrink: 0;
}

.admin-sticky-player-btn:hover {
    background: var(--accent-glow, #ff3333) !important;
    transform: scale(1.1);
    box-shadow: 0 6px 16px rgba(255, 0, 0, 0.6);
}

.admin-sticky-player-btn:active {
    transform: scale(0.95);
}

.admin-sticky-player-tooltip {
    position: absolute;
    bottom: calc(100% + 10px);
    right: 0;
    background: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 10px 15px;
    min-width: 180px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease, transform 0.3s ease;
    transform: translateY(5px);
    white-space: nowrap;
}

.admin-sticky-player:hover .admin-sticky-player-tooltip {
    opacity: 1;
    transform: translateY(0);
    pointer-events: auto;
}

.admin-sticky-player-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 0.9rem;
    font-weight: 600;
    color: white;
    margin-bottom: 4px;
}

.admin-sticky-player-status {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.7);
}

.admin-sticky-player-status.live {
    color: #00ff00;
    font-weight: 600;
}

@media (max-width: 768px) {
    .admin-sticky-player {
        bottom: 15px !important;
        right: 15px !important;
    }
    
    .admin-sticky-player-btn {
        width: 50px !important;
        height: 50px !important;
    }
    
    .admin-sticky-player-tooltip {
        min-width: 160px;
        padding: 8px 12px;
    }
}

/* Remove body padding for admin panel since we're using a floating button */
body.fi-body {
    padding-bottom: 0 !important;
}
</style>
