{{-- Unified Notification System Component --}}
@persist('notification-system')
<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 99999; max-width: 400px; pointer-events: none;"></div>
@endpersist

{{-- Session Flash Messages Handler --}}
@if(session('success') || session('error') || session('warning') || session('info') || session('status'))
    @php
        $type = session('error') ? 'error' : (session('warning') ? 'warning' : (session('info') ? 'info' : 'success'));
        $message = session('error') ?? session('warning') ?? session('info') ?? session('success') ?? session('status');
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof showNotification !== 'undefined') {
                showNotification(@json($message), @json($type));
            } else if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: @json($type),
                    title: @json(ucfirst($type)),
                    text: @json($message),
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true
                });
            }
        });
    </script>
@endif

{{-- Enhanced Notification System Script --}}
<script>
(function() {
    'use strict';
    
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:34',message:'Notification system script loading',data:{alreadyInitialized:!!window.NotificationSystemInitialized,hasSwal:typeof Swal !== 'undefined',hasLivewire:typeof Livewire !== 'undefined'},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'D'})}).catch(()=>{});
    // #endregion
    
    // Check if already initialized
    if (window.NotificationSystemInitialized) {
        // #region agent log
        fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:38',message:'Notification system already initialized, skipping',data:{},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'D'})}).catch(()=>{});
        // #endregion
        return;
    }
    window.NotificationSystemInitialized = true;
    
    // Enhanced Notification System
    class UnifiedNotificationSystem {
        constructor() {
            // #region agent log
            fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:45',message:'UnifiedNotificationSystem constructor called',data:{hasDocumentBody:!!document.body},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
            // #endregion
            
            this.container = document.getElementById('notification-container');
            // #region agent log
            fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:48',message:'Container lookup result',data:{containerFound:!!this.container,containerId:this.container?.id},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
            // #endregion
            
            this.notifications = [];
            this.maxNotifications = 5;
            this.useSweetAlert = typeof Swal !== 'undefined';
            
            if (!this.container) {
                // #region agent log
                fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:54',message:'Creating notification container',data:{hasDocumentBody:!!document.body},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
                // #endregion
                this.container = document.createElement('div');
                this.container.id = 'notification-container';
                this.container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 99999; max-width: 400px; pointer-events: none;';
                document.body.appendChild(this.container);
                // #region agent log
                fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:59',message:'Container created and appended',data:{containerInDOM:!!document.getElementById('notification-container')},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
                // #endregion
            }
            
            // Listen for custom notification events
            document.addEventListener('showNotification', (e) => {
                // #region agent log
                fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:65',message:'showNotification event received',data:{hasDetail:!!e.detail,message:e.detail?.message?.substring(0,50),type:e.detail?.type},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
                // #endregion
                this.show(e.detail.message, e.detail.type, e.detail.duration);
            });
            
            // Listen for Livewire navigation to preserve notifications
            document.addEventListener('livewire:navigated', () => {
                // #region agent log
                fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:71',message:'livewire:navigated event received',data:{containerExists:!!document.getElementById('notification-container'),hasContainerRef:!!this.container},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
                // #endregion
                // Re-initialize container if needed
                if (!document.getElementById('notification-container')) {
                    // #region agent log
                    fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:74',message:'Recreating container after navigation',data:{hasDocumentBody:!!document.body},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
                    // #endregion
                    this.container = document.createElement('div');
                    this.container.id = 'notification-container';
                    this.container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 99999; max-width: 400px; pointer-events: none;';
                    document.body.appendChild(this.container);
                } else {
                    // #region agent log
                    fetch('http://127.0.0.1:7242/ingest/8326855a-3336-4a44-9ebc-d1b5eb04ef0b',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:'notification-system.blade.php:80',message:'Container exists after navigation, reconnecting reference',data:{},timestamp:Date.now(),sessionId:'debug-session',runId:'run1',hypothesisId:'A'})}).catch(()=>{});
                    // #endregion
                    this.container = document.getElementById('notification-container');
                }
            });
        }
        
        show(message, type = 'info', duration = 4000) {
            if (this.useSweetAlert) {
                return this.showSweetAlert(message, type, duration);
            } else {
                return this.showCustomToast(message, type, duration);
            }
        }
        
        showSweetAlert(message, type, duration) {
            const iconMap = {
                success: 'success',
                error: 'error',
                warning: 'warning',
                info: 'info'
            };
            
            const config = {
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
            };
            
            if (type !== 'error') {
                config.timerProgressBar = true;
            }
            
            return Swal.fire(config);
        }
        
        showCustomToast(message, type, duration) {
            const notification = document.createElement('div');
            notification.className = `notification-toast notification-${type}`;
            notification.style.cssText = `
                background: ${this.getColor(type)};
                color: white;
                padding: 15px 20px;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                margin-bottom: 10px;
                pointer-events: auto;
                transform: translateX(400px);
                transition: transform 0.3s ease, opacity 0.3s ease;
                opacity: 0;
                max-width: 100%;
                font-weight: 500;
                font-size: 14px;
                display: flex;
                align-items: center;
                gap: 10px;
            `;
            
            const icon = this.getIcon(type);
            notification.innerHTML = `
                <i class="${icon}" style="font-size: 18px;"></i>
                <span style="flex: 1;">${this.escapeHtml(message)}</span>
                <button onclick="this.parentElement.remove()" style="background: none; border: none; color: white; cursor: pointer; padding: 5px; opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            this.container.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
                notification.style.opacity = '1';
            }, 10);
            
            // Remove after duration
            if (duration > 0) {
                setTimeout(() => {
                    notification.style.transform = 'translateX(400px)';
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }, duration);
            }
            
            return Promise.resolve();
        }
        
        getColor(type) {
            const colors = {
                success: '#00cc66',
                error: '#ff3333',
                warning: '#ffaa00',
                info: '#0099ff'
            };
            return colors[type] || colors.info;
        }
        
        getIcon(type) {
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-exclamation-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            return icons[type] || icons.info;
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
        
        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
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
    
    // Confirmation Modal
    class UnifiedConfirmationModal {
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
                return Promise.resolve(confirm(message));
            }
        }
    }
    
    // Initialize
    const notificationSystem = new UnifiedNotificationSystem();
    const confirmationModal = new UnifiedConfirmationModal();
    
    // Global functions
    window.showNotification = (message, type) => notificationSystem.show(message, type);
    window.showSuccess = (message) => notificationSystem.success(message);
    window.showError = (message) => notificationSystem.error(message);
    window.showWarning = (message) => notificationSystem.warning(message);
    window.showInfo = (message) => notificationSystem.info(message);
    window.confirmAction = (message, title) => confirmationModal.show(message, title);
    
    // Replace native alert and confirm
    window.originalAlert = window.alert;
    window.originalConfirm = window.confirm;
    
    window.alert = function(message) {
        notificationSystem.info(message);
    };
    
    window.confirm = function(message) {
        return confirmationModal.show(message, 'Confirm');
    };
    
    // Dispatch event helper
    window.dispatchNotification = function(message, type = 'info') {
        document.dispatchEvent(new CustomEvent('showNotification', {
            detail: { message, type }
        }));
    };
})();
</script>
