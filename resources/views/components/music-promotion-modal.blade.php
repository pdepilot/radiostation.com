{{-- Music Promotion Modal --}}
<div id="musicPromotionModal" class="music-promotion-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); backdrop-filter: blur(8px); z-index: 10000; align-items: center; justify-content: center; overflow-y: auto; padding: 20px;">
    <div class="music-promotion-modal-content" style="background: var(--glass); backdrop-filter: blur(20px); border-radius: 24px; padding: 40px; max-width: 600px; width: 100%; border: 1px solid var(--glass-border); box-shadow: 0 25px 70px rgba(0,0,0,0.6); position: relative; margin: auto;">
        <button class="close-promotion-modal" style="position: absolute; top: 20px; right: 20px; background: transparent; border: none; color: var(--text-secondary); font-size: 2rem; cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.1)'; this.style.color='var(--accent)'" onmouseout="this.style.background='transparent'; this.style.color='var(--text-secondary)'">&times;</button>
        
        <div id="promotionFormContainer">
            <h2 style="color: var(--accent); font-family: 'Orbitron', sans-serif; font-size: 2rem; margin-bottom: 10px; font-weight: 700; text-align: center;">🎵 Promote Your Music</h2>
            <p style="color: var(--text-secondary); text-align: center; margin-bottom: 30px; font-size: 0.95rem;">Get your music featured on Darling FM homepage</p>

            {{-- Waitlist Form (shown when slots are full) --}}
            <div id="waitlistForm" style="display: none;">
                <div style="text-align: center; padding: 20px; background: rgba(200,16,46,0.1); border-radius: 12px; margin-bottom: 20px; border: 1px solid rgba(200,16,46,0.3);">
                    <i class="fas fa-info-circle" style="color: var(--accent); font-size: 2rem; margin-bottom: 10px;"></i>
                    <p style="color: var(--light); margin-bottom: 15px; font-weight: 600;">All promotion slots are currently full</p>
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">Join our waitlist and we'll notify you when slots become available.</p>
                </div>
                <form id="waitlistFormElement">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label for="waitlistEmail" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Email Address</label>
                        <input type="email" id="waitlistEmail" name="email" required style="width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; font-family: inherit;" placeholder="your@email.com">
                    </div>
                    <button type="submit" style="width: 100%; padding: 14px; background: var(--accent); color: white; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='#a00d2e'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)'">
                        Join Waitlist
                    </button>
                </form>
            </div>

            {{-- Promotion Submission Form --}}
            <form id="promotionForm" style="display: none;">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="artistName" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Artist Name *</label>
                    <input type="text" id="artistName" name="artist_name" required style="width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; font-family: inherit;" placeholder="Enter artist name">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="trackTitle" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Track Title *</label>
                    <input type="text" id="trackTitle" name="track_title" required style="width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; font-family: inherit;" placeholder="Enter track title">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="description" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Description</label>
                    <textarea id="description" name="description" rows="3" style="width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; font-family: inherit; resize: vertical;" placeholder="Tell us about your track..."></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="audioEmbedUrl" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Audio Embed URL</label>
                    <input type="url" id="audioEmbedUrl" name="audio_embed_url" style="width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; font-family: inherit;" placeholder="https://soundcloud.com/... or Spotify embed URL">
                    <small style="color: var(--text-secondary); display: block; margin-top: 5px; font-size: 0.85rem;">Link to your track on SoundCloud, Spotify, YouTube, etc.</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="coverImage" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Cover Image</label>
                    <input type="file" id="coverImage" name="cover_image" accept="image/*" style="width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; font-family: inherit;">
                    <small style="color: var(--text-secondary); display: block; margin-top: 5px; font-size: 0.85rem;">JPG, PNG, GIF (max 2MB)</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="ctaUrl" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Call-to-Action URL</label>
                    <input type="url" id="ctaUrl" name="cta_url" style="width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; font-family: inherit;" placeholder="https://yourwebsite.com or streaming link">
                    <small style="color: var(--text-secondary); display: block; margin-top: 5px; font-size: 0.85rem;">Where should users go when they click your promotion?</small>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Promotion Duration *</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <label style="display: flex; align-items: center; padding: 15px; background: rgba(0,0,0,0.3); border: 2px solid var(--glass-border); border-radius: 10px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                            <input type="radio" name="duration_days" value="7" required style="margin-right: 10px; width: 20px; height: 20px; cursor: pointer;">
                            <div>
                                <div style="color: var(--light); font-weight: 600;">7 Days</div>
                                <div style="color: var(--accent); font-size: 0.9rem; font-weight: 700;" id="price7Days">₦5,000</div>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; padding: 15px; background: rgba(0,0,0,0.3); border: 2px solid var(--glass-border); border-radius: 10px; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--glass-border)'">
                            <input type="radio" name="duration_days" value="14" required style="margin-right: 10px; width: 20px; height: 20px; cursor: pointer;">
                            <div>
                                <div style="color: var(--light); font-weight: 600;">14 Days</div>
                                <div style="color: var(--accent); font-size: 0.9rem; font-weight: 700;" id="price14Days">₦9,000</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="email" style="display: block; color: var(--light); margin-bottom: 8px; font-weight: 600;">Email Address *</label>
                    <input type="email" id="email" name="email" required style="width: 100%; padding: 14px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); border-radius: 10px; color: var(--light); font-size: 1rem; font-family: inherit;" placeholder="your@email.com">
                    <small style="color: var(--text-secondary); display: block; margin-top: 5px; font-size: 0.85rem;">For payment receipt and updates</small>
                </div>

                <button type="submit" id="submitPromotionBtn" style="width: 100%; padding: 16px; background: var(--accent); color: white; border: none; border-radius: 10px; font-size: 1.1rem; font-weight: 700; cursor: pointer; transition: all 0.3s;" onmouseover="this.style.background='#a00d2e'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='var(--accent)'; this.style.transform='translateY(0)'">
                    Proceed to Payment
                </button>
            </form>

            <div id="promotionLoading" style="display: none; text-align: center; padding: 40px;">
                <div class="spinner" style="border: 4px solid rgba(200,16,46,0.3); border-top: 4px solid var(--accent); border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
                <p style="color: var(--text-secondary);">Processing...</p>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .music-promotion-modal input[type="radio"]:checked + div {
        color: var(--accent) !important;
    }

    .music-promotion-modal label:has(input[type="radio"]:checked) {
        border-color: var(--accent) !important;
        background: rgba(200,16,46,0.15) !important;
    }
</style>
