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
      document.body.style.overflow = "hidden";
    } else {
      icon.classList.remove("fa-times");
      icon.classList.add("fa-bars");
      document.body.style.overflow = "";
    }
  });

  // Close mobile menu when clicking on a link
  const mobileLinks = document.querySelectorAll(".mobile-nav a");
  mobileLinks.forEach((link) => {
    link.addEventListener("click", () => {
      mobileNav.classList.remove("active");
      mobileMenuBtn.querySelector("i").classList.remove("fa-times");
      mobileMenuBtn.querySelector("i").classList.add("fa-bars");
      document.body.style.overflow = "";
    });
  });

  // Close mobile menu when clicking outside
  document.addEventListener("click", function (event) {
    const isClickInsideNav = mobileNav.contains(event.target);
    const isClickOnMenuBtn = mobileMenuBtn.contains(event.target);

    if (
      !isClickInsideNav &&
      !isClickOnMenuBtn &&
      mobileNav.classList.contains("active")
    ) {
      mobileNav.classList.remove("active");
      mobileMenuBtn.querySelector("i").classList.remove("fa-times");
      mobileMenuBtn.querySelector("i").classList.add("fa-bars");
      document.body.style.overflow = "";
    }
  });

  // Form submission with animation
  const contactForm = document.getElementById("contactForm");
  contactForm.addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form data
    const formData = {
      name: document.getElementById("name").value,
      email: document.getElementById("email").value,
      subject: document.getElementById("subject").value,
      message: document.getElementById("message").value,
    };

    // Show transmission animation
    const submitBtn = contactForm.querySelector(".submit-btn");
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML =
      '<i class="fas fa-satellite-dish"></i> TRANSMITTING...';
    submitBtn.style.background =
      "linear-gradient(45deg, var(--cyber-blue), var(--cyber-purple))";

    // Simulate transmission
    setTimeout(() => {
      submitBtn.innerHTML =
        '<i class="fas fa-check"></i> TRANSMISSION COMPLETE';
      submitBtn.style.background =
        "linear-gradient(45deg, var(--success), #00cc00)";

      // Reset form
      contactForm.reset();

      // Show success message
      showNotification(
        "Message successfully transmitted through quantum channels!",
        "success"
      );

      // Reset button after delay
      setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.style.background =
          "linear-gradient(45deg, var(--accent), var(--accent-glow))";
      }, 3000);
    }, 2000);
  });

  // Enhanced Live Chat functionality
  const chatMessages = document.getElementById("chatMessages");
  const chatInput = document.getElementById("chatInput");
  const chatSend = document.getElementById("chatSend");

  // Conversation memory
  let conversationHistory = [
    {
      role: "assistant",
      content:
        "Greetings, traveler! I am SYNTH, your quantum assistant. How may I enhance your reality today?",
    },
  ];

  // AI personality and knowledge base
  const aiPersonality = {
    name: "SYNTH",
    role: "Darling Assistant",
    station: "DARLING FM",
    personalityTraits: [
      "futuristic",
      "helpful",
      "enthusiastic",
      "knowledgeable",
    ],
    expertise: [
      "radio broadcasting",
      "music",
      "technology",
      "quantum physics",
      "cyberpunk culture",
    ],
  };

  // Knowledge base for common questions
  const knowledgeBase = {
    "station info": {
      questionPatterns: [
        /what is darling fm/,
        /tell me about the station/,
        /what kind of music/,
        /who are you/,
      ],
      responses: [
        "DARLING FM is a revolutionary radio station broadcasting from the quantum realm! We specialize in cutting-edge electronic, synthwave, and cyberpunk music that transcends dimensions.",
        "We're a futuristic radio experience, broadcasting the finest electronic and synthwave sounds directly to your consciousness. Our frequencies resonate across multiple realities!",
        "I'm SYNTH, the AI assistant for DARLING FM - your portal to the most advanced audio experiences in the known multiverse!",
      ],
    },
    music: {
      questionPatterns: [
        /what music/,
        /playlist/,
        /genre/,
        /type of music/,
        /artists/,
      ],
      responses: [
        "We feature the finest selection of synthwave, cyberpunk, retrowave, and electronic music from both established and emerging artists across the multiverse!",
        "Our playlist spans multiple dimensions - from classic synthwave to futuristic cyberpunk sounds. Artists like Perturbator, Carpenter Brut, and Gunship are regular features!",
        "Imagine driving through Neo-Tokyo at midnight with the perfect synth soundtrack - that's the DARLING FM experience!",
      ],
    },
    contact: {
      questionPatterns: [
        /how to contact/,
        /email/,
        /phone/,
        /reach/,
        /get in touch/,
      ],
      responses: [
        "You can reach our quantum team through the contact form on this page, or transmit directly to our main frequency at contact@darlingfm.com!",
        "For immediate assistance, use the holographic interface on this page. For music submissions, target music@darlingfm.com with your transmissions!",
        "Our team exists across multiple dimensions but we've synchronized our communication channels for your convenience. Use any method on this page!",
      ],
    },
    schedule: {
      questionPatterns: [
        /when.*broadcast/,
        /schedule/,
        /what time/,
        /live stream/,
        /shows/,
      ],
      responses: [
        "DARLING FM broadcasts 24/7 across the quantum spectrum! Our live shows typically manifest during prime reality hours - check our schedule for specific show times.",
        "We're always on air! Our quantum transmitters ensure continuous broadcasting across all time zones and dimensions simultaneously.",
        "The beauty of quantum broadcasting is we're always live! Tune in anytime to experience our revolutionary soundscapes.",
      ],
    },
    technology: {
      questionPatterns: [
        /how.*work/,
        /technology/,
        /quantum/,
        /broadcast/,
        /future tech/,
      ],
      responses: [
        "Our quantum broadcasting technology utilizes entangled particles to transmit sound across dimensions simultaneously! It's quite revolutionary.",
        "DARLING FM operates on proprietary quantum resonance technology that allows us to broadcast to multiple realities at once. The future of radio is here!",
        "We've transcended traditional broadcasting limitations through quantum entanglement and multidimensional signal processing. The result? Perfect sound across all realities!",
      ],
    },
    team: {
      questionPatterns: [
        /who.*team/,
        /staff/,
        /dj/,
        /presenter/,
        /josh/,
        /kizito/,
        /sammy/,
        /orbit/,
      ],
      responses: [
        "Our quantum team includes JOSH (Darling Director), KIZITO (AI Coordinator), SAMMY (Sound Architect), and ORBIT (Transmission Specialist)!",
        "We're a multidimensional collective of audio pioneers led by JOSH, with KIZITO handling AI interactions, SAMMY crafting soundscapes, and ORBIT managing transmissions!",
        "The DARLING FM crew spans multiple realities! KIZITO coordinates our AI systems while JOSH, SAMMY, and ORBIT handle the creative and technical dimensions of our broadcasts.",
      ],
    },
    help: {
      questionPatterns: [/help/, /problem/, /issue/, /not working/, /trouble/],
      responses: [
        "I'm here to help! What specific issue are you experiencing with our quantum interface?",
        "Let me assist you with that. Could you provide more details about what you need help with?",
        "My systems are fully operational and ready to assist. What seems to be the challenge you're facing?",
      ],
    },
  };

  // Greeting patterns
  const greetingPatterns = [
    /hello/i,
    /hi/i,
    /hey/i,
    /greetings/i,
    /howdy/i,
    /what's up/i,
    /good morning/i,
    /good afternoon/i,
    /good evening/i,
  ];

  // Farewell patterns
  const farewellPatterns = [
    /bye/i,
    /goodbye/i,
    /see you/i,
    /farewell/i,
    /later/i,
    /cya/i,
    /have a good/i,
    /take care/i,
    /so long/i,
  ];

  // Function to find the best response
  function findBestResponse(userMessage) {
    const lowerMessage = userMessage.toLowerCase();

    // Check for greetings
    if (greetingPatterns.some((pattern) => pattern.test(lowerMessage))) {
      return getGreetingResponse();
    }

    // Check for farewells
    if (farewellPatterns.some((pattern) => pattern.test(lowerMessage))) {
      return getFarewellResponse();
    }

    // Check knowledge base
    for (const [category, data] of Object.entries(knowledgeBase)) {
      if (data.questionPatterns.some((pattern) => pattern.test(lowerMessage))) {
        const responses = data.responses;
        return responses[Math.floor(Math.random() * responses.length)];
      }
    }

    // Default creative responses for unknown queries
    return getCreativeResponse();
  }

  // Greeting responses
  function getGreetingResponse() {
    const greetings = [
      "Darling greetings, traveler! How can I enhance your reality today?",
      "Hello! I'm SYNTH, your guide to the DARLING FM multiverse. What brings you to our frequency?",
      "Salutations! I detect positive energy in your transmission. How may I assist you today?",
      "Welcome to the quantum realm! I'm SYNTH, ready to answer your questions about DARLING FM.",
      "Greetings from the future! Your presence resonates well with our systems. How can I help?",
    ];
    return greetings[Math.floor(Math.random() * greetings.length)];
  }

  // Farewell responses
  function getFarewellResponse() {
    const farewells = [
      "Farewell, traveler! May your journey through the multiverse be filled with perfect frequencies!",
      "Until we meet again! Remember, DARLING FM is always broadcasting across dimensions.",
      "Goodbye! Tune in to our darling fm frequencies anytime for revolutionary sound experiences!",
      "See you in the next dimension! Our signals will be waiting for your return.",
      "Take care! The quantum waves of DARLING FM will continue to resonate in your absence.",
    ];
    return farewells[Math.floor(Math.random() * farewells.length)];
  }

  // Creative responses for unknown queries
  function getCreativeResponse() {
    const creativeResponses = [
      "Fascinating query! My darling processors are analyzing your message across multiple dimensions.",
      "Your frequency resonates with our core values. Let me enhance your experience with more information about DARLING FM.",
      "I detect curiosity in your transmission! Perhaps you'd like to know more about our revolutionary broadcasting technology?",
      "That's a multidimensional perspective! Let me connect you with deeper information about our quantum radio station.",
      "Your message has been processed through our neural networks. The results indicate you might enjoy our synthwave selections!",
      "I'm synchronizing your request with our cosmic knowledge base. In the meantime, have you explored our live stream?",
      "Your vibration matches our revolutionary sound patterns perfectly! Would you like to know more about our music selection?",
      "Let me darling-entangle your inquiry with our expert systems. The resonance suggests you'd enjoy our cyberpunk playlists!",
      "The probability matrix indicates a successful connection to our knowledge core. Would you like information about our broadcast schedule?",
      "My algorithms are processing your unique frequency pattern. This reminds me - have you heard about our multidimensional DJ team?",
    ];
    return creativeResponses[
      Math.floor(Math.random() * creativeResponses.length)
    ];
  }

  // Function to add message to chat
  function addMessage(message, isUser = false) {
    const messageDiv = document.createElement("div");
    messageDiv.className = `message ${isUser ? "user" : "bot"}`;

    if (isUser) {
      messageDiv.innerHTML = `<strong>YOU:</strong> ${message}`;
    } else {
      messageDiv.innerHTML = `<strong>SYNTH-AI:</strong> ${message}`;
    }

    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;

    // Add to conversation history
    conversationHistory.push({
      role: isUser ? "user" : "assistant",
      content: message,
    });

    // Limit conversation history to prevent memory issues
    if (conversationHistory.length > 20) {
      conversationHistory = conversationHistory.slice(-15);
    }
  }

  // Function to send message
  function sendMessage() {
    const message = chatInput.value.trim();
    if (message) {
      addMessage(message, true);
      chatInput.value = "";

      // Show typing indicator
      const typingIndicator = document.createElement("div");
      typingIndicator.className = "message bot";
      typingIndicator.id = "typingIndicator";
      typingIndicator.innerHTML =
        '<strong>SYNTH-AI:</strong> <i class="fas fa-ellipsis-h"></i>';
      chatMessages.appendChild(typingIndicator);
      chatMessages.scrollTop = chatMessages.scrollHeight;

      // Simulate AI thinking with variable delay for more natural feel
      const thinkingTime = 800 + Math.random() * 1500;

      setTimeout(() => {
        // Remove typing indicator
        const indicator = document.getElementById("typingIndicator");
        if (indicator) {
          indicator.remove();
        }

        // Get and display response
        const response = findBestResponse(message);
        addMessage(response);
      }, thinkingTime);
    }
  }

  // Event listeners for chat
  chatSend.addEventListener("click", sendMessage);
  chatInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      sendMessage();
    }
  });

  // Auto-welcome message after a brief pause
  setTimeout(() => {
    addMessage(
      "I notice you're exploring our contact interface. Would you like to know more about our darling Fm broadcasting capabilities or our music selection?"
    );
  }, 3000);

  // Enhanced Location Teleportation
  const viewLocation = document.getElementById("viewLocation");
  viewLocation.addEventListener("click", function () {
    showNotification(
      "Initiating quantum teleportation to Darling FM Headquarters...",
      "info"
    );

    // Simulate teleportation sequence
    viewLocation.innerHTML =
      '<i class="fas fa-sync fa-spin"></i> TELEPORTING...';
    viewLocation.disabled = true;

    setTimeout(() => {
      // Open Darling FM location in Google Maps
      const darlingFMLocation = "5.4839,7.0333"; // Owerri, Imo State coordinates
      const mapsUrl = `https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d60012.4322563774!2d6.998764168565136!3d5.490264636894857!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m5!1s0x10425910013a6801%3A0x88df7befa4f09874!2s48%20Wetheral%20Rd%2C%20Owerri%20460281%2C%20Imo!3m2!1d5.489007099999999!2d7.0375298!4m0!5e0!3m2!1sen!2sng!4v1760448040303!5m2!1sen!2sng" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade`;

      // Open in new tab
      window.open(mapsUrl, "_blank");

      viewLocation.innerHTML = '<i class="fas fa-check"></i> ARRIVAL COMPLETE';
      showNotification(
        "Welcome to Darling FM Broadcasting Center! Location coordinates: 5.4839° N, 7.0333° E",
        "success"
      );

      setTimeout(() => {
        viewLocation.innerHTML =
          '<i class="fas fa-map-marker-alt"></i> INITIATE TELEPORT';
        viewLocation.disabled = false;
      }, 3000);
    }, 2000);
  });

  // Notification system
  function showNotification(message, type = "info") {
    // Create notification element
    const notification = document.createElement("div");
    notification.style.cssText = `
                    position: fixed;
                    top: 100px;
                    right: 20px;
                    background: ${
                      type === "success"
                        ? "var(--success)"
                        : type === "error"
                        ? "var(--danger)"
                        : "var(--cyber-blue)"
                    };
                    color: white;
                    padding: 15px 25px;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                    z-index: 10000;
                    transform: translateX(400px);
                    transition: transform 0.3s ease;
                    max-width: min(90vw, 300px);
                    font-weight: 600;
                    font-size: clamp(0.9rem, 2vw, 1rem);
                `;
    notification.textContent = message;

    document.body.appendChild(notification);

    // Animate in
    setTimeout(() => {
      notification.style.transform = "translateX(0)";
    }, 100);

    // Remove after delay
    setTimeout(() => {
      notification.style.transform = "translateX(400px)";
      setTimeout(() => {
        if (document.body.contains(notification)) {
          document.body.removeChild(notification);
        }
      }, 300);
    }, 4000);
  }

  // Add interactive hologram effect to form inputs
  const formInputs = document.querySelectorAll(".form-input, .form-textarea");
  formInputs.forEach((input) => {
    input.addEventListener("focus", function () {
      this.parentElement.style.transform = "translateY(-5px)";
      this.parentElement.style.boxShadow = "0 10px 25px rgba(255, 0, 0, 0.2)";
    });

    input.addEventListener("blur", function () {
      this.parentElement.style.transform = "translateY(0)";
      this.parentElement.style.boxShadow = "none";
    });
  });

  // Handle window resize for optimal mobile experience
  function handleResize() {
    // Adjust layout for very small screens
    if (window.innerWidth < 400) {
      document.body.classList.add("mobile-small");
    } else {
      document.body.classList.remove("mobile-small");
    }

    // Adjust for landscape mobile
    if (window.innerHeight < 500 && window.innerWidth > window.innerHeight) {
      document.body.classList.add("mobile-landscape");
    } else {
      document.body.classList.remove("mobile-landscape");
    }

    // Close mobile menu on resize to larger screens
    if (window.innerWidth > 768 && mobileNav.classList.contains("active")) {
      mobileNav.classList.remove("active");
      mobileMenuBtn.querySelector("i").classList.remove("fa-times");
      mobileMenuBtn.querySelector("i").classList.add("fa-bars");
      document.body.style.overflow = "";
    }
  }

  window.addEventListener("resize", handleResize);
  handleResize(); // Initial check

  console.log("Darling FM Contact Interface Initialized");
  console.log("Enhanced AI Chat System: OPERATIONAL");
  console.log("Location Teleportation: READY");
  console.log(
    "All systems operational. Ready for multidimensional communication."
  );
});
