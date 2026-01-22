// Main radio stream URL (24/7 broadcast) - Port 7572 = primary/default listener endpoint
// Use window property to avoid redeclaration errors during Livewire navigation
if (typeof window.MAIN_STREAM_URL === 'undefined') {
    window.MAIN_STREAM_URL = "https://phoebe.streamerr.co:7572/stream";
}
// Reference window property directly - no local variable to avoid redeclaration
if (typeof MAIN_STREAM_URL === 'undefined') {
    var MAIN_STREAM_URL = window.MAIN_STREAM_URL;
}

// OAP Live Stream URL (for special broadcasts)
if (typeof window.OAP_STREAM_URL === 'undefined') {
    window.OAP_STREAM_URL = "https://phoebe.streamerr.co:7572/stream";
}
if (typeof OAP_STREAM_URL === 'undefined') {
    var OAP_STREAM_URL = window.OAP_STREAM_URL;
}

// Backup stream URL (Port 7567 = secondary/failover)
if (typeof window.BACKUP_STREAM_URL === 'undefined') {
    window.BACKUP_STREAM_URL = "https://phoebe.streamerr.co:7567/stream";
}
if (typeof BACKUP_STREAM_URL === 'undefined') {
    var BACKUP_STREAM_URL = window.BACKUP_STREAM_URL;
}


// Prevent re-execution during Livewire navigation
(function() {
    if (window.indexJsInitialized) return;
    window.indexJsInitialized = true;
    
    function initIndexJs() {
    // Prevent pull-to-refresh duplicate navbar
    let lastScrollTop = 0;
    let isScrolling = false;
    
    // Mobile menu toggle
    const mobileMenu = document.querySelector(".mobile-menu");
    const desktopNav = document.querySelector(".desktop-nav");

    if (mobileMenu && desktopNav) {
        mobileMenu.addEventListener("click", function () {
            desktopNav.style.display =
                desktopNav.style.display === "flex" ? "none" : "flex";
        });
    }

    // Handle window resize
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768 && desktopNav) {
            desktopNav.style.display = "flex";
        } else if (desktopNav) {
            desktopNav.style.display = "none";
        }
    });

    // On-scroll navbar effect - prevent duplicate
    window.addEventListener("scroll", function() {
        const header = document.getElementById("main-header");
        if (!header) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Prevent duplicate navbar on pull-to-refresh
        if (scrollTop < 0) {
            return;
        }
        
        if (scrollTop > 50) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
        
        lastScrollTop = scrollTop;
    }, { passive: true });
    
    // Prevent overscroll behavior that causes duplicate navbar
    document.body.style.overscrollBehaviorY = 'contain';
    document.documentElement.style.overscrollBehaviorY = 'contain';

    // Listen button functionality
    const listenBtn = document.querySelector('.listen-btn');
    if (listenBtn) {
        listenBtn.addEventListener('click', function() {
            if (typeof showInfo === 'function') {
                showInfo('Connecting to live stream...');
            }
            // In a real implementation, this would connect to the live stream
        });
    }

    // Remind button functionality
    const remindBtns = document.querySelectorAll('.remind-btn');
    remindBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const showCard = this.closest('.show-card');
            if (!showCard) return;
            const showNameEl = showCard.querySelector('.show-name');
            const showTimeEl = showCard.querySelector('.show-time span');
            if (!showNameEl || !showTimeEl) return;
            const showName = showNameEl.textContent;
            const showTime = showTimeEl.textContent;
            
            if (typeof showSuccess === 'function') {
                showSuccess(`Reminder set for ${showName} at ${showTime}`);
            } else if (window.notifications) {
                window.notifications.success(`Reminder set for ${showName} at ${showTime}`);
            }
            
            // Change button state
            this.innerHTML = '<i class="fas fa-bell"></i> Reminder Set';
            this.style.background = 'var(--accent)';
            this.style.color = 'white';
            this.style.borderColor = 'var(--accent)';
        });
    });

    // =============================================
    // LIVE STREAMING FUNCTIONALITY
    // =============================================
    
    // DOM Elements
    const statusIndicator = document.getElementById('statusIndicator');
    const statusText = document.getElementById('statusText');
    const startStreamBtn = document.getElementById('startStreamBtn');
    const stopStreamBtn = document.getElementById('stopStreamBtn');
    const joinStreamBtn = document.getElementById('joinStreamBtn');
    const leaveStreamBtn = document.getElementById('leaveStreamBtn');
    const streamInfo = document.getElementById('streamInfo');
    const streamTitle = document.getElementById('streamTitle');
    const streamDescription = document.getElementById('streamDescription');
    const listenerCount = document.getElementById('listenerCount');
    const oapControls = document.getElementById('oapControls');
    const streamTitleInput = document.getElementById('streamTitleInput');
    const streamDescriptionInput = document.getElementById('streamDescriptionInput');
    const updateStreamBtn = document.getElementById('updateStreamBtn');
    
    // Stream State
    let isStreaming = false;
    let isListening = false;
    let currentListeners = 0;
    let userRole = 'listener';
    
    // Create Audio object for OAP live stream
    const liveStream = new Audio();
    liveStream.preload = "none";

    // Check if user is an OAP
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('role') === 'oap') {
        userRole = 'oap';
        if (startStreamBtn) startStreamBtn.style.display = 'flex';
        if (stopStreamBtn) stopStreamBtn.style.display = 'flex';
        if (joinStreamBtn) joinStreamBtn.style.display = 'none';
        if (leaveStreamBtn) leaveStreamBtn.style.display = 'none';
    } else {
        if (startStreamBtn) startStreamBtn.style.display = 'none';
        if (stopStreamBtn) stopStreamBtn.style.display = 'none';
        if (joinStreamBtn) joinStreamBtn.style.display = 'flex';
        if (leaveStreamBtn) leaveStreamBtn.style.display = 'flex';
    }
    
    // Start Stream (OAP only)
    if (startStreamBtn) {
        startStreamBtn.addEventListener('click', function() {
            if (!isStreaming) {
                // Set up OAP live stream
                liveStream.src = window.OAP_STREAM_URL || OAP_STREAM_URL;
                
                // For a real implementation, OAP url will be here when i get it
                // OAP stream started - connecting to stream
                
                isStreaming = true;
                updateStreamUI();
                
                if (streamInfo) streamInfo.style.display = 'block';
                if (oapControls) oapControls.style.display = 'block';
                
                if (statusIndicator) {
                    statusIndicator.style.background = '#00ff00';
                    statusIndicator.style.boxShadow = '0 0 10px #00ff00';
                }
                if (statusText) statusText.textContent = 'Live Now';
                
                startStreamBtn.disabled = true;
                if (stopStreamBtn) stopStreamBtn.disabled = false;
            }
        });
    }
    
    // Stop Stream (OAP only)
    if (stopStreamBtn) {
        stopStreamBtn.addEventListener('click', function() {
            if (isStreaming) {
                // Stop the live stream
                liveStream.pause();
                liveStream.src = '';
                
                // OAP stream stopped
                
                isStreaming = false;
                updateStreamUI();
                
                if (streamInfo) streamInfo.style.display = 'none';
                if (oapControls) oapControls.style.display = 'none';
                
                if (statusIndicator) {
                    statusIndicator.style.background = '#ff3333';
                    statusIndicator.style.boxShadow = '0 0 10px #ff3333';
                }
                if (statusText) statusText.textContent = 'Currently Offline';
                
                if (startStreamBtn) startStreamBtn.disabled = false;
                stopStreamBtn.disabled = true;
                
                currentListeners = 0;
                if (listenerCount) listenerCount.textContent = '0';
                
                if (isListening) {
                    isListening = false;
                    if (joinStreamBtn) joinStreamBtn.disabled = false;
                    if (leaveStreamBtn) leaveStreamBtn.disabled = true;
                }
            }
        });
    }
    
    // Join Stream (Listener)
    if (joinStreamBtn) {
        joinStreamBtn.addEventListener('click', function() {
            if (!isListening && isStreaming) {
                // Connect to OAP live stream
                liveStream.src = window.OAP_STREAM_URL || OAP_STREAM_URL;
                liveStream.play()
                    .then(() => {
                        isListening = true;
                        updateStreamUI();
                        
                        if (streamInfo) streamInfo.style.display = 'block';
                        
                        joinStreamBtn.disabled = true;
                        if (leaveStreamBtn) leaveStreamBtn.disabled = false;
                        
                        currentListeners++;
                        updateListenerCount();
                        
                        // Connected to OAP live stream
                    })
                    .catch(e => {
                        console.error("Error connecting to live stream:", e);
                        if (typeof showError === 'function') {
                            showError("Unable to connect to the live stream. Please try again.");
                        } else if (window.notifications) {
                            window.notifications.error("Unable to connect to the live stream. Please try again.");
                        }
                    });
            }
        });
    }
    
    // Leave Stream (Listener)
    if (leaveStreamBtn) {
        leaveStreamBtn.addEventListener('click', function() {
            if (isListening) {
                liveStream.pause();
                isListening = false;
                updateStreamUI();
                
                if (streamInfo) streamInfo.style.display = 'none';
                
                if (joinStreamBtn) joinStreamBtn.disabled = false;
                leaveStreamBtn.disabled = true;
                
                if (currentListeners > 0) {
                    currentListeners--;
                    updateListenerCount();
                }
            }
        });
    }
    
    // Update Stream Info (OAP only)
    if (updateStreamBtn) {
        updateStreamBtn.addEventListener('click', function() {
            const title = streamTitleInput?.value?.trim() || '';
            const description = streamDescriptionInput?.value?.trim() || '';
            
            if (title && streamTitle) {
                streamTitle.textContent = title;
                if (streamTitleInput) streamTitleInput.value = title;
            }
            
            if (description && streamDescription) {
                streamDescription.textContent = description;
                if (streamDescriptionInput) streamDescriptionInput.value = description;
            }
            
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Stream information updated!',
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    background: 'var(--glass)',
                    color: 'var(--text-primary)',
                    confirmButtonColor: '#c8102e'
                });
            } else if (typeof showSuccess === 'function') {
                showSuccess('Stream information updated!');
            } else if (window.notifications) {
                window.notifications.success('Stream information updated!');
            }
        });
    }
    
    function updateStreamUI() {
        if (listenerCount) {
            listenerCount.textContent = currentListeners;
        }
        
        if (userRole === 'oap') {
            if (startStreamBtn) startStreamBtn.disabled = isStreaming;
            if (stopStreamBtn) stopStreamBtn.disabled = !isStreaming;
        } else {
            if (joinStreamBtn) joinStreamBtn.disabled = isListening || !isStreaming;
            if (leaveStreamBtn) leaveStreamBtn.disabled = !isListening;
        }
    }
    
    function updateListenerCount() {
        if (listenerCount) {
            listenerCount.textContent = currentListeners;
        }
    }
    
    
    // Initialize UI
    updateStreamUI();

    // =============================================
    // ADDITIONAL FUNCTIONALITY (Posts, OAP profiles, etc.)
    // =============================================
    
    // OAP Profile Modal
    const profileModal = document.getElementById('profileModal');
    const profileButtons = document.querySelectorAll('.aop-profile-btn');
    const closeModalButtons = document.querySelectorAll('.close-modal');
    
    // OAP data with social media handles - UPDATED BIOS
    const oapData = {
        'dj-alex': {
            name: 'DJ XTREME (Soundboykiller)',
            show: 'Morning Show',
            schedule: 'Weekdays 6AM - 10AM',
            image: 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.16_d35f6e83_pv497n.jpg',
            bio: 'DJ XTREME (Soundboykiller)\n\nTitle/Role: Professional DJ | Radio Station DJ | Studio Technician | Event DJ | Darling Fm Head DJ Department\n\nDJ XTREME, popularly known as Soundboykiller, is a dynamic and versatile DJ recognized for his electrifying mixes and technical precision. With a passion for delivering unforgettable sound experiences, he brings an exceptional blend of creativity and skill to every performance — from high-energy club nights to top-tier radio shows.\n\nAs a seasoned studio technician, DJ XTREME\'s deep understanding of sound engineering ensures crisp, powerful audio quality both on and off the stage. His sets are a fusion of innovation and rhythm, designed to captivate audiences and keep the energy flowing.\n\nWith an ever-growing reputation across events and the airwaves, DJ XTREME continues to redefine the DJ scene — proving that true sound mastery lives in the mix.',
            social: [
                { platform: 'twitter', handle: '@dj_xtreme', url: 'https://twitter.com/dj_xtreme' },
                { platform: 'instagram', handle: '@dj_xtreme_official', url: 'https://instagram.com/dj_xtreme_official' },
                { platform: 'facebook', handle: 'DJ XTREME Official', url: 'https://facebook.com/djxtremeofficial' },
                { platform: 'youtube', handle: 'DJ XTREME', url: 'https://youtube.com/djxtreme' }
            ]
        },
        'sarah-miles': {
            name: 'COSMAS CHUKWUEMEKA PUYAKA',
            show: 'Afternoon Drive',
            schedule: 'Weekdays 3PM - 7PM',
            image: 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.31_e7adcda0_tehu62.jpg',
            bio: 'Head of Sports at Darling FM Chairman, Sports Writers Association of Nigeria, Imo State Chapter.',
            social: [
                { platform: 'twitter', handle: '@sarah_miles', url: 'https://twitter.com/sarah_miles' },
                { platform: 'instagram', handle: '@sarahmiles_radio', url: 'https://instagram.com/sarahmiles_radio' },
                { platform: 'facebook', handle: 'Sarah Miles Radio', url: 'https://facebook.com/sarahmilesradio' },
                { platform: 'tiktok', handle: '@sarahmiles', url: 'https://tiktok.com/@sarahmiles' }
            ]
        },
        'dj-ujah': {
            name: 'CHIDERA UJAH',
            show: 'Retro Rewind',
            schedule: 'Saturdays 2PM - 6PM',
            image: 'https://res.cloudinary.com/dl4hjr1p2/image/upload/v1762957223/OAP1_gtmlhf.jpg',
            bio: 'Chidera Ujah, is a broadcast journalist with years of experience in storytelling and media advocacy.\n\nA rights advocate for women and children.\n\nShe holds a Bachelor\'s degree in English and Literary Studies and has honed her skills in radio presentation, media and communications, and content creation.\n\nShe promotes awareness, empowerment, and community development across various audiences.',
            social: [
                { platform: 'twitter', handle: '@djmarco_rewind', url: 'https://twitter.com/djmarco_rewind' },
                { platform: 'instagram', handle: '@djmarco_classics', url: 'https://instagram.com/djmarco_classics' },
                { platform: 'facebook', handle: 'DJ Marco Retro Rewind', url: 'https://facebook.com/djmarco_retrorewind' },
                { platform: 'youtube', handle: 'DJ Marco Classics', url: 'https://youtube.com/djmarco_classics' }
            ]
        },
        'lena-cruz': {
            name: 'Lena Cruz',
            show: 'Night Beats',
            schedule: 'Weeknights 10PM - 2AM',
            image: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-4.0.3&auto=format&fit=crop&w=634&q=80',
            bio: 'Lena curates the perfect soundtrack for your late nights. Specializing in deep house, ambient, and experimental electronic music, she creates an immersive audio experience that transports listeners to another dimension. Her sets are known for their seamless transitions and emotional depth.',
            social: [
                { platform: 'twitter', handle: '@lena_cruz', url: 'https://twitter.com/lena_cruz' },
                { platform: 'instagram', handle: '@lenacruz_music', url: 'https://instagram.com/lenacruz_music' },
                { platform: 'facebook', handle: 'Lena Cruz Music', url: 'https://facebook.com/lenacruzmuzic' },
                { platform: 'soundcloud', handle: 'Lena Cruz', url: 'https://soundcloud.com/lena_cruz' }
            ]
        }
    };

    // Open profile modal
    profileButtons.forEach(button => {
        button.addEventListener('click', function() {
            const aopId = this.getAttribute('data-aop');
            const oap = oapData[aopId];
            
            if (oap) {
                const modalName = document.getElementById('modalProfileName');
                const modalShow = document.getElementById('modalProfileShow');
                const modalSchedule = document.getElementById('modalProfileSchedule');
                const modalBio = document.getElementById('modalProfileBio');
                const modalImage = document.getElementById('modalProfileImage');
                const socialContainer = document.getElementById('modalProfileSocial');
                
                if (modalName) modalName.textContent = oap.name;
                if (modalShow) modalShow.textContent = oap.show;
                if (modalSchedule) modalSchedule.textContent = oap.schedule;
                if (modalBio) modalBio.textContent = oap.bio;
                if (modalImage) modalImage.style.backgroundImage = `url('${oap.image}')`;
                
                // Add social media handles
                if (socialContainer) {
                    socialContainer.innerHTML = '';
                
                    oap.social.forEach(social => {
                        const socialLink = document.createElement('a');
                        socialLink.href = social.url;
                        socialLink.target = '_blank';
                        socialLink.className = 'social-handle';
                        socialLink.innerHTML = `
                            <i class="fab fa-${social.platform}"></i>
                            <span>${social.handle}</span>
                        `;
                        socialContainer.appendChild(socialLink);
                    });
                }
                
                if (profileModal) profileModal.style.display = 'flex';
            }
        });
    });


    // Comment functionality
    const commentToggleButtons = document.querySelectorAll(".comment-toggle-btn");
    const commentSubmitButtons = document.querySelectorAll(".comment-submit");

    commentToggleButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const postCard = this.closest(".post-card");
            if (postCard) {
                const commentsSection = postCard.querySelector(".comments-section");
                if (commentsSection) {
                    commentsSection.style.display = commentsSection.style.display === "block" ? "none" : "block";
                }
            }
        });
    });

    commentSubmitButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const commentInput = this.parentElement?.querySelector(".comment-input");
            const commentsSection = this.closest(".comments-section");
            const postCard = this.closest(".post-card");
            const commentCount = postCard?.querySelector(".comment-count");
            
            if (commentInput && commentInput.value.trim() !== "") {
                // Create new comment element
                const newComment = document.createElement("div");
                newComment.className = "comment";
                
                const now = new Date();
                const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                
                newComment.innerHTML = `
                    <div class="comment-header">
                        <span class="comment-author">You</span>
                        <span class="comment-date">Just now</span>
                    </div>
                    <p>${commentInput.value}</p>
                `;
                
                // Insert before the comment form
                const commentForm = commentsSection.querySelector(".comment-form");
                if (commentForm) {
                    commentsSection.insertBefore(newComment, commentForm);
                } else {
                    commentsSection.appendChild(newComment);
                }
                
                // Update comment count
                if (commentCount) {
                    const currentCount = parseInt(commentCount.textContent) || 0;
                    commentCount.textContent = currentCount + 1;
                }
                
                // Clear input
                commentInput.value = "";
            }
        });
    });

    // Share functionality
    const shareButtons = document.querySelectorAll(".share-btn");
    const shareModal = document.getElementById("shareModal");
    let currentPostTitle = "";

    shareButtons.forEach((button) => {
        button.addEventListener("click", function (e) {
            e.stopPropagation();
            currentPostTitle = this.getAttribute("data-post-title") || "";
            const postUrl = this.getAttribute("data-post-url") || window.location.href;
            window.currentShareUrl = postUrl;
            if (shareModal) {
                shareModal.style.display = "flex";
            }
        });
    });

    // Share option functionality
    const shareOptions = document.querySelectorAll(".share-option");

    shareOptions.forEach((option) => {
        option.addEventListener("click", function() {
            const platform = this.getAttribute("data-platform");
            const url = encodeURIComponent(window.currentShareUrl || window.location.href);
            const text = encodeURIComponent(`Check out this news: ${currentPostTitle}`);
            
            let shareUrl = "";
            
            switch(platform) {
                case "facebook":
                    shareUrl = `https://www.facebook.com/sharer/sharer.html?u=${url}`;
                    break;
                case "twitter":
                    shareUrl = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;
                    break;
                case "instagram":
                    // Instagram doesn't have a direct sharing API for web
                    if (typeof showInfo === 'function') {
                        showInfo("To share on Instagram, copy the link and paste it in your Instagram story or post.");
                    } else if (window.notifications) {
                        window.notifications.info("To share on Instagram, copy the link and paste it in your Instagram story or post.");
                    }
                    return;
                case "whatsapp":
                    shareUrl = `https://api.whatsapp.com/send?text=${text} ${url}`;
                    break;
            }
            
            if (shareUrl) {
                window.open(shareUrl, '_blank', 'width=600,height=400');
            }
        });
    });

    // Feedback form submission
    const submitBtn = document.querySelector('.submit-btn');
    if (submitBtn) {
        submitBtn.addEventListener('click', function() {
            const name = document.getElementById('name');
            const email = document.getElementById('email');
            const subject = document.getElementById('subject');
            const message = document.getElementById('message');
            
                if (name && email && subject && message && name.value && email.value && subject.value && message.value) {
                    if (typeof showSuccess === 'function') {
                        showSuccess('Thank you for your feedback! We appreciate your input.');
                    } else if (window.notifications) {
                        window.notifications.success('Thank you for your feedback! We appreciate your input.');
                    }
                    // In a real application, you would send this data to a server
                    name.value = '';
                    email.value = '';
                    subject.value = '';
                    message.value = '';
                } else {
                    if (typeof showError === 'function') {
                        showError('Please fill in all fields before submitting.');
                    } else if (window.notifications) {
                        window.notifications.error('Please fill in all fields before submitting.');
                    }
                }
            });
        }

    // Enhanced Ads Functionality
    // Initialize after DOM is fully ready
    setTimeout(() => {
        initImageSliders();
        initVideoPlayers();
    }, 300);
    
    // Close ad functionality
    const closeAdButtons = document.querySelectorAll(".close-ad");
    
    closeAdButtons.forEach((button) => {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.parentElement.style.display = "none";
        });
    });
    
    // Auto-play video ads
    const videoAds = document.querySelectorAll(".video-ad");
    videoAds.forEach(video => {
        video.muted = true;
        video.play().catch(e => {
            // Autoplay prevented
        });
    });
    
    // Image Slider Functionality
    function initImageSliders() {
        const sliders = document.querySelectorAll(".image-slider-ad");
        
        sliders.forEach(slider => {
            const slides = slider.querySelector(".image-slides");
            const dots = slider.querySelectorAll(".slider-dot");
            if (!slides || !dots || dots.length === 0) return;
            
            let currentSlide = 0;
            let slideInterval;
            const slideCount = dots.length;
            
            // Set slides container width dynamically
            slides.style.width = `${slideCount * 100}%`;
            
            // Function to show a specific slide
            function showSlide(index) {
                if (index < 0 || index >= slideCount || !slides) return;
                
                const translateX = -(index * 100);
                slides.style.transform = `translateX(${translateX}%)`;
                
                // Update active dot
                dots.forEach((dot, i) => {
                    dot.classList.remove("active");
                    if (i === index) {
                        dot.classList.add("active");
                        dot.style.background = "white";
                        dot.style.transform = "scale(1.3)";
                    } else {
                        dot.style.background = "rgba(255, 255, 255, 0.6)";
                        dot.style.transform = "scale(1)";
                    }
                });
                
                currentSlide = index;
            }
            
            // Function to go to next slide
            function nextSlide() {
                let nextIndex = (currentSlide + 1) % slideCount;
                showSlide(nextIndex);
            }
            
            // Add click event to dots
            dots.forEach(dot => {
                dot.addEventListener("click", function(e) {
                    e.stopPropagation(); // Prevent triggering the ad click
                    const slideIndex = parseInt(this.getAttribute("data-slide"));
                    showSlide(slideIndex);
                    resetInterval();
                });
            });
            
            // Start automatic sliding
            function startInterval() {
                slideInterval = setInterval(nextSlide, 4000);
            }
            
            // Reset interval when user interacts
            function resetInterval() {
                clearInterval(slideInterval);
                startInterval();
            }
            
            // Initialize first slide
            showSlide(0);
            
            // Start the slider
            startInterval();
            
            // Pause on hover
            slider.addEventListener("mouseenter", () => {
                clearInterval(slideInterval);
            });
            
            slider.addEventListener("mouseleave", () => {
                startInterval();
            });
        });
    }
    
    // Video Player Functionality
    function initVideoPlayers() {
        const videos = document.querySelectorAll(".video-ad");
        
        videos.forEach(video => {
            const videoId = video.id;
            const playPauseBtn = document.querySelector(`.play-pause[data-video="${videoId}"]`);
            const muteBtn = document.querySelector(`.mute-btn[data-video="${videoId}"]`);
            const volumeSlider = document.querySelector(`.video-volume-slider[data-video="${videoId}"]`);
            const progressContainer = document.querySelector(`.video-progress-container[data-video="${videoId}"]`);
            const progressBar = document.querySelector(`.video-progress-bar[data-video="${videoId}"]`);
            const fullscreenBtn = document.querySelector(`.fullscreen-btn[data-video="${videoId}"]`);
            const videoContainer = video.parentElement;
            
            // Start video muted and autoplay
            video.muted = true;
            video.play().catch(e => {
                // Autoplay prevented
            });
            
            // Play/Pause functionality
            playPauseBtn.addEventListener("click", (e) => {
                e.stopPropagation(); 
                if (video.paused) {
                    video.play();
                    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
                } else {
                    video.pause();
                    playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
                }
            });
            
            // Mute/Unmute functionality
            muteBtn.addEventListener("click", (e) => {
                e.stopPropagation(); 
                video.muted = !video.muted;
                muteBtn.innerHTML = video.muted ? 
                    '<i class="fas fa-volume-mute"></i>' : 
                    '<i class="fas fa-volume-up"></i>';
                volumeSlider.value = video.muted ? 0 : video.volume;
            });
            
            // Volume control
            volumeSlider.addEventListener("input", (e) => {
                e.stopPropagation(); 
                video.volume = volumeSlider.value;
                video.muted = (volumeSlider.value == 0);
                muteBtn.innerHTML = video.muted ? 
                    '<i class="fas fa-volume-mute"></i>' : 
                    '<i class="fas fa-volume-up"></i>';
            });
            
            // Fullscreen functionality
            fullscreenBtn.addEventListener("click", (e) => {
                e.stopPropagation(); 
                if (videoContainer.requestFullscreen) {
                    videoContainer.requestFullscreen();
                } else if (videoContainer.webkitRequestFullscreen) {
                    videoContainer.webkitRequestFullscreen();
                } else if (videoContainer.msRequestFullscreen) {
                    videoContainer.msRequestFullscreen();
                }
            });
            
            // Progress bar functionality
            video.addEventListener("timeupdate", () => {
                const progress = (video.currentTime / video.duration) * 100;
                progressBar.style.width = `${progress}%`;
            });
            
            // Click on progress bar to seek
            progressContainer.addEventListener("click", (e) => {
                e.stopPropagation(); 
                const rect = progressContainer.getBoundingClientRect();
                const pos = (e.clientX - rect.left) / rect.width;
                video.currentTime = pos * video.duration;
            });
            
            // Update play/pause button when video ends (for looping)
            video.addEventListener("ended", () => {
                    playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
            });
            
            // Show controls when hovering over video
            const videoControls = videoContainer.querySelector(".video-controls");
            if (videoControls) {
                videoContainer.addEventListener("mouseenter", () => {
                    videoControls.style.opacity = "1";
                });
                
                videoContainer.addEventListener("mouseleave", () => {
                    videoControls.style.opacity = "0";
                });
            }
        });
    }

    // Close modals
    closeModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            profileModal.style.display = 'none';
            shareModal.style.display = 'none';
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === profileModal) {
            profileModal.style.display = 'none';
        }
        if (event.target === shareModal) {
            shareModal.style.display = 'none';
        }
    });

    // Scroll reveal animation
    function revealOnScroll() {
        const elements = document.querySelectorAll('.post-card, .aop-card');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('revealed');
            }
        });
    }

    // Reveal immediately on load and on scroll
    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);
    
    // Also reveal immediately if DOM is already loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', revealOnScroll);
    } else {
        revealOnScroll();
    }
    
    // Force reveal after a short delay to ensure all elements are rendered
    setTimeout(revealOnScroll, 100);

    // =============================================
    // OAP CAROUSEL NAVIGATION
    // =============================================
    const aopsCarousel = document.getElementById('aopsCarousel');
    const aopsPrevBtn = document.getElementById('aopsPrevBtn');
    const aopsNextBtn = document.getElementById('aopsNextBtn');
    const aopsWrapper = document.querySelector('.aops-carousel-wrapper');

    if (aopsCarousel && aopsPrevBtn && aopsNextBtn && aopsWrapper) {
        let currentScrollPosition = 0;
        const cardWidth = 300; // min-width of aop-card
        const gap = 40; // gap between cards
        const scrollAmount = cardWidth + gap;

        // Function to update button visibility
        function updateButtonVisibility() {
            const wrapperWidth = aopsWrapper.clientWidth;
            const carouselWidth = aopsCarousel.scrollWidth;
            const maxScroll = Math.max(0, carouselWidth - wrapperWidth);
            
            if (currentScrollPosition <= 0) {
                aopsPrevBtn.style.opacity = '0.3';
                aopsPrevBtn.style.pointerEvents = 'none';
            } else {
                aopsPrevBtn.style.opacity = '1';
                aopsPrevBtn.style.pointerEvents = 'auto';
            }

            if (currentScrollPosition >= maxScroll - 10) {
                aopsNextBtn.style.opacity = '0.3';
                aopsNextBtn.style.pointerEvents = 'none';
            } else {
                aopsNextBtn.style.opacity = '1';
                aopsNextBtn.style.pointerEvents = 'auto';
            }
        }

        // Previous button click
        aopsPrevBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            currentScrollPosition = Math.max(0, currentScrollPosition - scrollAmount);
            aopsCarousel.style.transform = `translateX(-${currentScrollPosition}px)`;
            updateButtonVisibility();
        });

        // Next button click
        aopsNextBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            const wrapperWidth = aopsWrapper.clientWidth;
            const carouselWidth = aopsCarousel.scrollWidth;
            const maxScroll = Math.max(0, carouselWidth - wrapperWidth);
            currentScrollPosition = Math.min(maxScroll, currentScrollPosition + scrollAmount);
            aopsCarousel.style.transform = `translateX(-${currentScrollPosition}px)`;
            updateButtonVisibility();
        });
        
        // Ensure buttons are on top and clickable
        aopsPrevBtn.style.zIndex = '10000';
        aopsNextBtn.style.zIndex = '10000';
        aopsPrevBtn.style.pointerEvents = 'auto';
        aopsNextBtn.style.pointerEvents = 'auto';

        // Initialize button visibility
        updateButtonVisibility();

        // Update on window resize
        window.addEventListener('resize', function() {
            updateButtonVisibility();
        });
    }
    } // Close initIndexJs function
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initIndexJs);
    } else {
        initIndexJs();
    }
    
    // Re-initialize on Livewire navigation (only for elements that might be replaced)
    document.addEventListener('livewire:navigated', function() {
        // Re-initialize scroll to top and other page-specific functionality
        initScrollToTop();
    });
})();

// Scroll to Top Functionality
// Scroll to top button handler - with null checks for SPA navigation
function initScrollToTop() {
    const scrollToTopBtn = document.getElementById("scrollToTopBtn");
    
    if (!scrollToTopBtn) return; // Element doesn't exist on this page
    
    // Show or hide the button based on scroll position
    window.addEventListener("scroll", function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add("show");
        } else {
            scrollToTopBtn.classList.remove("show");
        }
    });
    
    // Scroll to top when button is clicked
    scrollToTopBtn.addEventListener("click", function() {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initScrollToTop);
} else {
    initScrollToTop();
}

// Re-initialize on Livewire navigation
document.addEventListener('livewire:navigated', initScrollToTop);

