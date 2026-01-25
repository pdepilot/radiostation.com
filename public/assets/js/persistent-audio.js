/**
 * Persistent Audio Player for Darling FM
 * 
 * Features:
 * - Never stops on page reload or navigation
 * - Uses sessionStorage to remember play state
 * - Single global audio instance
 * - Auto-resume on page load
 * - Sticky mini-player at bottom
 */

(function() {
    'use strict';

    // =============================================
    // CONFIGURATION
    // =============================================
    const STREAM_URLS = {
        main: "https://phoebe.streamerr.co:7572/stream",
        backup: "https://phoebe.streamerr.co:7567/stream"
    };

    const STORAGE_KEY = 'darlingfm_audio_state';
    const STORAGE_KEY_URL = 'darlingfm_audio_url';

    // =============================================
    // GLOBAL AUDIO INSTANCE
    // =============================================
    let globalAudio = null;
    let currentStreamUrl = null;
    let isPlaying = false;
    let retryCount = 0;
    const MAX_RETRIES = 3;

    // =============================================
    // INITIALIZE GLOBAL AUDIO
    // =============================================
    function initGlobalAudio() {
        // Get or create audio element
        globalAudio = document.getElementById('globalAudioPlayer');
        
        if (!globalAudio) {
            // Create audio element if it doesn't exist
            globalAudio = document.createElement('audio');
            globalAudio.id = 'globalAudioPlayer';
            globalAudio.preload = 'none';
            globalAudio.style.display = 'none';
            document.body.appendChild(globalAudio);
        }

        // Configure audio
        globalAudio.crossOrigin = 'anonymous';
        
        // Event listeners
        globalAudio.addEventListener('play', handleAudioPlay);
        globalAudio.addEventListener('pause', handleAudioPause);
        globalAudio.addEventListener('ended', handleAudioEnded);
        globalAudio.addEventListener('error', handleAudioError);
        globalAudio.addEventListener('loadstart', handleAudioLoadStart);
        globalAudio.addEventListener('canplay', handleAudioCanPlay);
        globalAudio.addEventListener('waiting', handleAudioWaiting);
        globalAudio.addEventListener('playing', handleAudioPlaying);

        // Expose to window for other scripts
        window.DarlingFMAudio = {
            player: globalAudio,
            play: playStream,
            pause: pauseStream,
            toggle: togglePlayback,
            isPlaying: () => isPlaying,
            getCurrentUrl: () => currentStreamUrl
        };
    }

    // =============================================
    // SESSION STORAGE HELPERS
    // =============================================
    function savePlayState() {
        try {
            sessionStorage.setItem(STORAGE_KEY, isPlaying ? 'playing' : 'paused');
            if (currentStreamUrl) {
                sessionStorage.setItem(STORAGE_KEY_URL, currentStreamUrl);
            }
        } catch (e) {
            console.warn('Failed to save play state:', e);
        }
    }

    function loadPlayState() {
        try {
            const state = sessionStorage.getItem(STORAGE_KEY);
            const url = sessionStorage.getItem(STORAGE_KEY_URL);
            return {
                isPlaying: state === 'playing',
                url: url || STREAM_URLS.main
            };
        } catch (e) {
            console.warn('Failed to load play state:', e);
            return { isPlaying: false, url: STREAM_URLS.main };
        }
    }

    // =============================================
    // PLAYBACK CONTROL
    // =============================================
    function playStream(url = null) {
        if (!globalAudio) {
            initGlobalAudio();
        }

        const streamUrl = url || currentStreamUrl || STREAM_URLS.main;
        
        // If already playing the same stream, do nothing
        if (isPlaying && globalAudio.src === streamUrl && !globalAudio.paused) {
            return Promise.resolve();
        }

        // Set source if different
        if (globalAudio.src !== streamUrl) {
            globalAudio.src = streamUrl;
            currentStreamUrl = streamUrl;
        }

        // Play
        return globalAudio.play()
            .then(() => {
                isPlaying = true;
                retryCount = 0;
                savePlayState();
                updateUI();
            })
            .catch(error => {
                console.error('Playback error:', error);
                handleAudioError(error);
                throw error;
            });
    }

    function pauseStream() {
        if (!globalAudio) return;
        
        globalAudio.pause();
        isPlaying = false;
        savePlayState();
        updateUI();
    }

    function togglePlayback() {
        if (isPlaying && !globalAudio.paused) {
            pauseStream();
        } else {
            playStream();
        }
    }

    // =============================================
    // AUDIO EVENT HANDLERS
    // =============================================
    function handleAudioPlay() {
        isPlaying = true;
        savePlayState();
        updateUI();
    }

    function handleAudioPause() {
        isPlaying = false;
        savePlayState();
        updateUI();
    }

    function handleAudioEnded() {
        isPlaying = false;
        savePlayState();
        updateUI();
    }

    function handleAudioError(error) {
        console.error('Audio error:', error);
        isPlaying = false;
        updateUI('Error loading stream');
        
        // Retry with backup stream
        if (retryCount < MAX_RETRIES && currentStreamUrl === STREAM_URLS.main) {
            retryCount++;
            setTimeout(() => {
                playStream(STREAM_URLS.backup);
            }, 2000);
        } else {
            updateUI('Stream unavailable');
        }
    }

    function handleAudioLoadStart() {
        updateUI('Connecting...');
    }

    function handleAudioCanPlay() {
        updateUI('Live');
    }

    function handleAudioWaiting() {
        updateUI('Buffering...');
    }

    function handleAudioPlaying() {
        updateUI('Live');
        retryCount = 0;
    }

    // =============================================
    // UI UPDATES
    // =============================================
    function updateUI(status = null) {
        const player = document.getElementById('stickyPlayer');
        const playBtn = document.getElementById('stickyPlayBtn');
        const title = document.getElementById('stickyPlayerTitle');
        const statusEl = document.getElementById('stickyPlayerStatus');

        if (!player || !playBtn || !statusEl) return;

        // Show player
        player.style.display = 'flex';

        // Update play button
        const icon = playBtn.querySelector('i');
        if (isPlaying && globalAudio && !globalAudio.paused) {
            icon.className = 'fas fa-pause';
            playBtn.setAttribute('aria-label', 'Pause');
        } else {
            icon.className = 'fas fa-play';
            playBtn.setAttribute('aria-label', 'Play');
        }

        // Update status
        if (status) {
            statusEl.textContent = status;
        } else if (isPlaying && globalAudio && !globalAudio.paused) {
            statusEl.textContent = 'Live';
        } else {
            statusEl.textContent = 'Tap to play';
        }

        // Update title
        if (title) {
            title.textContent = 'Darling FM 107.3';
        }
    }

    // =============================================
    // RESTORE PLAYBACK ON PAGE LOAD
    // =============================================
    function restorePlayback() {
        const savedState = loadPlayState();
        
        if (savedState.isPlaying) {
            // Small delay to ensure DOM is ready
            setTimeout(() => {
                playStream(savedState.url).catch(error => {
                    console.warn('Failed to restore playback:', error);
                    updateUI('Tap to play');
                });
            }, 100);
        } else {
            updateUI('Tap to play');
        }
    }

    // =============================================
    // INITIALIZE ON DOM READY
    // =============================================
    function init() {
        // Initialize audio
        initGlobalAudio();

        // Setup sticky player controls
        const playBtn = document.getElementById('stickyPlayBtn');
        const expandBtn = document.getElementById('stickyExpandBtn');
        const homePlayBtn = document.getElementById('homePlayButton');

        if (playBtn) {
            playBtn.addEventListener('click', togglePlayback);
        }

        if (expandBtn) {
            expandBtn.addEventListener('click', () => {
                // Navigate to full player page using SPA navigation
                if (window.Livewire && window.Livewire.navigate) {
                    window.Livewire.navigate('/live-stream');
                } else {
                    window.location.href = '/live-stream';
                }
            });
        }

        // Sync with homepage play button
        if (homePlayBtn) {
            homePlayBtn.addEventListener('click', () => {
                togglePlayback();
            });
        }

        // Restore playback state
        restorePlayback();

        // Listen for page visibility changes (resume when tab becomes visible)
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden && isPlaying && globalAudio && globalAudio.paused) {
                // Try to resume if it was playing
                globalAudio.play().catch(() => {
                    // If resume fails, restart stream
                    playStream();
                });
            }
        });

        // Listen for beforeunload to ensure state is saved
        window.addEventListener('beforeunload', () => {
            savePlayState();
        });
    }

    // =============================================
    // STARTUP
    // =============================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // Also expose restore function for manual calls
    window.DarlingFMAudioRestore = restorePlayback;

})();

