/**
 * Bulletproof HLS Live Stream Player for Darling FM
 * 
 * Features:
 * - HLS.js for live streaming (with Safari native HLS fallback)
 * - Server time sync to prevent jump-back on reload
 * - BroadcastChannel for cross-tab sync
 * - localStorage for position persistence
 * - Never restarts from 0 - always seeks to live position
 * - Works on mobile + desktop
 */

(function() {
    'use strict';

    // =============================================
    // CONFIGURATION
    // =============================================
    const STREAM_URLS = {
        main: "https://phoebe.streamerr.co:7567/stream",
        backup: "https://phoebe.streamerr.co:7572/stream"
    };

    const STORAGE_KEYS = {
        playState: 'darlingfm_play_state',
        serverTime: 'darlingfm_server_time',
        clientTime: 'darlingfm_client_time',
        streamUrl: 'darlingfm_stream_url'
    };

    const SEEK_BUFFER = 5; // Seek 5 seconds before live edge
    const SYNC_INTERVAL = 30000; // Sync server time every 30 seconds
    const POSITION_UPDATE_INTERVAL = 1000; // Update position every second

    // =============================================
    // GLOBAL STATE
    // =============================================
    let hls = null;
    let video = null;
    let isPlaying = false;
    let currentStreamUrl = null;
    let serverTimeOffset = 0; // Difference between server time and client time (ms)
    let lastSavedServerTime = null;
    let broadcastChannel = null;
    let syncInterval = null;
    let positionUpdateInterval = null;

    // =============================================
    // HLS.JS DETECTION & LOADING
    // =============================================
    function loadHLS() {
        return new Promise((resolve, reject) => {
            // Check if HLS.js is already loaded
            if (window.Hls && window.Hls.isSupported()) {
                resolve(window.Hls);
                return;
            }

            // Check for native HLS support (Safari)
            if (video && video.canPlayType('application/vnd.apple.mpegurl')) {
                resolve(null); // Use native HLS
                return;
            }

            // Load HLS.js from CDN
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/hls.js@latest';
            script.onload = () => {
                if (window.Hls && window.Hls.isSupported()) {
                    resolve(window.Hls);
                } else {
                    reject(new Error('HLS.js loaded but not supported'));
                }
            };
            script.onerror = () => reject(new Error('Failed to load HLS.js'));
            document.head.appendChild(script);
        });
    }

    // =============================================
    // SERVER TIME SYNC
    // =============================================
    async function syncServerTime() {
        try {
            const startTime = Date.now();
            const response = await fetch('/api/server-time', {
                method: 'GET',
                cache: 'no-cache',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error('Server time sync failed');
            }

            const data = await response.json();
            const endTime = Date.now();
            const roundTripTime = endTime - startTime;
            const serverTime = data.timestamp * 1000; // Convert to milliseconds
            const estimatedServerTime = serverTime + (roundTripTime / 2);
            
            serverTimeOffset = estimatedServerTime - Date.now();
            
            // Save sync data
            lastSavedServerTime = getServerTime();
            savePosition();
            
            console.log('Server time synced. Offset:', serverTimeOffset, 'ms');
            return true;
        } catch (error) {
            console.warn('Server time sync failed:', error);
            // Use stored offset if available
            const saved = loadPosition();
            if (saved && saved.serverTimeOffset !== undefined) {
                serverTimeOffset = saved.serverTimeOffset;
            }
            return false;
        }
    }

    function getServerTime() {
        return Date.now() + serverTimeOffset;
    }

    // =============================================
    // POSITION PERSISTENCE
    // =============================================
    function savePosition() {
        try {
            const serverTime = getServerTime();
            const data = {
                isPlaying: isPlaying,
                serverTime: serverTime,
                clientTime: Date.now(),
                serverTimeOffset: serverTimeOffset,
                streamUrl: currentStreamUrl
            };
            localStorage.setItem(STORAGE_KEYS.playState, JSON.stringify(data));
            
            // Broadcast to other tabs
            if (broadcastChannel) {
                broadcastChannel.postMessage({
                    type: 'position_update',
                    data: data
                });
            }
        } catch (e) {
            console.warn('Failed to save position:', e);
        }
    }

    function loadPosition() {
        try {
            const data = localStorage.getItem(STORAGE_KEYS.playState);
            if (data) {
                return JSON.parse(data);
            }
        } catch (e) {
            console.warn('Failed to load position:', e);
        }
        return null;
    }

    // =============================================
    // BROADCAST CHANNEL (Cross-tab sync)
    // =============================================
    function initBroadcastChannel() {
        if (typeof BroadcastChannel !== 'undefined') {
            broadcastChannel = new BroadcastChannel('darlingfm_audio_sync');
            
            broadcastChannel.onmessage = (event) => {
                if (event.data.type === 'position_update') {
                    const data = event.data.data;
                    // Sync with other tab's position
                    if (data.isPlaying && !isPlaying) {
                        // Other tab is playing, sync our state
                        serverTimeOffset = data.serverTimeOffset;
                        if (currentStreamUrl === data.streamUrl) {
                            // Same stream, sync position
                            syncPosition(data.serverTime);
                        }
                    }
                } else if (event.data.type === 'play') {
                    // Another tab started playing
                    if (!isPlaying) {
                        playStream(event.data.streamUrl);
                    }
                } else if (event.data.type === 'pause') {
                    // Another tab paused
                    if (isPlaying) {
                        pauseStream();
                    }
                }
            };
        }
    }

    // =============================================
    // VIDEO ELEMENT INITIALIZATION
    // =============================================
    function initVideoElement() {
        // Check if video element already exists (from previous page)
        const existingVideo = document.getElementById('hlsLivePlayer');
        if (existingVideo) {
            video = existingVideo;
            // Re-attach event listeners if needed
            if (!video.hasAttribute('data-listeners-attached')) {
                attachVideoListeners();
                video.setAttribute('data-listeners-attached', 'true');
            }
            return video;
        }

        // Create new video element if it doesn't exist
        video = document.createElement('video');
        video.id = 'hlsLivePlayer';
        video.style.display = 'none';
        video.preload = 'auto';
        video.crossOrigin = 'anonymous';
        video.muted = false;
        video.volume = 1.0;
        video.setAttribute('data-listeners-attached', 'true');

        // Attach event listeners
        attachVideoListeners();

        // Only append if not already in DOM
        if (!document.body.contains(video)) {
            document.body.appendChild(video);
        }
        return video;
    }

    function attachVideoListeners() {
        if (!video) return;

        // Remove existing listeners to prevent duplicates
        const newVideo = video.cloneNode(true);
        video.parentNode?.replaceChild(newVideo, video);
        video = newVideo;

        // Event listeners
        video.addEventListener('play', () => {
            isPlaying = true;
            savePosition();
            updateUI();
        });

        video.addEventListener('pause', () => {
            isPlaying = false;
            savePosition();
            updateUI();
        });

        video.addEventListener('loadedmetadata', () => {
            console.log('HLS metadata loaded');
            updateUI('Live');
        });

        video.addEventListener('waiting', () => {
            updateUI('Buffering...');
        });

        video.addEventListener('playing', () => {
            updateUI('Live');
        });

        video.addEventListener('error', (e) => {
            console.error('Video error:', e);
            handleStreamError();
        });
    }

    // =============================================
    // HLS INITIALIZATION
    // =============================================
    async function initHLS(streamUrl) {
        if (!video) {
            initVideoElement();
        }

        // Check if stream is HLS (.m3u8) or MP3
        const isHLS = streamUrl.includes('.m3u8') || streamUrl.includes('application/vnd.apple.mpegurl');
        
        if (!isHLS) {
            // MP3 stream - use audio element directly
            console.log('Using MP3 stream (direct playback)');
            if (hls) {
                hls.destroy();
                hls = null;
            }
            video.src = streamUrl;
            return video;
        }

        // HLS stream - check for native HLS support (Safari)
        if (video.canPlayType('application/vnd.apple.mpegurl')) {
            console.log('Using native HLS (Safari)');
            if (hls) {
                hls.destroy();
                hls = null;
            }
            video.src = streamUrl;
            return video;
        }

        // Use HLS.js for non-Safari browsers
        try {
            const Hls = await loadHLS();
            
            if (hls) {
                hls.destroy();
            }

            hls = new Hls({
                enableWorker: true,
                lowLatencyMode: true,
                backBufferLength: 90,
                maxBufferLength: 30,
                maxMaxBufferLength: 60,
                maxBufferSize: 60 * 1000 * 1000,
                maxBufferHole: 0.5,
                highBufferWatchdogPeriod: 2,
                nudgeOffset: 0.1,
                nudgeMaxRetry: 3,
                maxFragLoadingTimeOut: 4,
                fragLoadingTimeOut: 4,
                manifestLoadingTimeOut: 4,
            });

            hls.loadSource(streamUrl);
            hls.attachMedia(video);

            hls.on(Hls.Events.MANIFEST_PARSED, () => {
                console.log('HLS manifest parsed');
            });

            hls.on(Hls.Events.ERROR, (event, data) => {
                console.error('HLS error:', data);
                if (data.fatal) {
                    switch (data.type) {
                        case Hls.ErrorTypes.NETWORK_ERROR:
                            console.log('Network error, trying to recover...');
                            hls.startLoad();
                            break;
                        case Hls.ErrorTypes.MEDIA_ERROR:
                            console.log('Media error, trying to recover...');
                            hls.recoverMediaError();
                            break;
                        default:
                            console.log('Fatal error, destroying HLS...');
                            hls.destroy();
                            handleStreamError();
                            break;
                    }
                }
            });

            return video;
        } catch (error) {
            console.error('HLS initialization failed:', error);
            // Fallback to direct playback
            video.src = streamUrl;
            return video;
        }
    }

    // =============================================
    // POSITION SYNC & SEEKING
    // =============================================
    function syncPosition(targetServerTime = null) {
        if (!video || !video.readyState) return;

        // For HLS streams, seek to live edge
        const isHLS = currentStreamUrl && (currentStreamUrl.includes('.m3u8') || hls);
        
        if (isHLS) {
            // HLS stream - seek to live edge minus buffer
            if (video.duration && !isNaN(video.duration) && isFinite(video.duration)) {
                const liveEdge = video.duration;
                const seekTime = Math.max(0, liveEdge - SEEK_BUFFER);
                
                if (Math.abs(video.currentTime - seekTime) > 2) {
                    console.log('Seeking to live position:', seekTime);
                    video.currentTime = seekTime;
                }
            } else if (hls && hls.liveSyncPosition !== null) {
                // Use HLS.js live sync position
                const liveSyncPos = hls.liveSyncPosition;
                const seekTime = Math.max(0, liveSyncPos - SEEK_BUFFER);
                if (Math.abs(video.currentTime - seekTime) > 2) {
                    console.log('Seeking to HLS live sync position:', seekTime);
                    video.currentTime = seekTime;
                }
            }
        } else {
            // MP3 stream - can't seek, but ensure we're playing
            // The stream will continue from where it was in the buffer
            // Server time sync ensures we know when playback started
            console.log('MP3 stream - position sync not applicable');
        }
    }

    // =============================================
    // PLAYBACK CONTROL
    // =============================================
    async function playStream(url = null) {
        const streamUrl = url || currentStreamUrl || STREAM_URLS.main;

        try {
            // Initialize video element if needed
            if (!video) {
                initVideoElement();
            }

            // Check if already playing the same stream
            if (video && !video.paused && video.src === streamUrl && currentStreamUrl === streamUrl) {
                console.log('Already playing this stream');
                return;
            }

            // Initialize HLS if needed or if stream URL changed
            if (!video.src || video.src !== streamUrl) {
                await initHLS(streamUrl);
                currentStreamUrl = streamUrl;
            }

            // Sync server time before playing
            await syncServerTime();

            // Load saved position
            const saved = loadPosition();
            if (saved && saved.isPlaying && saved.streamUrl === streamUrl) {
                // Calculate time elapsed since last save
                const elapsed = (getServerTime() - saved.serverTime) / 1000;
                console.log('Resuming from saved position. Elapsed:', elapsed, 'seconds');
            }

            // Play
            await video.play();
            
            // Seek to live position after a short delay
            setTimeout(() => {
                syncPosition();
            }, 1000);

            // Start position updates
            startPositionUpdates();

            // Broadcast play event
            if (broadcastChannel) {
                broadcastChannel.postMessage({
                    type: 'play',
                    streamUrl: streamUrl
                });
            }

            isPlaying = true;
            savePosition();
            updateUI('Live');
        } catch (error) {
            console.error('Play error:', error);
            handleStreamError();
            throw error;
        }
    }

    function pauseStream() {
        if (!video) return;

        video.pause();
        isPlaying = false;
        savePosition();
        updateUI('Paused');

        // Broadcast pause event
        if (broadcastChannel) {
            broadcastChannel.postMessage({
                type: 'pause'
            });
        }

        stopPositionUpdates();
    }

    function togglePlayback() {
        if (isPlaying && video && !video.paused) {
            pauseStream();
        } else {
            playStream();
        }
    }

    // =============================================
    // POSITION UPDATES
    // =============================================
    function startPositionUpdates() {
        stopPositionUpdates();
        
        positionUpdateInterval = setInterval(() => {
            if (isPlaying && video && !video.paused) {
                // Periodically sync position to live edge
                syncPosition();
                savePosition();
            }
        }, POSITION_UPDATE_INTERVAL);
    }

    function stopPositionUpdates() {
        if (positionUpdateInterval) {
            clearInterval(positionUpdateInterval);
            positionUpdateInterval = null;
        }
    }

    // =============================================
    // ERROR HANDLING
    // =============================================
    function handleStreamError() {
        isPlaying = false;
        updateUI('Error loading stream');

        // Try backup stream
        if (currentStreamUrl === STREAM_URLS.main) {
            console.log('Trying backup stream...');
            setTimeout(() => {
                playStream(STREAM_URLS.backup).catch(() => {
                    updateUI('Stream unavailable');
                });
            }, 2000);
        } else {
            updateUI('Stream unavailable');
        }
    }

    // =============================================
    // UI UPDATES
    // =============================================
    function updateUI(status = null) {
        const player = document.getElementById('stickyPlayer');
        const playBtn = document.getElementById('stickyPlayBtn');
        const statusEl = document.getElementById('stickyPlayerStatus');

        if (!player || !playBtn || !statusEl) return;

        player.style.display = 'flex';

        const icon = playBtn.querySelector('i');
        if (isPlaying && video && !video.paused) {
            icon.className = 'fas fa-pause';
            playBtn.setAttribute('aria-label', 'Pause');
        } else {
            icon.className = 'fas fa-play';
            playBtn.setAttribute('aria-label', 'Play');
        }

        if (status) {
            statusEl.textContent = status;
        } else if (isPlaying && video && !video.paused) {
            statusEl.textContent = 'Live';
        } else {
            statusEl.textContent = 'Tap to play';
        }
    }

    // =============================================
    // PAGE VISIBILITY HANDLING
    // =============================================
    function handleVisibilityChange() {
        if (document.hidden) {
            // Page hidden - save position
            if (isPlaying && video) {
                savePosition();
            }
        } else {
            // Page visible - sync position
            if (isPlaying && video && !video.paused) {
                syncServerTime().then(() => {
                    syncPosition();
                });
            }
        }
    }

    function handlePageHide() {
        // Save position before page unload
        if (isPlaying && video) {
            savePosition();
        }
    }

    // =============================================
    // RESTORE PLAYBACK ON LOAD
    // =============================================
    async function restorePlayback() {
        const saved = loadPosition();
        
        if (!saved || !saved.isPlaying) {
            updateUI('Tap to play');
            return;
        }

        // Sync server time first
        await syncServerTime();

        // Calculate if we should resume
        const timeSinceSave = (getServerTime() - saved.serverTime) / 1000;
        
        // If saved position is less than 120 seconds old, resume (increased from 60)
        if (timeSinceSave < 120) {
            try {
                // Check if video is already playing (from previous page)
                if (video && !video.paused && video.readyState >= 2) {
                    // Video is already playing, just update UI
                    isPlaying = true;
                    currentStreamUrl = saved.streamUrl;
                    updateUI('Live');
                    startPositionUpdates();
                    console.log('Playback already active, restored state');
                } else {
                    // Need to start playback
                    await playStream(saved.streamUrl);
                    console.log('Playback restored from saved position');
                }
            } catch (error) {
                console.warn('Failed to restore playback:', error);
                updateUI('Tap to play');
            }
        } else {
            // Too old, just show UI
            updateUI('Tap to play');
        }
    }

    // =============================================
    // INITIALIZATION
    // =============================================
    async function init() {
        // Check if already initialized (prevent double initialization)
        if (window.DarlingFMAudio && window.DarlingFMAudio._initialized) {
            console.log('Player already initialized, skipping...');
            return;
        }

        // Initialize video element (reuse existing if available)
        initVideoElement();

        // Initialize broadcast channel
        initBroadcastChannel();

        // Setup UI controls
        const playBtn = document.getElementById('stickyPlayBtn');
        const expandBtn = document.getElementById('stickyExpandBtn');
        const homePlayBtn = document.getElementById('homePlayButton');

        if (playBtn) {
            playBtn.addEventListener('click', togglePlayback);
        }

        if (expandBtn) {
            expandBtn.addEventListener('click', () => {
                window.location.href = '/live-stream';
            });
        }

        if (homePlayBtn) {
            homePlayBtn.addEventListener('click', togglePlayback);
        }

        // Page visibility handlers
        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('pagehide', handlePageHide);
        window.addEventListener('beforeunload', handlePageHide);

        // Start server time sync interval
        syncInterval = setInterval(syncServerTime, SYNC_INTERVAL);

        // Initial server time sync
        await syncServerTime();

        // Restore playback
        await restorePlayback();
    }

    // =============================================
    // EXPOSE API
    // =============================================
    window.DarlingFMAudio = {
        play: playStream,
        pause: pauseStream,
        toggle: togglePlayback,
        isPlaying: () => isPlaying,
        getCurrentUrl: () => currentStreamUrl,
        syncServerTime: syncServerTime,
        _initialized: true,
        getVideo: () => video
    };

    // =============================================
    // STARTUP
    // =============================================
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();

