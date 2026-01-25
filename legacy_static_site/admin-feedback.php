<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darling FM - Feedback Management</title>
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

        /* Feedback Page Specific Styles */
        .feedback-header {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 20px;
        }

        .feedback-filters {
          display: flex;
          gap: 15px;
          margin-bottom: 20px;
        }

        .filter-btn {
          background: rgba(255, 255, 255, 0.1);
          border: 1px solid var(--glass-border);
          color: var(--light);
          padding: 8px 16px;
          border-radius: 5px;
          cursor: pointer;
          font-size: 0.9rem;
          transition: all 0.3s;
        }

        .filter-btn:hover,
        .filter-btn.active {
          background: var(--accent);
          color: white;
        }

        .search-box {
          display: flex;
          margin-bottom: 20px;
        }

        .search-box input {
          flex: 1;
          background: var(--glass);
          border: 1px solid var(--glass-border);
          border-right: none;
          border-radius: 5px 0 0 5px;
          padding: 10px 15px;
          color: var(--light);
          font-family: "Exo 2", sans-serif;
        }

        .search-box button {
          background: var(--accent);
          border: none;
          border-radius: 0 5px 5px 0;
          padding: 0 15px;
          color: white;
          cursor: pointer;
          transition: all 0.3s;
        }

        .search-box button:hover {
          background: var(--accent-glow);
        }

        .feedback-stats {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        .icon-total {
          background: rgba(0, 153, 255, 0.2);
          color: var(--info);
        }

        .icon-new {
          background: rgba(255, 170, 0, 0.2);
          color: var(--warning);
        }

        .icon-responded {
          background: rgba(0, 204, 102, 0.2);
          color: var(--success);
        }

        .icon-rating {
          background: rgba(255, 0, 0, 0.2);
          color: var(--accent);
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

        .feedback-list {
          background: var(--glass);
          backdrop-filter: blur(10px);
          border-radius: 15px;
          padding: 20px;
          border: 1px solid var(--glass-border);
        }

        .feedback-item {
          display: flex;
          padding: 20px;
          border-bottom: 1px solid var(--glass-border);
          transition: all 0.3s;
        }

        .feedback-item:hover {
          background: rgba(255, 255, 255, 0.05);
        }

        .feedback-item:last-child {
          border-bottom: none;
        }

        .feedback-avatar {
          width: 50px;
          height: 50px;
          border-radius: 50%;
          background: var(--accent);
          display: flex;
          align-items: center;
          justify-content: center;
          margin-right: 15px;
          font-weight: bold;
          flex-shrink: 0;
        }

        .feedback-content {
          flex: 1;
        }

        .feedback-header-row {
          display: flex;
          justify-content: space-between;
          align-items: flex-start;
          margin-bottom: 10px;
        }

        .feedback-user {
          font-weight: 600;
          margin-bottom: 5px;
        }

        .feedback-date {
          font-size: 0.8rem;
          opacity: 0.7;
        }

        .feedback-rating {
          display: flex;
          gap: 5px;
          margin-bottom: 10px;
        }

        .star {
          color: var(--warning);
        }

        .feedback-message {
          margin-bottom: 15px;
          line-height: 1.5;
        }

        .feedback-tags {
          display: flex;
          gap: 10px;
          margin-bottom: 15px;
        }

        .tag {
          background: rgba(255, 255, 255, 0.1);
          padding: 5px 10px;
          border-radius: 20px;
          font-size: 0.8rem;
        }

        .feedback-actions {
          display: flex;
          gap: 10px;
        }

        .action-btn {
          background: rgba(255, 255, 255, 0.1);
          border: 1px solid var(--glass-border);
          color: var(--light);
          padding: 5px 10px;
          border-radius: 5px;
          cursor: pointer;
          font-size: 0.8rem;
          transition: all 0.3s;
        }

        .action-btn:hover {
          background: var(--accent);
          color: white;
        }

        .action-btn.responded {
          background: rgba(0, 204, 102, 0.2);
          color: var(--success);
        }

        .feedback-status {
          display: flex;
          align-items: center;
          gap: 5px;
          font-size: 0.8rem;
          margin-top: 10px;
        }

        .status-new {
          color: var(--warning);
        }

        .status-responded {
          color: var(--success);
        }

        .status-archived {
          color: var(--accent);
        }

        .pagination {
          display: flex;
          justify-content: center;
          margin-top: 20px;
          gap: 10px;
        }

        .page-btn {
          background: rgba(255, 255, 255, 0.1);
          border: 1px solid var(--glass-border);
          color: var(--light);
          width: 35px;
          height: 35px;
          border-radius: 5px;
          display: flex;
          align-items: center;
          justify-content: center;
          cursor: pointer;
          transition: all 0.3s;
        }

        .page-btn:hover,
        .page-btn.active {
          background: var(--accent);
          color: white;
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
          .feedback-stats {
            grid-template-columns: repeat(2, 1fr);
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
          .feedback-stats {
            grid-template-columns: 1fr;
          }
          
          .feedback-header-row {
            flex-direction: column;
          }
          
          .feedback-rating {
            margin-top: 10px;
          }
        }

        @media (max-width: 576px) {
          .feedback-filters {
            flex-wrap: wrap;
          }
          
          .feedback-actions {
            flex-direction: column;
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

        /* Scroll reveal styles */
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
        .feedback-list {
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
                <li><a href="admin-dash.html"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="admin-livestream.html"><i class="fas fa-broadcast-tower"></i> <span>Live Stream</span></a></li>
                <li><a href="admin-shows.html"><i class="fas fa-music"></i> <span>Shows</span></a></li>
                <li><a href="admin-djs.html"><i class="fas fa-user"></i> <span>DJs</span></a></li>
                <li><a href="admin-feedback.html" class="active"><i class="fas fa-comment-alt"></i> <span>Feedback</span></a></li>
                
                <li class="menu-label">Content</li>
                <li><a href="admin-podcast.html"><i class="fas fa-podcast"></i> <span>Podcasts</span></a></li>
                <li><a href="admin-playlist.html"><i class="fas fa-play-circle"></i> <span>Playlists</span></a></li>
                <li><a href="admin-news.html"><i class="fas fa-newspaper"></i> <span>News</span></a></li>
                
                <li class="menu-label">Analytics</li>
                <li><a href="admin-statistic.html"><i class="fas fa-chart-line"></i> <span>Statistics</span></a></li>
                <li><a href="admin-audience.html"><i class="fas fa-users"></i> <span>Audience</span></a></li>
                <li><a href="admin-revenue.html"><i class="fas fa-money-bill-wave"></i> <span>Revenue</span></a></li>
                
                <li class="menu-label">Settings</li>
                <li><a href="admin-settings.html"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                <li><a href="admin-admins.html"><i class="fas fa-user-shield"></i> <span>Admins</span></a></li>
                <li><a href="admin-logout.html"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Feedback Management</h1>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div>
                        <div>Admin User</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">Super Admin</div>
                    </div>
                </div>
            </div>
            
            <!-- Feedback Stats -->
            <div class="feedback-stats">
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon icon-total">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Feedback</h3>
                        <div class="stat-value">247</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 12% from last week
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon icon-new">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3>New Feedback</h3>
                        <div class="stat-value">18</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 5 new today
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon icon-responded">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Responded</h3>
                        <div class="stat-value">189</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 76% response rate
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon icon-rating">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Avg. Rating</h3>
                        <div class="stat-value">4.2</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 0.3 from last month
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Feedback Filters and Search -->
            <div class="feedback-filters">
                <button class="filter-btn active">All Feedback</button>
                <button class="filter-btn">New</button>
                <button class="filter-btn">Responded</button>
                <button class="filter-btn">High Priority</button>
                <button class="filter-btn">Archived</button>
            </div>
            
            <div class="search-box">
                <input type="text" placeholder="Search feedback...">
                <button><i class="fas fa-search"></i></button>
            </div>
            
            <!-- Feedback List -->
            <div class="feedback-list reveal-on-scroll">
                <!-- Feedback Item 1 -->
                <div class="feedback-item">
                    <div class="feedback-avatar">JS</div>
                    <div class="feedback-content">
                        <div class="feedback-header-row">
                            <div>
                                <div class="feedback-user">John Smith</div>
                                <div class="feedback-date">2 hours ago</div>
                            </div>
                            <div class="feedback-rating">
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                            </div>
                        </div>
                        <div class="feedback-message">
                            Love the new morning show format! The music selection has been excellent and the hosts have great chemistry. Would love to hear more 80s classics during the afternoon drive.
                        </div>
                        <div class="feedback-tags">
                            <div class="tag">Music</div>
                            <div class="tag">Show Format</div>
                        </div>
                        <div class="feedback-actions">
                            <button class="action-btn"><i class="fas fa-reply"></i> Respond</button>
                            <button class="action-btn"><i class="fas fa-archive"></i> Archive</button>
                            <button class="action-btn"><i class="fas fa-flag"></i> Flag</button>
                        </div>
                        <div class="feedback-status status-new">
                            <i class="fas fa-circle"></i> New
                        </div>
                    </div>
                </div>
                
                <!-- Feedback Item 2 -->
                <div class="feedback-item">
                    <div class="feedback-avatar">MJ</div>
                    <div class="feedback-content">
                        <div class="feedback-header-row">
                            <div>
                                <div class="feedback-user">Maria Johnson</div>
                                <div class="feedback-date">1 day ago</div>
                            </div>
                            <div class="feedback-rating">
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="feedback-message">
                            The audio quality during the live concert broadcast was poor at times. There were several instances of crackling and volume drops. Otherwise, great selection of events!
                        </div>
                        <div class="feedback-tags">
                            <div class="tag">Technical</div>
                            <div class="tag">Audio Quality</div>
                        </div>
                        <div class="feedback-actions">
                            <button class="action-btn responded"><i class="fas fa-check"></i> Responded</button>
                            <button class="action-btn"><i class="fas fa-archive"></i> Archive</button>
                        </div>
                        <div class="feedback-status status-responded">
                            <i class="fas fa-circle"></i> Responded
                        </div>
                    </div>
                </div>
                
                <!-- Feedback Item 3 -->
                <div class="feedback-item">
                    <div class="feedback-avatar">RP</div>
                    <div class="feedback-content">
                        <div class="feedback-header-row">
                            <div>
                                <div class="feedback-user">Robert Parker</div>
                                <div class="feedback-date">3 days ago</div>
                            </div>
                            <div class="feedback-rating">
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="far fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                        <div class="feedback-message">
                            Too many commercials during peak hours. I understand the need for ads, but the frequency is disrupting the listening experience. Consider reducing ad breaks during morning and evening drives.
                        </div>
                        <div class="feedback-tags">
                            <div class="tag">Advertising</div>
                            <div class="tag">Programming</div>
                        </div>
                        <div class="feedback-actions">
                            <button class="action-btn"><i class="fas fa-reply"></i> Respond</button>
                            <button class="action-btn"><i class="fas fa-archive"></i> Archive</button>
                            <button class="action-btn"><i class="fas fa-flag"></i> Flag</button>
                        </div>
                        <div class="feedback-status status-new">
                            <i class="fas fa-circle"></i> New
                        </div>
                    </div>
                </div>
                
                <!-- Feedback Item 4 -->
                <div class="feedback-item">
                    <div class="feedback-avatar">SG</div>
                    <div class="feedback-content">
                        <div class="feedback-header-row">
                            <div>
                                <div class="feedback-user">Sarah Green</div>
                                <div class="feedback-date">1 week ago</div>
                            </div>
                            <div class="feedback-rating">
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                                <i class="fas fa-star star"></i>
                            </div>
                        </div>
                        <div class="feedback-message">
                            The new DJ, Alex, is fantastic! Great energy and music knowledge. The Friday night electronic mix has become my weekly ritual. Please keep this format!
                        </div>
                        <div class="feedback-tags">
                            <div class="tag">DJ</div>
                            <div class="tag">Music</div>
                            <div class="tag">Positive</div>
                        </div>
                        <div class="feedback-actions">
                            <button class="action-btn responded"><i class="fas fa-check"></i> Responded</button>
                            <button class="action-btn"><i class="fas fa-archive"></i> Archive</button>
                        </div>
                        <div class="feedback-status status-responded">
                            <i class="fas fa-circle"></i> Responded
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="pagination">
                <div class="page-btn"><i class="fas fa-chevron-left"></i></div>
                <div class="page-btn active">1</div>
                <div class="page-btn">2</div>
                <div class="page-btn">3</div>
                <div class="page-btn">4</div>
                <div class="page-btn"><i class="fas fa-chevron-right"></i></div>
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

          // Filter buttons interaction
          const filterBtns = document.querySelectorAll(".filter-btn");
          filterBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              filterBtns.forEach((b) => b.classList.remove("active"));
              this.classList.add("active");
            });
          });

          // Action buttons functionality
          const actionBtns = document.querySelectorAll(".action-btn");
          actionBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              if (this.textContent.includes("Respond")) {
                this.innerhtml = '<i class="fas fa-check"></i> Responded';
                this.classList.add("responded");
                
                // Update status
                const statusElement = this.closest('.feedback-item').querySelector('.feedback-status');
                statusElement.innerhtml = '<i class="fas fa-circle"></i> Responded';
                statusElement.className = 'feedback-status status-responded';
              } else if (this.textContent.includes("Archive")) {
                this.closest('.feedback-item').style.opacity = '0.5';
                
                // Update status
                const statusElement = this.closest('.feedback-item').querySelector('.feedback-status');
                statusElement.innerhtml = '<i class="fas fa-circle"></i> Archived';
                statusElement.className = 'feedback-status status-archived';
              }
            });
          });

          // Pagination buttons
          const pageBtns = document.querySelectorAll(".page-btn");
          pageBtns.forEach((btn) => {
            btn.addEventListener("click", function () {
              if (!this.querySelector("i")) {
                pageBtns.forEach((b) => b.classList.remove("active"));
                this.classList.add("active");
              }
            });
          });

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
    </script>
</body>
</html>