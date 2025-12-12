// Professional Notification System
class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Create notification container if it doesn't exist
        if (!document.getElementById('notification-container')) {
            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            this.container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 10000;
                display: flex;
                flex-direction: column;
                gap: 15px;
                pointer-events: none;
            `;
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('notification-container');
        }
    }

    show(message, type = 'info', duration = 4000) {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.style.cssText = `
            background: var(--glass);
            backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 18px 24px;
            min-width: 300px;
            max-width: 400px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            pointer-events: auto;
            animation: slideInRight 0.3s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: 15px;
        `;

        const iconMap = {
            success: '<i class="fas fa-check-circle" style="color: #00ff00; font-size: 1.5rem;"></i>',
            error: '<i class="fas fa-exclamation-circle" style="color: var(--accent); font-size: 1.5rem;"></i>',
            warning: '<i class="fas fa-exclamation-triangle" style="color: #ffaa00; font-size: 1.5rem;"></i>',
            info: '<i class="fas fa-info-circle" style="color: #00aaff; font-size: 1.5rem;"></i>'
        };

        notification.innerHTML = `
            ${iconMap[type] || iconMap.info}
            <div style="flex: 1; color: var(--light); font-size: 0.95rem; line-height: 1.5;">
                ${message}
            </div>
            <button class="notification-close" style="background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 1.2rem; padding: 0; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; transition: color 0.3s;" onmouseover="this.style.color='var(--light)'" onmouseout="this.style.color='var(--text-secondary)'">
                &times;
            </button>
        `;

        // Add close functionality
        const closeBtn = notification.querySelector('.notification-close');
        const closeNotification = () => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        };
        closeBtn.addEventListener('click', closeNotification);

        this.container.appendChild(notification);

        // Auto remove after duration
        if (duration > 0) {
            setTimeout(closeNotification, duration);
        }

        return notification;
    }

    success(message, duration = 4000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 5000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 4000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 4000) {
        return this.show(message, 'info', duration);
    }
}

// Professional Confirmation Modal
class ConfirmationModal {
    constructor() {
        this.modal = null;
        this.init();
    }

    init() {
        if (!document.getElementById('confirmation-modal')) {
            this.modal = document.createElement('div');
            this.modal.id = 'confirmation-modal';
            this.modal.style.cssText = `
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                backdrop-filter: blur(5px);
                z-index: 10000;
                align-items: center;
                justify-content: center;
            `;
            this.modal.innerHTML = `
                <div class="confirmation-content" style="background: var(--glass); backdrop-filter: blur(15px); border-radius: 20px; padding: 40px; max-width: 500px; width: 90%; border: 1px solid var(--glass-border); box-shadow: 0 20px 60px rgba(0,0,0,0.5); text-align: center;">
                    <div style="font-size: 3rem; color: var(--accent); margin-bottom: 20px;">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3 style="color: var(--light); font-size: 1.5rem; margin-bottom: 15px; font-family: 'Orbitron', sans-serif;" id="confirmation-title">Confirm Action</h3>
                    <p style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 30px; line-height: 1.6;" id="confirmation-message"></p>
                    <div style="display: flex; gap: 15px; justify-content: center;">
                        <button id="confirmation-cancel" style="background: rgba(255,255,255,0.1); color: var(--light); border: 1px solid var(--glass-border); padding: 12px 30px; border-radius: 10px; cursor: pointer; font-weight: 600; transition: all 0.3s; font-family: 'Exo 2', sans-serif;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                            Cancel
                        </button>
                        <button id="confirmation-confirm" style="background: var(--accent); color: white; border: none; padding: 12px 30px; border-radius: 10px; cursor: pointer; font-weight: 600; transition: all 0.3s; font-family: 'Exo 2', sans-serif;" onmouseover="this.style.background='var(--accent-glow)'" onmouseout="this.style.background='var(--accent)'">
                            Confirm
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(this.modal);
        } else {
            this.modal = document.getElementById('confirmation-modal');
        }
    }

    show(message, title = 'Confirm Action') {
        return new Promise((resolve) => {
            const messageEl = this.modal.querySelector('#confirmation-message');
            const titleEl = this.modal.querySelector('#confirmation-title');
            const confirmBtn = this.modal.querySelector('#confirmation-confirm');
            const cancelBtn = this.modal.querySelector('#confirmation-cancel');

            messageEl.textContent = message;
            titleEl.textContent = title;
            this.modal.style.display = 'flex';

            const cleanup = () => {
                this.modal.style.display = 'none';
                confirmBtn.onclick = null;
                cancelBtn.onclick = null;
            };

            confirmBtn.onclick = () => {
                cleanup();
                resolve(true);
            };

            cancelBtn.onclick = () => {
                cleanup();
                resolve(false);
            };

            // Close on backdrop click
            this.modal.onclick = (e) => {
                if (e.target === this.modal) {
                    cleanup();
                    resolve(false);
                }
            };
        });
    }
}

// Initialize notification system
const notifications = new NotificationSystem();
const confirmModal = new ConfirmationModal();

// Add CSS animations
if (!document.getElementById('notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

// Global functions for easy access
window.showNotification = (message, type = 'info') => notifications.show(message, type);
window.showSuccess = (message) => notifications.success(message);
window.showError = (message) => notifications.error(message);
window.showWarning = (message) => notifications.warning(message);
window.showInfo = (message) => notifications.info(message);
window.confirmAction = (message, title) => confirmModal.show(message, title);

