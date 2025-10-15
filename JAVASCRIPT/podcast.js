
      document.addEventListener("DOMContentLoaded", function () {
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById("mobileMenuBtn");
        const mobileNav = document.getElementById("mobileNav");

        mobileMenuBtn.addEventListener("click", function () {
          mobileNav.classList.toggle("active");
          const icon = mobileMenuBtn.querySelector("i");
          if (mobileNav.classList.contains("active")) {
            icon.classList.remove("fa-bars");
            icon.classList.add("fa-times");
          } else {
            icon.classList.remove("fa-times");
            icon.classList.add("fa-bars");
          }
        });

        // Hero Section Toggle
        const videoToggle = document.getElementById("videoToggle");
        const audioToggle = document.getElementById("audioToggle");
        const heroVideo = document.getElementById("heroVideo");
        const heroAudio = document.getElementById("heroAudio");
        const heroType = document.getElementById("heroType");
        const heroViews = document.getElementById("heroViews");
        const heroViewsIcon = document.getElementById("heroViewsIcon");
        const playHeroBtn = document.getElementById("playHeroBtn");
        const heroPlayBtn = document.getElementById("heroPlayBtn");

        videoToggle.addEventListener("click", function() {
          videoToggle.classList.add("active");
          audioToggle.classList.remove("active");
          heroVideo.style.display = "block";
          heroAudio.style.display = "none";
          heroType.textContent = "VIDEO PODCAST";
          heroViews.textContent = "24.5K views";
          heroViewsIcon.className = "fas fa-eye";
        });

        audioToggle.addEventListener("click", function() {
          audioToggle.classList.add("active");
          videoToggle.classList.remove("active");
          heroVideo.style.display = "none";
          heroAudio.style.display = "flex";
          heroType.textContent = "AUDIO PODCAST";
          heroViews.textContent = "18.7K listens";
          heroViewsIcon.className = "fas fa-headphones";
        });

        // Hero Video/Audio Playback
        let isHeroPlaying = false;

        function toggleHeroPlayback() {
          if (videoToggle.classList.contains("active")) {
            // Video playback
            if (heroVideo.paused) {
              heroVideo.play();
              playHeroBtn.innerHTML = '<i class="fas fa-pause"></i>';
              heroPlayBtn.innerHTML = '<i class="fas fa-pause"></i> Pause';
              isHeroPlaying = true;
            } else {
              heroVideo.pause();
              playHeroBtn.innerHTML = '<i class="fas fa-play"></i>';
              heroPlayBtn.innerHTML = '<i class="fas fa-play"></i> Play Now';
              isHeroPlaying = false;
            }
          } else {
            // Audio playback
            if (isHeroPlaying) {
              // Pause audio (would be connected to actual audio player)
              playHeroBtn.innerHTML = '<i class="fas fa-play"></i>';
              heroPlayBtn.innerHTML = '<i class="fas fa-play"></i> Play Now';
              isHeroPlaying = false;
              stopAudioVisualizer();
            } else {
              // Play audio (would be connected to actual audio player)
              playHeroBtn.innerHTML = '<i class="fas fa-pause"></i>';
              heroPlayBtn.innerHTML = '<i class="fas fa-pause"></i> Pause';
              isHeroPlaying = true;
              startAudioVisualizer();
            }
          }
        }

        playHeroBtn.addEventListener("click", toggleHeroPlayback);
        heroPlayBtn.addEventListener("click", toggleHeroPlayback);

        // Audio Visualizer
        let audioVisualizerInterval;

        function startAudioVisualizer() {
          const bars = document.querySelectorAll('.audio-bar');
          audioVisualizerInterval = setInterval(() => {
            bars.forEach(bar => {
              const randomHeight = Math.floor(Math.random() * 80) + 10;
              bar.style.height = `${randomHeight}px`;
            });
          }, 200);
        }

        function stopAudioVisualizer() {
          if (audioVisualizerInterval) {
            clearInterval(audioVisualizerInterval);
          }
          const bars = document.querySelectorAll('.audio-bar');
          bars.forEach(bar => {
            bar.style.height = '10px';
          });
        }

        // Video Playback for Video Podcasts
        const videoPlayers = document.querySelectorAll('.play-video-small');
        videoPlayers.forEach(player => {
          player.addEventListener('click', function(e) {
            e.stopPropagation();
            const videoId = this.getAttribute('data-video-id');
            // In a real implementation, this would load and play the specific video
            alert(`Video ${videoId} playback would start here in a full implementation`);
          });
        });

        // Like Functionality
        const likeButtons = document.querySelectorAll('.like-podcast-btn, #heroLikeBtn');
        likeButtons.forEach(button => {
          button.addEventListener('click', function(e) {
            e.stopPropagation();
            const podcastId = this.getAttribute('data-podcast-id');
            const icon = this.querySelector('i');
            const countElement = this.querySelector('.like-count') || document.getElementById('heroLikes');
            
            if (icon.classList.contains('liked')) {
              // Unlike
              icon.classList.remove('liked');
              let currentCount = parseInt(countElement.textContent);
              countElement.textContent = (currentCount - 1).toString();
            } else {
              // Like
              icon.classList.add('liked');
              let currentCount = parseInt(countElement.textContent);
              countElement.textContent = (currentCount + 1).toString();
            }
            
            // In a real app, you would send this to a backend
            console.log(`Podcast ${podcastId} like status toggled`);
          });
        });

        // Share Functionality
        const shareModal = document.getElementById('shareModal');
        const closeShare = document.getElementById('closeShare');
        const copyLink = document.getElementById('copyLink');
        const shareUrl = document.getElementById('shareUrl');
        const shareButtons = document.querySelectorAll('.share-podcast-btn, #heroShareBtn');

        // Open share modal
        shareButtons.forEach(button => {
          button.addEventListener('click', function(e) {
            e.stopPropagation();
            const podcastId = this.getAttribute('data-podcast-id') || 'featured';
            // Set the share URL based on the podcast
            shareUrl.value = `https://darlingfm.com/podcasts/${podcastId}`;
            shareModal.classList.add('active');
          });
        });

        // Close share modal
        closeShare.addEventListener('click', function() {
          shareModal.classList.remove('active');
        });

        // Copy link functionality
        copyLink.addEventListener('click', function() {
          shareUrl.select();
          document.execCommand('copy');
          this.textContent = 'Copied!';
          setTimeout(() => {
            this.textContent = 'Copy';
          }, 2000);
        });

        // Social platform sharing
        const sharePlatforms = document.querySelectorAll('.share-platform');
        sharePlatforms.forEach(platform => {
          platform.addEventListener('click', function() {
            const platformName = this.getAttribute('data-platform');
            const url = encodeURIComponent(shareUrl.value);
            const title = encodeURIComponent('Check out this amazing podcast on Darling FM!');
            
            let shareWindow;
            
            switch(platformName) {
              case 'facebook':
                shareWindow = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                break;
              case 'twitter':
                shareWindow = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                break;
              case 'linkedin':
                shareWindow = `https://www.linkedin.com/sharing/share-offsite/?url=${url}`;
                break;
              case 'whatsapp':
                shareWindow = `https://api.whatsapp.com/send?text=${title}%20${url}`;
                break;
              case 'telegram':
                shareWindow = `https://t.me/share/url?url=${url}&text=${title}`;
                break;
              case 'reddit':
                shareWindow = `https://reddit.com/submit?url=${url}&title=${title}`;
                break;
              case 'email':
                shareWindow = `mailto:?subject=${title}&body=${title}%0A%0A${url}`;
                break;
              default:
                return;
            }
            
            window.open(shareWindow, '_blank', 'width=600,height=400');
          });
        });

        // Close modal when clicking outside
        shareModal.addEventListener('click', function(e) {
          if (e.target === shareModal) {
            shareModal.classList.remove('active');
          }
        });

        // Podcast Player Functionality
        const podcastPlayer = document.getElementById('podcastPlayer');
        const playPauseBtn = document.getElementById('playPauseBtn');
        const progressBar = document.getElementById('progressBar');
        const progress = document.getElementById('progress');
        const currentTimeDisplay = document.getElementById('currentTime');
        const totalTimeDisplay = document.getElementById('totalTime');
        const playerImage = document.getElementById('playerImage');
        const playerTitle = document.getElementById('playerTitle');
        const playerHost = document.getElementById('playerHost');

        const audioPlayer = document.getElementById('audio-player');
        let isPlaying = false;

        // Sample podcast data
        const podcasts = {
          1: {
            title: "Sound Design Masterclass",
            host: "Audio Alchemy",
            duration: 2520, // 42 minutes in seconds
            audioUrl: "https://open.spotify.com/episode/3PL9rQWegcS6onCjjH8ETY?si=qmRZe-P1TeWJKsLs7_1e9w&context=spotify%3Aplaylist%3A37i9dQZF1FgnTBfUlzkeKt",
            imageUrl: "https://images.unsplash.com/photo-1478737270239-2f02b77fc618?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80"
          },
          2: {
            title: "Digital Nomad Diaries",
            host: "Global Voices",
            duration: 2160, // 36 minutes in seconds
            audioUrl: "https://www.soundjay.com/button/sounds/beep-07.wav",
            imageUrl: "https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80"
          },
          3: {
            title: "Cyber Security Today",
            host: "Tech Guardians",
            duration: 3480, // 58 minutes in seconds
            audioUrl: "https://www.soundjay.com/button/sounds/beep-07.wav",
            imageUrl: "https://images.unsplash.com/photo-1554260570-9140fd3b7614?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80"
          }
        };

        // Format time from seconds to MM:SS
        function formatTime(seconds) {
          const mins = Math.floor(seconds / 60);
          const secs = Math.floor(seconds % 60);
          return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
        }

        // Load and play a podcast
        function loadPodcast(podcastId) {
          const podcast = podcasts[podcastId];
          
          // Update audio source
          audioPlayer.src = podcast.audioUrl;
          
          // Update UI
          playerTitle.textContent = podcast.title;
          playerHost.textContent = podcast.host;
          playerImage.style.backgroundImage = `url('${podcast.imageUrl}')`;
          totalTimeDisplay.textContent = formatTime(podcast.duration);
          
          // Show player
          podcastPlayer.classList.add('active');
          
          // Play the podcast
          playPodcast();
        }

        // Play the current podcast
        function playPodcast() {
          audioPlayer.play();
          isPlaying = true;
          playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';
        }

        // Pause the current podcast
        function pausePodcast() {
          audioPlayer.pause();
          isPlaying = false;
          playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';
        }

        // Toggle play/pause
        function togglePlayPause() {
          if (isPlaying) {
            pausePodcast();
          } else {
            playPodcast();
          }
        }

        // Update progress bar
        function updateProgress() {
          const currentTime = audioPlayer.currentTime;
          const duration = audioPlayer.duration;
          
          if (duration) {
            const progressPercent = (currentTime / duration) * 100;
            progress.style.width = `${progressPercent}%`;
            currentTimeDisplay.textContent = formatTime(currentTime);
          }
        }

        // Seek in the podcast
        function seekPodcast(e) {
          const rect = progressBar.getBoundingClientRect();
          const percent = (e.clientX - rect.left) / rect.width;
          audioPlayer.currentTime = percent * audioPlayer.duration;
        }

        // Set up event listeners for podcast play buttons
        document.querySelectorAll('.play-podcast, .play-podcast-btn').forEach(btn => {
          btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const podcastId = this.getAttribute('data-podcast-id');
            loadPodcast(podcastId);
          });
        });

        // Set up player controls
        playPauseBtn.addEventListener('click', togglePlayPause);
        progressBar.addEventListener('click', seekPodcast);
        audioPlayer.addEventListener('timeupdate', updateProgress);
      });