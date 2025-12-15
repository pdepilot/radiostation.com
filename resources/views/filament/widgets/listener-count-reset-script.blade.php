<script>
document.addEventListener('DOMContentLoaded', function() {
    function addResetButton() {
        // Try multiple selectors to find the Live Listeners card
        let statCard = document.querySelector('.listener-count-stat-card');
        if (!statCard) {
            // Try finding by text content
            const allStats = document.querySelectorAll('.fi-wi-stats-overview-stat');
            allStats.forEach(card => {
                const label = card.querySelector('.fi-wi-stats-overview-stat-label');
                if (label && label.textContent.includes('Live Listeners')) {
                    statCard = card;
                }
            });
        }
        
        if (statCard && !statCard.querySelector('.reset-listener-btn')) {
            const button = document.createElement('button');
            button.className = 'reset-listener-btn fi-btn fi-btn-size-sm fi-color-warning items-center justify-center gap-x-1 rounded-lg font-semibold outline-none transition duration-75 focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-70 px-2 py-1 text-sm absolute top-2 right-2';
            button.innerHTML = '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd" /></svg>';
            button.title = 'Reset to 0';
            button.style.position = 'absolute';
            button.style.top = '8px';
            button.style.right = '8px';
            button.style.zIndex = '10';
            
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                if (confirm('Are you sure you want to reset the listener count to 0?')) {
                    fetch('/api/listener/reset', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            });
            
            statCard.style.position = 'relative';
            statCard.appendChild(button);
        }
    }
    
    // Try immediately
    addResetButton();
    
    // Try after delays (for Livewire updates)
    setTimeout(addResetButton, 500);
    setTimeout(addResetButton, 1000);
    setTimeout(addResetButton, 2000);
    
    // Watch for Livewire updates
    if (window.Livewire) {
        Livewire.hook('morph.updated', () => {
            setTimeout(addResetButton, 100);
        });
    }
    
    document.addEventListener('livewire:load', addResetButton);
    document.addEventListener('livewire:update', addResetButton);
});
</script>

