
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

        // Close mobile menu when clicking on a link
        const mobileNavLinks = mobileNav.querySelectorAll("a");
        mobileNavLinks.forEach((link) => {
          link.addEventListener("click", function () {
            mobileNav.classList.remove("active");
            const icon = mobileMenuBtn.querySelector("i");
            icon.classList.remove("fa-times");
            icon.classList.add("fa-bars");
          });
        });

        // Audio player functionality
        const audioPlayer = document.getElementById("audioPlayer");
        let currentlyPlaying = null;
        let currentAudioUrl = null;
        let playbackPosition = 0;

        // Format time function
        function formatTime(seconds) {
          const mins = Math.floor(seconds / 60);
          const secs = Math.floor(seconds % 60);
          return `${mins}:${secs < 10 ? "0" : ""}${secs}`;
        }

        // Update duration display
        function updateDurationDisplay(button, currentTime, duration) {
          const durationDisplay = button
            .closest(".dj-card, .mix-card, .spotlight-info")
            ?.querySelector(".audio-duration");
          if (durationDisplay) {
            const currentTimeEl =
              durationDisplay.querySelector(".current-time");
            const totalTimeEl = durationDisplay.querySelector(".total-time");

            if (currentTimeEl)
              currentTimeEl.textContent = formatTime(currentTime);
            if (totalTimeEl) totalTimeEl.textContent = formatTime(duration);

            durationDisplay.classList.add("playing");
          }
        }

        // Play buttons functionality
        const playButtons = document.querySelectorAll(".play-btn, .play-mix");

        playButtons.forEach((button) => {
          button.addEventListener("click", function (e) {
            e.stopPropagation();

            const audioUrl = this.getAttribute("data-audio");
            const icon = this.querySelector("i");

            // If this button is already playing, pause it
            if (currentlyPlaying === this) {
              audioPlayer.pause();
              playbackPosition = audioPlayer.currentTime; // Store current position
              icon.classList.remove("fa-pause");
              icon.classList.add("fa-play");
              this.classList.remove("playing");

              // Hide visualizer and duration
              const visualizer = this.closest(".dj-card")
                ? this.closest(".dj-card").querySelector(".audio-visualizer")
                : this.closest(".mix-card")
                ? this.closest(".mix-card").querySelector(".mix-visualizer")
                : document.getElementById("featuredVisualizer");

              if (visualizer) {
                visualizer.style.display = "none";
                visualizer.classList.remove("playing");
              }

              const durationDisplay = this.closest(
                ".dj-card, .mix-card, .spotlight-info"
              )?.querySelector(".audio-duration");
              if (durationDisplay) durationDisplay.classList.remove("playing");

              currentlyPlaying = null;
              return;
            }

            // Stop any currently playing audio
            if (currentlyPlaying) {
              const currentIcon = currentlyPlaying.querySelector("i");
              currentIcon.classList.remove("fa-pause");
              currentIcon.classList.add("fa-play");
              currentlyPlaying.classList.remove("playing");

              // Hide previous visualizer and duration
              const prevVisualizer = currentlyPlaying.closest(".dj-card")
                ? currentlyPlaying
                    .closest(".dj-card")
                    .querySelector(".audio-visualizer")
                : currentlyPlaying.closest(".mix-card")
                ? currentlyPlaying
                    .closest(".mix-card")
                    .querySelector(".mix-visualizer")
                : document.getElementById("featuredVisualizer");

              if (prevVisualizer) {
                prevVisualizer.style.display = "none";
                prevVisualizer.classList.remove("playing");
              }

              const prevDurationDisplay = currentlyPlaying
                .closest(".dj-card, .mix-card, .spotlight-info")
                ?.querySelector(".audio-duration");
              if (prevDurationDisplay)
                prevDurationDisplay.classList.remove("playing");
            }

            // Play the new audio
            if (audioUrl === currentAudioUrl && playbackPosition > 0) {
              // Resume from stored position if same audio
              audioPlayer.currentTime = playbackPosition;
            } else {
              // Start new audio from beginning
              audioPlayer.src = audioUrl;
              playbackPosition = 0;
              currentAudioUrl = audioUrl;
            }
            
            audioPlayer.play();

            icon.classList.remove("fa-play");
            icon.classList.add("fa-pause");
            this.classList.add("playing");
            currentlyPlaying = this;

            // Show visualizer
            const visualizer = this.closest(".dj-card")
              ? this.closest(".dj-card").querySelector(".audio-visualizer")
              : this.closest(".mix-card")
              ? this.closest(".mix-card").querySelector(".mix-visualizer")
              : document.getElementById("featuredVisualizer");

            if (visualizer) {
              visualizer.style.display = "flex";
              visualizer.classList.add("playing");
            }

            // Update button text if it's a featured DJ play button
            if (
              this.classList.contains("play-btn") &&
              this.textContent.includes("Play Latest")
            ) {
              this.innerHTML = '<i class="fas fa-pause"></i> Pause Mix';
            }
          });
        });

        // Update audio duration in real-time
        audioPlayer.addEventListener("timeupdate", function () {
          if (currentlyPlaying) {
            updateDurationDisplay(
              currentlyPlaying,
              audioPlayer.currentTime,
              audioPlayer.duration
            );
          }
        });

        // Handle audio ending
        audioPlayer.addEventListener("ended", function () {
          if (currentlyPlaying) {
            const icon = currentlyPlaying.querySelector("i");
            icon.classList.remove("fa-pause");
            icon.classList.add("fa-play");
            currentlyPlaying.classList.remove("playing");

            // Hide visualizer and duration
            const visualizer = currentlyPlaying.closest(".dj-card")
              ? currentlyPlaying
                  .closest(".dj-card")
                  .querySelector(".audio-visualizer")
              : currentlyPlaying.closest(".mix-card")
              ? currentlyPlaying
                  .closest(".mix-card")
                  .querySelector(".mix-visualizer")
              : document.getElementById("featuredVisualizer");

            if (visualizer) {
              visualizer.style.display = "none";
              visualizer.classList.remove("playing");
            }

            const durationDisplay = currentlyPlaying
              .closest(".dj-card, .mix-card, .spotlight-info")
              ?.querySelector(".audio-duration");
            if (durationDisplay) durationDisplay.classList.remove("playing");

            // Reset button text if it was a featured DJ play button
            if (
              currentlyPlaying.classList.contains("play-btn") &&
              currentlyPlaying.textContent.includes("Pause Mix")
            ) {
              currentlyPlaying.innerHTML =
                '<i class="fas fa-play"></i> Play Latest Mix';
            }

            // Reset playback position when audio ends
            playbackPosition = 0;
            currentAudioUrl = null;
            currentlyPlaying = null;
          }
        });

        // Filter functionality
        const filterButtons = document.querySelectorAll(".filter-btn");
        const djCards = document.querySelectorAll(".dj-card");

        filterButtons.forEach((button) => {
          button.addEventListener("click", function () {
            filterButtons.forEach((btn) => btn.classList.remove("active"));
            this.classList.add("active");

            const genre = this.getAttribute("data-genre");

            djCards.forEach((card) => {
              if (
                genre === "all" ||
                card.getAttribute("data-genre") === genre
              ) {
                card.style.display = "block";
              } else {
                card.style.display = "none";
              }
            });
          });
        });

        // Follow buttons functionality - redirect to DJ's social media
        const followButtons = document.querySelectorAll(".follow-btn");

        followButtons.forEach((button) => {
          button.addEventListener("click", function (e) {
            e.stopPropagation();
            const socialUrl = this.getAttribute("data-social");

            // Open DJ's social media page in a new tab
            if (socialUrl) {
              window.open(socialUrl, "_blank");
            } else {
              alert("Social media link not available for this DJ");
            }
          });
        });

        // Share modal functionality
        const shareModal = document.getElementById("shareModal");
        const shareButtons = document.querySelectorAll(".share-btn");
        const closeModal = document.querySelector(".close-modal");
        const shareDJText = document.getElementById("shareDJText");
        const socialShareButtons =
          document.querySelectorAll(".social-share-btn");

        shareButtons.forEach((button) => {
          button.addEventListener("click", function (e) {
            e.stopPropagation();
            const djName = this.getAttribute("data-dj");
            shareDJText.textContent = `Share ${djName} with your friends!`;
            shareModal.style.display = "flex";
          });
        });

        closeModal.addEventListener("click", function () {
          shareModal.style.display = "none";
        });

        // Close modal when clicking outside
        window.addEventListener("click", function (event) {
          if (event.target === shareModal) {
            shareModal.style.display = "none";
          }
        });

        // Social sharing functionality
        socialShareButtons.forEach((button) => {
          button.addEventListener("click", function () {
            const platform = this.getAttribute("data-platform");
            const djName = shareDJText.textContent
              .split("Share ")[1]
              .split(" with")[0];
            const streamUrl = encodeURIComponent("https://nexusradio.com/djs");
            const text = encodeURIComponent(
              `Check out ${djName} on Nexus Radio!`
            );

            let shareUrl = "";

            // Construct the share URL based on the platform
            switch (platform) {
              case "facebook":
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${streamUrl}&quote=${text}`;
                break;
              case "twitter":
                shareUrl = `https://twitter.com/intent/tweet?url=${streamUrl}&text=${text}`;
                break;
              case "whatsapp":
                shareUrl = `https://api.whatsapp.com/send?text=${text} ${streamUrl}`;
                break;
              case "telegram":
                shareUrl = `https://t.me/share/url?url=${streamUrl}&text=${text}`;
                break;
              case "reddit":
                shareUrl = `https://reddit.com/submit?url=${streamUrl}&title=${text}`;
                break;
              case "linkedin":
                shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${streamUrl}`;
                break;
              case "email":
                shareUrl = `mailto:?subject=Check out this DJ on Nexus Radio&body=${text} ${streamUrl}`;
                break;
            }

            // Open the share URL in a new window (except for email)
            if (platform === "email") {
              window.location.href = shareUrl;
            } else {
              window.open(shareUrl, "shareWindow", "width=600,height=400");
            }

            // Close the modal after sharing
            shareModal.style.display = "none";
          });
        });

        // Footer subscription
        const footerInput = document.querySelector(".footer-input input");
        const footerButton = document.querySelector(".footer-input button");

        footerButton.addEventListener("click", function () {
          if (footerInput.value.trim() !== "" && footerInput.checkValidity()) {
            alert(`Thank you for subscribing with: ${footerInput.value}`);
            footerInput.value = "";
          } else {
            alert("Please enter a valid email address");
          }
        });

        footerInput.addEventListener("keypress", function (e) {
          if (e.key === "Enter") {
            footerButton.click();
          }
        });
      });