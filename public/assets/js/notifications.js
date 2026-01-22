// Professional Notification System - Using SweetAlert2
// Check if already declared to avoid redeclaration during Livewire navigation
if (typeof NotificationSystem === 'undefined') {
    class NotificationSystem {
        constructor() {
            this.useSweetAlert = typeof Swal !== 'undefined';
        }

        show(message, type = 'info', duration = 4000) {
            if (this.useSweetAlert) {
                const iconMap = {
                    success: 'success',
                    error: 'error',
                    warning: 'warning',
                    info: 'info'
                };

                return Swal.fire({
                    icon: iconMap[type] || 'info',
                    title: this.getTitle(type),
                    text: message,
                    timer: duration,
                    showConfirmButton: type === 'error',
                    toast: type !== 'error',
                    position: type === 'error' ? 'center' : 'top-end',
                    background: 'rgba(15, 15, 17, 0.95)',
                    color: '#f0f0f5',
                    confirmButtonColor: '#c8102e',
                    backdrop: type === 'error' ? 'rgba(0,0,0,0.8)' : false,
                    customClass: {
                        popup: 'swal-dark-popup'
                    }
                });
            } else {
                // Fallback to console if SweetAlert2 not loaded
                console.log(`[${type.toUpperCase()}] ${message}`);
            }
        }

        getTitle(type) {
            const titles = {
                success: 'Success!',
                error: 'Error',
                warning: 'Warning',
                info: 'Info'
            };
            return titles[type] || 'Notification';
        }

        success(message, duration = 3000) {
            return this.show(message, 'success', duration);
        }

        error(message, duration = 5000) {
            return this.show(message, 'error', duration);
        }

        warning(message, duration = 4000) {
            return this.show(message, 'warning', duration);
        }

        info(message, duration = 3000) {
            return this.show(message, 'info', duration);
        }
    }

    // Professional Confirmation Modal - Using SweetAlert2
    class ConfirmationModal {
        constructor() {
            this.useSweetAlert = typeof Swal !== 'undefined';
        }

        show(message, title = 'Confirm Action') {
            if (this.useSweetAlert) {
                return Swal.fire({
                    title: title,
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#c8102e',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirm',
                    cancelButtonText: 'Cancel',
                    background: 'rgba(15, 15, 17, 0.95)',
                    color: '#f0f0f5',
                    backdrop: 'rgba(0,0,0,0.8)',
                    customClass: {
                        popup: 'swal-dark-popup'
                    }
                }).then((result) => {
                    return Promise.resolve(result.isConfirmed);
                });
            } else {
                // Fallback to native confirm
                return Promise.resolve(confirm(message));
            }
        }
    }
    
    // Initialize notification system only if not already initialized
    if (typeof window.notifications === 'undefined') {
        window.notifications = new NotificationSystem();
        window.confirmModal = new ConfirmationModal();
    }
}

// Use window references to avoid redeclaration - only declare if not already exists
if (typeof notifications === 'undefined') {
    var notifications = window.notifications || (typeof NotificationSystem !== 'undefined' ? new NotificationSystem() : null);
}
if (typeof confirmModal === 'undefined') {
    var confirmModal = window.confirmModal || (typeof ConfirmationModal !== 'undefined' ? new ConfirmationModal() : null);
}

// Add custom CSS for SweetAlert2 dark theme
if (!document.getElementById('swal-dark-styles')) {
    const style = document.createElement('style');
    style.id = 'swal-dark-styles';
    style.textContent = `
        .swal-dark-popup {
            background: rgba(15, 15, 17, 0.95) !important;
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        .swal2-title, .swal2-content {
            color: #f0f0f5 !important;
        }
        .swal2-confirm {
            background-color: #c8102e !important;
        }
        .swal2-confirm:hover {
            background-color: #a00d25 !important;
        }
    `;
    document.head.appendChild(style);
}

// Global functions for easy access - only assign if not already assigned
if (typeof window.showNotification === 'undefined') {
    window.showNotification = (message, type = 'info') => (notifications || window.notifications)?.show(message, type);
}
if (typeof window.showSuccess === 'undefined') {
    window.showSuccess = (message) => (notifications || window.notifications)?.success(message);
}
if (typeof window.showError === 'undefined') {
    window.showError = (message) => (notifications || window.notifications)?.error(message);
}
if (typeof window.showWarning === 'undefined') {
    window.showWarning = (message) => (notifications || window.notifications)?.warning(message);
}
if (typeof window.showInfo === 'undefined') {
    window.showInfo = (message) => (notifications || window.notifications)?.info(message);
}
if (typeof window.confirmAction === 'undefined') {
    window.confirmAction = (message, title) => (confirmModal || window.confirmModal)?.show(message, title);
}

