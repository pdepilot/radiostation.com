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
      const period = this.getAttribute('data-period');
      updateListenerChart(period);
    });
  });

  // Update listener chart with dynamic data
  function updateListenerChart(period) {
    console.log('Updating listener chart for period:', period);
    console.log('Fetching from URL:', `/admin/api/listener-analytics?period=${period}`);

    fetch(`/admin/api/listener-analytics?period=${period}`)
      .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
          throw new Error(`Network response was not ok: ${response.status}`);
        }
        return response.json();
      })
      .then(data => {
        console.log('Received chart data:', data);
        console.log('Series data:', data.series);
        console.log('Series length:', data.series ? data.series.length : 'undefined');

        // Update the stats counts
        const dayCountEl = document.getElementById('dayCount');
        const weekCountEl = document.getElementById('weekCount');
        const monthCountEl = document.getElementById('monthCount');

        if (dayCountEl) {
          dayCountEl.textContent = (data.daily || 0).toLocaleString();
          console.log('Updated day count to:', data.daily);
        }
        if (weekCountEl) {
          weekCountEl.textContent = (data.weekly || 0).toLocaleString();
          console.log('Updated week count to:', data.weekly);
        }
        if (monthCountEl) {
          monthCountEl.textContent = (data.monthly || 0).toLocaleString();
          console.log('Updated month count to:', data.monthly);
        }

        // Update the chart bars
        updateChartBars(data.series || []);
      })
      .catch(error => {
        console.error('Failed to fetch listener analytics:', error);
        // Show error state in chart
        const chartContainer = document.getElementById('listenerChartContainer');
        if (chartContainer) {
          chartContainer.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-secondary); flex-direction: column;">
              <i class="fas fa-exclamation-triangle" style="font-size: 2rem; opacity: 0.5; margin-bottom: 10px;"></i>
              <div>Error: ${error.message}</div>
              <div style="font-size: 0.8rem; margin-top: 5px;">Check browser console for details</div>
            </div>
          `;
        }
      });
  }

  // Update chart bars with new data
  function updateChartBars(series) {
    const chartContainer = document.getElementById('listenerChartContainer');

    if (!series || series.length === 0) {
      chartContainer.innerHTML = `
        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: var(--text-secondary);">
          <div style="text-align: center;">
            <i class="fas fa-chart-bar" style="font-size: 3rem; opacity: 0.3; margin-bottom: 15px;"></i>
            <p>No listener data available for this period</p>
          </div>
        </div>
      `;
      return;
    }

    // Calculate max value for scaling
    const maxValue = Math.max(...series.map(item => item.value || 0)) || 1;

    // Create the bars container
    const barsContainer = document.createElement('div');
    barsContainer.id = 'chartBars';
    barsContainer.style.cssText = 'display: flex; align-items: flex-end; justify-content: space-between; height: 100%; gap: 5px;';

    series.forEach((item, index) => {
      const value = item.value || 0;
      const date = item.date || `Day ${index + 1}`;
      const heightPercent = (value / maxValue) * 100;

      const bar = document.createElement('div');
      bar.className = 'chart-bar';
      const isLive = item.is_live;
      bar.style.cssText = `
        background: ${isLive ? 'linear-gradient(to top, #ff0000, #ff6666)' : 'linear-gradient(to top, var(--accent), var(--accent-glow))'};
        width: ${100 / series.length}%;
        height: ${Math.max(heightPercent, 2)}%;
        border-radius: 5px 5px 0 0;
        min-height: 10px;
        position: relative;
        transition: all 0.3s;
        cursor: pointer;
        animation: slideUp 0.5s ease-out ${index * 0.1}s both;
        ${isLive ? 'box-shadow: 0 0 15px rgba(255,0,0,0.5);' : ''}
      `;

      bar.setAttribute('data-value', value);
      bar.setAttribute('data-date', date);
      if (isLive) bar.setAttribute('data-live', 'true');

      // Add tooltip
      const tooltip = document.createElement('div');
      tooltip.className = 'chart-tooltip';
      tooltip.style.cssText = `
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0,0,0,0.9);
        color: white;
        padding: 6px 10px;
        border-radius: 5px;
        font-size: 0.75rem;
        white-space: nowrap;
        margin-bottom: 5px;
        opacity: 0;
        transition: opacity 0.3s;
        pointer-events: none;
        z-index: 10;
      `;
      const liveIndicator = isLive ? ' <span style="color: #ff0000; font-weight: bold;">● LIVE</span>' : '';
      const label = isLive ? 'Current Sessions' : 'Total Sessions';
      tooltip.innerHTML = `
        <div style="font-weight: 700;">${value.toLocaleString()} ${label}${liveIndicator}</div>
        <div style="font-size: 0.7rem; opacity: 0.8;">${date}</div>
      `;

      // Add hover effects
      bar.addEventListener('mouseover', () => {
        tooltip.style.opacity = '1';
        bar.style.transform = 'scale(1.05)';
        bar.style.filter = 'brightness(1.2)';
      });

      bar.addEventListener('mouseout', () => {
        tooltip.style.opacity = '0';
        bar.style.transform = 'scale(1)';
        bar.style.filter = 'brightness(1)';
      });

      bar.appendChild(tooltip);
      barsContainer.appendChild(bar);
    });

    // Replace chart content
    chartContainer.innerHTML = '';
    chartContainer.appendChild(barsContainer);

    // Add CSS animation for slideUp
    if (!document.getElementById('chartAnimations')) {
      const style = document.createElement('style');
      style.id = 'chartAnimations';
      style.textContent = `
        @keyframes slideUp {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
      `;
      document.head.appendChild(style);
    }
  }


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

  // Real-time listener count updates
  function updateLiveListenerCount() {
    fetch('/api/listener-count')
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.count !== undefined) {
          const listenerElement = document.getElementById('monthlyListenersValue');
          if (listenerElement) {
            const oldCount = parseInt(listenerElement.textContent.replace(/,/g, '') || '0');
            const newCount = data.count;

            // Update the display with formatted number
            listenerElement.textContent = newCount.toLocaleString();

            // Add visual feedback for changes
            if (newCount !== oldCount) {
              listenerElement.style.color = newCount > oldCount ? '#00ff00' : newCount < oldCount ? '#ff6666' : '';
              listenerElement.style.fontWeight = '700';

              // Reset color after 2 seconds
              setTimeout(() => {
                listenerElement.style.color = '';
                listenerElement.style.fontWeight = '';
              }, 2000);
            }
          }

          // Update status text if there are listeners
          const statusElement = listenerElement.parentElement.querySelector('.stat-change');
          if (statusElement && newCount > 0) {
            const currentText = statusElement.textContent;
            if (currentText.includes('Real-time data will appear')) {
              statusElement.innerHTML = '<i class="fas fa-users"></i> Live listener count updating...';
              statusElement.style.color = 'var(--accent)';
            }
          }
        }
      })
      .catch(error => {
        console.error('Failed to fetch listener count:', error);
      });
  }

  // Update listener count every 5 seconds
  updateLiveListenerCount(); // Initial update
  setInterval(updateLiveListenerCount, 5000);

  // Initialize chart with default period (month)
  console.log('Initializing listener analytics chart...');
  updateListenerChart('month');

  // Reset listener count button
  const resetBtn = document.getElementById('resetListenerCount');
  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      if (confirm('Are you sure you want to reset the listener count to 0? This will affect the current live stream.')) {
        fetch('/api/listener/reset', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Listener count reset to 0 successfully!');
            // Immediately update the display
            updateLiveListenerCount();
          } else {
            alert('Failed to reset listener count: ' + (data.message || 'Unknown error'));
          }
        })
        .catch(error => {
          console.error('Reset error:', error);
          alert('Failed to reset listener count. Check console for details.');
        });
      }
    });
  }

  // Sample data generation removed - using real data only
});
