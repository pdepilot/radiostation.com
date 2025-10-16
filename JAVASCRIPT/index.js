
// Main radio stream URL (24/7 broadcast)
const MAIN_STREAM_URL = "https://your-radioserver.com:8000/stream";

// OAP Live Stream URL (for special broadcasts)
const OAP_STREAM_URL = "https://your-radioserver.com:8000/live";

// Backup stream URL (optional)
const BACKUP_STREAM_URL = "https://your-backup-server.com:8000/stream";


document.addEventListener("DOMContentLoaded", function() {
    // Mobile menu toggle
    const mobileMenu = document.querySelector(".mobile-menu");
    const desktopNav = document.querySelector(".desktop-nav");

    mobileMenu.addEventListener("click", function () {
        desktopNav.style.display =
            desktopNav.style.display === "flex" ? "none" : "flex";
    });

    // Handle window resize
    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            desktopNav.style.display = "flex";
        } else {
            desktopNav.style.display = "none";
        }
    });

    // On-scroll navbar effect
    window.addEventListener("scroll", function() {
        const header = document.getElementById("main-header");
        if (window.scrollY > 50) {
            header.classList.add("scrolled");
        } else {
            header.classList.remove("scrolled");
        }
    });

    // Listen button functionality
    const listenBtn = document.querySelector('.listen-btn');
    listenBtn.addEventListener('click', function() {
        alert('Connecting to live stream...');
        // In a real implementation, this would connect to the live stream
    });

    // Remind button functionality
    const remindBtns = document.querySelectorAll('.remind-btn');
    remindBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const showName = this.closest('.show-card').querySelector('.show-name').textContent;
            const showTime = this.closest('.show-card').querySelector('.show-time span').textContent;
            
            alert(`Reminder set for ${showName} at ${showTime}`);
            
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
    const liveChat = document.getElementById('liveChat');
    const chatMessages = document.getElementById('chatMessages');
    const chatInput = document.getElementById('chatInput');
    const chatSend = document.getElementById('chatSend');
    const chatListenerCount = document.getElementById('chatListenerCount');
    
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
        startStreamBtn.style.display = 'flex';
        stopStreamBtn.style.display = 'flex';
        joinStreamBtn.style.display = 'none';
        leaveStreamBtn.style.display = 'none';
    } else {
        startStreamBtn.style.display = 'none';
        stopStreamBtn.style.display = 'none';
        joinStreamBtn.style.display = 'flex';
        leaveStreamBtn.style.display = 'flex';
    }
    
    // Start Stream (OAP only)
    startStreamBtn.addEventListener('click', function() {
        if (!isStreaming) {
            // Set up OAP live stream
            liveStream.src = OAP_STREAM_URL;
            
            // For a real implementation, OAP url will be here when i get it
            console.log("OAP stream started - connecting to:", OAP_STREAM_URL);
            
            isStreaming = true;
            updateStreamUI();
            
            streamInfo.style.display = 'block';
            oapControls.style.display = 'block';
            liveChat.style.display = 'flex';
            
            statusIndicator.style.background = '#00ff00';
            statusIndicator.style.boxShadow = '0 0 10px #00ff00';
            statusText.textContent = 'Live Now';
            
            startStreamBtn.disabled = true;
            stopStreamBtn.disabled = false;
            
            addChatMessage('System', 'Live stream has started!', true);
            simulateListenerJoin();
        }
    });
    
    // Stop Stream (OAP only)
    stopStreamBtn.addEventListener('click', function() {
        if (isStreaming) {
            // Stop the live stream
            liveStream.pause();
            liveStream.src = '';
            
            console.log("OAP stream stopped");
            
            isStreaming = false;
            updateStreamUI();
            
            streamInfo.style.display = 'none';
            oapControls.style.display = 'none';
            liveChat.style.display = 'none';
            
            statusIndicator.style.background = '#ff3333';
            statusIndicator.style.boxShadow = '0 0 10px #ff3333';
            statusText.textContent = 'Currently Offline';
            
            startStreamBtn.disabled = false;
            stopStreamBtn.disabled = true;
            
            currentListeners = 0;
            listenerCount.textContent = '0';
            chatListenerCount.textContent = '0';
            
            chatMessages.innerHTML = '';
            
            if (isListening) {
                isListening = false;
                joinStreamBtn.disabled = false;
                leaveStreamBtn.disabled = true;
            }
        }
    });
    
    // Join Stream (Listener)
    joinStreamBtn.addEventListener('click', function() {
        if (!isListening && isStreaming) {
            // Connect to OAP live stream
            liveStream.src = OAP_STREAM_URL;
            liveStream.play()
                .then(() => {
                    isListening = true;
                    updateStreamUI();
                    
                    streamInfo.style.display = 'block';
                    liveChat.style.display = 'flex';
                    
                    joinStreamBtn.disabled = true;
                    leaveStreamBtn.disabled = false;
                    
                    currentListeners++;
                    updateListenerCount();
                    
                    addChatMessage('System', 'You joined the live stream', true);
                    console.log("Connected to OAP live stream");
                })
                .catch(e => {
                    console.error("Error connecting to live stream:", e);
                    alert("Unable to connect to the live stream. Please try again.");
                });
        }
    });
    
    // Leave Stream (Listener)
    leaveStreamBtn.addEventListener('click', function() {
        if (isListening) {
            liveStream.pause();
            isListening = false;
            updateStreamUI();
            
            streamInfo.style.display = 'none';
            liveChat.style.display = 'none';
            
            joinStreamBtn.disabled = false;
            leaveStreamBtn.disabled = true;
            
            if (currentListeners > 0) {
                currentListeners--;
                updateListenerCount();
            }
            
            addChatMessage('System', 'You left the live stream', true);
        }
    });
    
    // Update Stream Info (OAP only)
    updateStreamBtn.addEventListener('click', function() {
        const title = streamTitleInput.value.trim();
        const description = streamDescriptionInput.value.trim();
        
        if (title) {
            streamTitle.textContent = title;
            streamTitleInput.value = title;
        }
        
        if (description) {
            streamDescription.textContent = description;
            streamDescriptionInput.value = description;
        }
        
        alert('Stream information updated!');
    });
    
    // Chat functionality
    chatSend.addEventListener('click', sendChatMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendChatMessage();
    });
    
    function sendChatMessage() {
        const message = chatInput.value.trim();
        
        if (message && (isStreaming || isListening)) {
            const username = userRole === 'oap' ? 'You (OAP)' : 'You';
            addChatMessage(username, message, true);
            chatInput.value = '';
            
            // Simulate responses
            if (Math.random() > 0.5) {
                setTimeout(() => {
                    const randomUser = getRandomName();
                    const responses = [
                        'Great stream!',
                        'Love this song!',
                        'Can you play my request?',
                        'Hello from the chat!',
                        'The audio quality is amazing!'
                    ];
                    const randomResponse = responses[Math.floor(Math.random() * responses.length)];
                    addChatMessage(randomUser, randomResponse, false);
                }, 1000 + Math.random() * 3000);
            }
        }
    }
    
    function addChatMessage(author, message, isUser) {
        const messageEl = document.createElement('div');
        messageEl.className = 'chat-message';
        
        const now = new Date();
        const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        
        messageEl.innerHTML = `
            <div class="message-header">
                <span class="message-author" style="color: ${isUser ? '#ff3333' : '#ffffff'}">${author}</span>
                <span class="message-time">${timeString}</span>
            </div>
            <p>${message}</p>
        `;
        
        chatMessages.appendChild(messageEl);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function updateStreamUI() {
        listenerCount.textContent = currentListeners;
        chatListenerCount.textContent = currentListeners;
        
        if (userRole === 'oap') {
            startStreamBtn.disabled = isStreaming;
            stopStreamBtn.disabled = !isStreaming;
        } else {
            joinStreamBtn.disabled = isListening || !isStreaming;
            leaveStreamBtn.disabled = !isListening;
        }
    }
    
    function updateListenerCount() {
        listenerCount.textContent = currentListeners;
        chatListenerCount.textContent = currentListeners;
    }
    
    function simulateListenerJoin() {
        const interval = setInterval(() => {
            if (isStreaming) {
                currentListeners += Math.floor(Math.random() * 3);
                updateListenerCount();
                
                if (Math.random() > 0.7) {
                    const randomUser = getRandomName();
                    const greetings = [
                        'Hello everyone!',
                        'Just tuned in, sounds great!',
                        'This is awesome!',
                        'Hey from the chat!',
                        'Love the music selection!'
                    ];
                    const randomGreeting = greetings[Math.floor(Math.random() * greetings.length)];
                    addChatMessage(randomUser, randomGreeting, false);
                }
            } else {
                clearInterval(interval);
            }
        }, 5000);
    }
    
    function getRandomName() {
        const names = ['Alex', 'Sam', 'Jordan', 'Taylor', 'Casey', 'Morgan', 'Riley', 'Avery', 'Quinn', 'Dakota'];
        return names[Math.floor(Math.random() * names.length)];
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
    
    // OAP data with social media handles
    const oapData = {
        'dj-alex': {
            name: 'DJ Alex',
            show: 'Morning Show',
            schedule: 'Weekdays 6AM - 10AM',
            image: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=634&q=80',
            bio: 'DJ Alex has been revolutionizing morning radio for over 5 years. With his energetic personality and eclectic music taste, he wakes up the city with the perfect blend of hits, humor, and heart. When not on air, Alex produces electronic music and hosts underground parties.',
            social: [
                { platform: 'twitter', handle: '@dj_alex', url: 'https://twitter.com/dj_alex' },
                { platform: 'instagram', handle: '@dj_alex_official', url: 'https://instagram.com/dj_alex_official' },
                { platform: 'facebook', handle: 'DJ Alex Official', url: 'https://facebook.com/djalexofficial' },
                { platform: 'youtube', handle: 'DJ Alex', url: 'https://youtube.com/djalex' }
            ]
        },
        'sarah-miles': {
            name: 'Sarah Miles',
            show: 'Afternoon Drive',
            schedule: 'Weekdays 3PM - 7PM',
            image: 'https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=634&q=80',
            bio: 'Sarah brings her passion for storytelling and music to the afternoon drive. With a background in music journalism, she provides insightful commentary on the latest tracks and artists. Her interviews with musicians are legendary in the industry.',
            social: [
                { platform: 'twitter', handle: '@sarah_miles', url: 'https://twitter.com/sarah_miles' },
                { platform: 'instagram', handle: '@sarahmiles_radio', url: 'https://instagram.com/sarahmiles_radio' },
                { platform: 'facebook', handle: 'Sarah Miles Radio', url: 'https://facebook.com/sarahmilesradio' },
                { platform: 'tiktok', handle: '@sarahmiles', url: 'https://tiktok.com/@sarahmiles' }
            ]
        },
        'dj-marco': {
            name: 'DJ Marco',
            show: 'Retro Rewind',
            schedule: 'Saturdays 2PM - 6PM',
            image: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            bio: 'A walking encyclopedia of music from the 70s to the 90s, DJ Marco takes listeners on a nostalgic journey every Saturday. His extensive vinyl collection and deep knowledge of music history make Retro Rewind a must-listen for music lovers of all generations.',
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
                document.getElementById('modalProfileName').textContent = oap.name;
                document.getElementById('modalProfileShow').textContent = oap.show;
                document.getElementById('modalProfileSchedule').textContent = oap.schedule;
                document.getElementById('modalProfileBio').textContent = oap.bio;
                document.getElementById('modalProfileImage').style.backgroundImage = `url('${oap.image}')`;
                
                // Add social media handles
                const socialContainer = document.getElementById('modalProfileSocial');
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
                
                profileModal.style.display = 'flex';
            }
        });
    });

    // Podcast data with video URLs
    const podcastData = {
        'behind-the-music': {
            title: 'Behind the Music',
            description: 'Dive deep into the stories behind your favorite songs and artists with host DJ Alex. In this episode, we explore the making of the iconic album "Midnight Dreams" and interview the producer who made it all happen.',
            image: 'https://images.unsplash.com/photo-1478737270239-2f02b77fc618?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            duration: '45:30',
            video: 'VIDEOS/vMbuJi9yg7_jV01c.mp4'
        },
        'sound-waves': {
            title: 'Sound Waves',
            description: 'Exploring the science and psychology of sound and music with expert guests. This episode features Dr. Elena Martinez discussing how different frequencies affect our brain waves and emotional states.',
            image: 'https://images.unsplash.com/photo-1589003077984-894e133dabab?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            duration: '60:15',
            video: 'https://assets.mixkit.co/videos/preview/mixkit-close-up-of-a-dj-mixing-music-39854-large.mp4'
        },
        'artist-spotlight': {
            title: 'Artist Spotlight',
            description: 'Intimate conversations with emerging and established music artists. This week we sit down with indie sensation River Moon to discuss her creative process and upcoming tour.',
            image: 'https://images.unsplash.com/photo-1571974599782-87624638275f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80',
            duration: '55:45',
            video: 'https://assets.mixkit.co/videos/preview/mixkit-woman-singing-into-a-microphone-39853-large.mp4'
        }
    };

    // Podcast functionality
    const podcastModal = document.getElementById('podcastModal');
    const podcastButtons = document.querySelectorAll('.podcast-play-btn');
    const podcastPlayBtn = document.getElementById('podcastPlayBtn');
    const videoPodcast = document.getElementById('videoPodcast');
    const podcastProgressBar = document.getElementById('podcastProgressBar');
    const currentTimeEl = document.getElementById('currentTime');
    const durationEl = document.getElementById('duration');
    const rewindBtn = document.getElementById('rewindBtn');
    const forwardBtn = document.getElementById('forwardBtn');
    
    let isPodcastPlaying = false;

    // Open podcast modal
    podcastButtons.forEach(button => {
        button.addEventListener('click', function() {
            const podcastId = this.getAttribute('data-podcast');
            const podcast = podcastData[podcastId];
            
            if (podcast) {
                document.getElementById('modalPodcastTitle').textContent = podcast.title;
                document.getElementById('modalPodcastDescription').textContent = podcast.description;
                document.getElementById('modalPodcastImage').style.backgroundImage = `url('${podcast.image}')`;
                durationEl.textContent = podcast.duration;
                
                // Set video source
                videoPodcast.src = podcast.video;
                
                // Reset player
                podcastProgressBar.style.width = '0%';
                currentTimeEl.textContent = '0:00';
                podcastPlayBtn.innerHTML = '<i class="fas fa-play"></i>';
                isPodcastPlaying = false;
                
                podcastModal.style.display = 'flex';
            }
        });
    });

    // Podcast player controls
    podcastPlayBtn.addEventListener('click', function() {
        if (isPodcastPlaying) {
            // Pause podcast
            videoPodcast.pause();
            podcastPlayBtn.innerHTML = '<i class="fas fa-play"></i>';
        } else {
            // Play podcast
            videoPodcast.play();
            podcastPlayBtn.innerHTML = '<i class="fas fa-pause"></i>';
        }
        
        isPodcastPlaying = !isPodcastPlaying;
    });

    // Update progress bar and time
    videoPodcast.addEventListener('timeupdate', function() {
        const progress = (videoPodcast.currentTime / videoPodcast.duration) * 100;
        podcastProgressBar.style.width = `${progress}%`;
        
        // Update time display
        const currentMinutes = Math.floor(videoPodcast.currentTime / 60);
        const currentSeconds = Math.floor(videoPodcast.currentTime % 60);
        currentTimeEl.textContent = `${currentMinutes}:${currentSeconds < 10 ? '0' : ''}${currentSeconds}`;
        
        const durationMinutes = Math.floor(videoPodcast.duration / 60);
        const durationSeconds = Math.floor(videoPodcast.duration % 60);
        durationEl.textContent = `${durationMinutes}:${durationSeconds < 10 ? '0' : ''}${durationSeconds}`;
    });

    rewindBtn.addEventListener('click', function() {
        videoPodcast.currentTime -= 10;
    });

    forwardBtn.addEventListener('click', function() {
        videoPodcast.currentTime += 10;
    });

    // Reset play button when video ends
    videoPodcast.addEventListener('ended', function() {
        podcastPlayBtn.innerHTML = '<i class="fas fa-play"></i>';
        isPodcastPlaying = false;
    });

    // Comment functionality
    const commentToggleButtons = document.querySelectorAll(".comment-toggle-btn");
    const commentSubmitButtons = document.querySelectorAll(".comment-submit");

    commentToggleButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const commentsSection = this.closest(".post-card").querySelector(".comments-section");
            commentsSection.style.display = commentsSection.style.display === "block" ? "none" : "block";
        });
    });

    commentSubmitButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const commentInput = this.parentElement.querySelector(".comment-input");
            const commentsSection = this.closest(".comments-section");
            const commentCount = this.closest(".post-card").querySelector(".comment-count");
            
            if (commentInput.value.trim() !== "") {
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
                commentsSection.insertBefore(newComment, commentsSection.querySelector(".comment-form"));
                
                // Update comment count
                const currentCount = parseInt(commentCount.textContent);
                commentCount.textContent = currentCount + 1;
                
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
        button.addEventListener("click", function () {
            currentPostTitle = this.getAttribute("data-post-title");
            shareModal.style.display = "flex";
        });
    });

    // Share option functionality
    const shareOptions = document.querySelectorAll(".share-option");

    shareOptions.forEach((option) => {
        option.addEventListener("click", function() {
            const platform = this.getAttribute("data-platform");
            const url = encodeURIComponent(window.location.href);
            const text = encodeURIComponent(`Check out this news: ${currentPostTitle}`);
            
            let shareUrl = "";
            
            switch(platform) {
                case "facebook":
                    shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                    break;
                case "twitter":
                    shareUrl = `https://twitter.com/intent/tweet?text=${text}&url=${url}`;
                    break;
                case "instagram":
                    // Instagram doesn't have a direct sharing API for web
                    alert("To share on Instagram, copy the link and paste it in your Instagram story or post.");
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

    // Like button functionality
    const likeButtons = document.querySelectorAll(".like-btn");

    likeButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const heartIcon = this.querySelector("i");
            const likeCount = this.querySelector(".like-count");
            let count = parseInt(likeCount.textContent);
            
            if (heartIcon.classList.contains("far")) {
                heartIcon.classList.remove("far");
                heartIcon.classList.add("fas");
                heartIcon.style.color = "#ff0000";
                likeCount.textContent = count + 1;
            } else {
                heartIcon.classList.remove("fas");
                heartIcon.classList.add("far");
                heartIcon.style.color = "";
                likeCount.textContent = count - 1;
            }
        });
    });

    // Feedback form submission
    const submitBtn = document.querySelector('.submit-btn');
    submitBtn.addEventListener('click', function() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const subject = document.getElementById('subject').value;
        const message = document.getElementById('message').value;
        
        if (name && email && subject && message) {
            alert('Thank you for your feedback! We appreciate your input.');
            // In a real application, you would send this data to a server
            document.getElementById('name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('subject').value = '';
            document.getElementById('message').value = '';
        } else {
            alert('Please fill in all fields before submitting.');
        }
    });

    // Enhanced Ads Functionality
    initImageSliders();
    
    // Initialize video players
    initVideoPlayers();
    
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
            console.log("Autoplay prevented:", e);
        });
    });
    
    // Image Slider Functionality
    function initImageSliders() {
        const sliders = document.querySelectorAll(".image-slider-ad");
        
        sliders.forEach(slider => {
            const slides = slider.querySelector(".image-slides");
            const dots = slider.querySelectorAll(".slider-dot");
            let currentSlide = 0;
            let slideInterval;
            
            // Function to show a specific slide
            function showSlide(index) {
                slides.style.transform = `translateX(-${index * 25}%)`;
                
                // Update active dot
                dots.forEach(dot => dot.classList.remove("active"));
                dots[index].classList.add("active");
                
                currentSlide = index;
            }
            
            // Function to go to next slide
            function nextSlide() {
                let nextIndex = (currentSlide + 1) % 4;
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
                console.log("Autoplay prevented:", e);
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
            videoContainer.addEventListener("mouseenter", () => {
                videoContainer.querySelector(".video-controls").style.opacity = "1";
            });
            
            videoContainer.addEventListener("mouseleave", () => {
                videoContainer.querySelector(".video-controls").style.opacity = "0";
            });
        });
    }

    // Close modals
    closeModalButtons.forEach(button => {
        button.addEventListener('click', function() {
            profileModal.style.display = 'none';
            podcastModal.style.display = 'none';
            shareModal.style.display = 'none';
            
            // Stop podcast if playing
            if (isPodcastPlaying) {
                videoPodcast.pause();
                isPodcastPlaying = false;
                podcastPlayBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === profileModal) {
            profileModal.style.display = 'none';
        }
        if (event.target === podcastModal) {
            podcastModal.style.display = 'none';
            
            // Stop podcast if playing
            if (isPodcastPlaying) {
                videoPodcast.pause();
                isPodcastPlaying = false;
                podcastPlayBtn.innerHTML = '<i class="fas fa-play"></i>';
            }
        }
        if (event.target === shareModal) {
            shareModal.style.display = 'none';
        }
    });

    // Scroll reveal animation
    function revealOnScroll() {
        const elements = document.querySelectorAll('.post-card, .aop-card, .podcast-card');
        
        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            
            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('revealed');
            }
        });
    }

    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);
});