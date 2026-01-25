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
    
    // #region agent log
    const DEBUG_LOG = (location, message, data, hypothesisId) => {
        fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                location,
                message,
                data: data || {},
                timestamp: Date.now(),
                sessionId: 'debug-session',
                runId: 'run1',
                hypothesisId: hypothesisId || 'A'
            })
        }).catch(() => {});
    };
    // #endregion
    
    // Enhanced debug logging for navigation persistence
    const LOG_NAV = (message, data, hypothesisId) => {
        DEBUG_LOG('hls-live-player.js:NAV', message, {
            ...data,
            isNavigating,
            isPlaying,
            videoExists: !!video,
            videoPaused: video?.paused,
            videoSrc: video?.src?.substring(0, 80),
            videoReadyState: video?.readyState,
            currentStreamUrl: currentStreamUrl?.substring(0, 50)
        }, hypothesisId);
    };

    // =============================================
    // CONFIGURATION
    // =============================================
    const STREAM_URLS = {
        main: "https://phoebe.streamerr.co:7572/stream",
        backup: "https://phoebe.streamerr.co:7567/stream"
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
    const STALL_THRESHOLD = 2000; // 2 seconds - if stalled longer, trigger catch-up
    const BUFFER_CHECK_INTERVAL = 5000; // Check buffer health every 5 seconds

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
    let bufferCheckInterval = null;
    let isPageUnloading = false; // Flag to prevent state corruption during unload
    let isNavigating = false; // Flag to prevent state corruption during SPA navigation
    let stallStartTime = null; // Track when buffering started
    let lastStallRecovery = 0; // Prevent too frequent recovery attempts
    let isReloading = false; // Track if reload is in progress (HYPOTHESIS A)
    let reloadTimeoutId = null; // Track pending play() timeout (HYPOTHESIS A)
    let isPlayingPromise = null; // Track pending play() promise to prevent multiple calls
    let eventListenersAttached = false; // Track if event listeners are already attached to prevent duplicates
    let videoElementWithListeners = null; // Track which video element has listeners attached
    let pauseFromBroadcastChannel = false; // Track if pause is from BroadcastChannel (should be ignored during navigation)
    let navigationStartTime = 0; // Track when navigation started to ignore pause events shortly after
    let lastPlayTime = 0; // Track when play() was last called to ignore pause events immediately after

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
    // BROADCAST CHANNEL (Cross-tab sync - Single Instance)
    // =============================================
    let isActiveTab = true;
    let tabId = Math.random().toString(36).substring(7);
    
    function initBroadcastChannel() {
        if (typeof BroadcastChannel !== 'undefined') {
            broadcastChannel = new BroadcastChannel('darlingfm_audio_sync');
            
            broadcastChannel.onmessage = (event) => {
                // Ignore messages from this tab
                if (event.data.tabId === tabId) return;
                
                if (event.data.type === 'position_update') {
                    const data = event.data.data;
                    // Only sync if this tab is not active and other tab is playing
                    if (!isActiveTab && data.isPlaying && !isPlaying) {
                        serverTimeOffset = data.serverTimeOffset;
                        if (currentStreamUrl === data.streamUrl) {
                            syncPosition(data.serverTime);
                        }
                    }
                } else if (event.data.type === 'play') {
                    // Another tab started playing - PAUSE this tab (single instance)
                    // BUT: Don't pause during SPA navigation
                    const videoIsNavigating = video && video.dataset.isNavigating === 'true';
                    const shouldIgnore = isNavigating || videoIsNavigating;
                    
                    if (isPlaying && event.data.tabId !== tabId && !shouldIgnore) {
                        console.log('Another tab started playing, pausing this tab');
                        // Set flag BEFORE calling pauseStream to ensure pause event handler sees it
                        pauseFromBroadcastChannel = true;
                        pauseStream();
                        // Reset flag after a delay to allow pause event to process
                        setTimeout(() => { pauseFromBroadcastChannel = false; }, 200);
                    } else if (shouldIgnore) {
                        console.log('Play message ignored - navigation in progress');
                    }
                } else if (event.data.type === 'pause') {
                    // Another tab paused - do nothing, this tab can play if active
                } else if (event.data.type === 'tab_active') {
                    // Another tab became active - pause this tab if it's playing
                    // BUT: Don't pause during SPA navigation (isNavigating flag)
                    // Also check video element's navigation flag as backup
                    const videoIsNavigating = video && video.dataset.isNavigating === 'true';
                    const shouldIgnore = isNavigating || videoIsNavigating;
                    
                    // #region agent log
                    DEBUG_LOG('hls-live-player.js:BroadcastChannel', 'tab_active message received', {
                        isNavigating,
                        videoIsNavigating,
                        isPlaying,
                        otherTabId: event.data.tabId,
                        currentTabId: tabId,
                        willPause: isPlaying && event.data.tabId !== tabId && !shouldIgnore,
                        shouldIgnore
                    }, 'H');
                    // #endregion
                    
                    if (isPlaying && event.data.tabId !== tabId && !shouldIgnore) {
                        console.log('Another tab became active, pausing this tab');
                        // Set flag BEFORE calling pauseStream to ensure pause event handler sees it
                        pauseFromBroadcastChannel = true;
                        pauseStream();
                        // Reset flag after a delay to allow pause event to process
                        setTimeout(() => { pauseFromBroadcastChannel = false; }, 200);
                    } else if (shouldIgnore) {
                        // #region agent log
                        DEBUG_LOG('hls-live-player.js:BroadcastChannel', 'tab_active ignored during navigation', {
                            isNavigating,
                            videoIsNavigating,
                            isPlaying
                        }, 'H');
                        // #endregion
                        console.log('Tab active message ignored - navigation in progress');
                    }
                }
            };
            
            // Announce this tab's active state
            broadcastChannel.postMessage({
                type: 'tab_active',
                tabId: tabId
            });
        }
    }
    
    // Handle tab visibility changes for single-instance playback
    function handleTabVisibility() {
        document.addEventListener('visibilitychange', () => {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:visibilitychange', 'visibility changed', {
                hidden: document.hidden,
                isNavigating,
                isPlaying,
                videoPaused: video?.paused
            }, 'I');
            // #endregion
            if (document.hidden) {
                // Tab is hidden - pause if playing (single instance)
                // BUT: Don't pause during SPA navigation
                isActiveTab = false;
                if (isPlaying && video && !video.paused && !isNavigating) {
                    console.log('Tab hidden, pausing stream (single instance)');
                    pauseStream();
                } else if (isNavigating) {
                    // #region agent log
                    DEBUG_LOG('hls-live-player.js:visibilitychange', 'Tab hidden during navigation - not pausing', {
                        isNavigating,
                        isPlaying
                    }, 'I');
                    // #endregion
                    console.log('Tab hidden during navigation - preserving playback');
                }
                // Announce tab is inactive
                if (broadcastChannel) {
                    broadcastChannel.postMessage({
                        type: 'tab_inactive',
                        tabId: tabId
                    });
                }
            } else {
                // Tab is visible - become active tab
                isActiveTab = true;
                if (broadcastChannel) {
                    broadcastChannel.postMessage({
                        type: 'tab_active',
                        tabId: tabId
                    });
                }
                // Try to resume if was playing (respects browser autoplay policies)
                const saved = loadPosition();
                if (saved && saved.isPlaying && video && video.paused) {
                    const timeSinceSave = (getServerTime() - saved.serverTime) / 1000;
                    if (timeSinceSave < 300) { // 5 minutes window
                        video.play().catch(error => {
                            if (error.name === 'NotAllowedError') {
                                console.log('Autoplay blocked - user interaction required');
                                updateUI('Tap to resume');
                            }
                        });
                    }
                }
            }
        });
    }

    // =============================================
    // VIDEO ELEMENT INITIALIZATION
    // =============================================
    function initVideoElement() {
        // #region agent log
        DEBUG_LOG('hls-live-player.js:initVideoElement', 'initVideoElement called', {
            hasVideo: !!video,
            videoId: video?.id,
            existingVideoInDOM: !!document.getElementById('station-player')
        }, 'B');
        // #endregion
        
        // Always check for existing video element first (persisted by Livewire)
        const existingVideo = document.getElementById('station-player');
        
        if (existingVideo) {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:initVideoElement', 'existing video found in DOM', {
                videoPaused: existingVideo.paused,
                videoSrc: existingVideo.src.substring(0, 100) + '...',
                videoReadyState: existingVideo.readyState,
                currentVideoRef: video ? 'exists' : 'null',
                videosMatch: video === existingVideo
            }, 'B');
            // #endregion
            
            // Video element exists (persisted), use it
            if (video && video !== existingVideo) {
                // We had a different reference, transfer state
                const wasPlaying = !video.paused;
                const currentSrc = video.src;
                const currentTime = video.currentTime;
                
                // #region agent log
                DEBUG_LOG('hls-live-player.js:initVideoElement', 'transferring state to persisted element', {
                    wasPlaying,
                    currentSrc: currentSrc.substring(0, 100) + '...',
                    currentTime
                }, 'B');
                // #endregion
                
                // Transfer to persisted element
                // #region agent log
                LOG_NAV('Setting existingVideo.src in initVideoElement', {
                    oldSrc: existingVideo.src?.substring(0, 80),
                    newSrc: currentSrc?.substring(0, 80),
                    wasPlaying,
                    existingVideoPaused: existingVideo.paused
                }, 'B');
                // #endregion
                existingVideo.src = currentSrc;
                existingVideo.currentTime = currentTime;
                if (wasPlaying && !existingVideo.paused) {
                    existingVideo.play().catch(() => {});
                }
            }
            video = existingVideo;
        } else if (!video) {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:initVideoElement', 'creating new video element (no persist?)', {}, 'B');
            // #endregion
            
            // No existing element and no video reference - create new (shouldn't happen with persist)
            video = document.createElement('video');
            video.id = 'station-player';
            video.style.display = 'none';
            video.preload = 'none';
            document.body.appendChild(video);
        }
        
        // Ensure preload is set to none
        if (video.preload !== 'none') {
            video.preload = 'none';
        }
        
        video.crossOrigin = 'anonymous';
        video.muted = false;
        video.volume = 1.0;
        
        // #region agent log
        DEBUG_LOG('hls-live-player.js:initVideoElement', 'video element initialized', {
            videoId: video.id,
            videoPaused: video.paused,
            videoSrc: video.src.substring(0, 100) + '...',
            videoReadyState: video.readyState
        }, 'B');
        // #endregion

        // CRITICAL: Only attach event listeners once per video element
        // If listeners are already attached to this exact element, skip reattachment
        // This prevents duplicate pause events from firing after navigation
        if (eventListenersAttached && videoElementWithListeners === video) {
            // #region agent log
            LOG_NAV('Skipping event listener reattachment (already attached to this element)', {
                videoPaused: video.paused,
                videoSrc: video.src?.substring(0, 80)
            }, 'C');
            // #endregion
            return video;
        }
        
        // Mark that we're attaching listeners to this element
        eventListenersAttached = true;
        videoElementWithListeners = video;

        // Event listeners
        video.addEventListener('play', () => {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:play', 'play event fired', {
                videoPaused: video.paused,
                videoSrc: video.src?.substring(0, 100) + '...',
                videoReadyState: video.readyState
            }, 'D');
            // #endregion
            
            isPlaying = true;
            savePosition();
            updateUI('Live');
            trackListenerCount('start');
            console.log('▶️ Video play event - state saved');
        });

        video.addEventListener('pause', () => {
            // Check video element's navigation flag as backup
            const videoIsNavigating = video.dataset.isNavigating === 'true';
            
            // Calculate time since navigation start (used in both debug log and logic)
            const timeSinceNavStart = Date.now() - navigationStartTime;
            const isRecentNavigation = navigationStartTime > 0 && timeSinceNavStart < 500;
            
            // CRITICAL: Also ignore pause events that occur immediately after play() was called
            // This prevents pause events from interrupting playback right after resume
            // Increased window to 3 seconds to account for navigation delays
            const timeSinceLastPlay = Date.now() - lastPlayTime;
            const isRecentPlay = lastPlayTime > 0 && timeSinceLastPlay < 3000; // 3 second window
            
            // #region agent log
            LOG_NAV('PAUSE EVENT FIRED', {
                isPageUnloading,
                isNavigating,
                videoIsNavigating,
                pauseFromBroadcastChannel,
                isRecentNavigation,
                timeSinceNavStart,
                isRecentPlay,
                timeSinceLastPlay,
                lastPlayTime,
                navigationStartTime,
                videoPaused: video.paused,
                videoSrc: video.src?.substring(0, 100),
                videoReadyState: video.readyState,
                currentStreamUrl: currentStreamUrl?.substring(0, 50),
                hasValidStreamUrl: !!(currentStreamUrl && typeof currentStreamUrl === 'string' && currentStreamUrl.length > 0),
                stackTrace: new Error().stack?.substring(0, 300)
            }, 'G');
            // #endregion
            
            // CRITICAL: Check if we have a valid stream URL - if so, we were playing before navigation
            // This is a fallback check in case isNavigating flag wasn't set in time
            const hasValidStreamUrl = currentStreamUrl && typeof currentStreamUrl === 'string' && currentStreamUrl.length > 0 && currentStreamUrl !== 'undefined';
            const wasPlayingBefore = isPlaying || hasValidStreamUrl;
            
            // Don't update state if page is unloading or navigating - preserve playing state
            // Also ignore pause events from BroadcastChannel during navigation
            // Also ignore pause events that occur shortly after navigation starts (browser-initiated pauses)
            // Also ignore pause events that occur immediately after play() was called (transitional state)
            // (timeSinceNavStart, isRecentNavigation, and isRecentPlay already calculated above)
            
            // Check both the global flag and the video element's flag
            // CRITICAL: Also ignore if we have a valid stream URL (indicates we were playing)
            // This is a fallback for cases where isPlaying was set to false but we still have a stream URL
            // If video has data (readyState >= 2), it might be buffering - ignore pause
            const videoHasData = video && video.readyState >= 2;
            const shouldIgnorePause = isPageUnloading || 
                                      isNavigating || 
                                      videoIsNavigating || 
                                      pauseFromBroadcastChannel || 
                                      isRecentNavigation || 
                                      isRecentPlay || 
                                      (wasPlayingBefore && !video.src) ||
                                      (hasValidStreamUrl && videoHasData); // If we have a stream URL and video has data, ignore pause (might be buffering)
            
            if (shouldIgnorePause) {
                // #region agent log
                LOG_NAV('PAUSE IGNORED (navigation/unload/stream-preserved/recent-play)', {
                    isPageUnloading,
                    isNavigating,
                    videoIsNavigating,
                    pauseFromBroadcastChannel,
                    isRecentNavigation,
                    isRecentPlay,
                    timeSinceNavStart,
                    timeSinceLastPlay,
                    wasPlayingBefore,
                    hasValidStreamUrl,
                    videoHasData,
                    videoHasSrc: !!video.src,
                    preservedIsPlaying: isPlaying || wasPlayingBefore
                }, 'A');
                // #endregion
                console.log('⏸️ Video paused during navigation/unload - preserving play state');
                // Keep isPlaying as true if it was playing before navigation
                if (wasPlayingBefore && !isPlaying) {
                    isPlaying = true; // Restore playing state
                }
                return;
            }
            
            // #region agent log
            LOG_NAV('PAUSE ACCEPTED (user action)', {
                wasPlaying: isPlaying,
                willSetPlayingFalse: true,
                hasValidStreamUrl,
                videoPaused: video.paused,
                videoReadyState: video.readyState
            }, 'G');
            // #endregion
            
            // CRITICAL: Double-check - if we have a valid stream URL and video has data,
            // this might be a false pause (e.g., during buffering). Don't accept it.
            if (hasValidStreamUrl && video && video.readyState >= 2) {
                // #region agent log
                LOG_NAV('PAUSE REJECTED - has valid stream URL and video has data', {
                    hasValidStreamUrl,
                    videoReadyState: video.readyState,
                    videoPaused: video.paused
                }, 'G');
                // #endregion
                console.log('⏸️ Pause event rejected - video has valid stream and data');
                // Restore playing state
                if (!isPlaying) {
                    isPlaying = true;
                }
                return;
            }
            
            // Only update state if this is a user-initiated pause (not during navigation)
            isPlaying = false;
            savePosition();
            updateUI('Paused');
            trackListenerCount('stop');
            console.log('⏸️ Video pause event - state saved');
        });
        
        video.addEventListener('ended', () => {
            isPlaying = false;
            savePosition();
            updateUI('Tap to play');
        });

        video.addEventListener('loadedmetadata', () => {
            console.log('HLS metadata loaded');
            // Only update UI to Live if actually playing
            if (video && !video.paused) {
                updateUI('Live');
            }
        });

        video.addEventListener('waiting', () => {
            updateUI('Buffering...');
            // Track when buffering starts
            if (!stallStartTime) {
                stallStartTime = Date.now();
                console.log('⚠️ Stream buffering started');
            }
            
            // Check if we've been buffering too long
            if (stallStartTime && (Date.now() - stallStartTime) > STALL_THRESHOLD) {
                console.log('⚠️ Buffering exceeded threshold, triggering catch-up');
                catchUpToLive();
            }
        });

        video.addEventListener('stalled', () => {
            updateUI('Buffering...');
            console.warn('⚠️ Stream stalled');
            if (!stallStartTime) {
                stallStartTime = Date.now();
            }
            
            // For MP3 streams, be more patient - stalled is normal during buffering
            // Only trigger catch-up if completely stuck (readyState === 0) for > 10 seconds
            const isHLS = hls || (currentStreamUrl && currentStreamUrl.includes('.m3u8'));
            const stallTimeout = isHLS ? STALL_THRESHOLD : 10000; // 10 seconds for MP3
            
            setTimeout(() => {
                if (video && !isReloading) {
                    if (isHLS && video.readyState < 3) {
                        // HLS: check if still stalled
                        console.log('⚠️ HLS stream still stalled, triggering catch-up');
                        catchUpToLive();
                    } else if (!isHLS && video.readyState === 0) {
                        // MP3: only if completely stuck (readyState === 0)
                        console.log('⚠️ MP3 stream completely stuck, triggering catch-up');
                        catchUpToLive();
                    }
                }
            }, stallTimeout);
        });

        video.addEventListener('playing', () => {
            updateUI('Live');
            // Reset stall tracking when playing resumes
            if (stallStartTime) {
                const stallDuration = Date.now() - stallStartTime;
                console.log('✅ Stream resumed after', stallDuration, 'ms of buffering');
                stallStartTime = null;
            }
        });
        
        video.addEventListener('canplay', () => {
            // Reset stall tracking when enough data is buffered
            if (stallStartTime) {
                stallStartTime = null;
            }
        });

        video.addEventListener('error', (e) => {
            console.error('Video error:', e);
            handleStreamError();
        });
        
        video.addEventListener('loadstart', () => {
        });
        
        video.addEventListener('canplay', () => {
        });

        return video;
    }

    // =============================================
    // CACHE-BUSTING URL HELPER
    // =============================================
    function addCacheBuster(url) {
        // Add timestamp to force live mode and prevent browser cache
        const separator = url.includes('?') ? '&' : '?';
        const timestamp = Date.now();
        return `${url}${separator}t=${timestamp}`;
    }

    // =============================================
    // CATCH-UP TO LIVE LOGIC
    // =============================================
    function catchUpToLive() {
        
        if (!video || !isPlaying) return;
        
        // Prevent too frequent recovery attempts (max once per 3 seconds)
        const now = Date.now();
        if (now - lastStallRecovery < 3000) {
            return;
        }
        lastStallRecovery = now;
        
        // Prevent catch-up if reload is already in progress
        if (isReloading) {
            return;
        }
        
        console.log('🔄 Attempting to catch up to live edge...');
        
        const isHLS = currentStreamUrl && (currentStreamUrl.includes('.m3u8') || hls);
        
        if (isHLS && hls) {
            // HLS stream - use HLS.js live sync position
            if (hls.liveSyncPosition !== null) {
                const liveSyncPos = hls.liveSyncPosition;
                const seekTime = Math.max(0, liveSyncPos - SEEK_BUFFER);
                
                console.log('🔄 Seeking to HLS live sync position:', seekTime);
                try {
                    video.currentTime = seekTime;
                } catch (e) {
                    console.warn('Seek failed, reloading stream:', e);
                    reloadStream();
                }
            } else if (video.duration && !isNaN(video.duration) && isFinite(video.duration)) {
                // Fallback to duration-based live edge
                const liveEdge = video.duration;
                const seekTime = Math.max(0, liveEdge - SEEK_BUFFER);
                console.log('🔄 Seeking to duration-based live edge:', seekTime);
                try {
                    video.currentTime = seekTime;
                } catch (e) {
                    console.warn('Seek failed, reloading stream:', e);
                    reloadStream();
                }
            } else {
                // Can't determine position, reload stream
                console.log('🔄 Cannot determine live position, reloading stream');
                reloadStream();
            }
        } else {
            // MP3 stream - be very patient, only reload if completely stuck
            
            // For MP3 streams:
            // - readyState 2 (HAVE_CURRENT_DATA) is NORMAL for live streams - don't reload
            // - readyState 1 (HAVE_METADATA) is also acceptable - don't reload
            // - Only reload if readyState 0 (HAVE_NOTHING) and stuck for > 10 seconds
            const isCompletelyStuck = video.readyState === 0 && stallStartTime && (Date.now() - stallStartTime) > 10000;
            
            if (isCompletelyStuck) {
                console.log('🔄 MP3 stream - completely stuck, reloading');
                reloadStream();
            } else {
                // Don't reload - MP3 streams naturally buffer and catch up
            }
        }
    }
    
    // =============================================
    // RELOAD STREAM (for catch-up)
    // =============================================
    function reloadStream() {
        
        if (!currentStreamUrl || !video) return;
        
        // Prevent concurrent reloads (HYPOTHESIS A)
        if (isReloading) {
            return;
        }
        
        // Cancel any pending play() timeout
        if (reloadTimeoutId) {
            clearTimeout(reloadTimeoutId);
            reloadTimeoutId = null;
        }
        
        isReloading = true;
        const wasPlaying = !video.paused;
        const currentTime = video.currentTime;
        
        // Reload with cache-busted URL
        const cacheBustedUrl = addCacheBuster(currentStreamUrl);
        
        if (hls) {
            // HLS.js - reload source
            console.log('🔄 Reloading HLS stream');
            hls.loadSource(cacheBustedUrl);
            hls.startLoad();
        } else {
            // Native HLS or MP3 - reload src
            console.log('🔄 Reloading stream src');
            video.src = cacheBustedUrl;
            video.load();
        }
        
        // Wait for load to complete before playing (HYPOTHESIS A)
        // Use 'canplaythrough' for better reliability, but fallback to 'canplay'
        const handleCanPlay = () => {
            
            // Clear timeout since we got the event
            if (reloadTimeoutId) {
                clearTimeout(reloadTimeoutId);
                reloadTimeoutId = null;
            }
            
            video.removeEventListener('canplay', handleCanPlay);
            video.removeEventListener('canplaythrough', handleCanPlayThrough);
            video.removeEventListener('error', handleLoadError);
            
            isReloading = false;
            
            // Small delay to ensure load is fully complete before play()
            setTimeout(() => {
                if (wasPlaying && video && !isPlayingPromise && !isReloading) {
                    isPlayingPromise = video.play();
                    if (isPlayingPromise !== undefined) {
                        isPlayingPromise
                            .then(() => {
                                isPlayingPromise = null;
                            })
                            .catch(err => {
                                isPlayingPromise = null;
                                console.warn('Failed to resume after reload:', err);
                            });
                    }
                }
            }, 100);
        };
        
        const handleCanPlayThrough = () => {
            
            // Clear timeout since we got the event
            if (reloadTimeoutId) {
                clearTimeout(reloadTimeoutId);
                reloadTimeoutId = null;
            }
            
            video.removeEventListener('canplay', handleCanPlay);
            video.removeEventListener('canplaythrough', handleCanPlayThrough);
            video.removeEventListener('error', handleLoadError);
            
            isReloading = false;
            
            // canplaythrough is more reliable - play immediately
            if (wasPlaying && !isPlayingPromise) {
                isPlayingPromise = video.play();
                if (isPlayingPromise !== undefined) {
                    isPlayingPromise
                        .then(() => {
                            isPlayingPromise = null;
                        })
                        .catch(err => {
                            isPlayingPromise = null;
                            console.warn('Failed to resume after reload (canplaythrough):', err);
                        });
                }
            }
        };
        
        const handleLoadError = (e) => {
            
            if (reloadTimeoutId) {
                clearTimeout(reloadTimeoutId);
                reloadTimeoutId = null;
            }
            
            video.removeEventListener('canplay', handleCanPlay);
            video.removeEventListener('canplaythrough', handleCanPlayThrough);
            video.removeEventListener('error', handleLoadError);
            isReloading = false;
        };
        
        // Listen for both events - canplaythrough is preferred but canplay is fallback
        video.addEventListener('canplaythrough', handleCanPlayThrough, { once: true });
        video.addEventListener('canplay', handleCanPlay, { once: true });
        video.addEventListener('error', handleLoadError, { once: true });
        
        // Fallback timeout in case events don't fire (increased to 5 seconds for MP3)
        reloadTimeoutId = setTimeout(() => {
            
            video.removeEventListener('canplay', handleCanPlay);
            video.removeEventListener('canplaythrough', handleCanPlayThrough);
            video.removeEventListener('error', handleLoadError);
            isReloading = false;
            
            // Only try to play if we have at least metadata (readyState >= 1) and no pending play
            if (wasPlaying && video && video.readyState >= 1 && !isPlayingPromise) {
                isPlayingPromise = video.play();
                if (isPlayingPromise !== undefined) {
                    isPlayingPromise.catch(err => {
                        isPlayingPromise = null;
                        console.warn('Failed to resume after reload (timeout):', err);
                    });
                }
            }
        }, 5000);
    }

    // =============================================
    // HLS INITIALIZATION
    // =============================================
    async function initHLS(streamUrl) {
        if (!video) {
            initVideoElement();
        }

        // #region agent log
        DEBUG_LOG('hls-live-player.js:initHLS', 'initHLS called', {
            streamUrl: streamUrl?.substring(0, 80),
            videoExists: !!video,
            videoPaused: video?.paused,
            videoSrc: video?.src?.substring(0, 80),
            isNavigating
        }, 'B');
        // #endregion

        // CRITICAL: Don't change src if video is already playing and src matches (without cache buster)
        if (video && video.src && !video.paused) {
            const currentSrcNoCache = video.src.replace(/\?t=\d+$/, '');
            const newSrcNoCache = streamUrl.replace(/\?t=\d+$/, '');
            if (currentSrcNoCache === newSrcNoCache) {
                // #region agent log
                DEBUG_LOG('hls-live-player.js:initHLS', 'Skipping src change - already playing same stream', {
                    currentSrc: video.src?.substring(0, 80),
                    streamUrl: streamUrl?.substring(0, 80)
                }, 'B');
                // #endregion
                return video;
            }
        }

        // Add cache-buster to stream URL to force live mode
        const cacheBustedUrl = addCacheBuster(streamUrl);

        // Check if stream is HLS (.m3u8) or MP3
        const isHLS = streamUrl.includes('.m3u8') || streamUrl.includes('application/vnd.apple.mpegurl');
        
        if (!isHLS) {
            // MP3 stream - use video element directly with cache-busted URL
            console.log('Using MP3 stream (direct playback)');
            if (hls) {
                hls.destroy();
                hls = null;
            }
            // #region agent log
            DEBUG_LOG('hls-live-player.js:initHLS', 'Setting video.src (MP3)', {
                oldSrc: video.src?.substring(0, 80),
                newSrc: cacheBustedUrl?.substring(0, 80),
                videoPaused: video.paused,
                isNavigating,
                willInterrupt: !video.paused
            }, 'B');
            // #endregion
            video.src = cacheBustedUrl;
            return video;
        }

        // HLS stream - check for native HLS support (Safari)
        if (video.canPlayType('application/vnd.apple.mpegurl')) {
            console.log('Using native HLS (Safari)');
            if (hls) {
                hls.destroy();
                hls = null;
            }
            // #region agent log
            DEBUG_LOG('hls-live-player.js:initHLS', 'Setting video.src (Native HLS)', {
                oldSrc: video.src?.substring(0, 80),
                newSrc: cacheBustedUrl?.substring(0, 80),
                videoPaused: video.paused,
                isNavigating,
                willInterrupt: !video.paused
            }, 'B');
            // #endregion
            video.src = cacheBustedUrl;
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
                // Optimized buffer settings for live streams to prevent over-buffering
                backBufferLength: 30, // Reduced from 90 - less back buffer for live
                maxBufferLength: 10, // Reduced from 30 - smaller buffer window
                maxMaxBufferLength: 20, // Reduced from 60 - prevent excessive buffering
                maxBufferSize: 30 * 1000 * 1000, // Reduced from 60MB - 30MB max buffer
                maxBufferHole: 0.3, // Reduced from 0.5 - tighter gap tolerance
                highBufferWatchdogPeriod: 1, // Reduced from 2 - check more frequently
                nudgeOffset: 0.1,
                nudgeMaxRetry: 5, // Increased from 3 - more retry attempts
                maxFragLoadingTimeOut: 3, // Reduced from 4 - faster timeout
                fragLoadingTimeOut: 3, // Reduced from 4 - faster timeout
                manifestLoadingTimeOut: 3, // Reduced from 4 - faster timeout
                // Additional live stream optimizations
                liveSyncDurationCount: 3, // Sync to 3 segments from live edge
                liveMaxLatencyDurationCount: 5, // Max 5 segments behind live
                liveDurationInfinity: false, // Don't treat live as infinite
                abrEwmaDefaultEstimate: 500000, // Default bitrate estimate
                abrBandWidthFactor: 0.95, // Conservative bandwidth factor
                abrBandWidthUpFactor: 0.7, // Conservative up factor
            });

            // #region agent log
            DEBUG_LOG('hls-live-player.js:initHLS', 'HLS.js loadSource called', {
                cacheBustedUrl: cacheBustedUrl?.substring(0, 80),
                videoPaused: video.paused,
                isNavigating,
                willInterrupt: !video.paused
            }, 'B');
            // #endregion
            hls.loadSource(cacheBustedUrl);
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
                            // Reload with cache-busted URL
                            const cacheBustedUrl = addCacheBuster(currentStreamUrl);
                            hls.loadSource(cacheBustedUrl);
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
                } else {
                    // Non-fatal errors - log but continue
                    if (data.type === Hls.ErrorTypes.NETWORK_ERROR) {
                        console.warn('Non-fatal network error, monitoring...');
                    }
                }
            });
            
            // Monitor buffer health
            hls.on(Hls.Events.BUFFER_APPENDING, () => {
                // Reset stall tracking when buffer is being filled
                if (stallStartTime) {
                    stallStartTime = null;
                }
            });

            return video;
        } catch (error) {
            console.error('HLS initialization failed:', error);
            // Fallback to direct playback with cache-busted URL
            video.src = cacheBustedUrl;
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
    // FETCH ACTIVE STREAM FROM API
    // =============================================
    let activeStreamData = null;
    
    async function fetchActiveStream() {
        try {
            const response = await fetch('/api/active-stream', {
                method: 'GET',
                cache: 'no-cache',
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                activeStreamData = await response.json();
                
                // Validate stream_url from API - must be from known good source
                if (activeStreamData.stream_url && 
                    !activeStreamData.stream_url.includes('phoebe.streamerr.co') && 
                    !activeStreamData.stream_url.includes('streamerr.co')) {
                    console.warn('⚠️ [Stream Update] Invalid stream URL from API:', activeStreamData.stream_url, '- using fallback');
                    // Replace invalid URL with fallback
                    activeStreamData.stream_url = STREAM_URLS.main;
                }
                
                console.log('🔄 [Stream Update] Active stream data:', activeStreamData);
                console.log('🔄 [Stream Update] Show:', activeStreamData.show || 'None', '| Title:', activeStreamData.title);
                updateHomepageUI(activeStreamData);
                return activeStreamData;
            } else {
                console.error('❌ [Stream Update] API response not OK:', response.status, response.statusText);
            }
        } catch (error) {
            console.error('❌ [Stream Update] Failed to fetch active stream:', error);
        }
        
        // Fallback - use known good stream URL
        activeStreamData = {
            stream_url: STREAM_URLS.main,
            title: 'Darling FM Live',
            status: 'live'
        };
        updateHomepageUI(activeStreamData);
        return activeStreamData;
    }
    
    function updateHomepageUI(data) {
        console.log('Updating homepage UI with:', data);
        const titleEl = document.getElementById('streamTitle');
        if (titleEl) {
            // Priority: show.title > show (if string) > title > default
            let displayText = 'Darling FM Live';
            if (data.show) {
                // Handle both object format {id, title} and string format
                displayText = typeof data.show === 'string' ? data.show : (data.show.title || 'Darling FM Live');
            } else if (data.title) {
                displayText = data.title;
            }
            titleEl.textContent = displayText;
            console.log('Updated streamTitle to:', displayText);
        }
        
        const badgeEl = document.getElementById('liveNowBadge');
        if (badgeEl) {
            // Show badge if there's a show (indicates live show) or status is live
            const hasShow = data.show && (typeof data.show === 'object' ? data.show.title : data.show);
            const shouldShow = (hasShow && data.status === 'live') ? true : false;
            badgeEl.style.display = shouldShow ? 'inline-block' : 'none';
            console.log('Updated liveNowBadge display:', shouldShow ? 'visible' : 'hidden');
        }
        
        // Update sticky player title with show name (or title if no show)
        const stickyTitleEl = document.getElementById('stickyPlayerTitle');
        if (stickyTitleEl) {
            // Use show.title if show is object, show if string, otherwise use title or default
            let displayText = 'Darling FM Live';
            if (data.show) {
                // Handle both object format {id, title} and string format
                displayText = typeof data.show === 'string' ? data.show : (data.show.title || 'Darling FM Live');
            } else if (data.title) {
                displayText = data.title;
            }
            stickyTitleEl.textContent = displayText;
        }
        
        // CRITICAL: Only update video.src if stream_url is different AND video is not currently playing
        // This prevents interrupting playback during navigation
        if (data.stream_url && video) {
            const currentSrc = video.src;
            const newSrc = data.stream_url;
            
            // Only update if:
            // 1. The URL is actually different
            // 2. AND the video is not currently playing (paused or no src)
            // This prevents interrupting active playback
            if (currentSrc !== newSrc && (video.paused || !currentSrc)) {
                console.log('Updating video src from stream_url:', newSrc);
                // Don't update if video is playing - let it continue with current stream
            } else if (currentSrc !== newSrc && !video.paused) {
                console.log('Skipping video src update - video is currently playing');
            }
        }
    }

    // =============================================
    // LISTENER COUNT TRACKING
    // =============================================
    // Generate or retrieve session ID
    function getSessionId() {
        let sessionId = sessionStorage.getItem('listener_session_id');
        if (!sessionId) {
            sessionId = 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            sessionStorage.setItem('listener_session_id', sessionId);
        }
        return sessionId;
    }

    async function trackListenerCount(action) {
        try {
            const sessionId = getSessionId();
            
            const response = await fetch('/api/listener/track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ 
                    action: action,
                    session_id: sessionId
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                console.log(`Listener count ${action === 'start' ? 'incremented' : 'decremented'}:`, data.count);
            } else {
                console.warn('Failed to track listener count:', await response.text());
            }
        } catch (error) {
            console.warn('Failed to track listener count:', error);
        }
    }

    // =============================================
    // PLAYBACK CONTROL
    // =============================================
    async function playStream(url = null) {
        // Fetch stream data if not available
        if (!activeStreamData) {
            await fetchActiveStream();
        }
        
        // Use provided URL, current URL, API stream_url, or fallback
        const streamUrl = url || currentStreamUrl || activeStreamData?.stream_url || STREAM_URLS.main;

        try {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:playStream', 'playStream called', {
                hasVideo: !!video,
                videoSrc: video?.src?.substring(0, 80),
                streamUrl: streamUrl?.substring(0, 80),
                videoPaused: video?.paused,
                isNavigating,
                willChangeSrc: !video || video.src !== streamUrl
            }, 'A');
            // #endregion
            
            // CRITICAL: Don't change video.src if video is already playing the same stream
            // This prevents AbortError during navigation
            const videoIsPlaying = video && !video.paused && video.src;
            const isSameStream = video && video.src && video.src.replace(/\?t=\d+$/, '') === streamUrl.replace(/\?t=\d+$/, '');
            
            // Initialize HLS if needed, but don't interrupt if already playing same stream
            if (!video) {
                await initHLS(streamUrl);
                currentStreamUrl = streamUrl;
            } else if (video.src !== streamUrl && !isSameStream) {
                // Only change src if not already playing the same stream
                // #region agent log
                DEBUG_LOG('hls-live-player.js:playStream', 'Changing video src', {
                    oldSrc: video.src?.substring(0, 80),
                    newSrc: streamUrl?.substring(0, 80),
                    videoIsPlaying,
                    isNavigating
                }, 'A');
                // #endregion
                await initHLS(streamUrl);
                currentStreamUrl = streamUrl;
            } else if (isSameStream && videoIsPlaying) {
                // Already playing the same stream - don't interrupt
                // #region agent log
                DEBUG_LOG('hls-live-player.js:playStream', 'Already playing same stream, skipping initHLS', {
                    videoSrc: video.src?.substring(0, 80),
                    streamUrl: streamUrl?.substring(0, 80)
                }, 'A');
                // #endregion
                // Just ensure currentStreamUrl is set
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

            // Ensure preload is set to none before playing (critical for live streams)
            if (video.preload !== 'none') {
                video.preload = 'none';
            }
            
            // Prevent multiple simultaneous play() calls
            if (isPlayingPromise) {
                try {
                    await isPlayingPromise;
                    return; // Already playing or handled
                } catch (e) {
                    // Previous play failed, continue with new attempt
                }
            }
            
            // Don't play if reload is in progress
            if (isReloading) {
                // Wait for reload to complete
                return;
            }
            
            try {
                // #region agent log
                DEBUG_LOG('hls-live-player.js:playStream', 'Calling video.play()', {
                    videoSrc: video.src?.substring(0, 80),
                    videoPaused: video.paused,
                    videoReadyState: video.readyState,
                    isNavigating,
                    isReloading
                }, 'D');
                // #endregion
                lastPlayTime = Date.now(); // Track when play() is called
                isPlayingPromise = video.play();
                await isPlayingPromise;
                isPlayingPromise = null;
                // #region agent log
                DEBUG_LOG('hls-live-player.js:playStream', 'video.play() succeeded', {
                    videoPaused: video.paused,
                    videoReadyState: video.readyState,
                    lastPlayTime
                }, 'D');
                // #endregion
                
            } catch (playError) {
                isPlayingPromise = null;
                
                // #region agent log
                DEBUG_LOG('hls-live-player.js:playStream', 'video.play() error', {
                    errorName: playError.name,
                    errorMessage: playError.message,
                    videoSrc: video.src?.substring(0, 80),
                    videoPaused: video.paused,
                    videoReadyState: video.readyState,
                    isReloading,
                    isNavigating
                }, 'D');
                // #endregion
                
                // Don't throw if it's an AbortError during reload - that's expected
                if (playError.name === 'AbortError' && isReloading) {
                    console.log('Play aborted during reload (expected)');
                    return;
                }
                
                throw playError;
            }
            
            setTimeout(() => {
                syncPosition();
            }, 1000);

            startPositionUpdates();
            startBufferHealthCheck();

            if (broadcastChannel) {
                broadcastChannel.postMessage({
                    type: 'play',
                    streamUrl: streamUrl,
                    tabId: tabId
                });
            }

            isPlaying = true;
            savePosition();
            updateUI('Live');
            trackListenerCount('start');
        } catch (error) {
            console.error('Play error:', error);
            handleStreamError();
            throw error;
        }
    }

    function pauseStream() {
        if (!video) {
            updateUI('Tap to play');
            return;
        }

        // If pause is from BroadcastChannel during navigation, don't actually pause
        // The pause event handler will ignore it, but we still need to prevent the actual pause
        if (pauseFromBroadcastChannel && (isNavigating || (video && video.dataset.isNavigating === 'true'))) {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:pauseStream', 'pauseStream called from BroadcastChannel during navigation - ignoring', {
                pauseFromBroadcastChannel,
                isNavigating,
                videoIsNavigating: video?.dataset.isNavigating
            }, 'J');
            // #endregion
            console.log('Pause from BroadcastChannel ignored - navigation in progress');
            // Don't set isPlaying = false here - preserve playing state
            return;
        }

        // Set flag AFTER checking if we should ignore
        isPlaying = false;
        
        // Pause the video
        video.pause();
        
        // Update UI immediately
        isPlaying = false;
        savePosition();
        updateUI('Paused');
        trackListenerCount('stop');

        if (broadcastChannel) {
            broadcastChannel.postMessage({
                type: 'pause',
                tabId: tabId
            });
        }

        stopPositionUpdates();
        stopBufferHealthCheck();
    }

    function togglePlayback() {
        // Check actual video state - this is the source of truth
        if (!video) {
            // No video element yet, start playing
            playStream().catch((error) => {
                console.error('Play error:', error);
                isPlaying = false;
                updateUI('Tap to play');
            });
            return;
        }
        
        const currentlyPlaying = !video.paused;
        
        if (currentlyPlaying) {
            pauseStream();
        } else {
            playStream().catch((error) => {
                console.error('Play error:', error);
                isPlaying = false;
                updateUI('Tap to play');
            });
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
    // BUFFER HEALTH MONITORING
    // =============================================
    function startBufferHealthCheck() {
        stopBufferHealthCheck();
        
        bufferCheckInterval = setInterval(() => {
            if (!video || !isPlaying || video.paused || isReloading) return;
            
            const isHLS = hls || (currentStreamUrl && currentStreamUrl.includes('.m3u8'));
            
            // For HLS streams: check if stuck (readyState < 3)
            // For MP3 streams: only check if completely stuck (readyState === 0)
            if (isHLS) {
                // HLS stream - check if stuck
                if (video.readyState < 3) { // Less than HAVE_FUTURE_DATA
                    if (!stallStartTime) {
                        stallStartTime = Date.now();
                    } else if ((Date.now() - stallStartTime) > STALL_THRESHOLD) {
                        console.warn('⚠️ Buffer health check: HLS stream appears stuck');
                        catchUpToLive();
                    }
                } else {
                    // Video has enough data, reset stall tracking
                    if (stallStartTime) {
                        stallStartTime = null;
                    }
                }
                
                // Check if we're too far behind live edge
                if (hls && hls.liveSyncPosition !== null) {
                    const livePos = hls.liveSyncPosition;
                    const currentPos = video.currentTime;
                    const lag = livePos - currentPos;
                    
                    // If more than 15 seconds behind, catch up
                    if (lag > 15) {
                        console.warn('⚠️ Buffer health check: Too far behind live edge (', lag, 's), catching up');
                        catchUpToLive();
                    }
                }
            } else {
                // MP3 stream - only check if completely stuck (readyState === 0)
                // readyState 1 (HAVE_METADATA) and 2 (HAVE_CURRENT_DATA) are NORMAL for live MP3
                if (video.readyState === 0) { // HAVE_NOTHING - completely stuck
                    if (!stallStartTime) {
                        stallStartTime = Date.now();
                    } else if ((Date.now() - stallStartTime) > 10000) { // 10 seconds for MP3
                        console.warn('⚠️ Buffer health check: MP3 stream completely stuck');
                        catchUpToLive();
                    }
                } else {
                    // MP3 has some data (readyState >= 1), reset stall tracking
                    if (stallStartTime) {
                        stallStartTime = null;
                    }
                }
            }
        }, BUFFER_CHECK_INTERVAL);
    }
    
    function stopBufferHealthCheck() {
        if (bufferCheckInterval) {
            clearInterval(bufferCheckInterval);
            bufferCheckInterval = null;
        }
    }

    // =============================================
    // ERROR HANDLING
    // =============================================
    function handleStreamError() {
        isPlaying = false;
        updateUI('Error loading stream');

        // Try backup stream
        if (currentStreamUrl === STREAM_URLS.main || (activeStreamData && currentStreamUrl === activeStreamData.stream_url)) {
            console.log('Trying backup stream...');
            setTimeout(() => {
                playStream(STREAM_URLS.backup).catch(() => {
                    updateUI('Tap to play');
                });
            }, 2000);
        } else {
            updateUI('Tap to play');
        }
    }

    // =============================================
    // UI UPDATES
    // =============================================
    function updateUI(status = null) {
        const player = document.getElementById('stickyPlayer');
        const playBtn = document.getElementById('stickyPlayBtn');
        const statusEl = document.getElementById('stickyPlayerStatus');

        if (!player || !playBtn || !statusEl) {
            // Retry if elements not found (might be during navigation)
            setTimeout(() => updateUI(status), 100);
            return;
        }

        // Always show sticky player - it should always be visible
        // Check if it's the admin floating button version
        const isAdminPlayer = player.classList.contains('admin-sticky-player');
        player.style.display = 'flex';
        player.style.visibility = 'visible';
        player.style.opacity = '1';

        // Update SVG icons
        const playIcon = playBtn.querySelector('.play-icon');
        const pauseIcon = playBtn.querySelector('.pause-icon');
        
        if (playIcon && pauseIcon) {
            const videoIsPlaying = video && !video.paused;
            const shouldShowPause = videoIsPlaying;
            
            if (shouldShowPause) {
                playIcon.style.display = 'none';
                pauseIcon.style.display = 'block';
                playBtn.setAttribute('aria-label', 'Pause');
            } else {
                playIcon.style.display = 'block';
                pauseIcon.style.display = 'none';
                playBtn.setAttribute('aria-label', 'Play');
            }
        }

        if (status) {
            statusEl.textContent = status;
        } else if (isPlaying && video && !video.paused) {
            statusEl.textContent = 'Live';
        } else {
            statusEl.textContent = 'Tap to play';
        }
        
        // Also update homepage button
        updateHomeButton();
    }
    
    // =============================================
    // HOMEPAGE BUTTON UPDATES
    // =============================================
    function updateHomeButton() {
        const homePlayBtn = document.getElementById('homePlayButton');
        if (!homePlayBtn) return;

        // Update SVG icons
        const playIcon = homePlayBtn.querySelector('.home-play-icon');
        const pauseIcon = homePlayBtn.querySelector('.home-pause-icon');
        
        if (playIcon && pauseIcon) {
            const videoIsPlaying = video && !video.paused;
            const shouldShowPause = videoIsPlaying;
            
            if (shouldShowPause) {
                playIcon.style.display = 'none';
                pauseIcon.style.display = 'block';
                homePlayBtn.setAttribute('aria-label', 'Pause live stream');
            } else {
                playIcon.style.display = 'block';
                pauseIcon.style.display = 'none';
                homePlayBtn.setAttribute('aria-label', 'Play live stream');
            }
        }
    }

    // =============================================
    // PAGE VISIBILITY HANDLING
    // =============================================
    async function handleVisibilityChange() {
        if (document.hidden) {
            // Page hidden - save position
            if (isPlaying && video) {
                savePosition();
            }
        } else {
            // Page visible - try to resume if was playing
            const saved = loadPosition();
            
            // Sync server time first
            await syncServerTime();
            
            if (saved && saved.isPlaying) {
                // Check if video is paused (should resume)
                if (video && video.paused) {
                    const timeSinceSave = (getServerTime() - saved.serverTime) / 1000;
                    console.log('Tab visible again. Time since save:', timeSinceSave, 'seconds');
                    
                    // Resume if within 5 minutes
                    if (timeSinceSave < 300) {
                        console.log('Attempting to resume playback...');
                        try {
                            
                            // Wait for any pending play to complete
                            if (isPlayingPromise) {
                                await isPlayingPromise;
                            }
                            
                            if (!isReloading && !isPlayingPromise) {
                                isPlayingPromise = video.play();
                                await isPlayingPromise;
                                isPlayingPromise = null;
                                
                                console.log('✅ Playback resumed successfully');
                            }
                            isPlaying = true;
                            savePosition();
                            updateUI('Live');
                            startPositionUpdates();
                        } catch (error) {
                            if (error.name === 'NotAllowedError') {
                                console.log('⚠️ Autoplay blocked on tab visibility change - user interaction required');
                                updateUI('Tap to resume');
                            } else {
                                console.warn('Failed to resume on visibility change:', error);
                                // Try full playStream to reinitialize if needed
                                try {
                                    await playStream(saved.streamUrl);
                                } catch (playError) {
                                    console.error('Full playStream also failed:', playError);
                                    updateUI('Tap to play');
                                }
                            }
                        }
                    } else {
                        // Too old, reset state
                        console.log('Saved position too old, resetting state');
                        isPlaying = false;
                        savePosition();
                        updateUI('Tap to play');
                    }
                } else if (video && !video.paused) {
                    // Already playing, just sync position
                    syncPosition();
                }
            } else if (isPlaying && video && !video.paused) {
                // Sync position if already playing
                syncPosition();
            }
        }
    }

    function handlePageHide() {
        // Set flags to prevent pause event from corrupting state
        isPageUnloading = true;
        isNavigating = true; // Also set for full page reloads
        
        // Save position before page unload - preserve current playing state
        if (video && !video.paused) {
            // Force isPlaying to true if video is actually playing
            isPlaying = true;
            savePosition();
        } else if (isPlaying && video) {
            // Video was playing but might be paused by browser - still save as playing
            savePosition();
        }
    }

    // =============================================
    // RESTORE PLAYBACK ON LOAD
    // =============================================
    async function restorePlayback() {
        // #region agent log
        DEBUG_LOG('hls-live-player.js:restorePlayback', 'restorePlayback called', {
            videoExists: !!video,
            videoPaused: video?.paused,
            videoSrc: video?.src?.substring(0, 80),
            isPlaying,
            currentStreamUrl: currentStreamUrl?.substring(0, 80),
            isNavigating
        }, 'C');
        // #endregion
        
        // CRITICAL: If video is already playing, don't restore (would interrupt)
        if (video && !video.paused && video.src) {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:restorePlayback', 'Skipping restore - video already playing', {
                videoSrc: video.src?.substring(0, 80),
                videoPaused: video.paused
            }, 'C');
            // #endregion
            console.log('✅ Video already playing, skipping restore');
            isPlaying = true;
            savePosition();
            updateUI('Live');
            return;
        }
        
        const saved = loadPosition();
        
        if (!saved || !saved.isPlaying) {
            updateUI('Tap to play');
            return;
        }

        // Sync server time first
        await syncServerTime();

        // Calculate if we should resume
        const timeSinceSave = (getServerTime() - saved.serverTime) / 1000;
        
        // Increased window to 5 minutes (300 seconds) for better auto-resume
        // This allows users to navigate between pages and come back
        if (timeSinceSave < 300) {
            try {
                console.log('Attempting to restore playback. Time since save:', timeSinceSave, 'seconds');
                
                // #region agent log
                DEBUG_LOG('hls-live-player.js:restorePlayback', 'restorePlayback starting', {
                    savedStreamUrl: saved.streamUrl?.substring(0, 80),
                    videoExists: !!video,
                    videoPaused: video?.paused,
                    videoSrc: video?.src?.substring(0, 80),
                    isNavigating
                }, 'C');
                // #endregion
                
                // CRITICAL: Check if video is already playing the same stream
                // If so, don't call playStream() which would interrupt it
                const videoIsPlaying = video && !video.paused && video.src;
                const savedSrcNoCache = saved.streamUrl?.replace(/\?t=\d+$/, '');
                const currentSrcNoCache = video?.src?.replace(/\?t=\d+$/, '');
                const isSameStream = videoIsPlaying && savedSrcNoCache === currentSrcNoCache;
                
                if (isSameStream) {
                    // #region agent log
                    DEBUG_LOG('hls-live-player.js:restorePlayback', 'Already playing same stream, skipping playStream', {
                        videoSrc: video.src?.substring(0, 80),
                        savedStreamUrl: saved.streamUrl?.substring(0, 80)
                    }, 'C');
                    // #endregion
                    console.log('✅ Video already playing same stream, no restore needed');
                    isPlaying = true;
                    savePosition();
                    updateUI('Live');
                    return;
                }
                
                // Set navigation flag to prevent pause events during restore
                isNavigating = true;
                
                await playStream(saved.streamUrl);
                
                // Clear navigation flag after restore
                isNavigating = false;
                navigationStartTime = 0;
                
                console.log('✅ Playback restored from saved position');
            } catch (error) {
                // Clear navigation flag on error
                isNavigating = false;
                navigationStartTime = 0;
                
                // #region agent log
                DEBUG_LOG('hls-live-player.js:restorePlayback', 'restorePlayback error', {
                    errorName: error.name,
                    errorMessage: error.message,
                    videoExists: !!video,
                    videoPaused: video?.paused
                }, 'C');
                // #endregion
                
                // Don't treat AbortError as a real error - it means play was interrupted
                // This can happen during navigation when video.src changes
                if (error.name === 'AbortError') {
                    console.log('⚠️ Playback restore aborted (video src changed during navigation)');
                    // Check if video is still playing
                    if (video && !video.paused) {
                        isPlaying = true;
                        savePosition();
                        updateUI('Live');
                    } else {
                        updateUI('Tap to resume');
                    }
                } else if (error.name === 'NotAllowedError' || error.name === 'NotSupportedError') {
                    console.log('⚠️ Autoplay blocked by browser. User interaction required.');
                    // Keep the UI showing it was playing, so user can click to resume
                    updateUI('Tap to resume');
                } else {
                    console.warn('Failed to restore playback:', error);
                    updateUI('Tap to play');
                }
            }
        } else {
            // Too old, just show UI
            console.log('Saved position too old:', timeSinceSave, 'seconds. Not auto-resuming.');
            updateUI('Tap to play');
        }
    }

    // =============================================
    // INITIALIZATION
    // =============================================
    async function init() {
        // Initialize video element
        initVideoElement();

        // Initialize broadcast channel
        initBroadcastChannel();
        
        // Fetch active stream data from API
        await fetchActiveStream();
        
        // Refresh stream data every 10 seconds to catch show start times almost immediately
        setInterval(fetchActiveStream, 10 * 1000);

        // Setup UI controls
        const playBtn = document.getElementById('stickyPlayBtn');

        if (playBtn && !playBtn.dataset.listenerAttached) {
            playBtn.dataset.listenerAttached = 'true';
            playBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                togglePlayback();
            });
        }

        // Note: homepage button listener is handled in home.blade.php
        // But we also reconnect it here after navigation to ensure it works
        const homePlayBtn = document.getElementById('homePlayButton');
        if (homePlayBtn && !homePlayBtn.dataset.listenerAttached) {
            homePlayBtn.dataset.listenerAttached = 'true';
            homePlayBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (window.DarlingFMAudio && window.DarlingFMAudio.toggle) {
                    window.DarlingFMAudio.toggle();
                }
            });
        }
        
        // Update the icon state
        updateHomeButton();

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
        
        // CRITICAL: Always show sticky player on initialization
        // Even if not playing, show it so user can start playback
        updateUI();
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
        updateHomeButton: updateHomeButton
    };

    // =============================================
    // STARTUP
    // =============================================
    // Initialize on page load
    function initializePlayer() {
        // #region agent log
        DEBUG_LOG('hls-live-player.js:initializePlayer', 'initializePlayer called', {
            hasVideo: !!video,
            videoInDOM: !!document.getElementById('station-player'),
            isPlaying,
            currentStreamUrl: currentStreamUrl?.substring(0, 50) + '...'
        }, 'A');
        // #endregion
        
        // Only initialize if not already initialized
        if (!video || !document.getElementById('station-player')) {
            init();
        } else {
            // Player already exists, just ensure UI is updated
            // CRITICAL: Ensure sticky player is visible
            const player = document.getElementById('stickyPlayer');
            if (player) {
                player.style.display = 'flex';
                player.style.visibility = 'visible';
                player.style.opacity = '1';
            }
            updateUI();
            updateHomeButton();
        }
    }
    
    // Track navigation events - CRITICAL: Set flag before navigation to prevent pause events
    // Use capture phase to set flag as early as possible
    document.addEventListener('livewire:navigate', function() {
        // #region agent log
        LOG_NAV('NAVIGATE STARTED (before DOM swap)', {
            hasVideo: !!video,
            videoPaused: video?.paused,
            videoSrc: video?.src?.substring(0, 100),
            isPlayingBeforeFlag: isPlaying,
            currentStreamUrl: currentStreamUrl?.substring(0, 50),
            videoInDOM: !!document.getElementById('station-player'),
            videoReadyState: video?.readyState,
            isNavigatingBefore: isNavigating
        }, 'A');
        // #endregion
        
        // Set navigation flag IMMEDIATELY and BEFORE navigation happens to prevent pause events
        // This must be set synchronously before any async operations
        isNavigating = true;
        navigationStartTime = Date.now(); // Track when navigation started
        
        // #region agent log
        DEBUG_LOG('hls-live-player.js:livewire:navigate', 'isNavigating flag set to true (SYNC)', {
            isNavigating,
            isPlaying,
            currentStreamUrl: currentStreamUrl?.substring(0, 50),
            willPreventPause: true,
            timestamp: Date.now(),
            navigationStartTime
        }, 'A');
        // #endregion
        
        // Also set a flag on the video element itself as a backup
        if (video) {
            video.dataset.isNavigating = 'true';
        }
        
        // #region agent log
        LOG_NAV('isNavigating flag set to true', {
            isNavigating,
            isPlaying
        }, 'A');
        // #endregion
        
        // CRITICAL FIX: Save state based on isPlaying flag and currentStreamUrl, not just video.paused
        // The video element may be paused by browser during navigation, but we should preserve our state
        // Check if we have a valid stream URL (even if video element is paused, the URL indicates it was playing)
        const hasValidStreamUrl = currentStreamUrl && typeof currentStreamUrl === 'string' && currentStreamUrl.length > 0 && currentStreamUrl !== 'undefined';
        
        if (isPlaying || hasValidStreamUrl) {
            // Force isPlaying to true if we have a stream URL (indicates was playing)
            if (!isPlaying && hasValidStreamUrl) {
                isPlaying = true;
                // #region agent log
                LOG_NAV('Forcing isPlaying=true because currentStreamUrl exists', {
                    currentStreamUrl: currentStreamUrl?.substring(0, 80)
                }, 'A');
                // #endregion
            }
            savePosition();
            // #region agent log
            LOG_NAV('State saved (isPlaying or currentStreamUrl indicates playing)', {
                savedIsPlaying: true,
                isPlaying,
                currentStreamUrl: currentStreamUrl?.substring(0, 80),
                hasValidStreamUrl,
                videoPaused: video?.paused,
                videoSrc: video?.src?.substring(0, 80)
            }, 'A');
            // #endregion
        } else if (video && !video.paused && video.src) {
            // Fallback: if video element shows playing, save that
            isPlaying = true;
            savePosition();
            // #region agent log
            LOG_NAV('State saved (video element shows playing)', {
                savedIsPlaying: true,
                videoSrc: video.src?.substring(0, 80)
            }, 'A');
            // #endregion
        } else {
            // #region agent log
            LOG_NAV('State NOT saved (no indication of playing)', {
                videoExists: !!video,
                videoPaused: video?.paused,
                isPlaying,
                currentStreamUrl: currentStreamUrl?.substring(0, 80) || 'none',
                hasValidStreamUrl,
                videoHasSrc: !!video?.src
            }, 'D');
            // #endregion
        }
    });
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializePlayer);
    } else {
        initializePlayer();
    }
    
    // CRITICAL: Show sticky player immediately on page load
    // This ensures it's visible even before audio initialization
    (function showStickyPlayerOnLoad() {
        const player = document.getElementById('stickyPlayer');
        if (player) {
            player.style.display = 'flex';
            player.style.visibility = 'visible';
            player.style.opacity = '1';
        } else {
            // Retry if not found yet
            setTimeout(showStickyPlayerOnLoad, 100);
        }
    })();
    
    // Re-initialize on Livewire navigation (SPA mode)
    document.addEventListener('livewire:navigated', function() {
        // Keep isNavigating flag set during reconnection to prevent pause events
        // (It was set in livewire:navigate event, keep it until resume is complete)
        
        // #region agent log
        LOG_NAV('NAVIGATED COMPLETED (after DOM swap)', {
            hasVideo: !!video,
            videoPaused: video?.paused,
            videoSrc: video?.src?.substring(0, 100),
            videoReadyState: video?.readyState,
            isPlaying,
            currentStreamUrl: currentStreamUrl?.substring(0, 50),
            videoInDOM: !!document.getElementById('station-player'),
            stickyPlayBtnExists: !!document.getElementById('stickyPlayBtn')
        }, 'A');
        // #endregion
        
        console.log('Livewire navigated - ensuring player persists');
        
        // CRITICAL: Load saved state to check if video was playing before navigation
        // The state was saved in livewire:navigate event before navigation started
        const savedState = loadPosition();
        // #region agent log
        LOG_NAV('Loaded saved state', {
            savedStateExists: !!savedState,
            savedIsPlaying: savedState?.isPlaying,
            savedStreamUrl: savedState?.streamUrl?.substring(0, 80),
            currentIsPlaying: isPlaying,
            currentStreamUrl: currentStreamUrl?.substring(0, 80),
            videoPaused: video?.paused,
            videoHasSrc: !!video?.src,
            videoReadyState: video?.readyState
        }, 'D');
        // #endregion
        
        // CRITICAL FIX: Use isPlaying state variable and currentStreamUrl instead of video element properties
        // The video element may lose its src during DOM swap, but our state variables persist
        // Priority: 1) savedState.isPlaying, 2) isPlaying flag, 3) currentStreamUrl exists (indicates was playing)
        const hasValidStreamUrl = currentStreamUrl && typeof currentStreamUrl === 'string' && currentStreamUrl.length > 0 && currentStreamUrl !== 'undefined';
        const wasPlayingBeforeNav = (savedState && savedState.isPlaying) || 
                                    isPlaying || 
                                    hasValidStreamUrl;
        
        // CRITICAL: If we were playing before navigation, set isPlaying and lastPlayTime IMMEDIATELY
        // This protects against pause events that fire during navigation before we can check video state
        // But only if video exists and has a src (indicates it was actually playing)
        if (wasPlayingBeforeNav && video && video.src) {
            isPlaying = true;
            lastPlayTime = Date.now();
            // #region agent log
            LOG_NAV('Setting isPlaying and lastPlayTime early (wasPlayingBeforeNav)', {
                isPlaying,
                lastPlayTime,
                wasPlayingBeforeNav,
                videoHasSrc: !!video.src,
                videoPaused: video.paused
            }, 'D');
            // #endregion
        }
        
        // Use currentStreamUrl if available, otherwise fall back to saved state or video.src
        const savedSrc = hasValidStreamUrl ? currentStreamUrl : (savedState ? savedState.streamUrl : null) || (video ? video.src : null);
        
        // #region agent log
        LOG_NAV('wasPlayingBeforeNav calculated (FIXED)', {
            wasPlayingBeforeNav,
            savedStateIsPlaying: savedState?.isPlaying,
            currentIsPlaying: isPlaying,
            currentStreamUrl: currentStreamUrl?.substring(0, 80) || 'none',
            hasValidStreamUrl,
            videoPaused: video?.paused,
            videoHasSrc: !!video?.src,
            savedSrc: savedSrc?.substring(0, 80) || 'none',
            calculationMethod: savedState?.isPlaying ? 'savedState' : (isPlaying ? 'isPlaying' : (hasValidStreamUrl ? 'currentStreamUrl' : 'none'))
        }, 'D');
        // #endregion
        
        // CRITICAL: Reconnect to persisted video element
        const existingVideo = document.getElementById('station-player');
        if (existingVideo) {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:livewire:navigated', 'found persisted video element', {
                existingVideoPaused: existingVideo.paused,
                existingVideoSrc: existingVideo.src?.substring(0, 100) + '...',
                existingVideoReadyState: existingVideo.readyState,
                currentVideoRef: video ? 'exists' : 'null',
                videosMatch: video === existingVideo,
                wasPlayingBeforeNav
            }, 'A');
            // #endregion
            
            // Always update video reference to persisted element
            if (video && video !== existingVideo) {
                // We had a different reference, transfer state
                const currentSrc = video.src || savedSrc || currentStreamUrl;
                const currentTime = video.currentTime;
                const wasPlaying = !video.paused;
                
                // #region agent log
                LOG_NAV('Transferring state to persisted element', {
                    oldVideoSrc: video.src?.substring(0, 80),
                    oldVideoPaused: video.paused,
                    oldVideoPlaying: wasPlaying,
                    existingVideoSrc: existingVideo.src?.substring(0, 80),
                    existingVideoPaused: existingVideo.paused,
                    currentSrc: currentSrc?.substring(0, 80),
                    currentTime,
                    willChangeSrc: existingVideo.src !== currentSrc
                }, 'B');
                // #endregion
                
                // CRITICAL: Don't change src if existingVideo is already playing the same stream
                // This prevents interrupting playback
                const existingSrcNoCache = existingVideo.src?.replace(/\?t=\d+$/, '');
                const currentSrcNoCache = currentSrc?.replace(/\?t=\d+$/, '');
                const isSameStream = existingSrcNoCache && currentSrcNoCache && existingSrcNoCache === currentSrcNoCache;
                
                // Transfer src and state to persisted element
                if (currentSrc && existingVideo.src !== currentSrc && !isSameStream) {
                    // #region agent log
                    LOG_NAV('Setting existingVideo.src (transfer state)', {
                        oldSrc: existingVideo.src?.substring(0, 80) || 'none',
                        newSrc: currentSrc?.substring(0, 80),
                        existingVideoPaused: existingVideo.paused,
                        wasPlayingBeforeNav,
                        willInterrupt: !existingVideo.paused
                    }, 'B');
                    // #endregion
                    existingVideo.src = currentSrc;
                    currentStreamUrl = currentSrc;
                } else if (isSameStream) {
                    // #region agent log
                    LOG_NAV('Skipping src change - same stream already loaded', {
                        existingSrc: existingVideo.src?.substring(0, 80),
                        currentSrc: currentSrc?.substring(0, 80)
                    }, 'B');
                    // #endregion
                    // Keep current src, just update currentStreamUrl
                    currentStreamUrl = existingVideo.src || currentSrc;
                }
                if (currentTime > 0) {
                    existingVideo.currentTime = currentTime;
                }
            } else if (!video) {
                // No video reference, use persisted element
                // #region agent log
                LOG_NAV('No video reference, using persisted element', {
                    existingVideoSrc: existingVideo.src?.substring(0, 80),
                    existingVideoPaused: existingVideo.paused,
                    savedSrc: savedSrc?.substring(0, 80)
                }, 'B');
                // #endregion
                // If persisted element has src, use it
                if (existingVideo.src) {
                    currentStreamUrl = existingVideo.src;
                } else if (savedSrc) {
                    // Use saved src if persisted element doesn't have one
                    // #region agent log
                    LOG_NAV('Setting existingVideo.src from savedSrc', {
                        oldSrc: existingVideo.src?.substring(0, 80) || 'none',
                        newSrc: savedSrc?.substring(0, 80),
                        existingVideoPaused: existingVideo.paused
                    }, 'B');
                    // #endregion
                    existingVideo.src = savedSrc;
                    currentStreamUrl = savedSrc;
                }
            }
            
            // Update video reference to persisted element
            video = existingVideo;
            
            // #region agent log
            LOG_NAV('Video reference updated', {
                videoSrc: video.src?.substring(0, 80),
                videoPaused: video.paused,
                videoReadyState: video.readyState
            }, 'B');
            // #endregion
            
            // Ensure video has src if it should be playing
            // CRITICAL: Only set src if video doesn't have one OR if it's different
            // Don't change src if video is already playing the same stream
            if (wasPlayingBeforeNav && savedSrc) {
                const videoSrcNoCache = video.src?.replace(/\?t=\d+$/, '');
                const savedSrcNoCache = savedSrc.replace(/\?t=\d+$/, '');
                const needsSrc = !video.src || (videoSrcNoCache !== savedSrcNoCache);
                
                if (needsSrc) {
                    // #region agent log
                    LOG_NAV('Setting video.src for wasPlayingBeforeNav', {
                        oldSrc: video.src?.substring(0, 80) || 'none',
                        newSrc: savedSrc?.substring(0, 80),
                        wasPlayingBeforeNav,
                        videoPaused: video.paused,
                        willInterrupt: !video.paused && video.src
                    }, 'B');
                    // #endregion
                    video.src = savedSrc;
                    currentStreamUrl = savedSrc;
                } else {
                    // #region agent log
                    LOG_NAV('Skipping video.src change - already has same stream', {
                        videoSrc: video.src?.substring(0, 80),
                        savedSrc: savedSrc?.substring(0, 80)
                    }, 'B');
                    // #endregion
                }
            }
            
            // #region agent log
            LOG_NAV('Before initVideoElement()', {
                videoSrc: video.src?.substring(0, 80),
                videoPaused: video.paused,
                isNavigating,
                eventListenersAttached,
                videoElementWithListenersMatches: videoElementWithListeners === video
            }, 'C');
            // #endregion
            
            // CRITICAL: Keep isNavigating flag set during listener reattachment
            // This prevents pause events from being accepted during the reattachment process
            const wasNavigating = isNavigating;
            if (!isNavigating) {
                isNavigating = true;
            }
            
            // Reattach event listeners to persisted element
            initVideoElement();
            
            // Restore isNavigating flag (or keep it true if it was already true)
            if (!wasNavigating) {
                    // Only clear if it wasn't set before - give it a moment for any pending events
                    setTimeout(() => {
                        // Only clear if video is still playing or was playing before nav
                        if (wasPlayingBeforeNav && video && !video.paused) {
                            isNavigating = false;
                            navigationStartTime = 0;
                            if (video) {
                                delete video.dataset.isNavigating;
                            }
                            // #region agent log
                            LOG_NAV('isNavigating cleared after initVideoElement (video playing)', {
                                isNavigating,
                                videoIsNavigating: video?.dataset.isNavigating
                            }, 'C');
                            // #endregion
                        } else if (!wasPlayingBeforeNav) {
                            isNavigating = false;
                            navigationStartTime = 0;
                            if (video) {
                                delete video.dataset.isNavigating;
                            }
                            // #region agent log
                            LOG_NAV('isNavigating cleared after initVideoElement (was not playing)', {
                                isNavigating,
                                videoIsNavigating: video?.dataset.isNavigating
                            }, 'C');
                            // #endregion
                        }
                    }, 100);
            }
            
            // #region agent log
            LOG_NAV('After initVideoElement()', {
                videoSrc: video.src?.substring(0, 80),
                videoPaused: video.paused,
                isNavigating,
                wasNavigating
            }, 'C');
            // #endregion
            
            // If we're using HLS and the video has a src, reattach HLS to persisted element
            if (hls && video.src && currentStreamUrl && currentStreamUrl.includes('.m3u8')) {
                // Reattach HLS to the persisted video element
                try {
                    hls.attachMedia(video);
                    console.log('✅ HLS reattached to persisted video element');
                } catch (err) {
                    console.warn('Failed to reattach HLS:', err);
                }
            }
        } else {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:livewire:navigated', 'WARNING: persisted video element not found!', {}, 'B');
            // #endregion
        }
        
        if (video) {
            // If video was playing before navigation, ensure it continues
            if (wasPlayingBeforeNav) {
                // Ensure video has a source
                if (!video.src && savedSrc) {
                    // #region agent log
                    LOG_NAV('Setting video.src (no src branch)', {
                        oldSrc: video.src?.substring(0, 80) || 'none',
                        newSrc: savedSrc?.substring(0, 80),
                        wasPlayingBeforeNav
                    }, 'B');
                    // #endregion
                    video.src = savedSrc;
                    currentStreamUrl = savedSrc;
                }
                
                if (video.src) {
                    // #region agent log
                    LOG_NAV('Video has src, checking if resume needed', {
                        videoSrc: video.src?.substring(0, 80),
                        videoPaused: video.paused,
                        videoReadyState: video.readyState,
                        isNavigating,
                        wasPlayingBeforeNav
                    }, 'E');
                    // #endregion
                    
                    if (video.paused) {
                        // Video was paused during navigation - resume it immediately
                        // Keep isNavigating flag set during resume to prevent pause event from firing
                        // #region agent log
                        LOG_NAV('ATTEMPTING RESUME (video paused)', {
                            videoSrc: video.src?.substring(0, 80),
                            videoPaused: video.paused,
                            videoReadyState: video.readyState,
                            isNavigating,
                            wasPlayingBeforeNav
                        }, 'E');
                        // #endregion
                        console.log('🔄 Resuming playback after navigation');
                        lastPlayTime = Date.now(); // Track when play() is called
                        const playPromise = video.play();
                        playPromise.then(() => {
                            // CRITICAL: Wait a moment for video to actually start playing
                            // Sometimes play() resolves but video.paused is still true briefly
                            setTimeout(() => {
                                // #region agent log
                                LOG_NAV('RESUME SUCCESS', {
                                    videoPaused: video.paused,
                                    videoReadyState: video.readyState,
                                    timeSincePlay: Date.now() - lastPlayTime
                                }, 'E');
                                // #endregion
                                
                                // CRITICAL: Check if video is actually playing
                                // For live streams, readyState >= 2 (HAVE_CURRENT_DATA) is acceptable even if paused
                                // The video might be buffering but will start playing soon
                                const isActuallyPlaying = !video.paused;
                                const hasData = video.readyState >= 2; // HAVE_CURRENT_DATA or better
                                
                                if (isActuallyPlaying || hasData) {
                                    // Video is playing or has data (will play soon)
                                    isPlaying = true;
                                    lastPlayTime = Date.now(); // Update lastPlayTime to protect against pause events
                                    savePosition();
                                    updateUI('Live');
                                    startPositionUpdates();
                                    startBufferHealthCheck();
                                    
                                    if (!isActuallyPlaying && hasData) {
                                        // Video has data but is paused - might be buffering
                                        console.log('⚠️ Video has data but is paused - might be buffering');
                                    }
                                } else {
                                    // Video didn't actually start - might need user interaction
                                    console.warn('⚠️ Play() resolved but video has no data');
                                    updateUI('Tap to resume');
                                }
                                
                                // Clear navigation flag AFTER resume is complete
                                isNavigating = false;
                                navigationStartTime = 0; // Clear navigation start time
                                if (video) {
                                    delete video.dataset.isNavigating;
                                }
                                // #region agent log
                                LOG_NAV('isNavigating cleared after successful resume', {
                                    isNavigating,
                                    videoIsNavigating: video?.dataset.isNavigating
                                }, 'E');
                                // #endregion
                                console.log('✅ Playback resumed successfully after navigation');
                            }, 100); // Small delay to let video actually start
                        }).catch(err => {
                            // #region agent log
                            LOG_NAV('RESUME FAILED', {
                                errorName: err.name,
                                errorMessage: err.message,
                                videoPaused: video.paused,
                                videoReadyState: video.readyState
                            }, 'E');
                            // #endregion
                            // Clear navigation flag on error
                            isNavigating = false;
                            navigationStartTime = 0; // Clear navigation start time
                            if (video) {
                                delete video.dataset.isNavigating;
                            }
                            // Autoplay might be blocked - this is expected in some browsers
                            if (err.name === 'NotAllowedError') {
                                console.log('⚠️ Autoplay blocked after navigation - user interaction required');
                                updateUI('Tap to resume');
                            } else {
                                console.error('Failed to resume playback after navigation:', err);
                                updateUI('Tap to play');
                            }
                        });
                    } else if (!video.paused) {
                        // Video is still playing - just update state
                        // CRITICAL: Set lastPlayTime to prevent pause events from interrupting
                        // This protects against pause events that fire immediately after navigation
                        lastPlayTime = Date.now();
                        // #region agent log
                        LOG_NAV('Video still playing (no resume needed)', {
                            videoPaused: video.paused,
                            videoReadyState: video.readyState,
                            lastPlayTime,
                            willProtectFromPause: true
                        }, 'F');
                        // #endregion
                        isPlaying = true;
                        savePosition();
                        updateUI('Live');
                        startPositionUpdates();
                        startBufferHealthCheck();
                        // Clear navigation flag - video is already playing
                        // But keep it set briefly to protect against immediate pause events
                        setTimeout(() => {
                            isNavigating = false;
                            navigationStartTime = 0; // Clear navigation start time
                            if (video) {
                                delete video.dataset.isNavigating;
                            }
                            // #region agent log
                            LOG_NAV('isNavigating cleared (video already playing)', {
                                isNavigating,
                                videoIsNavigating: video?.dataset.isNavigating
                            }, 'F');
                            // #endregion
                        }, 200); // Small delay to protect against immediate pause events
                        console.log('✅ Video still playing after navigation');
                    }
                } else {
                    // Video should be playing but has no src - reinitialize stream
                    console.log('🔄 Video should be playing but has no src - reinitializing stream');
                    if (savedSrc || currentStreamUrl) {
                        playStream(savedSrc || currentStreamUrl).then(() => {
                            isNavigating = false;
                            if (video) {
                                delete video.dataset.isNavigating;
                            }
                        }).catch(err => {
                            isNavigating = false;
                            if (video) {
                                delete video.dataset.isNavigating;
                            }
                            console.error('Failed to reinitialize stream after navigation:', err);
                            updateUI('Tap to play');
                        });
                    } else {
                        isNavigating = false;
                        navigationStartTime = 0;
                        if (video) {
                            delete video.dataset.isNavigating;
                        }
                    }
                }
            } else {
                // Was not playing before navigation - clear flag immediately
                isNavigating = false;
                navigationStartTime = 0;
                if (video) {
                    delete video.dataset.isNavigating;
                }
                if (!wasPlayingBeforeNav && !video.paused) {
                    // Video is playing but shouldn't be - pause it
                    video.pause();
                    isPlaying = false;
                    updateUI('Tap to play');
                }
            }
        } else {
            // No video element - clear flag
            isNavigating = false;
            navigationStartTime = 0;
            if (video) {
                delete video.dataset.isNavigating;
            }
        }
        
        // Reconnect UI event listeners if needed
        const stickyPlayBtn = document.getElementById('stickyPlayBtn');
        if (!stickyPlayBtn) {
            // #region agent log
            DEBUG_LOG('hls-live-player.js:livewire:navigated', 'UI replaced, reinitializing', {}, 'A');
            // #endregion
            
            // UI was replaced, need to reconnect
            setTimeout(initializePlayer, 100);
        } else {
            // Reconnect button handler if needed (prevent duplicates)
            if (!stickyPlayBtn.dataset.listenerAttached) {
                stickyPlayBtn.dataset.listenerAttached = 'true';
                stickyPlayBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    togglePlayback();
                });
            }
            
            // CRITICAL: Reconnect home play button handler after navigation
            const homePlayBtn = document.getElementById('homePlayButton');
            if (homePlayBtn && !homePlayBtn.dataset.listenerAttached) {
                homePlayBtn.dataset.listenerAttached = 'true';
                homePlayBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (window.DarlingFMAudio && window.DarlingFMAudio.toggle) {
                        window.DarlingFMAudio.toggle();
                    }
                });
            }
            
            // CRITICAL: Ensure sticky player is visible after navigation
            const player = document.getElementById('stickyPlayer');
            if (player) {
                player.style.display = 'flex';
                player.style.visibility = 'visible';
                player.style.opacity = '1';
            }
            updateUI();
            updateHomeButton();
        }
    });

})();

