// Dynamic News Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('navNewsSearch');
    const searchResults = document.getElementById('navSearchResults');
    let searchTimeout;
    let isSearching = false;

    if (!searchInput || !searchResults) return;

    // Debounce search function
    function performSearch(query) {
        if (query.length < 2) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }

        isSearching = true;
        searchResults.style.display = 'block';
        searchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);"><i class="fas fa-spinner fa-spin"></i> Searching...</div>';

        fetch(`/api/news/search?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            isSearching = false;
            displayResults(data.results || []);
        })
        .catch(error => {
            console.error('Search error:', error);
            isSearching = false;
            searchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);">Error loading results</div>';
        });
    }

    function displayResults(results) {
        if (results.length === 0) {
            searchResults.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-secondary);">No results found</div>';
            return;
        }

        let html = '';
        results.forEach(result => {
            html += `
                <a href="${result.url}" style="display: block; padding: 15px; border-bottom: 1px solid var(--glass-border); text-decoration: none; color: inherit; transition: background 0.2s;" 
                   onmouseover="this.style.background='rgba(255,255,255,0.05)'" 
                   onmouseout="this.style.background='transparent'">
                    <div style="display: flex; gap: 15px; align-items: start;">
                        <div style="width: 60px; height: 60px; border-radius: 8px; background-image: url('${result.image}'); background-size: cover; background-position: center; flex-shrink: 0;"></div>
                        <div style="flex: 1; min-width: 0;">
                            <h4 style="color: var(--accent); font-size: 0.95rem; font-weight: 600; margin-bottom: 5px; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">${result.title}</h4>
                            <p style="color: var(--text-secondary); font-size: 0.85rem; margin-bottom: 5px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">${result.excerpt}</p>
                            <span style="color: var(--text-secondary); font-size: 0.75rem;">${result.date}</span>
                        </div>
                    </div>
                </a>
            `;
        });
        searchResults.innerHTML = html;
    }

    // Search input event listener
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            searchResults.innerHTML = '';
            return;
        }

        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300); // 300ms debounce
    });

    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });

    // Handle search input focus
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2 && searchResults.innerHTML) {
            searchResults.style.display = 'block';
        }
    });
});

