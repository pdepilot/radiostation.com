// =============================================
// MOBILE MENU FUNCTIONALITY
// =============================================
document.addEventListener("DOMContentLoaded", function () {
  const mobileMenuBtn = document.querySelector(".mobile-menu");
  const mobileNav = document.getElementById("mobileNav");

  // Toggle mobile menu
  mobileMenuBtn.addEventListener("click", function () {
    mobileNav.classList.toggle("active");

    // Change hamburger icon to X when menu is open
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

  // Close mobile menu when clicking outside
  document.addEventListener("click", function (event) {
    if (
      !mobileMenuBtn.contains(event.target) &&
      !mobileNav.contains(event.target)
    ) {
      mobileNav.classList.remove("active");
      const icon = mobileMenuBtn.querySelector("i");
      icon.classList.remove("fa-times");
      icon.classList.add("fa-bars");
    }
  });
});

// =============================================
// SOCIAL SHARING FUNCTIONALITY
// =============================================
document.addEventListener("DOMContentLoaded", function () {
  // Get the share modal and buttons
  const shareModal = document.getElementById("shareStreamModal");
  const shareButtons = document.querySelectorAll(".social-share-btn");

  // Define share URLs for each platform
  const shareUrls = {
    facebook: "https://www.facebook.com/sharer/sharer.php?u=",
    twitter: "https://twitter.com/intent/tweet?url=",
    whatsapp: "https://api.whatsapp.com/send?text=",
    telegram: "https://t.me/share/url?url=",
    reddit: "https://reddit.com/submit?url=",
    linkedin: "https://www.linkedin.com/sharing/share-offsite/?url=",
    email: "mailto:?subject=Check out this live stream&body=",
  };

  // Define share text for each platform
  const shareText = {
    facebook: "Check out this awesome live radio stream!",
    twitter: "Tune in to this amazing live radio stream! 🎧 #LiveRadio #Music",
    whatsapp: "Check out this awesome live radio stream!",
    telegram: "Check out this awesome live radio stream!",
    reddit: "Check out this awesome live radio stream!",
    linkedin: "Check out this awesome live radio stream!",
    email: "I thought you might enjoy this live radio stream!",
  };

  // Add click event listeners to all share buttons
  shareButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const platform = this.getAttribute("data-platform");
      const streamUrl = encodeURIComponent("https://nexusradio.com/live");
      const text = encodeURIComponent(shareText[platform]);

      let shareUrl = "";

      // Construct the share URL based on the platform
      switch (platform) {
        case "facebook":
          shareUrl = `${shareUrls.facebook}${streamUrl}`;
          break;
        case "twitter":
          shareUrl = `${shareUrls.twitter}${streamUrl}&text=${text}`;
          break;
        case "whatsapp":
          shareUrl = `${shareUrls.whatsapp}${text} ${streamUrl}`;
          break;
        case "telegram":
          shareUrl = `${shareUrls.telegram}${streamUrl}&text=${text}`;
          break;
        case "reddit":
          shareUrl = `${shareUrls.reddit}${streamUrl}&title=${text}`;
          break;
        case "linkedin":
          shareUrl = `${shareUrls.linkedin}${streamUrl}`;
          break;
        case "email":
          shareUrl = `${shareUrls.email}${text} ${streamUrl}`;
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
});

// =============================================
// CONFIGURATION - REPLACE THESE WITH YOUR URLs
// =============================================

// Main radio stream URL (24/7 broadcast)
const MAIN_STREAM_URL = "https://your-radioserver.com:8000/stream";

// OAP Live Stream URL (for special broadcasts)
const OAP_STREAM_URL = "https://your-radioserver.com:8000/live";

// Backup stream URL (optional)
const BACKUP_STREAM_URL = "https://your-backup-server.com:8000/stream";

// =============================================
// MAIN RADIO STREAM FUNCTIONALITY
// =============================================

document.addEventListener("DOMContentLoaded", function () {
  const playButton = document.getElementById("playButton");
  const playIcon = playButton.querySelector("i");
  const currentStream = document.getElementById("currentStream");
  const streamStatus = document.getElementById("streamStatus");
  const streamOptions = document.querySelectorAll(".stream-option");
  let isPlaying = false;
  let currentStreamType = "main";

  // Create Audio object for main stream
  const radioStream = new Audio();
  radioStream.src = MAIN_STREAM_URL;
  radioStream.preload = "none";

  // Stream type mapping
  const streamUrls = {
    main: MAIN_STREAM_URL,
    oap: OAP_STREAM_URL,
    backup: BACKUP_STREAM_URL,
  };

  const streamNames = {
    main: "Main Radio Stream",
    oap: "OAP Live Stream",
    backup: "Backup Stream",
  };

  // Play button functionality
  playButton.addEventListener("click", function () {
    if (!isPlaying) {
      // Start streaming
      radioStream
        .play()
        .then(() => {
          playIcon.classList.remove("fa-play");
          playIcon.classList.add("fa-pause");
          playButton.style.background =
            "linear-gradient(45deg, var(--highlight), var(--accent))";
          isPlaying = true;
          streamStatus.textContent = `Now playing: ${streamNames[currentStreamType]}`;
          console.log("Connected to main stream");
        })
        .catch((e) => {
          console.error("Stream error:", e);
          // Try backup stream if main fails
          if (currentStreamType !== "backup") {
            radioStream.src = BACKUP_STREAM_URL;
            radioStream
              .play()
              .then(() => {
                playIcon.classList.remove("fa-play");
                playIcon.classList.add("fa-pause");
                playButton.style.background =
                  "linear-gradient(45deg, var(--highlight), var(--accent))";
                isPlaying = true;
                currentStreamType = "backup";
                currentStream.textContent = streamNames[currentStreamType];
                streamStatus.textContent = `Now playing: ${streamNames[currentStreamType]}`;
                updateStreamOptions();
                console.log("Connected to backup stream");
              })
              .catch((backupError) => {
                console.error("Backup stream also failed:", backupError);
                alert(
                  "Unable to connect to any radio stream. Please try again later."
                );
              });
          } else {
            alert(
              "Unable to connect to any radio stream. Please try again later."
            );
          }
        });
    } else {
      // Pause streaming
      radioStream.pause();
      playIcon.classList.remove("fa-pause");
      playIcon.classList.add("fa-play");
      playButton.style.background =
        "linear-gradient(45deg, var(--accent), var(--accent-glow))";
      isPlaying = false;
      streamStatus.textContent = "Stream paused";
      console.log("Stream paused");
    }
  });

  // Handle stream events
  radioStream.addEventListener("ended", () => {
    isPlaying = false;
    playIcon.classList.remove("fa-pause");
    playIcon.classList.add("fa-play");
    streamStatus.textContent = "Stream ended";
  });

  radioStream.addEventListener("error", (e) => {
    console.error("Stream error:", e);
    isPlaying = false;
    playIcon.classList.remove("fa-pause");
    playIcon.classList.add("fa-play");
    streamStatus.textContent = "Connection error";
  });

  // Stream selection
  streamOptions.forEach((option) => {
    option.addEventListener("click", function () {
      const streamType = this.getAttribute("data-stream");

      if (streamType !== currentStreamType) {
        // Change stream
        radioStream.src = streamUrls[streamType];
        currentStreamType = streamType;
        currentStream.textContent = streamNames[streamType];

        // Update UI
        updateStreamOptions();

        // If currently playing, restart with new stream
        if (isPlaying) {
          radioStream
            .play()
            .then(() => {
              streamStatus.textContent = `Now playing: ${streamNames[currentStreamType]}`;
            })
            .catch((e) => {
              console.error("Error switching streams:", e);
              streamStatus.textContent = "Error switching streams";
            });
        } else {
          streamStatus.textContent = `Selected: ${streamNames[currentStreamType]}`;
        }
      }
    });
  });

  function updateStreamOptions() {
    streamOptions.forEach((option) => {
      if (option.getAttribute("data-stream") === currentStreamType) {
        option.classList.add("active");
      } else {
        option.classList.remove("active");
      }
    });
  }

  // Audio Visualizer
  const visualizer = document.getElementById("visualizer");
  const barCount = 50;

  // Create visualizer bars
  for (let i = 0; i < barCount; i++) {
    const bar = document.createElement("div");
    bar.className = "visualizer-bar";
    visualizer.appendChild(bar);
  }

  // Animate bars randomly (in a real app, this would connect to actual audio)
  const bars = document.querySelectorAll(".visualizer-bar");

  function animateBars() {
    bars.forEach((bar) => {
      const height = Math.random() * 90 + 10;
      bar.style.height = `${height}%`;
    });
  }

  setInterval(animateBars, 100);

  // Simulate live listener count updates
  const listenerCount = document.querySelector(".stat-value");
  let count = 1247;

  setInterval(() => {
    // Randomly increase or decrease listener count
    const change = Math.floor(Math.random() * 10) - 3;
    count = Math.max(1200, count + change);
    listenerCount.textContent = count.toLocaleString();
  }, 5000);

  // Chat functionality
  const chatInput = document.querySelector(".chat-input input");
  const chatSend = document.querySelector(".chat-input button");
  const chatMessages = document.querySelector(".chat-messages");

  function addMessage(author, text) {
    const message = document.createElement("div");
    message.className = "message";

    const avatar = document.createElement("div");
    avatar.className = "message-avatar";
    avatar.textContent = author.substring(0, 2).toUpperCase();

    const content = document.createElement("div");
    content.className = "message-content";

    const header = document.createElement("div");
    header.className = "message-header";

    const authorSpan = document.createElement("span");
    authorSpan.className = "message-author";
    authorSpan.textContent = author;

    const timeSpan = document.createElement("span");
    timeSpan.className = "message-time";

    const now = new Date();
    timeSpan.textContent = now.toLocaleTimeString([], {
      hour: "2-digit",
      minute: "2-digit",
    });

    header.appendChild(authorSpan);
    header.appendChild(timeSpan);

    const textP = document.createElement("p");
    textP.className = "message-text";
    textP.textContent = text;

    content.appendChild(header);
    content.appendChild(textP);

    message.appendChild(avatar);
    message.appendChild(content);

    chatMessages.appendChild(message);
    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  chatSend.addEventListener("click", function () {
    if (chatInput.value.trim() !== "") {
      addMessage("You", chatInput.value);
      chatInput.value = "";

      // Simulate response after a delay
      setTimeout(() => {
        const responses = [
          "Great point!",
          "I love this track too!",
          "Anyone else feeling the vibes?",
          "What should we play next?",
          "The bass on this is incredible!",
        ];
        const randomUser = ["MusicLover", "BassHead", "DnBFan", "RadioJunkie"][
          Math.floor(Math.random() * 4)
        ];
        const randomResponse =
          responses[Math.floor(Math.random() * responses.length)];
        addMessage(randomUser, randomResponse);
      }, 1000 + Math.random() * 3000);
    }
  });

  chatInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      chatSend.click();
    }
  });

  // Modal functionality
  const voiceChatBtn = document.getElementById("voiceChatBtn");
  const requestSongBtn = document.getElementById("requestSongBtn");
  const livePollBtn = document.getElementById("livePollBtn");
  const shareStreamBtn = document.getElementById("shareStreamBtn");

  const voiceChatModal = document.getElementById("voiceChatModal");
  const requestSongModal = document.getElementById("requestSongModal");
  const livePollModal = document.getElementById("livePollModal");
  const shareStreamModal = document.getElementById("shareStreamModal");

  const closeModalButtons = document.querySelectorAll(
    ".close-modal, .btn-outline"
  );

  // Open modals
  voiceChatBtn.addEventListener("click", function () {
    voiceChatModal.style.display = "flex";
  });

  requestSongBtn.addEventListener("click", function () {
    requestSongModal.style.display = "flex";
  });

  livePollBtn.addEventListener("click", function () {
    livePollModal.style.display = "flex";
  });

  shareStreamBtn.addEventListener("click", function () {
    shareStreamModal.style.display = "flex";
  });

  // Close modals
  closeModalButtons.forEach((button) => {
    button.addEventListener("click", function () {
      voiceChatModal.style.display = "none";
      requestSongModal.style.display = "none";
      livePollModal.style.display = "none";
      shareStreamModal.style.display = "none";
    });
  });

  // Close modals when clicking outside
  window.addEventListener("click", function (event) {
    if (event.target === voiceChatModal) {
      voiceChatModal.style.display = "none";
    }
    if (event.target === requestSongModal) {
      requestSongModal.style.display = "none";
    }
    if (event.target === livePollModal) {
      livePollModal.style.display = "none";
    }
    if (event.target === shareStreamModal) {
      shareStreamModal.style.display = "none";
    }
  });

  // Copy URL functionality
  const copyUrlBtn = document.getElementById("copyUrlBtn");
  const shareUrl = document.getElementById("shareUrl");

  copyUrlBtn.addEventListener("click", function () {
    shareUrl.select();
    document.execCommand("copy");
    copyUrlBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
    setTimeout(() => {
      copyUrlBtn.innerHTML = '<i class="fas fa-copy"></i> Copy URL';
    }, 2000);
  });
});
