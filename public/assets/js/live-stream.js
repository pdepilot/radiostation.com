// =============================================
// MOBILE MENU FUNCTIONALITY
// =============================================
document.addEventListener("DOMContentLoaded", function () {
  const mobileMenuBtn = document.querySelector(".mobile-menu");
  const mobileNav = document.getElementById("mobileNav");

  if (mobileMenuBtn && mobileNav) {
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
  }

  // =============================================
  // REAL-TIME LIVE CHAT FUNCTIONALITY
  // =============================================
  const chatInput = document.getElementById("chatInput");
  const chatSend = document.getElementById("chatSend");
  const chatMessages = document.getElementById("chatMessages");
  let lastMessageId = 0;
  let chatPollInterval = null;

  if (chatInput && chatSend && chatMessages) {
    function addChatMessage(name, message, time, isUser = false, isVerified = false) {
      // Check if message already exists
      const existingMessages = chatMessages.querySelectorAll('.message');
      for (let msg of existingMessages) {
        const msgText = msg.querySelector('.message-text')?.textContent;
        if (msgText === message) return; // Skip duplicate
      }

      const messageDiv = document.createElement("div");
      messageDiv.className = "message";
      if (isUser) messageDiv.classList.add("user-message");

      const avatar = document.createElement("div");
      avatar.className = "message-avatar";
      avatar.textContent = name.substring(0, 2).toUpperCase();

      const content = document.createElement("div");
      content.className = "message-content";

      const header = document.createElement("div");
      header.className = "message-header";

      const authorSpan = document.createElement("span");
      authorSpan.className = "message-author";
      authorSpan.textContent = name;
      if (isVerified) {
        const verifiedBadge = document.createElement("span");
        verifiedBadge.style.cssText = "background: var(--success); color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.7rem; margin-left: 5px;";
        verifiedBadge.textContent = "✓";
        authorSpan.appendChild(verifiedBadge);
      }

      const timeSpan = document.createElement("span");
      timeSpan.className = "message-time";
      timeSpan.textContent = time;

      header.appendChild(authorSpan);
      header.appendChild(timeSpan);

      const textP = document.createElement("p");
      textP.className = "message-text";
      textP.textContent = message;

      content.appendChild(header);
      content.appendChild(textP);

      messageDiv.appendChild(avatar);
      messageDiv.appendChild(content);

      chatMessages.appendChild(messageDiv);
      chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function loadChatMessages() {
      fetch("/api/live-chat")
        .then((res) => res.json())
        .then((data) => {
          if (Array.isArray(data) && data.length > 0) {
            data.forEach((msg) => {
              if (msg.id > lastMessageId) {
                addChatMessage(msg.name, msg.message, msg.time, false, msg.is_verified);
                lastMessageId = Math.max(lastMessageId, msg.id);
              }
            });
          }
        })
        .catch((err) => console.error("Chat load error:", err));
    }

    function sendChatMessage() {
      const message = chatInput.value.trim();
      if (!message) return;

      const userName = window.authUser ? window.authUser.name : 'Guest';

      fetch("/api/live-chat", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
        },
        body: JSON.stringify({ 
          message,
          name: userName === 'Guest' ? (prompt('Enter your name:') || 'Guest') : null
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            chatInput.value = "";
            addChatMessage(data.message.name, data.message.message, data.message.time, true, data.message.is_verified);
            lastMessageId = Math.max(lastMessageId, data.message.id);
          } else if (data.error) {
            if (typeof showError === 'function') {
              showError(data.error);
            } else {
              if (typeof showError === 'function') {
                  showError(data.error);
              } else if (window.notifications) {
                  window.notifications.error(data.error);
              }
            }
          }
        })
        .catch((err) => {
          console.error("Chat send error:", err);
          if (typeof showError === 'function') {
            showError("Failed to send message. Please try again.");
          } else {
            if (typeof showError === 'function') {
                showError("Failed to send message. Please try again.");
            } else if (window.notifications) {
                window.notifications.error("Failed to send message. Please try again.");
            }
          }
        });
    }

    // Load initial messages
    loadChatMessages();

    // Poll for new messages every 2 seconds
    chatPollInterval = setInterval(loadChatMessages, 2000);

    // Send message handlers
    chatSend.addEventListener("click", sendChatMessage);
    chatInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        sendChatMessage();
      }
    });
  }

  // =============================================
  // RADIO PLAYER FUNCTIONALITY - USING GLOBAL AUDIO
  // =============================================
  const playButton = document.getElementById("playButton");
  let isPlaying = false;

  // Use global audio player if available, otherwise fallback to local
  const getAudioPlayer = function() {
    if (window.DarlingFMAudio && window.DarlingFMAudio.player) {
      return window.DarlingFMAudio;
    }
    return null;
  };

  // Sync UI with global audio state
  function syncUIWithGlobalAudio() {
    if (!window.DarlingFMAudio) return;
    
    const globalAudio = window.DarlingFMAudio;
    isPlaying = globalAudio.isPlaying;
    
    if (playButton) {
      playButton.innerHTML = isPlaying ? '<i class="fas fa-pause"></i>' : '<i class="fas fa-play"></i>';
    }
    
    const streamStatus = document.getElementById("streamStatus");
    if (streamStatus) {
      streamStatus.textContent = isPlaying ? "Now Playing" : "Click play to start listening";
    }
    
    // Sync active stream button - CRITICAL: Updates button highlights dynamically
    const currentStream = globalAudio.currentStream || globalAudio.getCurrentStream?.() || localStorage.getItem('darlingfm_active_stream') || 'main';
    streamOptions.forEach((opt) => {
      const streamType = opt.dataset.stream;
      if (streamType === currentStream) {
        // Active stream - highlight it
        opt.classList.add("active");
        opt.style.background = 'rgba(255,0,0,0.2)';
        opt.style.border = '1px solid var(--accent)';
        opt.style.color = 'var(--light)';
        opt.style.fontWeight = '600';
      } else {
        // Inactive stream - remove highlight
        opt.classList.remove("active");
        opt.style.background = 'rgba(255,255,255,0.05)';
        opt.style.border = '1px solid var(--glass-border)';
        opt.style.color = 'var(--text-secondary)';
        opt.style.fontWeight = '400';
      }
    });
    
    // Update current stream text
    const currentStreamEl = document.getElementById("currentStream");
    if (currentStreamEl) {
      const streamLabels = {
        main: 'Main Radio Stream',
        oap: 'OAP Live Stream',
        backup: 'Backup Stream'
      };
      const currentStream = globalAudio.currentStream || globalAudio.getCurrentStream?.() || localStorage.getItem('darlingfm_active_stream') || 'main';
      currentStreamEl.textContent = streamLabels[currentStream] || 'Main Radio Stream';
    }
  }

  // Wait for global audio to be available
  function initializePlayButton() {
    if (!playButton) return;
    
    // Check if global audio is available, if not wait a bit
    if (!window.DarlingFMAudio) {
      setTimeout(initializePlayButton, 100);
      return;
    }
    
    const globalAudio = window.DarlingFMAudio;
    
    // Listen to global audio state changes
    globalAudio.listeners.play.push(() => {
      syncUIWithGlobalAudio();
    });
    
    // Initial sync
    syncUIWithGlobalAudio();
    
    playButton.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      
      if (!globalAudio) {
        if (window.showError) {
          window.showError("Audio player not initialized. Please refresh the page.");
        } else if (window.notifications) {
          window.notifications.error("Audio player not initialized. Please refresh the page.");
        }
        return;
      }
      
      const wasPlaying = globalAudio.isPlaying;
      
      if (!wasPlaying) {
        // Start playing
        globalAudio.play()
          .then(() => {
            syncUIWithGlobalAudio();
            if (window.showSuccess) {
              window.showSuccess("Stream started!");
            } else if (window.notifications) {
              window.notifications.success("Stream started!");
            }
          })
          .catch((err) => {
            console.error("Play error:", err);
            syncUIWithGlobalAudio();
            if (window.showError) {
              window.showError("Unable to start stream. Please check your connection and try again.");
            } else if (window.notifications) {
              window.notifications.error("Unable to start stream. Please check your connection and try again.");
            }
          });
      } else {
        // Pause
        globalAudio.pause();
        syncUIWithGlobalAudio();
        if (window.showInfo) {
          window.showInfo("Stream paused.");
        } else if (window.notifications) {
          window.notifications.info("Stream paused.");
        }
      }
    });
  }
  
  // Initialize play button
  initializePlayButton();

  // =============================================
  // STREAM SELECTOR - FULLY FUNCTIONAL WITH ACTIVE STATE
  // =============================================
  const streamOptions = document.querySelectorAll(".stream-option");
  // Stream URLs - Main Stream (primary 7572), Backup (7567). OAP aliases to main for legacy buttons.
  const streamUrls = {
    main: "https://phoebe.streamerr.co:7572/stream",
    oap: "https://phoebe.streamerr.co:7572/stream",
    backup: "https://phoebe.streamerr.co:7567/stream"
  };
  
  // Get current active stream from localStorage or default to main
  let currentStreamType = localStorage.getItem('darlingfm_active_stream') || 'main';
  
  // Initialize stream selector with proper event handlers
  function initializeStreamSelector() {
    if (streamOptions.length === 0) return;
    
    // Wait for global audio if needed
    if (!window.DarlingFMAudio) {
      setTimeout(initializeStreamSelector, 100);
      return;
    }
    
    streamOptions.forEach((option) => {
      // Remove any existing listeners to prevent duplicates
      const newOption = option.cloneNode(true);
      option.parentNode.replaceChild(newOption, option);
      
      newOption.addEventListener("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        const streamType = this.dataset.stream;
        
        // Prevent switching to the same stream
        const currentStream = window.DarlingFMAudio?.getCurrentStream?.() || 
                             window.DarlingFMAudio?.currentStream || 
                             localStorage.getItem('darlingfm_active_stream') || 
                             'main';
        
        if (streamType === currentStream) {
          return; // Already on this stream
        }
        
        if (window.DarlingFMAudio) {
          // Update UI immediately for better UX
          streamOptions.forEach((opt) => {
            opt.classList.remove("active");
            opt.style.background = 'rgba(255,255,255,0.05)';
            opt.style.border = '1px solid var(--glass-border)';
            opt.style.color = 'var(--text-secondary)';
            opt.style.fontWeight = '400';
          });
          
          // Highlight clicked option immediately
          this.classList.add("active");
          this.style.background = 'rgba(255,0,0,0.2)';
          this.style.border = '1px solid var(--accent)';
          this.style.color = 'var(--light)';
          this.style.fontWeight = '600';
          
          // Switch stream
          const wasPlaying = window.DarlingFMAudio.isPlaying;
          window.DarlingFMAudio.switchStream(streamType)
            .then(() => {
              // Ensure UI is synced after switch
              syncUIWithGlobalAudio();
              if (wasPlaying && window.showSuccess) {
                window.showSuccess(`Switched to ${streamType === 'main' ? 'Main Stream' : streamType === 'oap' ? 'OAP Live' : 'Backup Stream'}`);
              } else if (wasPlaying && window.notifications) {
                window.notifications.success(`Switched to ${streamType === 'main' ? 'Main Stream' : streamType === 'oap' ? 'OAP Live' : 'Backup Stream'}`);
              }
            })
            .catch((err) => {
              console.error("Stream switch error:", err);
              // Revert UI on error
              syncUIWithGlobalAudio();
              if (window.showError) {
                window.showError("Unable to switch stream. Please try again.");
              } else if (window.notifications) {
                window.notifications.error("Unable to switch stream. Please try again.");
              }
            });
        } else {
          // Fallback if global audio not available
          streamOptions.forEach((opt) => {
            opt.classList.remove("active");
            opt.style.background = 'rgba(255,255,255,0.05)';
            opt.style.border = '1px solid var(--glass-border)';
            opt.style.color = 'var(--text-secondary)';
          });
          
          this.classList.add("active");
          this.style.background = 'rgba(255,0,0,0.2)';
          this.style.border = '1px solid var(--accent)';
          this.style.color = 'var(--light)';
          this.style.fontWeight = '600';
          localStorage.setItem('darlingfm_active_stream', streamType);
          
          const currentStreamEl = document.getElementById("currentStream");
          if (currentStreamEl) {
            currentStreamEl.textContent = 
              streamType === 'main' ? 'Main Radio Stream' : 
              streamType === 'oap' ? 'OAP Live Stream' : 'Backup Stream';
          }
        }
      });
    });
  }
  
  // Initialize stream selector
  initializeStreamSelector();
  
  // Sync UI on page load and listen for stream changes
  if (window.DarlingFMAudio) {
    // Listen for stream changes
    window.DarlingFMAudio.listeners.streamChange.push(() => {
      syncUIWithGlobalAudio();
      initializeStreamSelector(); // Re-initialize selector to ensure correct highlighting
    });
    
    // Initial sync
    syncUIWithGlobalAudio();
    initializeStreamSelector();
    
    // Also listen for play/pause to update UI
    window.DarlingFMAudio.listeners.play.push(() => {
      syncUIWithGlobalAudio();
    });
  }
  
  // Listener count functionality removed - div has been removed from HTML
  
  // Show visualizer when playing
  const audioVisualizer = document.getElementById("audioVisualizer");
  if (audioVisualizer && playButton) {
    playButton.addEventListener("click", function() {
      if (isPlaying) {
        audioVisualizer.style.display = "flex";
        animateVisualizer();
      } else {
        audioVisualizer.style.display = "none";
      }
    });
  }
  
  // Animate visualizer bars
  function animateVisualizer() {
    if (!isPlaying) return;
    const bars = document.querySelectorAll('.viz-bar');
    bars.forEach((bar, index) => {
      const height = Math.random() * 60 + 20; // Random height between 20-80px
      bar.style.height = height + 'px';
    });
    if (isPlaying) {
      requestAnimationFrame(animateVisualizer);
    }
  }

  // =============================================
  // MODAL FUNCTIONALITY
  // =============================================
  const modals = document.querySelectorAll(".modal");
  const closeButtons = document.querySelectorAll(".close-modal");

  closeButtons.forEach((btn) => {
    btn.addEventListener("click", function () {
      const modal = this.closest(".modal");
      if (modal) {
        modal.style.display = "none";
        modal.classList.remove("active");
      }
    });
  });

  // Voice Chat Button
  const voiceChatBtn = document.getElementById("voiceChatBtn");
  if (voiceChatBtn) {
    voiceChatBtn.addEventListener("click", function () {
      const modal = document.getElementById("voiceChatModal");
      if (modal) {
        modal.style.display = "flex";
        modal.classList.add("active");
      }
    });
  }

  // Request Song Button
  const requestSongBtn = document.getElementById("requestSongBtn");
  if (requestSongBtn) {
    requestSongBtn.addEventListener("click", function () {
      const modal = document.getElementById("requestSongModal");
      if (modal) {
        modal.style.display = "flex";
        modal.classList.add("active");
      }
    });
  }

  // Live Poll Button
  const livePollBtn = document.getElementById("livePollBtn");
  if (livePollBtn) {
    livePollBtn.addEventListener("click", function () {
      const modal = document.getElementById("livePollModal");
      if (modal) {
        modal.style.display = "flex";
        modal.classList.add("active");
      }
    });
  }

  // Share Stream Button
  const shareStreamBtn = document.getElementById("shareStreamBtn");
  if (shareStreamBtn) {
    shareStreamBtn.addEventListener("click", function () {
      const modal = document.getElementById("shareStreamModal");
      if (modal) {
        modal.style.display = "flex";
        modal.classList.add("active");
      }
    });
  }

  // Copy URL Button
  const copyUrlBtn = document.getElementById("copyUrlBtn");
  if (copyUrlBtn) {
    copyUrlBtn.addEventListener("click", function () {
      const urlInput = document.getElementById("shareUrl");
      if (urlInput) {
        urlInput.select();
        document.execCommand("copy");
        this.innerHTML = '<i class="fas fa-check"></i> Copied!';
        setTimeout(() => {
          this.innerHTML = '<i class="fas fa-copy"></i> Copy URL';
        }, 2000);
      }
    });
  }

  // =============================================
  // AUDIO VISUALIZER
  // =============================================
  const visualizer = document.getElementById("visualizer");
  if (visualizer) {
    // Clear any existing content
    visualizer.innerHTML = "";
    
    // Create bars
    for (let i = 0; i < 20; i++) {
      const bar = document.createElement("div");
      bar.style.cssText = `
        width: 4px;
        height: ${Math.random() * 50 + 10}px;
        background: linear-gradient(to top, var(--accent), #0099ff);
        margin: 0 2px;
        border-radius: 2px;
        transition: height 0.1s ease;
        display: inline-block;
        vertical-align: bottom;
      `;
      visualizer.appendChild(bar);
    }

    // Animate bars
    const animateBars = setInterval(() => {
      if (!visualizer || visualizer.children.length === 0) {
        clearInterval(animateBars);
        return;
      }
      const bars = visualizer.querySelectorAll("div");
      bars.forEach((bar) => {
        bar.style.height = Math.random() * 50 + 10 + "px";
      });
    }, 100);
  }

  // Cleanup on page unload
  window.addEventListener("beforeunload", function () {
    if (chatPollInterval) {
      clearInterval(chatPollInterval);
    }
    if (audioPlayer) {
      audioPlayer.pause();
    }
  });
});
