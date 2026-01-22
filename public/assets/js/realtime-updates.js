/**
 * Real-time Content Updates System
 * Handles polling for content updates and refreshes UI without page reload
 */

(function() {
    'use strict';
    
    let lastUpdateTime = Math.floor(Date.now() / 1000);
    let pollInterval = null;
    let isPolling = false;
    let consecutiveErrors = 0;
    let backoffDelay = 30000; // Start with 30 seconds
    const POLL_INTERVAL = 30000; // 30 seconds
    const MAX_BACKOFF = 300000; // Max 5 minutes
    const BACKOFF_MULTIPLIER = 2;
    
    /**
     * Initialize real-time updates
     */
    function init() {
        // Start polling for updates
        startPolling();
        
        // Listen for visibility changes (pause when tab is hidden)
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopPolling();
            } else {
                startPolling();
            }
        });
        
        // Stop polling when page is unloading
        window.addEventListener('beforeunload', stopPolling);
    }
    
    /**
     * Start polling for updates
     */
    function startPolling() {
        if (isPolling) return;
        
        isPolling = true;
        // Use current backoff delay (which resets to POLL_INTERVAL on success)
        pollInterval = setInterval(checkForUpdates, backoffDelay);
        
        // Initial check
        checkForUpdates();
    }
    
    /**
     * Stop polling
     */
    function stopPolling() {
        if (pollInterval) {
            clearInterval(pollInterval);
            pollInterval = null;
        }
        isPolling = false;
    }
    
    /**
     * Check for content updates
     */
    async function checkForUpdates() {
        try {
            // Use relative URL for environment compatibility
            const pollUrl = `${window.location.origin}/api/realtime/poll?last_update=${lastUpdateTime}`;
            const response = await fetch(pollUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            
            if (!response.ok) {
                throw new Error(`Poll request failed: ${response.status}`);
            }
            
            const data = await response.json();
            
            // Reset error count on success
            consecutiveErrors = 0;
            backoffDelay = POLL_INTERVAL;
            
            if (data.has_updates && data.updates.length > 0) {
                processUpdates(data.updates);
                // Update last update time to most recent timestamp
                if (data.updates.length > 0) {
                    lastUpdateTime = Math.max(...data.updates.map(u => u.timestamp));
                }
            }
            
            if (data.current_time) {
                lastUpdateTime = data.current_time;
            }
        } catch (error) {
            consecutiveErrors++;
            console.error('Real-time update check failed:', error);
            
            // Exponential backoff on errors
            if (consecutiveErrors > 0) {
                backoffDelay = Math.min(backoffDelay * BACKOFF_MULTIPLIER, MAX_BACKOFF);
                console.warn(`Real-time polling backing off. Next retry in ${backoffDelay / 1000} seconds`);
                
                // Stop current interval and restart with backoff
                stopPolling();
                setTimeout(() => {
                    if (!document.hidden) {
                        startPolling();
                    }
                }, backoffDelay);
            }
        }
    }
    
    /**
     * Process updates and refresh UI
     */
    function processUpdates(updates) {
        updates.forEach(update => {
            switch (update.type) {
                case 'news':
                    handleNewsUpdate(update);
                    break;
                case 'show':
                    handleShowUpdate(update);
                    break;
                case 'event':
                    handleEventUpdate(update);
                    break;
            }
        });
    }
    
    /**
     * Handle news post updates
     */
    function handleNewsUpdate(update) {
        const { action, data, id } = update;
        
        // Find existing news card
        const existingCard = document.querySelector(`[data-post-id="${id}"]`);
        
        if (action === 'deleted' && existingCard) {
            // Fade out and remove
            existingCard.style.transition = 'opacity 0.5s';
            existingCard.style.opacity = '0';
            setTimeout(() => existingCard.remove(), 500);
            return;
        }
        
        if (action === 'created' || action === 'updated') {
            // If card exists, update it with animation
            if (existingCard) {
                updateNewsCard(existingCard, data);
            } else {
                // Add new card to the top of the grid
                addNewsCard(data);
            }
        }
    }
    
    /**
     * Update existing news card
     */
    function updateNewsCard(card, data) {
        // Highlight the card to show it was updated
        card.style.transition = 'box-shadow 0.3s';
        card.style.boxShadow = '0 0 20px rgba(255, 0, 0, 0.5)';
        
        // Update content
        const titleEl = card.querySelector('.post-title');
        const imageEl = card.querySelector('.post-image');
        const metaEl = card.querySelector('.post-meta');
        
        if (titleEl && data.title) {
            titleEl.textContent = data.title;
        }
        
        if (imageEl && data.image) {
            imageEl.style.backgroundImage = `url('${data.image}')`;
        }
        
        if (metaEl && data.date) {
            const dateSpan = metaEl.querySelector('span');
            if (dateSpan) {
                dateSpan.innerHTML = `<i class="far fa-calendar"></i> ${data.date}`;
            }
        }
        
        // Remove highlight after 3 seconds
        setTimeout(() => {
            card.style.boxShadow = '';
        }, 3000);
    }
    
    /**
     * Add new news card to the grid
     */
    function addNewsCard(data) {
        const postsGrid = document.querySelector('.posts-grid');
        if (!postsGrid) return;
        
        // Create new card element
        const card = document.createElement('a');
        card.href = data.url || `/news/${data.slug}`;
        card.className = 'post-card';
        card.setAttribute('data-post-id', data.id);
        card.style.cssText = 'text-decoration: none; color: inherit; display: block; opacity: 0; transform: translateY(-20px); transition: all 0.5s;';
        
        card.innerHTML = `
            <div class="post-image" style="background-image: url('${data.image || '/assets/images/darling studio.jpg'}'); background-size: cover; background-position: center; height: 200px; width: 100%;"></div>
            <div class="post-content">
                <div class="post-meta">
                    <span><i class="far fa-calendar"></i> ${data.date || 'Just now'}</span>
                </div>
                <h3 class="post-title">${escapeHtml(data.title)}</h3>
                <p class="post-excerpt">${escapeHtml(data.excerpt || '')}</p>
            </div>
        `;
        
        // Insert at the beginning
        postsGrid.insertBefore(card, postsGrid.firstChild);
        
        // Animate in
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 10);
        
        // Show notification
        showUpdateNotification('New news article: ' + data.title);
    }
    
    /**
     * Handle show updates
     */
    function handleShowUpdate(update) {
        // Similar to news updates
        const { action, data, id } = update;
        
        if (action === 'created' || action === 'updated') {
            showUpdateNotification('Show updated: ' + data.title);
            // Refresh show cards if on shows page
            if (window.location.pathname.includes('/shows')) {
                refreshShowsGrid();
            }
        }
    }
    
    /**
     * Handle event updates
     */
    function handleEventUpdate(update) {
        const { action, data, id } = update;
        
        if (action === 'created' || action === 'updated') {
            showUpdateNotification('Event updated: ' + data.title);
            // Refresh events if on events page
            if (window.location.pathname.includes('/events')) {
                refreshEventsGrid();
            }
        }
    }
    
    /**
     * Refresh shows grid (AJAX)
     */
    async function refreshShowsGrid() {
        try {
            const response = await fetch('/shows?ajax=1', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            });
            
            if (response.ok) {
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newGrid = doc.querySelector('.shows-simple-grid');
                const currentGrid = document.querySelector('.shows-simple-grid');
                
                if (newGrid && currentGrid) {
                    currentGrid.innerHTML = newGrid.innerHTML;
                }
            }
        } catch (error) {
            console.error('Failed to refresh shows:', error);
        }
    }
    
    /**
     * Refresh events grid (AJAX)
     */
    async function refreshEventsGrid() {
        try {
            const response = await fetch('/events?ajax=1', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            });
            
            if (response.ok) {
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newUpcoming = doc.querySelector('[data-events="upcoming"]');
                const newPast = doc.querySelector('[data-events="past"]');
                
                if (newUpcoming) {
                    const currentUpcoming = document.querySelector('[data-events="upcoming"]');
                    if (currentUpcoming) currentUpcoming.innerHTML = newUpcoming.innerHTML;
                }
                
                if (newPast) {
                    const currentPast = document.querySelector('[data-events="past"]');
                    if (currentPast) currentPast.innerHTML = newPast.innerHTML;
                }
            }
        } catch (error) {
            console.error('Failed to refresh events:', error);
        }
    }
    
    /**
     * Show update notification
     */
    function showUpdateNotification(message) {
        // Remove existing notification if any
        const existing = document.getElementById('realtime-notification');
        if (existing) existing.remove();
        
        const notification = document.createElement('div');
        notification.id = 'realtime-notification';
        notification.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            background: var(--accent);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            z-index: 10000;
            max-width: 300px;
            animation: slideInRight 0.3s ease-out;
        `;
        notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-sync-alt fa-spin"></i>
                <span>${escapeHtml(message)}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => notification.remove(), 300);
        }, 5000);
    }
    
    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Track if already initialized to prevent multiple instances
    let isInitialized = false;
    
    /**
     * Initialize real-time updates (only once)
     */
    function initializeOnce() {
        if (isInitialized) return;
        isInitialized = true;
        init();
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeOnce);
    } else {
        initializeOnce();
    }
    
    // Re-initialize on Livewire navigation (but only if not already initialized)
    document.addEventListener('livewire:navigated', function() {
        // Don't reinitialize if already running - just ensure polling continues
        if (!isPolling && !document.hidden) {
            startPolling();
        }
    });
    
    // Export for external use
    window.RealtimeUpdates = {
        start: startPolling,
        stop: stopPolling,
        check: checkForUpdates,
    };
})();

