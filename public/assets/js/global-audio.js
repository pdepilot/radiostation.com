// Global Persistent Audio Player for Darling FM
// This player persists across page navigation

(function() {
    'use strict';
    
    // Create global audio player instance
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
        
        // Initialize audio player
        window.DarlingFMAudio.player = new Audio();
        window.DarlingFMAudio.player.preload = 'none';
        window.DarlingFMAudio.player.crossOrigin = 'anonymous';
        
        // Restore state from localStorage
        const savedState = localStorage.getItem('darlingfm_audio_state');
        if (savedState) {
            try {
                const state = JSON.parse(savedState);
                window.DarlingFMAudio.currentStream = state.stream || 'main';
                window.DarlingFMAudio.isPlaying = state.isPlaying || false;
                
                if (window.DarlingFMAudio.isPlaying) {
                    window.DarlingFMAudio.player.src = window.DarlingFMAudio.streamUrls[window.DarlingFMAudio.currentStream];
                    // Auto-resume if was playing (respects browser autoplay policies)
                    window.DarlingFMAudio.player.play().catch(() => {
                        // Autoplay blocked, will need user interaction
                        window.DarlingFMAudio.isPlaying = false;
                    });
                }
            } catch(e) {
                console.error('Failed to restore audio state:', e);
            }
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
        
        // Play/pause handlers with listener tracking
        window.DarlingFMAudio.player.addEventListener('play', function() {
            window.DarlingFMAudio.isPlaying = true;
            saveState();
            updateUI();
            // Track listener start
            trackListenerActivity('start');
        });

        window.DarlingFMAudio.player.addEventListener('pause', function() {
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
        
        // Expose methods
        window.DarlingFMAudio.play = function() {
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
            if (window.DarlingFMAudio.isPlaying) {
                // Use synchronous request for beforeunload
                const xhr = new XMLHttpRequest();
                xhr.open('POST', '/api/listener/track', false);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')?.content || '');
                xhr.send(JSON.stringify({
                    action: 'stop',
                    stream_url: window.DarlingFMAudio.player.src,
                    user_id: window.authUser?.id || null
                }));
            }
        });

        // Track listener activity (start/stop listening)
        function trackListenerActivity(action) {
            if (!window.DarlingFMAudio.player.src) return;

            fetch('/api/listener/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    action: action,
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
    }
})();

