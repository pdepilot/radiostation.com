// Global Persistent Audio Player for Darling FM
// This player persists across page navigation using Livewire's @persist directive

(function() {
    'use strict';
    
    // Initialize or get existing global audio player instance
    if (!window.DarlingFMAudio) {
        window.DarlingFMAudio = {
            player: null,
            isPlaying: false,
            currentStream: 'main',
            streamUrls: {
                // Primary/backup ordering: main=7572, backup=7567; oap is an alias to main for legacy UI
                main: "https://phoebe.streamerr.co:7572/stream",
                oap: "https://phoebe.streamerr.co:7572/stream",
                backup: "https://phoebe.streamerr.co:7567/stream"
            },
            listeners: {
                play: [],
                pause: [],
                streamChange: []
            }
        };
    }
    
    // Save state to localStorage
    function saveState() {
        localStorage.setItem('darlingfm_audio_state', JSON.stringify({
            stream: window.DarlingFMAudio.currentStream,
            isPlaying: window.DarlingFMAudio.isPlaying
        }));
    }
    
    // Update UI across all pages when audio state changes
    function updateUI() {
        window.DarlingFMAudio.listeners.play.forEach(cb => cb(window.DarlingFMAudio.isPlaying));
        window.DarlingFMAudio.listeners.streamChange.forEach(cb => cb(window.DarlingFMAudio.currentStream));
    }
    
    // Track listener activity (start/stop listening)
    function trackListenerActivity(action) {
        if (!window.DarlingFMAudio || !window.DarlingFMAudio.player || !window.DarlingFMAudio.player.src) return;

        // Get or create session ID
        function getSessionId() {
            let sessionId = sessionStorage.getItem('darlingfm_listener_session_id');
            if (!sessionId) {
                sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
                sessionStorage.setItem('darlingfm_listener_session_id', sessionId);
            }
            return sessionId;
        }

        const sessionId = getSessionId();

        fetch('/api/listener/track', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
            },
            body: JSON.stringify({
                action: action,
                session_id: sessionId,
                stream_url: window.DarlingFMAudio.player.src,
                user_id: window.authUser?.id || null
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Listener ${action} tracked. Current count: ${data.count}`);
            } else {
                console.warn('Failed to track listener activity:', data.message);
            }
        })
        .catch(error => {
            console.error('Listener tracking error:', error);
        });
    }
    
    // Function to initialize player using persisted element
    function initializePlayer() {
        // Use the persisted audio element from layout instead of creating new one
        const persistedAudio = document.getElementById('main-radio-player');
        if (persistedAudio && !window.DarlingFMAudio.player) {
            window.DarlingFMAudio.player = persistedAudio;
            // Ensure attributes are set
            if (!window.DarlingFMAudio.player.hasAttribute('preload')) {
                window.DarlingFMAudio.player.preload = 'none';
            }
            if (!window.DarlingFMAudio.player.hasAttribute('crossorigin')) {
                window.DarlingFMAudio.player.crossOrigin = 'anonymous';
            }
        } else if (!persistedAudio) {
            // Fallback: create new audio element only if persisted one doesn't exist
            // This should not happen in normal SPA flow, but provides safety
            console.warn('Persisted audio element not found, creating fallback');
            window.DarlingFMAudio.player = new Audio();
            window.DarlingFMAudio.player.preload = 'none';
            window.DarlingFMAudio.player.crossOrigin = 'anonymous';
        } else if (persistedAudio && window.DarlingFMAudio.player !== persistedAudio) {
            // If we have a different player instance, switch to persisted one
            const wasPlaying = window.DarlingFMAudio.isPlaying;
            const currentSrc = window.DarlingFMAudio.player.src;
            window.DarlingFMAudio.player = persistedAudio;
            if (currentSrc) {
                persistedAudio.src = currentSrc;
            }
            if (wasPlaying && currentSrc) {
                persistedAudio.play().catch(() => {
                    window.DarlingFMAudio.isPlaying = false;
                    saveState();
                });
            }
        }
    }
    
    // Setup event listeners (only once)
    function attachEventListeners() {
        if (!window.DarlingFMAudio.player || window.DarlingFMAudio._listenersAttached) {
            return;
        }
        
        window.DarlingFMAudio._listenersAttached = true;
        
        // Play/pause handlers with listener tracking
        window.DarlingFMAudio.player.addEventListener('play', function() {
            window.DarlingFMAudio.isPlaying = true;
            saveState();
            updateUI();
            // Track listener start
            trackListenerActivity('start');
        });

        window.DarlingFMAudio.player.addEventListener('pause', function() {
            // Don't update state if we're navigating (preserve playing state)
            const isNavigating = document.visibilityState === 'hidden' || (typeof Livewire !== 'undefined' && Livewire.hook && Livewire.hook('navigate'));
            if (isNavigating || document.visibilityState === 'hidden') {
                return; // Preserve playing state during navigation
            }
            window.DarlingFMAudio.isPlaying = false;
            saveState();
            updateUI();
            // Track listener stop
            trackListenerActivity('stop');
        });
        
        // Error handling with auto fallback to backup if main fails
        window.DarlingFMAudio.player.addEventListener('error', function(e) {
            console.error('Audio player error:', e);
            if (window.DarlingFMAudio.currentStream !== 'backup' && window.DarlingFMAudio.streamUrls.backup) {
                window.DarlingFMAudio.switchStream('backup')
                    .then(() => window.DarlingFMAudio.play())
                    .catch(err => console.error('Backup stream also failed:', err));
            } else {
                window.DarlingFMAudio.isPlaying = false;
                saveState();
                updateUI();
            }
        });
    }
    
    // Restore state and auto-resume
    function restoreState() {
        if (!window.DarlingFMAudio.player) {
            return;
        }
        
        const savedState = localStorage.getItem('darlingfm_audio_state');
        if (savedState) {
            try {
                const state = JSON.parse(savedState);
                window.DarlingFMAudio.currentStream = state.stream || 'main';
                const wasPlaying = state.isPlaying || false;
                
                if (wasPlaying) {
                    window.DarlingFMAudio.player.src = window.DarlingFMAudio.streamUrls[window.DarlingFMAudio.currentStream];
                    // Auto-resume if was playing (respects browser autoplay policies)
                    window.DarlingFMAudio.player.play().then(() => {
                        window.DarlingFMAudio.isPlaying = true;
                        saveState();
                        updateUI();
                    }).catch(() => {
                        // Autoplay blocked, will need user interaction
                        window.DarlingFMAudio.isPlaying = false;
                        saveState();
                    });
                }
            } catch(e) {
                console.error('Failed to restore audio state:', e);
            }
        }
    }
    
    // Complete initialization: get player, attach listeners, restore state
    function completeInitialization() {
        initializePlayer();
        attachEventListeners();
        restoreState();
    }
    
    // Expose methods (define once, outside initialization)
    window.DarlingFMAudio.play = function() {
        if (!window.DarlingFMAudio.player) {
            console.warn('Audio player not initialized');
            return Promise.reject(new Error('Audio player not initialized'));
        }
        
        const tryPlay = (streamKey) => {
            const src = window.DarlingFMAudio.streamUrls[streamKey] || window.DarlingFMAudio.streamUrls.main;
            if (!src) return Promise.reject(new Error('No stream URL configured'));
            window.DarlingFMAudio.currentStream = streamKey;
            window.DarlingFMAudio.player.src = src;
            return window.DarlingFMAudio.player.play().then(() => {
                window.DarlingFMAudio.isPlaying = true;
                saveState();
                updateUI();
            });
        };

        console.log('Global audio play called for stream:', window.DarlingFMAudio.currentStream);
        return tryPlay(window.DarlingFMAudio.currentStream)
            .then(() => {
                console.log('Global audio play successful');
            })
            .catch(err => {
                // Auto-fallback to backup if primary fails
                if (window.DarlingFMAudio.currentStream !== 'backup' && window.DarlingFMAudio.streamUrls.backup) {
                    console.warn('Primary stream failed, switching to backup:', err?.message || err);
                    return tryPlay('backup');
                }
                console.error('Play error:', err);
                throw err;
            });
    };
    
    window.DarlingFMAudio.pause = function() {
        console.log('Global audio pause called');
        if (window.DarlingFMAudio.player) {
            window.DarlingFMAudio.player.pause();
            console.log('HTML5 audio element paused');
        }
        window.DarlingFMAudio.isPlaying = false;
        saveState();
        updateUI();
    };
    
    window.DarlingFMAudio.switchStream = function(streamType) {
        if (!window.DarlingFMAudio.player) {
            return Promise.reject(new Error('Audio player not initialized'));
        }
        
        const target = window.DarlingFMAudio.streamUrls[streamType] ? streamType : 'main';
        const wasPlaying = window.DarlingFMAudio.isPlaying;
        if (wasPlaying) {
            window.DarlingFMAudio.player.pause();
        }
        window.DarlingFMAudio.currentStream = target;
        window.DarlingFMAudio.player.src = window.DarlingFMAudio.streamUrls[target];
        localStorage.setItem('darlingfm_active_stream', target);
        saveState();
        updateUI(); // Update UI immediately
        if (wasPlaying) {
            return window.DarlingFMAudio.play().then(() => {
                updateUI(); // Update again after play starts
            });
        }
        return Promise.resolve();
    };
    
    // Expose currentStream getter
    window.DarlingFMAudio.getCurrentStream = function() {
        return window.DarlingFMAudio.currentStream || localStorage.getItem('darlingfm_active_stream') || 'main';
    };
    
    // Save state and track listener stop before page unload
    window.addEventListener('beforeunload', function() {
        saveState();
        // If user is listening and leaving the page, track as stop
        if (window.DarlingFMAudio.isPlaying && window.DarlingFMAudio.player && window.DarlingFMAudio.player.src) {
            // Use synchronous request for beforeunload
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/api/listener/track', false);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content || '');
            const sessionId = sessionStorage.getItem('darlingfm_listener_session_id') || 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            xhr.send(JSON.stringify({
                action: 'stop',
                session_id: sessionId,
                stream_url: (window.DarlingFMAudio.player && window.DarlingFMAudio.player.src) || '',
                user_id: window.authUser?.id || null
            }));
        }
    });
    
    // Run complete initialization on load and navigation
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', completeInitialization);
    } else {
        completeInitialization();
    }
    
    // Re-initialize on Livewire navigation (critical for SPA persistence and auto-resume)
    document.addEventListener('livewire:navigated', function() {
        // Preserve playing state before re-initialization
        const wasPlaying = window.DarlingFMAudio?.isPlaying || false;
        const playerWasPlaying = window.DarlingFMAudio?.player && !window.DarlingFMAudio.player.paused;
        const shouldResume = wasPlaying || playerWasPlaying;
        completeInitialization();
        // If was playing, try to resume after a short delay to allow DOM to settle
        if (shouldResume && window.DarlingFMAudio.player && window.DarlingFMAudio.player.src) {
            setTimeout(() => {
                if (window.DarlingFMAudio.player && window.DarlingFMAudio.player.paused && window.DarlingFMAudio.player.src) {
                    window.DarlingFMAudio.player.play().then(() => {
                        window.DarlingFMAudio.isPlaying = true;
                        saveState();
                        updateUI();
                    }).catch((err) => {
                        // Autoplay blocked - user will need to click play
                        window.DarlingFMAudio.isPlaying = false;
                        saveState();
                    });
                }
            }, 100);
        }
    });
})();
