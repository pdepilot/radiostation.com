// Mobile hamburger menu functionality
document.addEventListener("DOMContentLoaded", function () {
  const hamburgerMenu = document.getElementById("hamburgerMenu");
  const sidebar = document.getElementById("sidebar");
  const sidebarOverlay = document.getElementById("sidebarOverlay");

  // Toggle sidebar on hamburger click
  hamburgerMenu.addEventListener("click", function () {
    this.classList.toggle("active");
    sidebar.classList.toggle("active");
    sidebarOverlay.classList.toggle("active");
  });

  // Close sidebar when overlay is clicked
  sidebarOverlay.addEventListener("click", function () {
    hamburgerMenu.classList.remove("active");
    sidebar.classList.remove("active");
    this.classList.remove("active");
  });

  // Add hover effects to cards
  const cards = document.querySelectorAll(".stat-card, .action-card");
  cards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-5px)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0)";
    });
  });

  // Chart buttons interaction
  const chartBtns = document.querySelectorAll(".chart-btn");
  chartBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      chartBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
    });
  });

  // Simulate real-time listener count update
  setInterval(() => {
    const listenerElement = document.querySelector(
      ".stat-card:nth-child(1) .stat-value"
    );
    const currentCount = parseInt(listenerElement.textContent.replace(",", ""));
    const randomChange = Math.floor(Math.random() * 50) - 25;
    const newCount = Math.max(12000, currentCount + randomChange);
    listenerElement.textContent = newCount.toLocaleString();
  }, 5000);

  // NEW: Scroll reveal functionality
  const revealElements = document.querySelectorAll(".reveal-on-scroll");

  function checkReveal() {
    const windowHeight = window.innerHeight;
    const revealPoint = 150;

    revealElements.forEach((element) => {
      const elementTop = element.getBoundingClientRect().top;

      if (elementTop < windowHeight - revealPoint) {
        element.classList.add("revealed");
      }
    });
  }

  // Initial check
  checkReveal();

  // Check on scroll
  window.addEventListener("scroll", checkReveal);
});
