/**
 * Intelligent Instant News Search for Darling FM
 * 
 * Features:
 * - As-you-type search (debounced 300ms)
 * - Search by: title, keyword, presenter name, category, date
 * - Real-time results dropdown
 * - Highlight matching text
 * - Show thumbnail + title + excerpt + date
 * - "View all results" link + Enter key support
 * - Mobile-friendly with smooth animations
 * - Friendly "No results" message with suggestions
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        const searchInput = document.getElementById('navNewsSearch');
        const searchResults = document.getElementById('navSearchResults');
        
        if (!searchInput || !searchResults) {
            console.warn('News search elements not found');
            return;
        }

        let searchTimeout;
        let currentQuery = '';
        let isSearching = false;
        let searchResultsData = [];

        /**
         * Perform search with debouncing
         */
        function performSearch(query) {
            currentQuery = query.trim();
            
            // Minimum 2 characters
            if (currentQuery.length < 2) {
                hideResults();
                return;
            }

            // Show loading state
            showLoading();

            // Clear previous timeout
            clearTimeout(searchTimeout);

            // Debounce: wait 300ms before searching
            searchTimeout = setTimeout(() => {
                if (currentQuery !== query.trim()) return; // Query changed, ignore this result
                
                isSearching = true;
                
                fetch(`/api/news/search?q=${encodeURIComponent(currentQuery)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Search request failed');
                    }
                    return response.json();
                })
                .then(data => {
                    if (currentQuery !== query.trim()) return; // Query changed, ignore
                    
                    isSearching = false;
                    searchResultsData = data.results || [];
                    displayResults(data);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    isSearching = false;
                    showError();
                });
            }, 300);
        }

        /**
         * Display search results
         */
        function displayResults(data) {
            const results = data.results || [];
            const total = data.total || 0;
            const hasMore = data.hasMore || false;
            const query = data.query || '';

            if (results.length === 0) {
                showNoResults(query);
                return;
            }

            let html = '';
            
            // Build results HTML
            results.forEach(result => {
                const highlightedTitle = highlightText(result.title, query);
                const highlightedExcerpt = highlightText(result.excerpt, query);
                const imageUrl = result.image || '/assets/images/darling studio.jpg';
                
                html += `
                    <a href="${result.url}" 
                       class="search-result-item" 
                       data-result-id="${result.id}">
                        <div class="search-result-content">
                            <div class="search-result-image" 
                                 style="background-image: url('${imageUrl}');"></div>
                            <div class="search-result-info">
                                <h4 class="search-result-title">${highlightedTitle}</h4>
                                <p class="search-result-excerpt">${highlightedExcerpt}</p>
                                <div class="search-result-meta">
                                    ${result.author ? `<span class="search-meta-author"><i class="fas fa-user"></i> ${escapeHtml(result.author)}</span>` : ''}
                                    ${result.category ? `<span class="search-meta-category"><i class="fas fa-tag"></i> ${escapeHtml(result.category)}</span>` : ''}
                                    <span class="search-meta-date"><i class="far fa-calendar"></i> ${result.date}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                `;
            });

            // Add "View all results" link if there are more results
            if (hasMore && total > 5) {
                html += `
                    <div class="search-view-all">
                        <a href="/news?search=${encodeURIComponent(query)}" class="view-all-link">
                            <i class="fas fa-arrow-right"></i>
                            View all ${total} results
                        </a>
                    </div>
                `;
            }

            searchResults.innerHTML = html;
            showResults();
        }

        /**
         * Highlight matching text in search results
         */
        function highlightText(text, query) {
            if (!text || !query) return escapeHtml(text);
            
            const escapedText = escapeHtml(text);
            const escapedQuery = escapeHtml(query);
            
            // Create regex for case-insensitive matching
            const regex = new RegExp(`(${escapedQuery.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            
            return escapedText.replace(regex, '<mark class="search-highlight">$1</mark>');
        }

        /**
         * Escape HTML to prevent XSS
         */
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        /**
         * Show loading state
         */
        function showLoading() {
            searchResults.innerHTML = `
                <div class="search-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                    <span>Searching...</span>
                </div>
            `;
            showResults();
        }

        /**
         * Show error state
         */
        function showError() {
            searchResults.innerHTML = `
                <div class="search-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>Error loading results. Please try again.</span>
                </div>
            `;
            showResults();
        }

        /**
         * Show no results with suggestions
         */
        function showNoResults(query) {
            const suggestions = [
                'Try different keywords',
                'Check spelling',
                'Search by date (e.g., "December 2024")',
                'Search by presenter name',
                'Browse all news'
            ];
            
            let suggestionsHtml = suggestions.map(s => `<li>${s}</li>`).join('');
            
            searchResults.innerHTML = `
                <div class="search-no-results">
                    <div class="no-results-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>No news found</h4>
                    <p>We couldn't find any news matching "<strong>${escapeHtml(query)}</strong>"</p>
                    <div class="search-suggestions">
                        <p><strong>Suggestions:</strong></p>
                        <ul>${suggestionsHtml}</ul>
                    </div>
                    <a href="/news" class="browse-all-link">
                        <i class="fas fa-newspaper"></i> Browse All News
                    </a>
                </div>
            `;
            showResults();
        }

        /**
         * Show results dropdown
         */
        function showResults() {
            searchResults.style.display = 'block';
            searchResults.style.opacity = '0';
            searchResults.style.transform = 'translateY(-10px)';
            
            // Smooth fade-in animation
            requestAnimationFrame(() => {
                searchResults.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
                searchResults.style.opacity = '1';
                searchResults.style.transform = 'translateY(0)';
            });
        }

        /**
         * Hide results dropdown
         */
        function hideResults() {
            searchResults.style.opacity = '0';
            searchResults.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                searchResults.style.display = 'none';
            }, 200);
        }

        /**
         * Handle Enter key - navigate to search page or first result
         */
        function handleEnterKey() {
            if (searchResultsData.length > 0) {
                // Navigate to first result using Livewire SPA navigation
                const firstResult = searchResults.querySelector('.search-result-item');
                if (firstResult) {
                    const href = firstResult.href;
                    if (window.Livewire && window.Livewire.navigate) {
                        window.Livewire.navigate(href);
                    } else {
                        window.location.href = href;
                    }
                }
            } else if (currentQuery.length >= 2) {
                // Navigate to search page with query using Livewire SPA navigation
                const searchUrl = `/news?search=${encodeURIComponent(currentQuery)}`;
                if (window.Livewire && window.Livewire.navigate) {
                    window.Livewire.navigate(searchUrl);
                } else {
                    window.location.href = searchUrl;
                }
            }
        }

        // Event Listeners
        
        /**
         * Input event - trigger search
         */
        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.trim();
            performSearch(query);
        });

        /**
         * Enter key support
         */
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                handleEnterKey();
            }
            
            // Escape key to close results
            if (e.key === 'Escape') {
                hideResults();
                searchInput.blur();
            }
        });

        /**
         * Focus event - show results if query exists
         */
        searchInput.addEventListener('focus', function() {
            if (this.value.trim().length >= 2 && searchResultsData.length > 0) {
                showResults();
            }
        });

        /**
         * Click outside to close results
         */
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                hideResults();
            }
        });

        /**
         * Prevent results from closing when clicking inside
         */
        searchResults.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
})();
