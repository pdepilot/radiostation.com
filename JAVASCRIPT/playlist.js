document.addEventListener("DOMContentLoaded", function () {
  // Sample track data with real audio URLs
  const tracks = {
    1: {
      title: "Neon Dreams",
      artist: "DJ Nova",
      album: "Cyber Dreams - 2023",
      duration: 272, // 4:32 in seconds
      audioUrl: "AUDIO/Christian_Hymn_-_He_Arose_CeeNaija.com_.mp3", // Sample audio
      imageUrl:
        "https://images.unsplash.com/photo-1571330735066-03aaa9429d89?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      likes: 245,
    },
    2: {
      title: "Digital Sunrise",
      artist: "Nich Oma",
      album: "Eze Nmuo - 2023",
      duration: 225, 
      audioUrl: "AUDIO/Eze Nmuo by Nich Oma .mp3", // Sample audio
      imageUrl:
        "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      likes: 189,
    },
    3: {
      title: "Revelation",
      artist: "Mr_M_Revelation",
      album: "Product_Of_Grace - 2023",
      duration: 312, 
      audioUrl:
        "AUDIO/Mr_M_Revelation_-_Product_Of_Grace_CeeNaija.com_ - 2025-04-28T172659.239.mp3", // Sample audio
      imageUrl:
        "https://images.unsplash.com/photo-1492684223066-81342ee5ff30?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      likes: 312,
    },
    4: {
      title: "Ebube-Juru",
      artist: "Gozie Okeke ",
      album: "Ebube-Juru-Igwe - 2023",
      duration: 383,
      audioUrl: "AUDIO/Prince-Gozie-Okeke-Ebube-Juru-Igwe.mp3", 
      imageUrl:
        "https://images.unsplash.com/photo-1459749411175-04bf5292ceea?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      likes: 156,
    },
    5: {
      title: "Binary Flow",
      artist: "Zara",
      album: "Circuit Breaker - 2023",
      duration: 245, 
      audioUrl: "https://www.soundjay.com/button/sounds/beep-07.wav",
      imageUrl:
        "https://images.unsplash.com/photo-1487180144351-b8472da7d491?ixlib=rb-4.0.3&auto=format&fit=crop&w=1352&q=80",
      likes: 278,
    },
    6: {
      title: "They way",
      artist: "Nkechi_Abugu",
      album: "Abugu_You_Know_The_Way",
      duration: 232, 
      audioUrl: "AUDIO/Nkechi_Abugu_You_Know_The_Way_Soundwela.mp3", 
      imageUrl:
        "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      likes: 421,
    },



    
// I AM GOING TO ADD NEW MUSIC FROM HERE
    7: {
      title: "They way",
      artist: "Nkechi_Abugu",
      album: "Abugu_You_Know_The_Way",
      duration: 232, 
      audioUrl: "AUDIO/Nkechi_Abugu_You_Know_The_Way_Soundwela.mp3", 
      imageUrl:
        "https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      likes: 421,
    },
  };

  // Audio player setup
  const audioPlayer = document.getElementById("audio-player");
  const playPauseBtn = document.getElementById("play-pause-btn");
  const progressBar = document.getElementById("progress-bar");
  const progress = document.getElementById("progress");
  const currentTimeDisplay = document.getElementById("current-time");
  const totalTimeDisplay = document.getElementById("total-time");
  const nowPlayingTitle = document.getElementById("now-playing-title");
  const nowPlayingArtist = document.getElementById("now-playing-artist");
  const nowPlayingAlbum = document.getElementById("now-playing-album");
  const nowPlayingImage = document.getElementById("now-playing-image");
  const nowPlayingEqualizer = document.getElementById("now-playing-equalizer");
  const queueItems = document.getElementById("queue-items");
  const prevBtn = document.getElementById("prev-btn");
  const nextBtn = document.getElementById("next-btn");
  const shuffleBtn = document.getElementById("shuffle-btn");
  const repeatBtn = document.getElementById("repeat-btn");
  const shuffleQueueBtn = document.getElementById("shuffle-queue-btn");
  const clearQueueBtn = document.getElementById("clear-queue-btn");
  const searchInput = document.getElementById("search-input");
  const tracksGrid = document.getElementById("tracks-grid");
  const trackCount = document.getElementById("track-count");

  let currentTrackId = null;
  let isPlaying = false;
  let queue = [];
  let allTracks = [];
  let filteredTracks = [];
  let equalizerInterval;

  // Initialize the music library
  function initializeLibrary() {
    allTracks = Object.keys(tracks).map((id) => ({
      id: parseInt(id),
      ...tracks[id],
    }));
    filteredTracks = [...allTracks];
    renderTracks();
    updateTrackCount();

    // Initialize the queue with all tracks
    queue = allTracks.map((track) => track.id);
    renderQueue();
  }

  // Render the tracks in the library
  function renderTracks() {
    tracksGrid.innerHTML = "";
    filteredTracks.forEach((track) => {
      const trackCard = document.createElement("div");
      trackCard.className = `track-card ${
        track.id === currentTrackId ? "active" : ""
      }`;
      trackCard.setAttribute("data-track-id", track.id);
      trackCard.innerHTML = `
              <div class="track-image" style="background-image: url('${
                track.imageUrl
              }')"></div>
              <div class="track-info">
                <div class="track-title">${track.title}</div>
                <div class="track-artist">${track.artist}</div>
                <div class="track-duration">${formatTime(track.duration)}</div>
              </div>
              <div class="track-actions">
                <button class="track-action-btn like-track-btn" data-track-id="${
                  track.id
                }">
                  <i class="${
                    isLiked(track.id) ? "fas fa-heart liked" : "far fa-heart"
                  }"></i>
                </button>
              </div>
            `;
      tracksGrid.appendChild(trackCard);
    });

    // Add event listeners for track cards
    document.querySelectorAll(".track-card").forEach((card) => {
      card.addEventListener("click", function () {
        const trackId = parseInt(this.getAttribute("data-track-id"));
        loadTrack(trackId);
      });
    });

    // Add event listeners for like buttons
    document.querySelectorAll(".like-track-btn").forEach((btn) => {
      btn.addEventListener("click", function (e) {
        e.stopPropagation();
        const trackId = parseInt(this.getAttribute("data-track-id"));
        toggleLike(trackId);
      });
    });
  }

  // Update track count display
  function updateTrackCount() {
    trackCount.textContent = `${filteredTracks.length} track${
      filteredTracks.length !== 1 ? "s" : ""
    }`;
  }

  // Format time from seconds to MM:SS
  function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs < 10 ? "0" : ""}${secs}`;
  }

  // Render the queue
  function renderQueue() {
    queueItems.innerHTML = "";
    queue.forEach((trackId, index) => {
      const track = tracks[trackId];
      const queueItem = document.createElement("div");
      queueItem.className = `queue-item ${
        trackId === currentTrackId ? "active" : ""
      }`;
      queueItem.innerHTML = `
              <div class="queue-item-image" style="background-image: url('${
                track.imageUrl
              }')"></div>
              <div class="queue-item-info">
                <div class="queue-item-title">${track.title}</div>
                <div class="queue-item-artist">${track.artist}</div>
              </div>
              <div class="queue-item-duration">${formatTime(
                track.duration
              )}</div>
              <div class="queue-item-actions">
                <button class="queue-item-btn like-queue-btn" data-track-id="${trackId}">
                  <i class="${isLiked(trackId) ? "fas" : "far"} fa-heart"></i>
                </button>
                <button class="queue-item-btn">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
              </div>
            `;
      queueItems.appendChild(queueItem);
    });

    // Add event listeners for queue like buttons
    document.querySelectorAll(".like-queue-btn").forEach((btn) => {
      btn.addEventListener("click", function () {
        const trackId = parseInt(this.getAttribute("data-track-id"));
        toggleLike(trackId);
        renderQueue();
      });
    });
  }

  // Load and play a track
  function loadTrack(trackId) {
    const track = tracks[trackId];
    currentTrackId = trackId;

    // Update audio source
    audioPlayer.src = track.audioUrl;

    // Update UI
    nowPlayingTitle.textContent = track.title;
    nowPlayingArtist.textContent = track.artist;
    nowPlayingAlbum.textContent = track.album;
    nowPlayingImage.style.backgroundImage = `url('${track.imageUrl}')`;
    totalTimeDisplay.textContent = formatTime(track.duration);

    // Update track cards
    renderTracks();

    // Update queue
    renderQueue();

    // Play the track
    playTrack();
  }

  // Play the current track
  function playTrack() {
    audioPlayer.play();
    isPlaying = true;
    playPauseBtn.innerHTML = '<i class="fas fa-pause"></i>';

    // Start equalizer animation
    startEqualizer();
  }

  // Pause the current track
  function pauseTrack() {
    audioPlayer.pause();
    isPlaying = false;
    playPauseBtn.innerHTML = '<i class="fas fa-play"></i>';

    // Stop equalizer animation
    stopEqualizer();
  }

  // Toggle play/pause
  function togglePlayPause() {
    if (currentTrackId === null) {
      // If no track is selected, play the first one
      if (filteredTracks.length > 0) {
        loadTrack(filteredTracks[0].id);
      }
    } else if (isPlaying) {
      pauseTrack();
    } else {
      playTrack();
    }
  }

  // Start equalizer animation
  function startEqualizer() {
    stopEqualizer(); // Clear any existing interval

    equalizerInterval = setInterval(() => {
      document
        .querySelectorAll(".now-playing-equalizer .equalizer-bar")
        .forEach((bar) => {
          const randomHeight = Math.floor(Math.random() * 35) + 5;
          bar.style.height = `${randomHeight}px`;
        });
    }, 200);
  }

  // Stop equalizer animation
  function stopEqualizer() {
    if (equalizerInterval) {
      clearInterval(equalizerInterval);
    }

    // Reset equalizer bars
    document
      .querySelectorAll(".now-playing-equalizer .equalizer-bar")
      .forEach((bar) => {
        bar.style.height = "5px";
      });
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

  // Seek in the track
  function seekTrack(e) {
    const rect = progressBar.getBoundingClientRect();
    const percent = (e.clientX - rect.left) / rect.width;
    audioPlayer.currentTime = percent * audioPlayer.duration;
  }

  // Check if a track is liked
  function isLiked(trackId) {
    return localStorage.getItem(`liked_${trackId}`) === "true";
  }

  // Toggle like status
  function toggleLike(trackId) {
    const currentlyLiked = isLiked(trackId);
    localStorage.setItem(`liked_${trackId}`, !currentlyLiked);

    // Update like count in tracks data
    if (!currentlyLiked) {
      tracks[trackId].likes += 1;
    } else {
      tracks[trackId].likes -= 1;
    }

    // Update like buttons
    updateLikeButtons(trackId);
  }

  // Update like buttons for a track
  function updateLikeButtons(trackId) {
    const isTrackLiked = isLiked(trackId);

    // Update like buttons in track cards
    document
      .querySelectorAll(`.like-track-btn[data-track-id="${trackId}"]`)
      .forEach((btn) => {
        const icon = btn.querySelector("i");
        icon.className = isTrackLiked ? "fas fa-heart liked" : "far fa-heart";
      });

    // Update like buttons in queue
    document
      .querySelectorAll(`.like-queue-btn[data-track-id="${trackId}"]`)
      .forEach((btn) => {
        const icon = btn.querySelector("i");
        icon.className = isTrackLiked ? "fas fa-heart liked" : "far fa-heart";
      });
  }

  // Play next track in queue
  function playNext() {
    if (currentTrackId === null) return;

    const currentIndex = queue.indexOf(currentTrackId);
    if (currentIndex < queue.length - 1) {
      loadTrack(queue[currentIndex + 1]);
    } else {
      // If at the end of queue, loop back to start if repeat is on
      if (repeatBtn.classList.contains("active")) {
        loadTrack(queue[0]);
      } else {
        // Otherwise, stop playback
        pauseTrack();
        currentTrackId = null;
        nowPlayingTitle.textContent = "Select a track to play";
        nowPlayingArtist.textContent = "Artist";
        nowPlayingAlbum.textContent = "Album";
        progress.style.width = "0%";
        currentTimeDisplay.textContent = "0:00";
        totalTimeDisplay.textContent = "0:00";
        renderTracks();
      }
    }
  }

  // Play previous track in queue
  function playPrev() {
    if (currentTrackId === null) return;

    const currentIndex = queue.indexOf(currentTrackId);
    if (currentIndex > 0) {
      loadTrack(queue[currentIndex - 1]);
    } else {
      // If at the start of queue, loop to end if repeat is on
      if (repeatBtn.classList.contains("active")) {
        loadTrack(queue[queue.length - 1]);
      }
    }
  }

  // Shuffle the queue
  function shuffleQueue() {
    for (let i = queue.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [queue[i], queue[j]] = [queue[j], queue[i]];
    }
    renderQueue();
  }

  // Clear the queue
  function clearQueue() {
    queue = [];
    renderQueue();
  }

  // Search functionality
  function searchTracks(query) {
    if (query.trim() === "") {
      filteredTracks = [...allTracks];
    } else {
      const lowerQuery = query.toLowerCase();
      filteredTracks = allTracks.filter(
        (track) =>
          track.title.toLowerCase().includes(lowerQuery) ||
          track.artist.toLowerCase().includes(lowerQuery) ||
          track.album.toLowerCase().includes(lowerQuery)
      );
    }
    renderTracks();
    updateTrackCount();

    // Update queue with filtered tracks
    queue = filteredTracks.map((track) => track.id);
    renderQueue();
  }

  // Initialize the player
  function initializePlayer() {
    // Set up event listeners
    playPauseBtn.addEventListener("click", togglePlayPause);
    progressBar.addEventListener("click", seekTrack);
    audioPlayer.addEventListener("timeupdate", updateProgress);
    audioPlayer.addEventListener("ended", playNext);
    prevBtn.addEventListener("click", playPrev);
    nextBtn.addEventListener("click", playNext);

    // Search functionality
    searchInput.addEventListener("input", function () {
      searchTracks(this.value);
    });

    // Shuffle and repeat buttons
    shuffleBtn.addEventListener("click", function () {
      this.classList.toggle("active");
    });

    repeatBtn.addEventListener("click", function () {
      this.classList.toggle("active");
    });

    // Queue control buttons
    shuffleQueueBtn.addEventListener("click", shuffleQueue);
    clearQueueBtn.addEventListener("click", clearQueue);

    // Initialize like buttons
    Object.keys(tracks).forEach((trackId) => {
      updateLikeButtons(parseInt(trackId));
    });
  }

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

  // Initialize the player and library
  initializePlayer();
  initializeLibrary();
});
