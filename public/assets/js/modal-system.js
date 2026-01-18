/**
 * Modal System for Content Viewing
 * Replaces full page navigation with modal dialogs for better UX
 */

(function() {
    'use strict';
    
    let currentModal = null;
    
    /**
     * Initialize modal system
     */
    function init() {
        // Intercept clicks on content links
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[data-modal]');
            if (link) {
                e.preventDefault();
                const type = link.getAttribute('data-modal');
                const id = link.getAttribute('data-id');
                const slug = link.getAttribute('data-slug') || link.getAttribute('href')?.split('/').pop();
                
                if (type && (id || slug)) {
                    openModal(type, id, slug, link.getAttribute('href'));
                }
            }
        });
        
        // Close modal on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && currentModal) {
                closeModal();
            }
        });
    }
    
    /**
     * Open content modal
     */
    async function openModal(type, id, slug, url) {
        // Show loading state
        showModalLoading();
        
        try {
            // Fetch content via API
            const apiUrl = `/api/realtime/content?type=${type}&${id ? `id=${id}` : `slug=${slug}`}`;
            const response = await fetch(apiUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            
            if (!response.ok) {
                throw new Error('Failed to load content');
            }
            
            const result = await response.json();
            showModalContent(result.type, result.data);
            
        } catch (error) {
            console.error('Modal load error:', error);
            // Fallback: navigate to full page
            if (url) {
                window.location.href = url;
            }
        }
    }
    
    /**
     * Show loading state in modal
     */
    function showModalLoading() {
        const modal = createModal();
        modal.innerHTML = `
            <div class="modal-content" style="max-width: 900px; width: 90%;">
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-spinner fa-spin" style="font-size: 3rem; color: var(--accent); margin-bottom: 20px;"></i>
                    <p style="color: var(--light); font-size: 1.1rem;">Loading...</p>
                </div>
            </div>
        `;
        currentModal = modal;
        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';
    }
    
    /**
     * Show content in modal
     */
    function showModalContent(type, data) {
        if (!currentModal) return;
        
        let contentHtml = '';
        
        switch (type) {
            case 'news':
                contentHtml = renderNewsModal(data);
                break;
            case 'show':
                contentHtml = renderShowModal(data);
                break;
            case 'event':
                contentHtml = renderEventModal(data);
                break;
        }
        
        const modalContent = currentModal.querySelector('.modal-content');
        if (modalContent) {
            modalContent.innerHTML = contentHtml;
            // Scroll to top
            modalContent.scrollTop = 0;
        }
    }
    
    /**
     * Render news article modal
     */
    function renderNewsModal(data) {
        return `
            <div class="modal-header" style="position: sticky; top: 0; background: var(--secondary); padding: 20px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; z-index: 10;">
                <h2 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin: 0; font-size: 1.5rem;">${escapeHtml(data.title)}</h2>
                <button class="modal-close" onclick="window.ModalSystem.close()" style="background: transparent; border: none; color: var(--light); font-size: 1.5rem; cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">&times;</button>
            </div>
            <div class="modal-body" style="padding: 30px; max-height: calc(100vh - 200px); overflow-y: auto;">
                ${data.image ? `<img src="${escapeHtml(data.image)}" alt="${escapeHtml(data.title)}" style="width: 100%; height: 400px; object-fit: cover; border-radius: 15px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);" onerror="this.src='/assets/images/studio.jpg';">` : ''}
                <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px; display: flex; align-items: center; gap: 15px;">
                    <span><i class="far fa-calendar"></i> ${data.date || 'Published'}</span>
                    ${data.author ? `<span><i class="fas fa-user"></i> ${escapeHtml(data.author)}</span>` : ''}
                </div>
                ${data.excerpt ? `<p style="color: var(--text-secondary); font-size: 1.2rem; line-height: 1.8; margin-bottom: 40px; font-weight: 300;">${escapeHtml(data.excerpt)}</p>` : ''}
                <div style="color: var(--light); font-size: 1.1rem; line-height: 2; margin-bottom: 40px;">
                    ${data.body || '<p>Content coming soon...</p>'}
                </div>
                <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid var(--glass-border);">
                    <a href="${escapeHtml(data.url)}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: var(--accent); border: 1px solid rgba(255,255,255,0.2); border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-external-link-alt"></i>
                        <span>View Full Page</span>
                    </a>
                </div>
            </div>
        `;
    }
    
    /**
     * Render show modal
     */
    function renderShowModal(data) {
        return `
            <div class="modal-header" style="position: sticky; top: 0; background: var(--secondary); padding: 20px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; z-index: 10;">
                <h2 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin: 0; font-size: 1.5rem;">${escapeHtml(data.title)}</h2>
                <button class="modal-close" onclick="window.ModalSystem.close()" style="background: transparent; border: none; color: var(--light); font-size: 1.5rem; cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">&times;</button>
            </div>
            <div class="modal-body" style="padding: 30px; max-height: calc(100vh - 200px); overflow-y: auto;">
                ${data.image ? `<div style="height: 300px; background-image: url('${escapeHtml(data.image)}'); background-size: cover; background-position: center; border-radius: 15px; margin-bottom: 30px;"></div>` : ''}
                ${data.description ? `<div style="color: var(--light); line-height: 1.8; font-size: 1.1rem; margin-bottom: 30px;">${escapeHtml(data.description)}</div>` : ''}
                ${data.dj ? `
                    <div style="background: var(--glass); backdrop-filter: blur(10px); border-radius: 20px; padding: 20px; margin-bottom: 30px; border: 1px solid var(--glass-border);">
                        <h3 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin-bottom: 10px;">Hosted by ${escapeHtml(data.dj.name)}</h3>
                        ${data.dj.avatar ? `<img src="${escapeHtml(data.dj.avatar)}" alt="${escapeHtml(data.dj.name)}" style="width: 80px; height: 80px; border-radius: 50%; border: 3px solid var(--accent);">` : ''}
                    </div>
                ` : ''}
                ${data.schedule ? `<div style="color: var(--text-secondary); margin-bottom: 20px;"><i class="far fa-clock"></i> ${escapeHtml(data.schedule)}</div>` : ''}
                <div style="margin-top: 30px;">
                    <a href="${escapeHtml(data.url)}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: var(--accent); border: 1px solid rgba(255,255,255,0.2); border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 0.95rem;">
                        <i class="fas fa-external-link-alt"></i>
                        <span>View Full Page</span>
                    </a>
                </div>
            </div>
        `;
    }
    
    /**
     * Render event modal
     */
    function renderEventModal(data) {
        return `
            <div class="modal-header" style="position: sticky; top: 0; background: var(--secondary); padding: 20px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; z-index: 10;">
                <h2 style="color: var(--accent); font-family: 'Orbitron', sans-serif; margin: 0; font-size: 1.5rem;">${escapeHtml(data.title)}</h2>
                <button class="modal-close" onclick="window.ModalSystem.close()" style="background: transparent; border: none; color: var(--light); font-size: 1.5rem; cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='transparent'">&times;</button>
            </div>
            <div class="modal-body" style="padding: 30px; max-height: calc(100vh - 200px); overflow-y: auto;">
                ${data.image ? `<div style="height: 300px; background-image: url('${escapeHtml(data.image)}'); background-size: cover; background-position: center; border-radius: 15px; margin-bottom: 30px;"></div>` : ''}
                <div style="display: flex; gap: 30px; flex-wrap: wrap; margin-bottom: 30px; font-size: 1.1rem;">
                    ${data.date ? `<div><i class="far fa-calendar-alt"></i> ${escapeHtml(data.date)}</div>` : ''}
                    ${data.time ? `<div><i class="far fa-clock"></i> ${escapeHtml(data.time)}</div>` : ''}
                    ${data.venue ? `<div><i class="fas fa-map-marker-alt"></i> ${escapeHtml(data.venue)}</div>` : ''}
                </div>
                ${data.description ? `<div style="color: var(--light); line-height: 1.8; font-size: 1.05rem; margin-bottom: 30px;">${escapeHtml(data.description)}</div>` : ''}
                ${data.ticket_url ? `<a href="${escapeHtml(data.ticket_url)}" target="_blank" style="display: inline-block; background: var(--accent); color: white; padding: 15px 25px; border-radius: 10px; text-decoration: none; font-weight: 700; margin-top: 20px;"><i class="fas fa-ticket-alt"></i> Get Tickets</a>` : ''}
                <div style="margin-top: 30px;">
                    <a href="${escapeHtml(data.url)}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); color: var(--accent); border: 1px solid rgba(255,255,255,0.2); border-radius: 25px; text-decoration: none; font-weight: 600; font-size: 0.95rem;">
                        <i class="fas fa-external-link-alt"></i>
                        <span>View Full Page</span>
                    </a>
                </div>
            </div>
        `;
    }
    
    /**
     * Create modal container
     */
    function createModal() {
        const modal = document.createElement('div');
        modal.className = 'content-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.9);
            backdrop-filter: blur(5px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease-out;
        `;
        
        // Close on backdrop click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
        
        return modal;
    }
    
    /**
     * Close modal
     */
    function closeModal() {
        if (currentModal) {
            currentModal.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                if (currentModal && currentModal.parentNode) {
                    currentModal.parentNode.removeChild(currentModal);
                }
                currentModal = null;
                document.body.style.overflow = '';
            }, 300);
        }
    }
    
    /**
     * Escape HTML
     */
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Export API
    window.ModalSystem = {
        open: openModal,
        close: closeModal,
    };
    
    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); }
            to { transform: translateX(100%); }
        }
        .content-modal .modal-content {
            background: var(--glass);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            max-width: 900px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        }
    `;
    document.head.appendChild(style);
})();

