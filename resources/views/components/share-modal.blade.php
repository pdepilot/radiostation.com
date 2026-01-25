{{-- Share Modal Component --}}
<div id="shareModal{{ $shareId ?? '' }}" class="share-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); z-index: 10000; align-items: center; justify-content: center;">
    <div class="share-modal-content" style="background: var(--glass); backdrop-filter: blur(15px); border-radius: 20px; padding: 30px; max-width: 500px; width: 90%; border: 1px solid var(--glass-border); box-shadow: 0 20px 60px rgba(0,0,0,0.5); position: relative;">
        <button class="close-share-modal" style="position: absolute; top: 15px; right: 15px; background: transparent; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='var(--accent)'" onmouseout="this.style.background='transparent'; this.style.color='var(--text-secondary)'">&times;</button>
        <h3 style="color: var(--accent); font-family: 'Orbitron', sans-serif; font-size: 1.5rem; margin-bottom: 20px; font-weight: 700;">Share</h3>
        <div class="form-group" style="margin-bottom: 25px;">
            <label for="shareMessage{{ $shareId ?? '' }}" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Custom Message (Optional)</label>
            <textarea id="shareMessage{{ $shareId ?? '' }}" rows="3" placeholder="Add your personal message here..." style="width: 100%; padding: 12px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 0.95rem; resize: vertical; font-family: inherit;"></textarea>
        </div>
        <div style="margin-bottom: 25px;">
            <label style="display: block; color: var(--light); margin-bottom: 15px; font-weight: 600;">Share to:</label>
            <div class="share-options-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                <button class="share-option-btn" data-platform="facebook" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(24,119,242,0.2); border: 1px solid rgba(24,119,242,0.4); border-radius: 12px; color: #1877f2; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(24,119,242,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(24,119,242,0.2)'; this.style.transform='translateY(0)'">
                    <i class="fab fa-facebook-f" style="font-size: 1.3rem;"></i>
                    <span>Facebook</span>
                </button>
                <button class="share-option-btn" data-platform="twitter" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(29,161,242,0.2); border: 1px solid rgba(29,161,242,0.4); border-radius: 12px; color: #1da1f2; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(29,161,242,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(29,161,242,0.2)'; this.style.transform='translateY(0)'">
                    <i class="fab fa-twitter" style="font-size: 1.3rem;"></i>
                    <span>Twitter</span>
                </button>
                <button class="share-option-btn" data-platform="whatsapp" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(37,211,102,0.2); border: 1px solid rgba(37,211,102,0.4); border-radius: 12px; color: #25d366; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(37,211,102,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(37,211,102,0.2)'; this.style.transform='translateY(0)'">
                    <i class="fab fa-whatsapp" style="font-size: 1.3rem;"></i>
                    <span>WhatsApp</span>
                </button>
                <button class="share-option-btn" data-platform="telegram" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(37,150,190,0.2); border: 1px solid rgba(37,150,190,0.4); border-radius: 12px; color: #2596be; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(37,150,190,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(37,150,190,0.2)'; this.style.transform='translateY(0)'">
                    <i class="fab fa-telegram" style="font-size: 1.3rem;"></i>
                    <span>Telegram</span>
                </button>
                <button class="share-option-btn" data-platform="linkedin" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(0,119,181,0.2); border: 1px solid rgba(0,119,181,0.4); border-radius: 12px; color: #0077b5; cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(0,119,181,0.3)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(0,119,181,0.2)'; this.style.transform='translateY(0)'">
                    <i class="fab fa-linkedin-in" style="font-size: 1.3rem;"></i>
                    <span>LinkedIn</span>
                </button>
                <button class="share-option-btn" data-platform="copy" style="display: flex; align-items: center; justify-content: center; gap: 10px; padding: 15px; background: rgba(255,255,255,0.1); border: 1px solid var(--glass-border); border-radius: 12px; color: var(--light); cursor: pointer; transition: all 0.3s; font-weight: 600;" onmouseover="this.style.background='rgba(255,255,255,0.15)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.transform='translateY(0)'">
                    <i class="fas fa-link" style="font-size: 1.3rem;"></i>
                    <span>Copy Link</span>
                </button>
            </div>
        </div>
    </div>
</div>

