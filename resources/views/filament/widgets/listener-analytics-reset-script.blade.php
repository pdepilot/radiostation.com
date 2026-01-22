<script>
document.addEventListener('DOMContentLoaded', function() {
    function addResetButton() {
        // Find the Listener Analytics widget
        const widget = document.querySelector('[wire\\:id*="listener-analytics-chart-widget"]') || 
                      document.querySelector('.fi-wi-chart');
        
        if (!widget) return;
        
        // Check if button already exists
        const existingButton = widget.querySelector('.reset-analytics-btn');
        if (existingButton) return;
        
        // Find the header section
        const header = widget.querySelector('.fi-section-header') || 
                      widget.querySelector('header') ||
                      widget.querySelector('.fi-wi-header');
        
        if (!header) return;
        
        // Create reset button
        const button = document.createElement('button');
        button.className = 'reset-analytics-btn fi-btn fi-btn-size-sm fi-color-warning items-center justify-center gap-x-1 rounded-lg font-semibold outline-none transition duration-75 focus-visible:ring-2 disabled:pointer-events-none disabled:opacity-70 px-2 py-1 text-sm';
        button.innerHTML = '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd" /></svg>';
        button.title = 'Reset Analytics';
        button.style.marginLeft = 'auto';
        
        button.addEventListener('click', async function(e) {
            e.stopPropagation();
            
            // Get current filter period
            const filterSelect = widget.querySelector('select[wire\\:model="filter"]') || 
                                widget.querySelector('select');
            const currentPeriod = filterSelect ? filterSelect.value : 'all';
            
            const periodLabels = {
                'day': 'today',
                'week': 'this week',
                'month': 'this month',
                'year': 'this year',
                'all': 'all data'
            };
            
            const periodLabel = periodLabels[currentPeriod] || 'all data';
            
            const confirmed = await (window.confirmAction || window.confirm)(`Are you sure you want to reset ${periodLabel} analytics data to 0? This action cannot be undone.`);
            if (confirmed) {
                button.disabled = true;
                button.innerHTML = '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                
                fetch('/admin/api/analytics/reset', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ period: currentPeriod })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to refresh the chart
                        location.reload();
                    } else {
                        (window.showError || window.showNotification)('Failed to reset analytics: ' + (data.message || 'Unknown error'), 'error');
                        button.disabled = false;
                        button.innerHTML = '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd" /></svg>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    (window.showError || window.showNotification)('Failed to reset analytics. Please try again.', 'error');
                    button.disabled = false;
                    button.innerHTML = '<svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z" clip-rule="evenodd" /></svg>';
                });
            }
        });
        
        // Add button to header
        const headerActions = header.querySelector('.flex.items-center.gap-3') || 
                            header.querySelector('.flex') ||
                            header;
        
        if (headerActions) {
            // Create a flex container if needed
            if (!headerActions.classList.contains('flex')) {
                headerActions.classList.add('flex');
                headerActions.classList.add('items-center');
            }
            headerActions.appendChild(button);
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

