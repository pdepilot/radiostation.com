<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="icon" type="image/png" href="./images/REAL_LOGO-removebg-preview.png" />
    <title>Darling FM - Live Stream Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
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
            background: radial-gradient(ellipse at center, var(--secondary) 0%, var(--primary) 70%);
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

        /* Mobile Header */
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

        .sidebar-menu a:hover, .sidebar-menu a.active {
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

        /* Sidebar Overlay */
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

        /* Live Stream Stats */
        .stream-stats {
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
            background: linear-gradient(135deg, rgba(255, 0, 0, 0.1), rgba(255, 255, 255, 0.05));
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

        .icon-bitrate {
            background: rgba(0, 204, 102, 0.2);
            color: var(--success);
        }

        .icon-quality {
            background: rgba(255, 170, 0, 0.2);
            color: var(--warning);
        }

        .icon-uptime {
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

        /* Stream Controls */
        .stream-controls {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .control-panel {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid var(--glass-border);
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .panel-header h3 {
            font-size: 1.3rem;
            color: var(--highlight);
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .live-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--live-green);
            box-shadow: var(--live-glow);
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }

        .control-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }

        .control-btn {
            padding: 15px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--live-green), #00cc00);
            color: var(--dark);
            box-shadow: 0 0 15px rgba(0, 255, 0, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0, 255, 0, 0.6);
        }

        .btn-secondary {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            color: var(--light);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent);
        }

        .btn-danger {
            background: linear-gradient(45deg, var(--accent), var(--accent-glow));
            color: white;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 0, 0, 0.6);
        }

        .stream-settings {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .setting-group {
            margin-bottom: 15px;
        }

        .setting-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--highlight);
            font-size: 0.9rem;
        }

        .setting-control {
            width: 100%;
            padding: 12px 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: var(--light);
            font-size: 0.9rem;
        }

        .setting-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
        }

        /* Stream Preview */
        .stream-preview {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid var(--glass-border);
            display: flex;
            flex-direction: column;
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .preview-header h3 {
            font-size: 1.3rem;
            color: var(--highlight);
        }

        .preview-container {
            flex: 1;
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-placeholder {
            text-align: center;
            color: var(--light);
            opacity: 0.7;
        }

        .preview-placeholder i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: var(--accent);
        }

        /* Active Streams */
        .active-streams {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid var(--glass-border);
            margin-bottom: 30px;
        }

        .streams-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .streams-header h3 {
            font-size: 1.3rem;
            color: var(--highlight);
        }

        .streams-list {
            display: grid;
            gap: 15px;
        }

        .stream-item {
            display: flex;
            align-items: center;
            padding: 15px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
            border-left: 3px solid var(--live-green);
        }

        .stream-avatar {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            background-size: cover;
            background-position: center;
            margin-right: 15px;
            position: relative;
        }

        .stream-avatar::after {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 12px;
            background: linear-gradient(45deg, var(--live-green), transparent, var(--live-green));
            z-index: -1;
            animation: rotate 3s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .stream-details {
            flex: 1;
        }

        .stream-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stream-host {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .stream-meta {
            display: flex;
            gap: 15px;
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .stream-actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid var(--glass-border);
            color: var(--light);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background: var(--accent);
            transform: scale(1.1);
        }

        /* Stream Analytics */
        .stream-analytics {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .analytics-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid var(--glass-border);
        }

        .analytics-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .analytics-header h3 {
            font-size: 1.3rem;
            color: var(--highlight);
        }

        .chart-container {
            height: 250px;
            position: relative;
        }

        .analytics-list {
            list-style: none;
        }

        .analytics-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--glass-border);
        }

        .analytics-item:last-child {
            border-bottom: none;
        }

        .analytics-label {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .analytics-value {
            font-weight: 600;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
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

        /* Media Queries */
        @media (max-width: 1200px) {
            .stream-controls {
                grid-template-columns: 1fr;
            }
            
            .stream-analytics {
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
            
            .control-buttons {
                grid-template-columns: 1fr;
            }
            
            .stream-settings {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .stream-stats {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .stream-stats {
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
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .stat-card, .control-panel, .stream-preview, .active-streams, .analytics-card, .action-card {
            animation: fadeIn 0.5s ease forwards;
        }

        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.2s; }
        .stat-card:nth-child(4) { animation-delay: 0.3s; }
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
    
    <!-- Sidebar Overlay -->
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
                <li><a href="admin-livestream.php" class="active"><i class="fas fa-broadcast-tower"></i> <span>Live Stream</span></a></li>
                <li><a href="admin-show.php"><i class="fas fa-music"></i> <span>Shows</span></a></li>
                <li><a href="admin-djs.php"><i class="fas fa-user"></i> <span>DJs</span></a></li>
                <li><a href="admin-feedback.php" class="active"><i class="fas fa-comment-alt"></i> <span>Feedback</span></a></li>
                
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
                <h1>Live Stream Management</h1>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div>
                        <div>Admin User</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">Stream Manager</div>
                    </div>
                </div>
            </div>
            
            <!-- Stream Stats -->
            <div class="stream-stats">
                <div class="stat-card">
                    <div class="stat-icon icon-listeners">
                        <i class="fas fa-headphones"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Active Listeners</h3>
                        <div class="stat-value">8,427</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 15.3% from yesterday
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon icon-bitrate">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Stream Bitrate</h3>
                        <div class="stat-value">320 kbps</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-check"></i> Optimal
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon icon-quality">
                        <i class="fas fa-signal"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Stream Quality</h3>
                        <div class="stat-value">98.7%</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-check"></i> Excellent
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon icon-uptime">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Uptime</h3>
                        <div class="stat-value">99.9%</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-check"></i> Stable
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stream Controls -->
            <div class="stream-controls">
                <div class="control-panel">
                    <div class="panel-header">
                        <h3>Stream Controls</h3>
                        <div class="live-indicator">
                            <div class="live-dot"></div>
                            <span>LIVE NOW</span>
                        </div>
                    </div>
                    
                    <div class="control-buttons">
                        <button class="control-btn btn-primary">
                            <i class="fas fa-play"></i> Start Stream
                        </button>
                        <button class="control-btn btn-secondary">
                            <i class="fas fa-pause"></i> Pause Stream
                        </button>
                        <button class="control-btn btn-secondary">
                            <i class="fas fa-sync"></i> Restart Stream
                        </button>
                        <button class="control-btn btn-danger">
                            <i class="fas fa-stop"></i> Stop Stream
                        </button>
                    </div>
                    
                    <div class="stream-settings">
                        <div class="setting-group">
                            <label for="streamTitle">Stream Title</label>
                            <input type="text" id="streamTitle" class="setting-control" value="Morning Show with DJ Alex">
                        </div>
                        <div class="setting-group">
                            <label for="streamQuality">Stream Quality</label>
                            <select id="streamQuality" class="setting-control">
                                <option>High (320 kbps)</option>
                                <option selected>Standard (192 kbps)</option>
                                <option>Low (128 kbps)</option>
                            </select>
                        </div>
                        <div class="setting-group">
                            <label for="streamFormat">Audio Format</label>
                            <select id="streamFormat" class="setting-control">
                                <option selected>MP3</option>
                                <option>AAC</option>
                                <option>OGG</option>
                            </select>
                        </div>
                        <div class="setting-group">
                            <label for="streamServer">Stream Server</label>
                            <select id="streamServer" class="setting-control">
                                <option selected>Primary Server (US East)</option>
                                <option>Backup Server (EU West)</option>
                                <option>CDN Network</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="stream-preview">
                    <div class="preview-header">
                        <h3>Stream Preview</h3>
                        <div class="live-indicator">
                            <div class="live-dot"></div>
                            <span>PREVIEW</span>
                        </div>
                    </div>
                    <div class="preview-container">
                        <div class="preview-placeholder">
                            <i class="fas fa-broadcast-tower"></i>
                            <p>Stream Preview</p>
                            <p style="font-size: 0.9rem; margin-top: 10px;">Live audio visualization will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Active Streams -->
            <div class="active-streams">
                <div class="streams-header">
                    <h3>Active Streams</h3>
                    <button class="control-btn btn-secondary" style="padding: 8px 15px;">
                        <i class="fas fa-plus"></i> New Stream
                    </button>
                </div>
                <div class="streams-list">
                    <div class="stream-item">
                        <div class="stream-avatar" style="background-image: url('https://res.cloudinary.com/dl4hjr1p2/image/upload/v1763062522/WhatsApp_Image_2025-11-12_at_15.35.16_d35f6e83_pv497n.jpg')"></div>
                        <div class="stream-details">
                            <div class="stream-name">Morning Show</div>
                            <div class="stream-host">DJ Alex</div>
                            <div class="stream-meta">
                                <span><i class="fas fa-headphones"></i> 4.2K listeners</span>
                                <span><i class="fas fa-clock"></i> 2h 15m</span>
                                <span><i class="fas fa-signal"></i> 320 kbps</span>
                            </div>
                        </div>
                        <div class="stream-actions">
                            <button class="action-btn" title="Monitor Stream">
                                <i class="fas fa-chart-bar"></i>
                            </button>
                            <button class="action-btn" title="Configure">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button class="action-btn" title="Stop Stream">
                                <i class="fas fa-stop"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="stream-item">
                        <div class="stream-avatar" style="background-image: url('https://res.cloudinary.com/dl4hjr1p2/image/upload/v1762957223/OAP1_gtmlhf.jpg')"></div>
                        <div class="stream-details">
                            <div class="stream-name">Afternoon Drive</div>
                            <div class="stream-host">Sarah Miles</div>
                            <div class="stream-meta">
                                <span><i class="fas fa-headphones"></i> 3.8K listeners</span>
                                <span><i class="fas fa-clock"></i> 45m</span>
                                <span><i class="fas fa-signal"></i> 320 kbps</span>
                            </div>
                        </div>
                        <div class="stream-actions">
                            <button class="action-btn" title="Monitor Stream">
                                <i class="fas fa-chart-bar"></i>
                            </button>
                            <button class="action-btn" title="Configure">
                                <i class="fas fa-cog"></i>
                            </button>
                            <button class="action-btn" title="Stop Stream">
                                <i class="fas fa-stop"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stream Analytics -->
            <div class="stream-analytics">
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>Listener Analytics</h3>
                        <div class="chart-actions">
                            <button class="chart-btn">Day</button>
                            <button class="chart-btn active">Week</button>
                            <button class="chart-btn">Month</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <!-- Chart would be rendered here with a library like Chart.js -->
                        <div style="display: flex; align-items: flex-end; justify-content: space-between; height: 100%; padding: 20px 0;">
                            <div style="background: var(--accent); width: 12%; height: 40%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 65%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 85%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 95%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 75%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 60%; border-radius: 5px 5px 0 0;"></div>
                            <div style="background: var(--accent); width: 12%; height: 45%; border-radius: 5px 5px 0 0;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-card">
                    <div class="analytics-header">
                        <h3>Stream Health</h3>
                        <div class="live-indicator">
                            <div class="live-dot"></div>
                            <span>HEALTHY</span>
                        </div>
                    </div>
                    <ul class="analytics-list">
                        <li class="analytics-item">
                            <div class="analytics-label">
                                <i class="fas fa-server" style="color: var(--success);"></i>
                                <span>Server Status</span>
                            </div>
                            <div class="analytics-value" style="color: var(--success);">Optimal</div>
                        </li>
                        <li class="analytics-item">
                            <div class="analytics-label">
                                <i class="fas fa-network-wired" style="color: var(--success);"></i>
                                <span>Connection</span>
                            </div>
                            <div class="analytics-value" style="color: var(--success);">Stable</div>
                        </li>
                        <li class="analytics-item">
                            <div class="analytics-label">
                                <i class="fas fa-microphone" style="color: var(--warning);"></i>
                                <span>Audio Input</span>
                            </div>
                            <div class="analytics-value" style="color: var(--warning);">Good</div>
                        </li>
                        <li class="analytics-item">
                            <div class="analytics-label">
                                <i class="fas fa-hdd" style="color: var(--success);"></i>
                                <span>Storage</span>
                            </div>
                            <div class="analytics-value" style="color: var(--success);">85% Free</div>
                        </li>
                        <li class="analytics-item">
                            <div class="analytics-label">
                                <i class="fas fa-bolt" style="color: var(--success);"></i>
                                <span>CPU Load</span>
                            </div>
                            <div class="analytics-value" style="color: var(--success);">42%</div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <h3>Stream Settings</h3>
                    <p>Configure stream parameters</p>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>Stream History</h3>
                    <p>View past broadcasts</p>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>Auto DJ</h3>
                    <p>Configure automated streaming</p>
                </div>
                
                <div class="action-card">
                    <div class="action-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Security</h3>
                    <p>Stream protection settings</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile hamburger menu functionality
        document.addEventListener('DOMContentLoaded', function() {
            const hamburgerMenu = document.getElementById('hamburgerMenu');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Toggle sidebar on hamburger click
            hamburgerMenu.addEventListener('click', function() {
                this.classList.toggle('active');
                sidebar.classList.toggle('active');
                sidebarOverlay.classList.toggle('active');
            });
            
            // Close sidebar when overlay is clicked
            sidebarOverlay.addEventListener('click', function() {
                hamburgerMenu.classList.remove('active');
                sidebar.classList.remove('active');
                this.classList.remove('active');
            });
            
            // Stream control buttons
            const controlButtons = document.querySelectorAll('.control-btn');
            controlButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const action = this.textContent.trim();
                    alert(`Stream action: ${action}`);
                    
                    // Simulate stream state change
                    if (action.includes('Start')) {
                        document.querySelector('.live-indicator span').textContent = 'LIVE NOW';
                        document.querySelector('.preview-placeholder i').className = 'fas fa-broadcast-tower';
                        document.querySelector('.preview-placeholder i').style.color = 'var(--live-green)';
                    } else if (action.includes('Stop')) {
                        document.querySelector('.live-indicator span').textContent = 'OFFLINE';
                        document.querySelector('.preview-placeholder i').className = 'fas fa-broadcast-tower';
                        document.querySelector('.preview-placeholder i').style.color = 'var(--accent)';
                    }
                });
            });
            
            // Chart buttons interaction
            const chartBtns = document.querySelectorAll('.chart-btn');
            chartBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    chartBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });
            
            // Simulate real-time listener count update
            setInterval(() => {
                const listenerElement = document.querySelector('.stat-card:nth-child(1) .stat-value');
                const currentCount = parseInt(listenerElement.textContent.replace(',', ''));
                const randomChange = Math.floor(Math.random() * 100) - 30;
                const newCount = Math.max(8000, currentCount + randomChange);
                listenerElement.textContent = newCount.toLocaleString();
            }, 5000);
            
            // Action cards interaction
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach(card => {
                card.addEventListener('click', function() {
                    const action = this.querySelector('h3').textContent;
                    alert(`Opening: ${action}`);
                });
            });
        });
    </script>
</body>
</html>