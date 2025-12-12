// Professional form confirmation handler
document.addEventListener('DOMContentLoaded', function() {
    // Replace all onsubmit confirm() calls with professional modal
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
        const originalOnsubmit = form.getAttribute('onsubmit');
        if (originalOnsubmit && originalOnsubmit.includes('confirm')) {
            // Extract the confirmation message
            const match = originalOnsubmit.match(/confirm\(['"]([^'"]+)['"]\)/);
            if (match) {
                const message = match[1];
                form.removeAttribute('onsubmit');
                
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    if (typeof confirmAction === 'function') {
                        confirmAction(message, 'Confirm Action').then(confirmed => {
                            if (confirmed) {
                                form.submit();
                            }
                        });
                    } else {
                        // Fallback to native confirm
                        if (confirm(message)) {
                            form.submit();
                        }
                    }
                });
            }
        }
    });

    // Replace all onclick confirm() calls
    document.querySelectorAll('[onclick*="confirm"]').forEach(element => {
        const originalOnclick = element.getAttribute('onclick');
        if (originalOnclick && originalOnclick.includes('confirm')) {
            const match = originalOnclick.match(/confirm\(['"]([^'"]+)['"]\)/);
            if (match) {
                const message = match[1];
                element.removeAttribute('onclick');
                
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    if (typeof confirmAction === 'function') {
                        confirmAction(message, 'Confirm Action').then(confirmed => {
                            if (confirmed) {
                                // If it's a form submit button, submit the form
                                if (element.type === 'submit' && element.form) {
                                    element.form.submit();
                                } else if (element.closest('form')) {
                                    element.closest('form').submit();
                                }
                            }
                        });
                    } else {
                        // Fallback to native confirm
                        if (confirm(message)) {
                            if (element.type === 'submit' && element.form) {
                                element.form.submit();
                            } else if (element.closest('form')) {
                                element.closest('form').submit();
                            }
                        }
                    }
                });
            }
        }
    });
});

