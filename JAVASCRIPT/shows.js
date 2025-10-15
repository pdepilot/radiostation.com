document.addEventListener("DOMContentLoaded", function () {
  // Mobile menu functionality
  const mobileMenuBtn = document.querySelector(".mobile-menu");
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

  // Filter functionality
  const filterButtons = document.querySelectorAll(".filter-btn");
  const showCards = document.querySelectorAll(".show-card");

  filterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      filterButtons.forEach((btn) => btn.classList.remove("active"));
      this.classList.add("active");

      const category = this.getAttribute("data-category");

      showCards.forEach((card) => {
        if (
          category === "all" ||
          card.getAttribute("data-category") === category
        ) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
    });
  });

  // Audio player functionality
  const audioPlayer = document.getElementById("audioPlayer");
  let currentlyPlaying = null;

  const playButtons = document.querySelectorAll(".play-btn");

  playButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation();

      const audioUrl = this.getAttribute("data-audio");
      const icon = this.querySelector("i");

      // If this button is already playing, pause it
      if (currentlyPlaying === this) {
        audioPlayer.pause();
        icon.classList.remove("fa-pause");
        icon.classList.add("fa-play");
        this.classList.remove("playing");
        currentlyPlaying = null;
        return;
      }

      // Stop any currently playing audio
      if (currentlyPlaying) {
        const currentIcon = currentlyPlaying.querySelector("i");
        currentIcon.classList.remove("fa-pause");
        currentIcon.classList.add("fa-play");
        currentlyPlaying.classList.remove("playing");
      }

      // Play the new audio
      audioPlayer.src = audioUrl;
      audioPlayer.play();

      icon.classList.remove("fa-play");
      icon.classList.add("fa-pause");
      this.classList.add("playing");
      currentlyPlaying = this;

      // Update button text if it's a "Listen Live" button
      if (this.textContent.includes("Listen Live")) {
        this.innerHTML = '<i class="fas fa-pause"></i> Pause Live';
      } else {
        this.innerHTML = '<i class="fas fa-pause"></i> Pause';
      }
    });
  });

  // Handle audio ending
  audioPlayer.addEventListener("ended", function () {
    if (currentlyPlaying) {
      const icon = currentlyPlaying.querySelector("i");
      icon.classList.remove("fa-pause");
      icon.classList.add("fa-play");
      currentlyPlaying.classList.remove("playing");

      // Reset button text if it was a "Listen Live" button
      if (currentlyPlaying.textContent.includes("Pause Live")) {
        currentlyPlaying.innerHTML = '<i class="fas fa-play"></i> Listen Live';
      } else {
        currentlyPlaying.innerHTML = '<i class="fas fa-play"></i> Play Latest';
      }

      currentlyPlaying = null;
    }
  });

  // Schedule data
  const scheduleData = {
    today: [
      {
        time: "6:00 AM",
        show: "Morning Vibes",
        host: "Sarah Miles",
        image:
          "https://images.unsplash.com/photo-1571330735066-03aaa9429d89?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "10:00 AM",
        show: "Retro Rewind",
        host: "DJ Marco",
        image:
          "https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "3:00 PM",
        show: "The Urban Sound",
        host: "Jamal Roberts",
        image:
          "https://images.unsplash.com/photo-1554260570-9140fd3b7614?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "8:00 PM",
        show: "Bass Culture",
        host: "Tyler Knight",
        image:
          "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "10:00 PM",
        show: "Night Beats",
        host: "Lena Cruz",
        image:
          "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
    ],
    tomorrow: [
      {
        time: "7:00 AM",
        show: "Morning Vibes",
        host: "Sarah Miles",
        image:
          "https://images.unsplash.com/photo-1571330735066-03aaa9429d89?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "11:00 AM",
        show: "Sound Therapy",
        host: "Elena Martinez",
        image:
          "https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "2:00 PM",
        show: "Retro Rewind",
        host: "DJ Marco",
        image:
          "https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "6:00 PM",
        show: "The Urban Sound",
        host: "Jamal Roberts",
        image:
          "https://images.unsplash.com/photo-1554260570-9140fd3b7614?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "9:00 PM",
        show: "Future Beats",
        host: "DJ Nova",
        image:
          "https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
    ],
    week: [
      {
        time: "Mon 6:00 AM",
        show: "Morning Vibes",
        host: "Sarah Miles",
        image:
          "https://images.unsplash.com/photo-1571330735066-03aaa9429d89?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "Tue 3:00 PM",
        show: "The Urban Sound",
        host: "Jamal Roberts",
        image:
          "https://images.unsplash.com/photo-1554260570-9140fd3b7614?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "Wed 10:00 PM",
        show: "Night Beats",
        host: "Lena Cruz",
        image:
          "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "Thu 8:00 PM",
        show: "Bass Culture",
        host: "Tyler Knight",
        image:
          "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "Fri 8:00 PM",
        show: "Future Beats",
        host: "DJ Nova",
        image:
          "https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "Sat 2:00 PM",
        show: "Retro Rewind",
        host: "DJ Marco",
        image:
          "https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
      {
        time: "Sun 10:00 AM",
        show: "Sound Therapy",
        host: "Elena Martinez",
        image:
          "https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
      },
    ],
  };

  // Schedule tabs
  const scheduleTabs = document.querySelectorAll(".schedule-tab");
  const scheduleList = document.getElementById("scheduleList");

  function renderSchedule(scheduleType) {
    scheduleList.innerHTML = "";

    scheduleData[scheduleType].forEach((item) => {
      const scheduleItem = document.createElement("div");
      scheduleItem.className = "schedule-item";
      scheduleItem.innerHTML = `
                        <div class="schedule-time">${item.time}</div>
                        <div class="schedule-show">
                            <div class="schedule-image" style="background-image: url('${item.image}')"></div>
                            <div class="schedule-details">
                                <div class="schedule-title">${item.show}</div>
                                <div class="schedule-host">with ${item.host}</div>
                            </div>
                        </div>
                        <div class="schedule-actions">
                            <button class="action-btn play-btn" data-audio="https://stream.zeno.fm/0r0xa792kwzuv">
                                <i class="fas fa-play"></i>
                            </button>
                        </div>
                    `;
      scheduleList.appendChild(scheduleItem);
    });

    // Add event listeners to new play buttons
    const newPlayButtons = scheduleList.querySelectorAll(".play-btn");
    newPlayButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        e.stopPropagation();

        const audioUrl = this.getAttribute("data-audio");
        const icon = this.querySelector("i");

        // If this button is already playing, pause it
        if (currentlyPlaying === this) {
          audioPlayer.pause();
          icon.classList.remove("fa-pause");
          icon.classList.add("fa-play");
          this.classList.remove("playing");
          currentlyPlaying = null;
          return;
        }

        // Stop any currently playing audio
        if (currentlyPlaying) {
          const currentIcon = currentlyPlaying.querySelector("i");
          currentIcon.classList.remove("fa-pause");
          currentIcon.classList.add("fa-play");
          currentlyPlaying.classList.remove("playing");
        }

        // Play the new audio
        audioPlayer.src = audioUrl;
        audioPlayer.play();

        icon.classList.remove("fa-play");
        icon.classList.add("fa-pause");
        this.classList.add("playing");
        currentlyPlaying = this;
      });
    });
  }

  // Initial render
  renderSchedule("today");

  scheduleTabs.forEach((tab) => {
    tab.addEventListener("click", function () {
      scheduleTabs.forEach((t) => t.classList.remove("active"));
      this.classList.add("active");

      const scheduleType = this.getAttribute("data-schedule");
      renderSchedule(scheduleType);
    });
  });

  // Follow buttons - redirect to OAP Facebook pages
  const followButtons = document.querySelectorAll(".follow-btn");

  followButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation();
      const hostName = this.getAttribute("data-host");

      // Map host names to Facebook URLs
      const hostFacebookUrls = {
        "DJ Alex": "https://facebook.com/djalexnexus",
        "Sarah Miles": "https://facebook.com/sarahmilesnexus",
        "DJ Marco": "https://facebook.com/djmarconexus",
        "Lena Cruz": "https://facebook.com/lenacruznexus",
        "Jamal Roberts": "https://facebook.com/jamalrobertsnexus",
        "Elena Martinez": "https://facebook.com/elenamartineznexus",
        "Tyler Knight": "https://facebook.com/tylerknightnexus",
        "DJ Nova": "https://facebook.com/djnovanexus",
      };

      const facebookUrl =
        hostFacebookUrls[hostName] || "https://facebook.com/nexusradio";

      // Open Facebook page in a new tab
      window.open(facebookUrl, "_blank");
    });
  });

  // Share modal functionality
  const shareModal = document.getElementById("shareModal");
  const shareButtons = document.querySelectorAll(".share-btn");
  const closeModal = document.querySelector(".close-modal");
  const shareShowText = document.getElementById("shareShowText");
  const socialShareButtons = document.querySelectorAll(".social-share-btn");

  shareButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.stopPropagation();
      const showName = this.getAttribute("data-show");
      shareShowText.textContent = `Share "${showName}" with your friends!`;
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
      const showName = shareShowText.textContent.split('"')[1];
      const streamUrl = encodeURIComponent("https://nexusradio.com/shows");
      const text = encodeURIComponent(
        `Check out "${showName}" on Nexus Radio!`
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
          shareUrl = `mailto:?subject=Check out this show on Nexus Radio&body=${text} ${streamUrl}`;
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

  // Show card interactions
  showCards.forEach((card) => {
    card.addEventListener("click", function (e) {
      if (!e.target.closest(".show-actions")) {
        // In a real app, this would navigate to the show page
        console.log("Navigating to show page");
      }
    });
  });

  // Search functionality
  const searchInput = document.querySelector(".search-box input");

  searchInput.addEventListener("input", function () {
    const searchTerm = this.value.toLowerCase();

    showCards.forEach((card) => {
      const title = card
        .querySelector(".show-title span")
        .textContent.toLowerCase();
      const description = card
        .querySelector(".show-description")
        .textContent.toLowerCase();
      const host = card.querySelector(".host-name").textContent.toLowerCase();

      if (
        title.includes(searchTerm) ||
        description.includes(searchTerm) ||
        host.includes(searchTerm)
      ) {
        card.style.display = "block";
      } else {
        card.style.display = "none";
      }
    });
  });

  // Footer subscription
  const footerInput = document.querySelector(".footer-input input");
  const footerButton = document.querySelector(".footer-input button");

  footerButton.addEventListener("click", function () {
    if (footerInput.value.trim() !== "") {
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
