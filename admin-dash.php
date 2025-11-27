<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darling FM - Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
     <link rel="icon" type="image/png" href="./images/REAL_LOGO-removebg-preview.png" />
    <style>
        :root {
          --primary: #000000;
          --secondary: #1a1a1a;
          --accent: #ff0000;
          --accent-glow: #ff3333;
          --highlight: #ffffff;
          --light: #f5f5f5;
          --dark: #0a0a0a;
          --nav-bg: rgba(0, 0, 0, 0.95);
          --glass: rgba(255, 255, 255, 0.05);
          --glass-border: rgba(255, 255, 255, 0.1);
          --neon-glow: 0 0 10px var(--accent), 0 0 20px var(--accent);
          --live-green: #00ff00;
          --live-glow: 0 0 10px #00ff00, 0 0 20px #00ff00;
          --success: #00cc66;
          --warning: #ffaa00;
          --info: #0099ff;
        }

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        body {
          background-color: var(--primary);
          color: var(--light);
          font-family: "Exo 2", sans-serif;
          line-height: 1.6;
          overflow-x: hidden;
          background: radial-gradient(
            ellipse at center,
            var(--secondary) 0%,
            var(--primary) 70%
          );
          min-height: 100vh;
        }

        .cyber-grid {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-image: linear-gradient(rgba(255, 0, 0, 0.1) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 0, 0, 0.1) 1px, transparent 1px);
          background-size: 50px 50px;
          z-index: -1;
          pointer-events: none;
        }

        .dashboard-container {
          display: flex;
          min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
          width: 260px;
          background: var(--glass);
          backdrop-filter: blur(15px);
          border-right: 1px solid var(--glass-border);
          padding: 20px 0;
          position: fixed;
          height: 100vh;
          overflow-y: auto;
          z-index: 100;
          transition: all 0.3s ease;
        }

        .sidebar-header {
          display: flex;
          align-items: center;
          padding: 0 20px 20px;
          border-bottom: 1px solid var(--glass-border);
          margin-bottom: 20px;
        }

        .sidebar-header img {
          width: 40px;
          height: 40px;
          margin-right: 10px;
          filter: drop-shadow(0 0 5px var(--accent));
        }

        .sidebar-header h2 {
          font-family: "Orbitron", sans-serif;
          font-size: 1.2rem;
          background: linear-gradient(45deg, var(--accent), var(--highlight));
          -webkit-background-clip: text;
          background-clip: text;
          color: transparent;
        }

        .sidebar-menu {
          list-style: none;
        }

        .sidebar-menu li {
          margin-bottom: 5px;
        }

        .sidebar-menu a {
          display: flex;
          align-items: center;
          padding: 12px 20px;
          color: var(--light);
          text-decoration: none;
          transition: all 0.3s;
          border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
          background: rgba(255, 0, 0, 0.1);
          border-left-color: var(--accent);
          color: var(--highlight);
        }

        .sidebar-menu i {
          margin-right: 10px;
          width: 20px;
          text-align: center;
        }

        .menu-label {
          padding: 15px 20px 5px;
          font-size: 0.8rem;
          text-transform: uppercase;
          color: var(--accent);
          font-weight: 600;
          letter-spacing: 1px;
        }

        /* Main Content Styles */
        .main-content {
          flex: 1;
          margin-left: 260px;
          padding: 20px;
          transition: all 0.3s ease;
        }

        .header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 30px;
          padding-bottom: 20px;
          border-bottom: 1px solid var(--glass-border);
        }

        .header h1 {
          font-family: "Orbitron", sans-serif;
          font-size: 2rem;
          background: linear-gradient(45deg, var(--accent), var(--highlight));
          -webkit-background-clip: text;
          background-clip: text;
          color: transparent;
        }

        .user-info {
          display: flex;
          align-items: center;
        }

        .user-avatar {
          width: 40px;
          height: 40px;
          border-radius: 50%;
          background: var(--accent);
          display: flex;
          align-items: center;
          justify-content: center;
          margin-right: 10px;
          font-weight: bold;
        }

        /* Hamburger Menu */
        .hamburger-menu {
          display: none;
          flex-direction: column;
          justify-content: space-between;
          width: 30px;
          height: 21px;
          cursor: pointer;
          margin-right: 15px;
        }

        .hamburger-menu span {
          display: block;
          height: 3px;
          width: 100%;
          background-color: var(--highlight);
          border-radius: 3px;
          transition: all 0.3s ease;
        }

        .hamburger-menu.active span:nth-child(1) {
          transform: translateY(9px) rotate(45deg);
        }

        .hamburger-menu.active span:nth-child(2) {
          opacity: 0;
        }

        .hamburger-menu.active span:nth-child(3) {
          transform: translateY(-9px) rotate(-45deg);
        }

        .mobile-header {
          display: none;
          align-items: center;
          padding: 15px 20px;
          background: var(--glass);
          backdrop-filter: blur(15px);
          border-bottom: 1px solid var(--glass-border);
          position: sticky;
          top: 0;
          z-index: 99;
        }

        .mobile-header img {
          width: 35px;
          height: 35px;
          margin-right: 10px;
          filter: drop-shadow(0 0 5px var(--accent));
        }

        .mobile-header h2 {
          font-family: "Orbitron", sans-serif;
          font-size: 1.1rem;
          background: linear-gradient(45deg, var(--accent), var(--highlight));
          -webkit-background-clip: text;
          background-clip: text;
          color: transparent;
        }

        /* Stats Grid */
        .stats-grid {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
        }

        .stat-card {
          background: var(--glass);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          padding: 20px;
          display: flex;
          align-items: center;
          border: 1px solid var(--glass-border);
          transition: all 0.3s ease;
          position: relative;
          overflow: hidden;
        }

        .stat-card::before {
          content: "";
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: linear-gradient(
            135deg,
            rgba(255, 0, 0, 0.1),
            rgba(255, 255, 255, 0.05)
          );
          z-index: -1;
          opacity: 0;
          transition: opacity 0.4s;
        }

        .stat-card:hover {
          transform: translateY(-5px);
          box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .stat-card:hover::before {
          opacity: 1;
        }

        .stat-icon {
          width: 60px;
          height: 60px;
          border-radius: 12px;
          display: flex;
          align-items: center;
          justify-content: center;
          margin-right: 15px;
          font-size: 24px;
        }

        .icon-listeners {
          background: rgba(0, 255, 0, 0.2);
          color: var(--live-green);
        }

        .icon-revenue {
          background: rgba(0, 204, 102, 0.2);
          color: var(--success);
        }

        .icon-shows {
          background: rgba(255, 170, 0, 0.2);
          color: var(--warning);
        }

        .icon-djs {
          background: rgba(0, 153, 255, 0.2);
          color: var(--info);
        }

        .stat-info h3 {
          font-size: 0.9rem;
          color: var(--light);
          opacity: 0.8;
          margin-bottom: 5px;
        }

        .stat-value {
          font-size: 1.8rem;
          font-weight: 700;
          margin-bottom: 5px;
        }

        .stat-change {
          font-size: 0.8rem;
          display: flex;
          align-items: center;
        }

        .change-positive {
          color: var(--success);
        }

        .change-negative {
          color: var(--accent);
        }

        /* Charts Section */
        .charts-section {
          display: grid;
          grid-template-columns: 2fr 1fr;
          gap: 20px;
          margin-bottom: 30px;
        }

        .chart-card {
          background: var(--glass);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          padding: 20px;
          border: 1px solid var(--glass-border);
        }

        .chart-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
        }

        .chart-header h3 {
          font-size: 1.2rem;
          color: var(--highlight);
        }

        .chart-actions {
          display: flex;
          gap: 10px;
        }

        .chart-btn {
          background: rgba(255, 255, 255, 0.1);
          border: 1px solid var(--glass-border);
          color: var(--light);
          padding: 5px 10px;
          border-radius: 5px;
          cursor: pointer;
          font-size: 0.8rem;
          transition: all 0.3s;
        }

        .chart-btn:hover,
        .chart-btn.active {
          background: var(--accent);
          color: white;
        }

        .chart-container {
          height: 300px;
          position: relative;
        }

        /* Recent Activity & Top Shows */
        .activity-shows {
          display: grid;
          grid-template-columns: 1fr 1fr;
          gap: 20px;
          margin-bottom: 30px;
        }

        .activity-card,
        .shows-card {
          background: var(--glass);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          padding: 20px;
          border: 1px solid var(--glass-border);
        }

        .card-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
        }

        .card-header h3 {
          font-size: 1.2rem;
          color: var(--highlight);
        }

        .view-all {
          color: var(--accent);
          text-decoration: none;
          font-size: 0.9rem;
          transition: all 0.3s;
        }

        .view-all:hover {
          text-decoration: underline;
        }

        .activity-list,
        .shows-list {
          list-style: none;
        }

        .activity-item,
        .show-item {
          display: flex;
          align-items: center;
          padding: 15px 0;
          border-bottom: 1px solid var(--glass-border);
        }

        .activity-item:last-child,
        .show-item:last-child {
          border-bottom: none;
        }

        .activity-icon {
          width: 40px;
          height: 40px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          margin-right: 15px;
          font-size: 16px;
        }

        .icon-success {
          background: rgba(0, 204, 102, 0.2);
          color: var(--success);
        }

        .icon-warning {
          background: rgba(255, 170, 0, 0.2);
          color: var(--warning);
        }

        .icon-info {
          background: rgba(0, 153, 255, 0.2);
          color: var(--info);
        }

        .activity-details {
          flex: 1;
        }

        .activity-details p {
          margin-bottom: 5px;
        }

        .activity-time {
          font-size: 0.8rem;
          opacity: 0.7;
        }

        .show-avatar {
          width: 50px;
          height: 50px;
          border-radius: 10px;
          background-size: cover;
          background-position: center;
          margin-right: 15px;
        }

        .show-details {
          flex: 1;
        }

        .show-name {
          font-weight: 600;
          margin-bottom: 5px;
        }

        .show-host {
          font-size: 0.9rem;
          opacity: 0.8;
          margin-bottom: 5px;
        }

        .show-stats {
          display: flex;
          gap: 15px;
        }

        .show-stat {
          display: flex;
          align-items: center;
          gap: 5px;
          font-size: 0.8rem;
          opacity: 0.8;
        }

        /* Quick Actions */
        .quick-actions {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
          gap: 20px;
          margin-bottom: 30px;
        }

        .action-card {
          background: var(--glass);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          padding: 25px;
          text-align: center;
          border: 1px solid var(--glass-border);
          transition: all 0.3s ease;
          cursor: pointer;
        }

        .action-card:hover {
          transform: translateY(-5px);
          background: rgba(255, 0, 0, 0.1);
          border-color: var(--accent);
        }

        .action-icon {
          width: 60px;
          height: 60px;
          border-radius: 50%;
          display: flex;
          align-items: center;
          justify-content: center;
          margin: 0 auto 15px;
          font-size: 24px;
          background: rgba(255, 0, 0, 0.2);
          color: var(--accent);
        }

        .action-card h3 {
          font-size: 1.1rem;
          margin-bottom: 10px;
        }

        .action-card p {
          font-size: 0.9rem;
          opacity: 0.8;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: rgba(0, 0, 0, 0.7);
          z-index: 98;
          backdrop-filter: blur(5px);
        }

        /* Media Queries */
        @media (max-width: 1200px) {
          .charts-section {
            grid-template-columns: 1fr;
          }

          .activity-shows {
            grid-template-columns: 1fr;
          }
        }

        @media (max-width: 992px) {
          .sidebar {
            transform: translateX(-100%);
            width: 280px;
          }

          .sidebar.active {
            transform: translateX(0);
          }

          .main-content {
            margin-left: 0;
          }

          .mobile-header {
            display: flex;
          }

          .hamburger-menu {
            display: flex;
          }

          .sidebar-overlay.active {
            display: block;
          }
        }

        @media (max-width: 768px) {
          .stats-grid {
            grid-template-columns: 1fr 1fr;
          }
        }

        @media (max-width: 576px) {
          .stats-grid {
            grid-template-columns: 1fr;
          }

          .quick-actions {
            grid-template-columns: 1fr;
          }

          .main-content {
            padding: 15px;
          }
        }

        /* Animations */
        @keyframes fadeIn {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        /* NEW: Scroll reveal styles */
        .reveal-on-scroll {
          opacity: 0;
          transform: translateY(30px);
          transition: all 0.6s ease;
        }

        .reveal-on-scroll.revealed {
          opacity: 1;
          transform: translateY(0);
        }

        /* Apply initial animation to stat cards */
        .stat-card,
        .chart-card,
        .activity-card,
        .shows-card,
        .action-card {
          animation: fadeIn 0.5s ease forwards;
        }

        .stat-card:nth-child(2) {
          animation-delay: 0.1s;
        }
        .stat-card:nth-child(3) {
          animation-delay: 0.2s;
        }
        .stat-card:nth-child(4) {
          animation-delay: 0.3s;
        }
    </style>
</head>
<body>
    <div class="cyber-grid"></div>
    
    <!-- Mobile Header -->
    <div class="mobile-header">
        <div class="hamburger-menu" id="hamburgerMenu">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <img src="./images/REAL_LOGO-removebg-preview.png" alt="Darling FM Logo">
        <h2>DARLING FM ADMIN</h2>
    </div>
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="./images/REAL_LOGO-removebg-preview.png" alt="Darling FM Logo">
                <h2>DARLING FM ADMIN</h2>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-label">Main</li>
                <li><a href="admin-dash.php" class="active"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="admin-livestream.php"><i class="fas fa-broadcast-tower"></i> <span>Live Stream</span></a></li>
                <li><a href="admin-shows.php"><i class="fas fa-music"></i> <span>Shows</span></a></li>
                <li><a href="admin-djs.php"><i class="fas fa-user"></i> <span>DJs</span></a></li>
                <li><a href="admin-djs.php"><i class="fas fa-user"></i> <span>Feedback</span></a></li>
                
                <li class="menu-label">Content</li>
                <li><a href="admin-podcast.php"><i class="fas fa-podcast"></i> <span>Podcasts</span></a></li>
                <li><a href="admin-playlist.php"><i class="fas fa-play-circle"></i> <span>Playlists</span></a></li>
                <li><a href="admin-news.php"><i class="fas fa-newspaper"></i> <span>News</span></a></li>
                
                <li class="menu-label">Analytics</li>
                <li><a href="admin-statistic.php"><i class="fas fa-chart-line"></i> <span>Statistics</span></a></li>
                <li><a href="admin-audience.php"><i class="fas fa-users"></i> <span>Audience</span></a></li>
                <li><a href="admin-revenue.php"><i class="fas fa-money-bill-wave"></i> <span>Revenue</span></a></li>
                
                <li class="menu-label">Settings</li>
                <li><a href="admin-settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                <li><a href="admin-admins.php"><i class="fas fa-user-shield"></i> <span>Admins</span></a></li>
                <li><a href="admin-logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard Overview</h1>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div>
                        <div>Admin User</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">Super Admin</div>
                    </div>
                </div>
            </div>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon icon-listeners">
                        <i class="fas fa-headphones"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Active Listeners</h3>
                        <div class="stat-value">12,458</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 12.5% from yesterday
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon icon-revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Today's Revenue</h3>
                        <div class="stat-value">$3,842</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 8.2% from yesterday
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon icon-shows">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Live Shows</h3>
                        <div class="stat-value">7</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 2 new today
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon icon-djs">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Active DJs</h3>
                        <div class="stat-value">14</div>
                        <div class="stat-change change-negative">
                            <i class="fas fa-arrow-down"></i> 1 offline today
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-card reveal-on-scroll">
                    <div class="chart-header">
                        <h3>Listener Analytics</h3>
                        <div class="chart-actions">
                            <button class="chart-btn">Day</button>
                            <button class="chart-btn">Week</button>
                            <button class="chart-btn active">Month</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <!-- Chart would be rendered here with a library like Chart.js -->
                        <div style="display: flex; align-items: flex-end; justify-content: space-between; height: 100%; padding: 20px 0;">
                            <div style="background: var(--accent); width: 12%; height: 80%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 65%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 45%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 75%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 90%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 60%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 85%; border-radius: 5px 5px 0 0;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="chart-card reveal-on-scroll">
                    <div class="chart-header">
                        <h3>Top Genres</h3>
                        <div class="chart-actions">
                            <button class="chart-btn active">All Time</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <!-- Pie chart would be rendered here -->
                        <div style="display: flex; flex-direction: column; justify-content: center; height: 100%;">
                            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                <div style="width: 15px; height: 15px; background: var(--accent); margin-right: 10px; border-radius: 3px;"></div>
                                <div style="flex: 1;">Pop (32%)</div>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                <div style="width: 15px; height: 15px; background: var(--info); margin-right: 10px; border-radius: 3px;"></div>
                                <div style="flex: 1;">Hip Hop (24%)</div>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                <div style="width: 15px; height: 15px; background: var(--success); margin-right: 10px; border-radius: 3px;"></div>
                                <div style="flex: 1;">Electronic (18%)</div>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                                <div style="width: 15px; height: 15px; background: var(--warning); margin-right: 10px; border-radius: 3px;"></div>
                                <div style="flex: 1;">Rock (14%)</div>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <div style="width: 15px; height: 15px; background: var(--live-green); margin-right: 10px; border-radius: 3px;"></div>
                                <div style="flex: 1;">Other (12%)</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity & Top Shows -->
            <div class="activity-shows">
                <div class="activity-card reveal-on-scroll">
                    <div class="card-header">
                        <h3>Recent Activity</h3>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    <ul class="activity-list">
                        <li class="activity-item">
                            <div class="activity-icon icon-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="activity-details">
                                <p>New podcast uploaded: "Behind the Music"</p>
                                <div class="activity-time">10 minutes ago</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon icon-info">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div class="activity-details">
                                <p>New DJ registered: DJ Nova</p>
                                <div class="activity-time">45 minutes ago</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon icon-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="activity-details">
                                <p>Stream interruption detected and resolved</p>
                                <div class="activity-time">2 hours ago</div>
                            </div>
                        </li>
                        <li class="activity-item">
                            <div class="activity-icon icon-success">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="activity-details">
                                <p>Ad campaign "Summer Hits" launched</p>
                                <div class="activity-time">5 hours ago</div>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <div class="shows-card reveal-on-scroll">
                    <div class="card-header">
                        <h3>Top Shows</h3>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    <ul class="shows-list">
                        <li class="show-item">
                            <div class="show-avatar" style="background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=634&q=80')"></div>
                            <div class="show-details">
                                <div class="show-name">Morning Show</div>
                                <div class="show-host">DJ Alex</div>
                                <div class="show-stats">
                                    <div class="show-stat">
                                        <i class="fas fa-headphones"></i> 4.2K
                                    </div>
                                    <div class="show-stat">
                                        <i class="fas fa-heart"></i> 892
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="show-item">
                            <div class="show-avatar" style="background-image: url('https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=634&q=80')"></div>
                            <div class="show-details">
                                <div class="show-name">Afternoon Drive</div>
                                <div class="show-host">Sarah Miles</div>
                                <div class="show-stats">
                                    <div class="show-stat">
                                        <i class="fas fa-headphones"></i> 3.8K
                                    </div>
                                    <div class="show-stat">
                                        <i class="fas fa-heart"></i> 745
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="show-item">
                            <div class="show-avatar" style="background-image: url('https://images.unsplash.com/photo-1517841905240-472988babdf9?ixlib=rb-4.0.3&auto=format&fit=crop&w=634&q=80')"></div>
                            <div class="show-details">
                                <div class="show-name">Night Beats</div>
                                <div class="show-host">Lena Cruz</div>
                                <div class="show-stats">
                                    <div class="show-stat">
                                        <i class="fas fa-headphones"></i> 5.1K
                                    </div>
                                    <div class="show-stat">
                                        <i class="fas fa-heart"></i> 1.2K
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="action-card reveal-on-scroll" data-action="add-show">
                    <div class="action-icon">
                        <i class="fas fa-plus"></i>
                    </div>
                    <h3>Add New Show</h3>
                    <p>Schedule a new radio show</p>
                </div>
                
                <div class="action-card reveal-on-scroll" data-action="go-live">
                    <div class="action-icon">
                        <i class="fas fa-broadcast-tower"></i>
                    </div>
                    <h3>Go Live</h3>
                    <p>Start a live broadcast</p>
                </div>
                
                <div class="action-card reveal-on-scroll" data-action="view-reports">
                    <div class="action-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>View Reports</h3>
                    <p>Analytics and insights</p>
                </div>
                
                <div class="action-card reveal-on-scroll" data-action="manage-settings">
                    <div class="action-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3>Settings</h3>
                    <p>Manage station settings</p>
                </div>
            </div>
        </div>
    </div>

    <script>
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
          
          // NEW: Quick Actions functionality
          const actionCards = document.querySelectorAll('.action-card');
          
          actionCards.forEach(card => {
            card.addEventListener('click', function() {
              const action = this.getAttribute('data-action');
              
              switch(action) {
                case 'add-show':
                  // Navigate to shows management page
                  window.location.href = 'admin-shows.php';
                  break;
                case 'go-live':
                  // Navigate to live stream page
                  window.location.href = 'admin-livestream.php';
                  break;
                case 'view-reports':
                  // Navigate to statistics page
                  window.location.href = 'admin-statistic.php';
                  break;
                case 'manage-settings':
                  // Navigate to settings page
                  window.location.href = 'admin-settings.php';
                  break;
                default:
                  console.log('Unknown action:', action);
              }
            });
          });
        });
    </script>
</body>
</html>