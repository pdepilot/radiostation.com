<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darling FM - Playlist Management</title>
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

        /* Playlist-specific styles */
        .playlist-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .playlist-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .playlist-stat-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .playlist-stat-card::before {
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

        .playlist-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .playlist-stat-card:hover::before {
            opacity: 1;
        }

        .playlist-stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
            float: left;
        }

        .playlist-stat-info {
            overflow: hidden;
        }

        .playlist-stat-info h3 {
            font-size: 0.9rem;
            color: var(--light);
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .playlist-stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .playlist-stat-change {
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

        .playlist-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-family: "Exo 2", sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
        }

        .btn-primary:hover {
            background: var(--accent-glow);
            box-shadow: var(--neon-glow);
        }

        .btn-secondary {
            background: var(--glass);
            color: var(--light);
            border: 1px solid var(--glass-border);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .playlist-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .playlist-list-container, .playlist-details-container {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            border: 1px solid var(--glass-border);
            overflow: hidden;
        }

        .playlist-list-header, .playlist-details-header {
            padding: 20px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .playlist-list-header h3, .playlist-details-header h3 {
            font-size: 1.2rem;
            color: var(--highlight);
        }

        .playlist-search {
            display: flex;
            gap: 10px;
            padding: 0 20px 20px;
        }

        .search-input {
            flex: 1;
            padding: 10px 15px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            color: var(--light);
            font-family: "Exo 2", sans-serif;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--accent);
        }

        .playlist-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .playlist-item {
            display: flex;
            padding: 15px 20px;
            border-bottom: 1px solid var(--glass-border);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .playlist-item:hover {
            background: rgba(255, 0, 0, 0.1);
        }

        .playlist-item.active {
            background: rgba(255, 0, 0, 0.2);
            border-left: 3px solid var(--accent);
        }

        .playlist-cover {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            margin-right: 15px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .playlist-cover-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 10px;
        }

        .playlist-cover:hover .playlist-cover-overlay {
            opacity: 1;
        }

        .playlist-info {
            flex: 1;
        }

        .playlist-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .playlist-meta {
            font-size: 0.8rem;
            opacity: 0.7;
            display: flex;
            gap: 15px;
        }

        .playlist-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .status-active {
            background: rgba(0, 204, 102, 0.2);
            color: var(--success);
        }

        .status-draft {
            background: rgba(255, 170, 0, 0.2);
            color: var(--warning);
        }

        .playlist-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.1);
            color: var(--light);
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            background: var(--accent);
        }

        .playlist-details {
            padding: 20px;
        }

        .playlist-cover-large {
            width: 100%;
            height: 200px;
            border-radius: 10px;
            background-size: cover;
            background-position: center;
            margin-bottom: 20px;
            position: relative;
        }

        .playlist-cover-large-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
            padding: 20px;
            border-radius: 0 0 10px 10px;
        }

        .playlist-detail-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .playlist-detail-creator {
            font-size: 1rem;
            opacity: 0.8;
        }

        .playlist-detail-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }

        .playlist-detail-stat {
            text-align: center;
            padding: 15px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 8px;
        }

        .playlist-detail-stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .playlist-detail-stat-label {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .playlist-tracks {
            margin-top: 20px;
        }

        .track-item {
            display: flex;
            padding: 15px;
            border-bottom: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .track-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .track-play {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .track-play:hover {
            background: var(--accent-glow);
            box-shadow: var(--neon-glow);
        }

        .track-info {
            flex: 1;
        }

        .track-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .track-meta {
            font-size: 0.8rem;
            opacity: 0.7;
            display: flex;
            gap: 15px;
        }

        .track-duration {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .track-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal {
            background: var(--glass);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            border: 1px solid var(--glass-border);
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal {
            transform: translateY(0);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            font-size: 1.2rem;
            color: var(--highlight);
        }

        .modal-close {
            background: none;
            border: none;
            color: var(--light);
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            color: var(--accent);
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border-radius: 8px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            color: var(--light);
            font-family: "Exo 2", sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
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
            .playlist-content {
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
            .playlist-stats {
                grid-template-columns: 1fr 1fr;
            }
            
            .playlist-controls {
                flex-wrap: wrap;
            }
        }

        @media (max-width: 576px) {
            .playlist-stats {
                grid-template-columns: 1fr;
            }

            .playlist-detail-stats {
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

        /* Apply initial animation to cards */
        .playlist-stat-card,
        .playlist-list-container,
        .playlist-details-container {
            animation: fadeIn 0.5s ease forwards;
        }

        .playlist-stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }
        .playlist-stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }
        .playlist-stat-card:nth-child(4) {
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
                <li><a href="admin-dash.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="admin-livestream.php"><i class="fas fa-broadcast-tower"></i> <span>Live Stream</span></a></li>
                <li><a href="admin-shows.php"><i class="fas fa-music"></i> <span>Shows</span></a></li>
                <li><a href="admin-djs.php"><i class="fas fa-user"></i> <span>DJs</span></a></li>
                <li><a href="admin-feedback.php" class="active"><i class="fas fa-comment-alt"></i> <span>Feedback</span></a></li>
                
                <li class="menu-label">Content</li>
                <li><a href="admin-podcast.php"><i class="fas fa-podcast"></i> <span>Podcasts</span></a></li>
                <li><a href="admin-playlist.php" class="active"><i class="fas fa-play-circle"></i> <span>Playlists</span></a></li>
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
                <h1>Playlist Management</h1>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div>
                        <div>Admin User</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">Super Admin</div>
                    </div>
                </div>
            </div>
            
            <!-- Playlist Stats -->
            <div class="playlist-stats">
                <div class="playlist-stat-card reveal-on-scroll">
                    <div class="playlist-stat-icon" style="background: rgba(0, 255, 0, 0.2); color: var(--live-green);">
                        <i class="fas fa-list"></i>
                    </div>
                    <div class="playlist-stat-info">
                        <h3>Total Playlists</h3>
                        <div class="playlist-stat-value">68</div>
                        <div class="playlist-stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 8 new this month
                        </div>
                    </div>
                </div>
                
                <div class="playlist-stat-card reveal-on-scroll">
                    <div class="playlist-stat-icon" style="background: rgba(0, 204, 102, 0.2); color: var(--success);">
                        <i class="fas fa-music"></i>
                    </div>
                    <div class="playlist-stat-info">
                        <h3>Total Tracks</h3>
                        <div class="playlist-stat-value">1,247</div>
                        <div class="playlist-stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 42 this week
                        </div>
                    </div>
                </div>
                
                <div class="playlist-stat-card reveal-on-scroll">
                    <div class="playlist-stat-icon" style="background: rgba(255, 170, 0, 0.2); color: var(--warning);">
                        <i class="fas fa-headphones"></i>
                    </div>
                    <div class="playlist-stat-info">
                        <h3>Total Plays</h3>
                        <div class="playlist-stat-value">2.8M</div>
                        <div class="playlist-stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 12.7% from last month
                        </div>
                    </div>
                </div>
                
                <div class="playlist-stat-card reveal-on-scroll">
                    <div class="playlist-stat-icon" style="background: rgba(0, 153, 255, 0.2); color: var(--info);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="playlist-stat-info">
                        <h3>Avg. Duration</h3>
                        <div class="playlist-stat-value">3.2h</div>
                        <div class="playlist-stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 0.4h from last quarter
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Playlist Controls -->
            <div class="playlist-controls">
                <button class="btn btn-primary" id="addPlaylistBtn">
                    <i class="fas fa-plus"></i> Create New Playlist
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-upload"></i> Import Playlist
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Refresh Data
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-download"></i> Export Reports
                </button>
            </div>
            
            <!-- Playlist Content -->
            <div class="playlist-content">
                <!-- Playlist List -->
                <div class="playlist-list-container reveal-on-scroll">
                    <div class="playlist-list-header">
                        <h3>All Playlists</h3>
                        <div class="playlist-actions">
                            <button class="action-btn"><i class="fas fa-filter"></i></button>
                            <button class="action-btn"><i class="fas fa-sort"></i></button>
                        </div>
                    </div>
                    <div class="playlist-search">
                        <input type="text" class="search-input" placeholder="Search playlists...">
                        <button class="action-btn"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="playlist-list">
                        <!-- Playlist items will be dynamically populated -->
                    </div>
                </div>
                
                <!-- Playlist Details -->
                <div class="playlist-details-container reveal-on-scroll">
                    <div class="playlist-details-header">
                        <h3>Playlist Details</h3>
                        <div class="playlist-actions">
                            <button class="action-btn"><i class="fas fa-edit"></i></button>
                            <button class="action-btn"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="playlist-details">
                        <div class="playlist-cover-large" style="background-image: url('https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80')">
                            <div class="playlist-cover-large-overlay">
                                <div class="playlist-detail-title">Cyberpunk Energy</div>
                                <div class="playlist-detail-creator">Created by DJ Nova</div>
                            </div>
                        </div>
                        
                        <div class="playlist-detail-stats">
                            <div class="playlist-detail-stat">
                                <div class="playlist-detail-stat-value">24</div>
                                <div class="playlist-detail-stat-label">Tracks</div>
                            </div>
                            <div class="playlist-detail-stat">
                                <div class="playlist-detail-stat-value">3.2h</div>
                                <div class="playlist-detail-stat-label">Duration</div>
                            </div>
                            <div class="playlist-detail-stat">
                                <div class="playlist-detail-stat-value">245K</div>
                                <div class="playlist-detail-stat-label">Plays</div>
                            </div>
                            <div class="playlist-detail-stat">
                                <div class="playlist-detail-stat-value">92%</div>
                                <div class="playlist-detail-stat-label">Completion</div>
                            </div>
                        </div>
                        
                        <p>A high-energy mix of synthwave, electronic, and future bass tracks perfect for late-night coding sessions or intense gaming marathons.</p>
                        
                        <div class="playlist-tracks">
                            <h4 style="margin-bottom: 15px;">Track List</h4>
                            <div class="track-item">
                                <div class="track-play">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="track-info">
                                    <div class="track-title">Neon Dreams</div>
                                    <div class="track-meta">
                                        <span>The Midnight</span>
                                        <span class="track-duration"><i class="far fa-clock"></i> 4:32</span>
                                    </div>
                                </div>
                                <div class="track-actions">
                                    <button class="action-btn"><i class="fas fa-heart"></i></button>
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </div>
                            <div class="track-item">
                                <div class="track-play">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="track-info">
                                    <div class="track-title">Digital Rain</div>
                                    <div class="track-meta">
                                        <span>Perturbator</span>
                                        <span class="track-duration"><i class="far fa-clock"></i> 5:18</span>
                                    </div>
                                </div>
                                <div class="track-actions">
                                    <button class="action-btn"><i class="fas fa-heart"></i></button>
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </div>
                            <div class="track-item">
                                <div class="track-play">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="track-info">
                                    <div class="track-title">Tokyo Night</div>
                                    <div class="track-meta">
                                        <span>Kavinsky</span>
                                        <span class="track-duration"><i class="far fa-clock"></i> 3:45</span>
                                    </div>
                                </div>
                                <div class="track-actions">
                                    <button class="action-btn"><i class="fas fa-heart"></i></button>
                                    <button class="action-btn"><i class="fas fa-ellipsis-v"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Playlist Modal -->
    <div class="modal-overlay" id="addPlaylistModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Create New Playlist</h3>
                <button class="modal-close" id="closeModalBtn">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addPlaylistForm">
                    <div class="form-group">
                        <label class="form-label" for="playlistTitle">Playlist Title</label>
                        <input type="text" class="form-control" id="playlistTitle" placeholder="Enter playlist title">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="playlistDescription">Description</label>
                        <textarea class="form-control" id="playlistDescription" rows="4" placeholder="Enter playlist description"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="playlistGenre">Genre</label>
                        <select class="form-control" id="playlistGenre">
                            <option value="">Select genre</option>
                            <option value="electronic">Electronic</option>
                            <option value="rock">Rock</option>
                            <option value="hiphop">Hip Hop</option>
                            <option value="pop">Pop</option>
                            <option value="jazz">Jazz</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="playlistCover">Cover Image</label>
                        <input type="file" class="form-control" id="playlistCover">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="playlistVisibility">Visibility</label>
                        <select class="form-control" id="playlistVisibility">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                            <option value="unlisted">Unlisted</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" id="cancelBtn">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Playlist</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sample playlist data
        const playlists = [
            {
                id: 1,
                title: "Cyberpunk Energy",
                creator: "DJ Nova",
                cover: "https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
                tracks: 24,
                duration: "3.2h",
                plays: 245000,
                status: "active",
                genre: "Electronic"
            },
            {
                id: 2,
                title: "Morning Motivation",
                creator: "Sarah Miles",
                cover: "https://images.unsplash.com/photo-1511379938547-c1f69419868d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
                tracks: 18,
                duration: "2.1h",
                plays: 187500,
                status: "active",
                genre: "Pop"
            },
            {
                id: 3,
                title: "Deep Focus",
                creator: "Alex Chen",
                cover: "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
                tracks: 32,
                duration: "4.5h",
                plays: 321000,
                status: "active",
                genre: "Ambient"
            },
            {
                id: 4,
                title: "Weekend Vibes",
                creator: "Lena Cruz",
                cover: "https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
                tracks: 22,
                duration: "2.8h",
                plays: 198000,
                status: "draft",
                genre: "Hip Hop"
            },
            {
                id: 5,
                title: "Retro Rewind",
                creator: "Mike Johnson",
                cover: "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80",
                tracks: 28,
                duration: "3.6h",
                plays: 267000,
                status: "active",
                genre: "Rock"
            }
        ];

        // DOM Elements
        const hamburgerMenu = document.getElementById("hamburgerMenu");
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");
        const addPlaylistBtn = document.getElementById("addPlaylistBtn");
        const addPlaylistModal = document.getElementById("addPlaylistModal");
        const closeModalBtn = document.getElementById("closeModalBtn");
        const cancelBtn = document.getElementById("cancelBtn");
        const addPlaylistForm = document.getElementById("addPlaylistForm");
        const playlistList = document.querySelector(".playlist-list");

        // Mobile hamburger menu functionality
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

        // Modal functionality
        addPlaylistBtn.addEventListener("click", function () {
            addPlaylistModal.classList.add("active");
        });

        closeModalBtn.addEventListener("click", function () {
            addPlaylistModal.classList.remove("active");
        });

        cancelBtn.addEventListener("click", function () {
            addPlaylistModal.classList.remove("active");
        });

        // Close modal when clicking outside
        addPlaylistModal.addEventListener("click", function (e) {
            if (e.target === addPlaylistModal) {
                addPlaylistModal.classList.remove("active");
            }
        });

        // Form submission
        addPlaylistForm.addEventListener("submit", function (e) {
            e.preventDefault();
            // In a real app, you would send the data to a server here
            alert("Playlist created successfully!");
            addPlaylistModal.classList.remove("active");
            addPlaylistForm.reset();
        });

        // Populate playlist list
        function populatePlaylistList() {
            playlistList.innerhtml = "";
            
            playlists.forEach(playlist => {
                const playlistItem = document.createElement("div");
                playlistItem.className = "playlist-item";
                playlistItem.innerhtml = `
                    <div class="playlist-cover" style="background-image: url('${playlist.cover}')">
                        <div class="playlist-cover-overlay">
                            <i class="fas fa-play"></i>
                        </div>
                    </div>
                    <div class="playlist-info">
                        <div class="playlist-title">${playlist.title}</div>
                        <div class="playlist-meta">
                            <span>${playlist.creator}</span>
                            <span>${playlist.tracks} tracks</span>
                            <span class="playlist-status status-${playlist.status}">${playlist.status}</span>
                        </div>
                    </div>
                    <div class="playlist-actions">
                        <button class="action-btn"><i class="fas fa-edit"></i></button>
                        <button class="action-btn"><i class="fas fa-trash"></i></button>
                    </div>
                `;
                
                playlistList.appendChild(playlistItem);
            });
        }

        // Scroll reveal functionality
        const revealElements = document.querySelectorAll('.reveal-on-scroll');
        
        function checkReveal() {
            const windowHeight = window.innerHeight;
            const revealPoint = 150;
            
            revealElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                
                if (elementTop < windowHeight - revealPoint) {
                    element.classList.add('revealed');
                }
            });
        }
        
        // Initial check
        checkReveal();
        
        // Check on scroll
        window.addEventListener('scroll', checkReveal);

        // Initialize the page
        document.addEventListener("DOMContentLoaded", function () {
            populatePlaylistList();
        });
    </script>
</body>
</html>