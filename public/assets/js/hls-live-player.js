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
        streamUrl: 'darlingfm_stream_url',
        // SessionStorage keys for immediate resume
        radioPlaying: 'radioPlaying', // 'true' or 'false'
        radioVolume: 'radioVolume' // 0.0 to 1.0
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
    let playRetryCount = 0;
    const MAX_PLAY_RETRIES = 3;

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
            
            // Save play state to sessionStorage for immediate resume
            if (typeof sessionStorage !== 'undefined') {
                sessionStorage.setItem(STORAGE_KEYS.radioPlaying, isPlaying ? 'true' : 'false');
                if (video) {
                    sessionStorage.setItem(STORAGE_KEYS.radioVolume, video.volume.toString());
                }
            }
            
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
        // Check if video element already exists in DOM (from sticky-player component)
        const existingVideo = document.getElementById('hlsLivePlayer');
        if (existingVideo) {
            video = existingVideo;
        } else if (!video) {
            // Create new video element if it doesn't exist
            video = document.createElement('video');
            video.id = 'hlsLivePlayer';
            video.style.display = 'none';
            document.body.appendChild(video);
        }

        // Configure video element properties
        video.preload = 'auto';
        video.crossOrigin = 'anonymous';
        video.muted = false;
        if (!video.volume) {
            video.volume = 1.0;
        }

        // Event listeners
        video.addEventListener('play', () => {
            isPlaying = true;
            playRetryCount = 0; // Reset retry count on successful play
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
            isPlaying = false;
            savePosition(); // Save paused state on error
            handleStreamError();
        });

        video.addEventListener('ended', () => {
            isPlaying = false;
            savePosition(); // Save paused state on end
            updateUI('Ended');
        });

        // Only append if not already in DOM
        if (!document.body.contains(video)) {
            document.body.appendChild(video);
        }
        return video;
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
    /**
     * Attempt to play the stream with retry logic for autoplay policy
     * @param {boolean} muted - Whether to attempt muted playback first
     * @returns {Promise<void>}
     */
    async function attemptPlay(muted = false) {
        if (!video) {
            throw new Error('Video element not initialized');
        }

        // Try muted play if browser blocks autoplay
        if (muted && !video.muted) {
            const originalVolume = video.volume;
            video.muted = true;
            try {
                await video.play();
                // Unmute after successful play
                video.muted = false;
                video.volume = originalVolume;
                return;
            } catch (e) {
                video.muted = false;
                video.volume = originalVolume;
                throw e;
            }
        }

        // Normal play attempt
        await video.play();
    }

    /**
     * Play stream with retry logic and autoplay policy handling
     */
    async function playStream(url = null, retryMuted = false) {
        const streamUrl = url || currentStreamUrl || STREAM_URLS.main;

        try {
            // Initialize HLS if needed
            if (!video || video.src !== streamUrl) {
                await initHLS(streamUrl);
                currentStreamUrl = streamUrl;
            }

            // Restore volume from sessionStorage
            if (typeof sessionStorage !== 'undefined') {
                const savedVolume = sessionStorage.getItem(STORAGE_KEYS.radioVolume);
                if (savedVolume !== null) {
                    video.volume = parseFloat(savedVolume) || 1.0;
                }
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

            // Attempt to play with retry logic
            try {
                await attemptPlay(retryMuted);
            } catch (error) {
                // If play fails and we haven't tried muted yet, try muted
                if (!retryMuted && playRetryCount < MAX_PLAY_RETRIES) {
                    playRetryCount++;
                    console.log(`Play blocked, trying muted playback (attempt ${playRetryCount})...`);
                    return playStream(streamUrl, true);
                }
                // If muted also fails, try again up to MAX_RETRIES
                if (playRetryCount < MAX_PLAY_RETRIES) {
                    playRetryCount++;
                    console.log(`Play failed, retrying (attempt ${playRetryCount}/${MAX_PLAY_RETRIES})...`);
                    await new Promise(resolve => setTimeout(resolve, 500));
                    return playStream(streamUrl, false);
                }
                throw error;
            }
            
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
            // If autoplay is blocked, show UI but don't throw
            if (error.name === 'NotAllowedError' || error.name === 'NotSupportedError') {
                console.warn('Autoplay blocked. User interaction required.');
                updateUI('Tap to play');
                isPlaying = false;
                savePosition();
            } else {
                handleStreamError();
                throw error;
            }
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
    /**
     * Restore playback state immediately on page load
     * Uses sessionStorage for instant resume without waiting for server sync
     */
    async function restorePlayback() {
        // Check sessionStorage first for immediate resume
        let shouldResume = false;
        let savedStreamUrl = null;
        
        if (typeof sessionStorage !== 'undefined') {
            const radioPlaying = sessionStorage.getItem(STORAGE_KEYS.radioPlaying);
            if (radioPlaying === 'true') {
                shouldResume = true;
                // Get stream URL from localStorage or use default
                const saved = loadPosition();
                savedStreamUrl = (saved && saved.streamUrl) || STREAM_URLS.main;
            }
        }

        // Fallback to localStorage if sessionStorage not available
        if (!shouldResume) {
            const saved = loadPosition();
            if (saved && saved.isPlaying) {
                // Sync server time first
                await syncServerTime();
                
                // Calculate if we should resume
                const timeSinceSave = (getServerTime() - saved.serverTime) / 1000;
                
                // If saved position is less than 120 seconds old, resume
                if (timeSinceSave < 120) {
                    shouldResume = true;
                    savedStreamUrl = saved.streamUrl || STREAM_URLS.main;
                }
            }
        }

        if (!shouldResume || !savedStreamUrl) {
            updateUI('Tap to play');
            return;
        }

        // Initialize video element if needed
        if (!video) {
            initVideoElement();
        }

        // Sync server time (non-blocking for immediate resume)
        syncServerTime().catch(err => console.warn('Server time sync failed:', err));

        // Immediately attempt to resume playback
        try {
            console.log('Auto-resuming playback from sessionStorage...');
            await playStream(savedStreamUrl, true); // Try muted first for autoplay policy
            console.log('Playback restored successfully');
        } catch (error) {
            console.warn('Failed to auto-resume playback:', error);
            // If autoplay is blocked, that's okay - user can click play
            if (error.name !== 'NotAllowedError' && error.name !== 'NotSupportedError') {
                updateUI('Tap to play');
            }
        }
    }

    // =============================================
    // INITIALIZATION
    // =============================================
    async function init() {
        // Initialize video element first
        initVideoElement();

        // Restore volume from sessionStorage immediately
        if (video && typeof sessionStorage !== 'undefined') {
            const savedVolume = sessionStorage.getItem(STORAGE_KEYS.radioVolume);
            if (savedVolume !== null) {
                video.volume = parseFloat(savedVolume) || 1.0;
            }
        }

        // Initialize broadcast channel
        initBroadcastChannel();

        // Setup UI controls
        const playBtn = document.getElementById('stickyPlayBtn');
        const expandBtn = document.getElementById('stickyExpandBtn');
        const homePlayBtn = document.getElementById('homePlayButton');

        if (playBtn) {
            playBtn.addEventListener('click', () => {
                // On first user interaction, unmute if muted due to autoplay policy
                if (video && video.muted && isPlaying) {
                    video.muted = false;
                }
                togglePlayback();
            });
        }

        if (expandBtn) {
            expandBtn.addEventListener('click', () => {
                window.location.href = '/live-stream';
            });
        }

        if (homePlayBtn) {
            homePlayBtn.addEventListener('click', () => {
                // On first user interaction, unmute if muted due to autoplay policy
                if (video && video.muted && isPlaying) {
                    video.muted = false;
                }
                togglePlayback();
            });
        }

        // Page visibility handlers
        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('pagehide', handlePageHide);
        window.addEventListener('beforeunload', handlePageHide);

        // Start server time sync interval (non-blocking)
        syncInterval = setInterval(() => {
            syncServerTime().catch(err => console.warn('Server time sync failed:', err));
        }, SYNC_INTERVAL);

        // Initial server time sync (non-blocking for immediate resume)
        syncServerTime().catch(err => console.warn('Initial server time sync failed:', err));

        // Restore playback immediately (don't await - let it happen in background)
        restorePlayback().catch(err => {
            console.warn('Playback restoration failed:', err);
            updateUI('Tap to play');
        });
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
        syncServerTime: syncServerTime
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

