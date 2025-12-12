// Advertisement System
document.addEventListener("DOMContentLoaded", function () {
  const advertContainer = document.getElementById("advertContainer");
  if (!advertContainer) return;

  let loadedAdverts = [];
  let viewedAdverts = JSON.parse(sessionStorage.getItem("viewedAdverts") || "[]");
  let closedAdverts = JSON.parse(sessionStorage.getItem("closedAdverts") || "[]");

  // Load adverts
  function loadAdverts() {
    fetch("/api/adverts")
      .then((res) => res.json())
      .then((adverts) => {
        loadedAdverts = adverts;
        displayAdverts();
      })
      .catch((err) => console.error("Advert load error:", err));
  }

  function displayAdverts() {
    // Clear existing adverts first
    advertContainer.innerHTML = "";
    
    // Filter out closed adverts and show only popup/sidebar ads
    const activeAdverts = loadedAdverts.filter(
      (ad) =>
        !closedAdverts.includes(ad.id) &&
        (ad.position === "popup" || ad.position === "sidebar")
    );

    if (activeAdverts.length === 0) {
      return;
    }

    activeAdverts.forEach((advert) => {
      // Check if user has viewed this ad at least once
      const hasViewed = viewedAdverts.includes(advert.id);

      if (advert.type === "google_adsense" && advert.google_adsense_code) {
        // Google AdSense ad
        const adDiv = document.createElement("div");
        adDiv.className = "advert-item";
        adDiv.id = `advert-${advert.id}`;
        adDiv.style.cssText = `
          background: var(--glass);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          padding: 15px;
          margin-bottom: 15px;
          border: 1px solid var(--glass-border);
          position: relative;
        `;

        if (hasViewed) {
          const closeBtn = document.createElement("button");
          closeBtn.innerHTML = "&times;";
          closeBtn.style.cssText = `
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255,0,0,0.7);
            color: white;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            z-index: 10;
          `;
          closeBtn.addEventListener("click", () => closeAdvert(advert.id));
          adDiv.appendChild(closeBtn);
        }

        adDiv.innerHTML += advert.google_adsense_code;
        advertContainer.appendChild(adDiv);

        // Track view
        if (!hasViewed) {
          trackView(advert.id);
        }
      } else if (advert.image_url) {
        // Image ad with slideshow support
        const adDiv = document.createElement("div");
        adDiv.className = "advert-item";
        adDiv.id = `advert-${advert.id}`;
        adDiv.style.cssText = `
          background: var(--glass);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          padding: 15px;
          margin-bottom: 15px;
          border: 1px solid var(--glass-border);
          position: relative;
          overflow: hidden;
        `;

        if (hasViewed) {
          const closeBtn = document.createElement("button");
          closeBtn.innerHTML = "&times;";
          closeBtn.className = "close-ad";
          closeBtn.style.cssText = `
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
          `;
          closeBtn.addEventListener("click", (e) => {
            e.preventDefault();
            e.stopPropagation();
            closeAdvert(advert.id);
          });
          adDiv.appendChild(closeBtn);
        }

        const adLink = document.createElement("a");
        adLink.href = advert.link_url || "#";
        adLink.target = "_blank";
        adLink.addEventListener("click", () => trackClick(advert.id));
        adLink.style.cssText = "display: block; position: relative; width: 100%; height: 100%;";

        // Check if multiple images (comma-separated)
        const images = advert.image_url.split(',').map(img => img.trim()).filter(img => img);
        
        if (images.length > 1) {
          // Create slideshow
          const sliderDiv = document.createElement("div");
          sliderDiv.className = "image-slider-ad";
          sliderDiv.id = `slider-${advert.id}`;
          sliderDiv.style.cssText = "position: relative; width: 100%; height: 280px; overflow: hidden; border-radius: 10px;";

          const slidesDiv = document.createElement("div");
          slidesDiv.className = "image-slides";
          slidesDiv.style.cssText = `
            display: flex;
            width: ${images.length * 100}%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
          `;

          images.forEach((imgUrl, index) => {
            const slideDiv = document.createElement("div");
            slideDiv.className = "image-slide";
            slideDiv.style.cssText = `
              width: ${100 / images.length}%;
              height: 100%;
              flex-shrink: 0;
              position: relative;
            `;
            
            const img = document.createElement("img");
            img.src = imgUrl;
            img.alt = `${advert.title} - Slide ${index + 1}`;
            img.style.cssText = "width: 100%; height: 100%; object-fit: cover;";
            
            slideDiv.appendChild(img);
            slidesDiv.appendChild(slideDiv);
          });

          sliderDiv.appendChild(slidesDiv);

          // Add slider controls
          const controlsDiv = document.createElement("div");
          controlsDiv.className = "slider-controls";
          controlsDiv.id = `controls-${advert.id}`;
          controlsDiv.style.cssText = `
            position: absolute;
            bottom: 15px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 8px;
            z-index: 15;
          `;

          images.forEach((_, index) => {
            const dot = document.createElement("div");
            dot.className = `slider-dot ${index === 0 ? 'active' : ''}`;
            dot.setAttribute("data-slide", index);
            dot.style.cssText = `
              width: 10px;
              height: 10px;
              border-radius: 50%;
              background: ${index === 0 ? 'white' : 'rgba(255, 255, 255, 0.6)'};
              cursor: pointer;
              transition: all 0.3s ease;
              border: 1px solid rgba(255, 255, 255, 0.3);
            `;
            controlsDiv.appendChild(dot);
          });

          sliderDiv.appendChild(controlsDiv);
          adLink.appendChild(sliderDiv);
          
          // Initialize slideshow
          initAdvertSlideshow(`slider-${advert.id}`, images.length);
        } else {
          // Single image
          const adImage = document.createElement("img");
          adImage.src = advert.image_url;
          adImage.alt = advert.title;
          adImage.style.cssText = "width: 100%; border-radius: 10px; display: block; height: 280px; object-fit: cover;";
          adLink.appendChild(adImage);
        }

        adDiv.appendChild(adLink);
        advertContainer.appendChild(adDiv);

        // Track view
        if (!hasViewed) {
          trackView(advert.id);
        }
      }
    });
  }

  function trackView(advertId) {
    fetch(`/api/adverts/${advertId}/view`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
      },
    })
      .then(() => {
        viewedAdverts.push(advertId);
        sessionStorage.setItem("viewedAdverts", JSON.stringify(viewedAdverts));
        // Re-render to show close button
        displayAdverts();
      })
      .catch((err) => console.error("Advert view tracking error:", err));
  }

  function trackClick(advertId) {
    fetch(`/api/adverts/${advertId}/click`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
      },
    }).catch((err) => console.error("Advert click tracking error:", err));
  }

  function closeAdvert(advertId) {
    fetch(`/api/adverts/${advertId}/close`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
      },
    })
      .then(() => {
        closedAdverts.push(advertId);
        sessionStorage.setItem("closedAdverts", JSON.stringify(closedAdverts));
        const adElement = document.getElementById(`advert-${advertId}`);
        if (adElement) {
          adElement.style.display = "none";
        }
      })
      .catch((err) => console.error("Advert close error:", err));
  }

  // Initialize slideshow for adverts
  function initAdvertSlideshow(sliderId, slideCount) {
    const slider = document.getElementById(sliderId);
    if (!slider) return;

    const slides = slider.querySelector(".image-slides");
    const dots = slider.querySelectorAll(".slider-dot");
    let currentSlide = 0;
    let slideInterval;

    function showSlide(index) {
      slides.style.transform = `translateX(-${index * (100 / slideCount)}%)`;
      dots.forEach(dot => dot.classList.remove("active"));
      if (dots[index]) {
        dots[index].classList.add("active");
        dots[index].style.background = "white";
        dots[index].style.transform = "scale(1.3)";
      }
      dots.forEach((dot, i) => {
        if (i !== index) {
          dot.style.background = "rgba(255, 255, 255, 0.6)";
          dot.style.transform = "scale(1)";
        }
      });
      currentSlide = index;
    }

    function nextSlide() {
      let nextIndex = (currentSlide + 1) % slideCount;
      showSlide(nextIndex);
    }

    dots.forEach((dot, index) => {
      dot.addEventListener("click", function(e) {
        e.preventDefault();
        e.stopPropagation();
        showSlide(index);
        resetInterval();
      });
    });

    function startInterval() {
      slideInterval = setInterval(nextSlide, 4000);
    }

    function resetInterval() {
      clearInterval(slideInterval);
      startInterval();
    }

    startInterval();

    // Pause on hover
    slider.addEventListener("mouseenter", () => clearInterval(slideInterval));
    slider.addEventListener("mouseleave", startInterval);
  }

  // Load adverts on page load
  loadAdverts();

  // Reload adverts every 5 minutes
  setInterval(loadAdverts, 300000);
});

