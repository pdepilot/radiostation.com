// Professional form confirmation handler - Enhanced for unified notification system
(function() {
    'use strict';
    
    function initFormConfirmations() {
        // Replace all onsubmit confirm() calls with professional modal
        document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
            const originalOnsubmit = form.getAttribute('onsubmit');
            if (originalOnsubmit && originalOnsubmit.includes('confirm')) {
                // Extract the confirmation message (handles both single and double quotes, and escaped quotes)
                const match = originalOnsubmit.match(/confirm\(['"`]([^'"`]+)['"`]\)/);
                if (match) {
                    const message = match[1];
                    form.removeAttribute('onsubmit');
                    
                    form.addEventListener('submit', async function(e) {
                        e.preventDefault();
                        
                        const confirmed = await (window.confirmAction || window.confirm)(message, 'Confirm Action');
                        if (confirmed) {
                            form.submit();
                        }
                    });
                }
            }
        });

        // Replace all onclick confirm() calls
        document.querySelectorAll('[onclick*="confirm"]').forEach(element => {
            const originalOnclick = element.getAttribute('onclick');
            if (originalOnclick && originalOnclick.includes('confirm')) {
                const match = originalOnclick.match(/confirm\(['"`]([^'"`]+)['"`]\)/);
                if (match) {
                    const message = match[1];
                    element.removeAttribute('onclick');
                    
                    element.addEventListener('click', async function(e) {
                        e.preventDefault();
                        
                        const confirmed = await (window.confirmAction || window.confirm)(message, 'Confirm Action');
                        if (confirmed) {
                            // If it's a form submit button, submit the form
                            if (element.type === 'submit' && element.form) {
                                element.form.submit();
                            } else if (element.closest('form')) {
                                element.closest('form').submit();
                            } else if (element.href) {
                                window.location.href = element.href;
                            }
                        }
                    });
                }
            }
        });
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFormConfirmations);
    } else {
        initFormConfirmations();
    }
    
    // Re-initialize after Livewire navigation
    document.addEventListener('livewire:navigated', initFormConfirmations);
})();

