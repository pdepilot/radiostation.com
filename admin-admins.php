<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darling FM - Admin Management</title>
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
            --revenue-purple: #8a2be2;
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

        /* Admin Management Styles */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .admin-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            border: none;
            font-family: "Exo 2", sans-serif;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
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

        .btn-danger {
            background: rgba(255, 0, 0, 0.2);
            color: var(--accent);
            border: 1px solid rgba(255, 0, 0, 0.3);
        }

        .btn-danger:hover {
            background: rgba(255, 0, 0, 0.3);
        }

        .stats-overview {
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
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .stat-icon {
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

        .stat-info {
            overflow: hidden;
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

        .admin-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .admin-list-container, .admin-details {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 25px;
            border: 1px solid var(--glass-border);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--glass-border);
        }

        .section-header h2 {
            font-size: 1.5rem;
            color: var(--highlight);
        }

        .search-box {
            display: flex;
            margin-bottom: 20px;
        }

        .search-input {
            flex: 1;
            padding: 12px 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 8px 0 0 8px;
            color: var(--light);
            font-family: "Exo 2", sans-serif;
        }

        .search-btn {
            padding: 0 20px;
            background: var(--accent);
            border: none;
            border-radius: 0 8px 8px 0;
            color: white;
            cursor: pointer;
        }

        .admin-list {
            list-style: none;
            max-height: 500px;
            overflow-y: auto;
        }

        .admin-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid var(--glass-border);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .admin-item:hover, .admin-item.active {
            background: rgba(255, 255, 255, 0.05);
        }

        .admin-item:last-child {
            border-bottom: none;
        }

        .admin-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
            font-size: 1.2rem;
        }

        .admin-info {
            flex: 1;
        }

        .admin-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .admin-role {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 3px;
        }

        .admin-status {
            font-size: 0.7rem;
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            background: rgba(0, 204, 102, 0.2);
            color: var(--success);
        }

        .admin-status.offline {
            background: rgba(255, 0, 0, 0.2);
            color: var(--accent);
        }

        .admin-actions-small {
            display: flex;
            gap: 5px;
        }

        .action-btn {
            background: none;
            border: none;
            color: var(--light);
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .action-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .action-btn.delete:hover {
            background: rgba(255, 0, 0, 0.2);
            color: var(--accent);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--highlight);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: var(--light);
            font-family: "Exo 2", sans-serif;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 2px rgba(255, 0, 0, 0.2);
        }

        .form-text {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
            margin-top: 5px;
        }

        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .checkbox-group input {
            margin-right: 10px;
            width: 18px;
            height: 18px;
        }

        .checkbox-group label {
            cursor: pointer;
        }

        .activity-log {
            margin-top: 30px;
        }

        .activity-list {
            list-style: none;
            max-height: 300px;
            overflow-y: auto;
        }

        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--glass-border);
            font-size: 0.9rem;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-time {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.8rem;
            margin-top: 3px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--glass);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid var(--glass-border);
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--glass-border);
        }

        .modal-header h2 {
            font-size: 1.5rem;
            color: var(--highlight);
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--light);
            font-size: 1.5rem;
            cursor: pointer;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 25px;
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

            .admin-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .permissions-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-overview {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 15px;
            }
            
            .stats-overview {
                grid-template-columns: 1fr;
            }
            
            .admin-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .admin-actions .btn {
                width: 100%;
                justify-content: center;
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
                <li><a href="admin-playlist.php"><i class="fas fa-play-circle"></i> <span>Playlists</span></a></li>
                <li><a href="admin-news.php"><i class="fas fa-newspaper"></i> <span>News</span></a></li>
                
                <li class="menu-label">Analytics</li>
                <li><a href="admin-statistic.php"><i class="fas fa-chart-line"></i> <span>Statistics</span></a></li>
                <li><a href="admin-audience.php"><i class="fas fa-users"></i> <span>Audience</span></a></li>
                <li><a href="admin-revenue.php"><i class="fas fa-money-bill-wave"></i> <span>Revenue</span></a></li>
                
                <li class="menu-label">Settings</li>
                <li><a href="admin-settings.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                <li><a href="admin-admins.php" class="active"><i class="fas fa-user-shield"></i> <span>Admins</span></a></li>
                <li><a href="admin-logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Admin Management</h1>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div>
                        <div>Admin User</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">Super Admin</div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Stats -->
            <div class="stats-overview">
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon" style="background: rgba(0, 204, 102, 0.2); color: var(--success);">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Admins</h3>
                        <div class="stat-value">8</div>
                        <div class="stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 2 new this month
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon" style="background: rgba(255, 170, 0, 0.2); color: var(--warning);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Active Now</h3>
                        <div class="stat-value">3</div>
                        <div class="stat-change">
                            <i class="fas fa-circle" style="color: var(--success); font-size: 0.6rem;"></i> 3 online
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon" style="background: rgba(0, 153, 255, 0.2); color: var(--info);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Super Admins</h3>
                        <div class="stat-value">2</div>
                        <div class="stat-change">
                            Full system access
                        </div>
                    </div>
                </div>
                
                <div class="stat-card reveal-on-scroll">
                    <div class="stat-icon" style="background: rgba(138, 43, 226, 0.2); color: var(--revenue-purple);">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Last Activity</h3>
                        <div class="stat-value">2 min ago</div>
                        <div class="stat-change">
                            <i class="fas fa-sync-alt"></i> Updated recently
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Header -->
            <div class="admin-header">
                <h2>Administrator Accounts</h2>
                <div class="admin-actions">
                    <button class="btn btn-secondary">
                        <i class="fas fa-download"></i> Export List
                    </button>
                    <button class="btn btn-primary" id="addAdminBtn">
                        <i class="fas fa-plus"></i> Add New Admin
                    </button>
                </div>
            </div>
            
            <!-- Admin Content -->
            <div class="admin-content">
                <!-- Admin List -->
                <div class="admin-list-container reveal-on-scroll">
                    <div class="section-header">
                        <h2>All Administrators</h2>
                    </div>
                    
                    <div class="search-box">
                        <input type="text" class="search-input" placeholder="Search admins...">
                        <button class="search-btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <ul class="admin-list">
                        <li class="admin-item active" data-admin="1">
                            <div class="admin-avatar" style="background: var(--accent);">AD</div>
                            <div class="admin-info">
                                <div class="admin-name">Admin User</div>
                                <div class="admin-role">Super Admin • admin@darlingfm.com</div>
                                <span class="admin-status">Online</span>
                            </div>
                            <div class="admin-actions-small">
                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                <button class="action-btn"><i class="fas fa-key"></i></button>
                            </div>
                        </li>
                        <li class="admin-item" data-admin="2">
                            <div class="admin-avatar" style="background: #0099ff;">MJ</div>
                            <div class="admin-info">
                                <div class="admin-name">Michael Johnson</div>
                                <div class="admin-role">Content Manager • michael@darlingfm.com</div>
                                <span class="admin-status">Online</span>
                            </div>
                            <div class="admin-actions-small">
                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                <button class="action-btn"><i class="fas fa-key"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </li>
                        <li class="admin-item" data-admin="3">
                            <div class="admin-avatar" style="background: #00cc66;">SR</div>
                            <div class="admin-info">
                                <div class="admin-name">Sarah Rodriguez</div>
                                <div class="admin-role">Analyst • sarah@darlingfm.com</div>
                                <span class="admin-status">Online</span>
                            </div>
                            <div class="admin-actions-small">
                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                <button class="action-btn"><i class="fas fa-key"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </li>
                        <li class="admin-item" data-admin="4">
                            <div class="admin-avatar" style="background: #ffaa00;">RK</div>
                            <div class="admin-info">
                                <div class="admin-name">Robert Kim</div>
                                <div class="admin-role">Moderator • robert@darlingfm.com</div>
                                <span class="admin-status offline">Offline</span>
                            </div>
                            <div class="admin-actions-small">
                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                <button class="action-btn"><i class="fas fa-key"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </li>
                        <li class="admin-item" data-admin="5">
                            <div class="admin-avatar" style="background: #8a2be2;">AJ</div>
                            <div class="admin-info">
                                <div class="admin-name">Amanda Jones</div>
                                <div class="admin-role">Support Admin • amanda@darlingfm.com</div>
                                <span class="admin-status offline">Offline</span>
                            </div>
                            <div class="admin-actions-small">
                                <button class="action-btn"><i class="fas fa-edit"></i></button>
                                <button class="action-btn"><i class="fas fa-key"></i></button>
                                <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <!-- Admin Details -->
                <div class="admin-details reveal-on-scroll">
                    <div class="section-header">
                        <h2>Admin Details</h2>
                        <button class="btn btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                    
                    <div class="admin-info-large">
                        <div style="display: flex; align-items: center; margin-bottom: 20px;">
                            <div class="admin-avatar" style="width: 70px; height: 70px; background: var(--accent); font-size: 1.5rem;">AD</div>
                            <div style="margin-left: 15px;">
                                <h3 style="font-size: 1.5rem; margin-bottom: 5px;">Admin User</h3>
                                <p style="color: rgba(255, 255, 255, 0.7);">Super Admin</p>
                                <span class="admin-status">Online</span>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email Address</label>
                            <div class="form-control" style="background: rgba(0, 0, 0, 0.5);">admin@darlingfm.com</div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Join Date</label>
                                <div class="form-control" style="background: rgba(0, 0, 0, 0.5);">January 15, 2022</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Active</label>
                                <div class="form-control" style="background: rgba(0, 0, 0, 0.5);">Today, 14:30</div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Admin Role</label>
                            <select class="form-control">
                                <option>Super Admin</option>
                                <option>Content Manager</option>
                                <option>Analyst</option>
                                <option>Moderator</option>
                                <option>Support Admin</option>
                            </select>
                            <div class="form-text">Super Admins have full system access</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Permissions</label>
                            <div class="permissions-grid">
                                <div class="checkbox-group">
                                    <input type="checkbox" id="permDashboard" checked disabled>
                                    <label for="permDashboard">Dashboard Access</label>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="permContent" checked>
                                    <label for="permContent">Content Management</label>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="permUsers" checked>
                                    <label for="permUsers">User Management</label>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="permAnalytics" checked>
                                    <label for="permAnalytics">Analytics & Reports</label>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="permRevenue" checked>
                                    <label for="permRevenue">Revenue Management</label>
                                </div>
                                <div class="checkbox-group">
                                    <input type="checkbox" id="permSettings" checked>
                                    <label for="permSettings">System Settings</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="activity-log">
                            <h3 style="margin-bottom: 15px;">Recent Activity</h3>
                            <ul class="activity-list">
                                <li class="activity-item">
                                    <div>Logged in from new device</div>
                                    <div class="activity-time">Today, 14:30 • IP: 192.168.1.105</div>
                                </li>
                                <li class="activity-item">
                                    <div>Updated revenue settings</div>
                                    <div class="activity-time">Today, 13:45</div>
                                </li>
                                <li class="activity-item">
                                    <div>Added new admin user</div>
                                    <div class="activity-time">Yesterday, 16:20</div>
                                </li>
                                <li class="activity-item">
                                    <div>Modified user permissions</div>
                                    <div class="activity-time">June 28, 2023 • 10:15</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Admin Modal -->
    <div class="modal" id="addAdminModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Administrator</h2>
                <button class="close-modal">&times;</button>
            </div>
            
            <form id="addAdminForm">
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" class="form-control" placeholder="Enter full name" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" placeholder="Enter email address" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Admin Role</label>
                    <select class="form-control" required>
                        <option value="">Select a role</option>
                        <option>Super Admin</option>
                        <option>Content Manager</option>
                        <option>Analyst</option>
                        <option>Moderator</option>
                        <option>Support Admin</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Permissions</label>
                    <div class="permissions-grid">
                        <div class="checkbox-group">
                            <input type="checkbox" id="newPermDashboard" checked disabled>
                            <label for="newPermDashboard">Dashboard Access</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="newPermContent" checked>
                            <label for="newPermContent">Content Management</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="newPermUsers">
                            <label for="newPermUsers">User Management</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="newPermAnalytics" checked>
                            <label for="newPermAnalytics">Analytics & Reports</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="newPermRevenue">
                            <label for="newPermRevenue">Revenue Management</label>
                        </div>
                        <div class="checkbox-group">
                            <input type="checkbox" id="newPermSettings">
                            <label for="newPermSettings">System Settings</label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Admin</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // DOM Elements
        const hamburgerMenu = document.getElementById("hamburgerMenu");
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");
        const addAdminBtn = document.getElementById("addAdminBtn");
        const addAdminModal = document.getElementById("addAdminModal");
        const closeModalBtns = document.querySelectorAll('.close-modal');
        const addAdminForm = document.getElementById('addAdminForm');
        const adminItems = document.querySelectorAll('.admin-item');
        const searchInput = document.querySelector('.search-input');
        const searchBtn = document.querySelector('.search-btn');

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
        addAdminBtn.addEventListener('click', function() {
            addAdminModal.classList.add('active');
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                addAdminModal.classList.remove('active');
            });
        });

        // Close modal when clicking outside
        addAdminModal.addEventListener('click', function(e) {
            if (e.target === addAdminModal) {
                addAdminModal.classList.remove('active');
            }
        });

        // Form submission
        addAdminForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerhtml;
            submitBtn.innerhtml = '<i class="fas fa-spinner fa-spin"></i> Creating...';
            submitBtn.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                submitBtn.innerhtml = '<i class="fas fa-check"></i> Created!';
                
                setTimeout(() => {
                    addAdminModal.classList.remove('active');
                    submitBtn.innerhtml = originalText;
                    submitBtn.disabled = false;
                    addAdminForm.reset();
                    
                    // Show success message
                    alert('Admin user created successfully! An invitation has been sent to their email.');
                }, 1000);
            }, 1500);
        });

        // Admin item selection
        adminItems.forEach(item => {
            item.addEventListener('click', function() {
                adminItems.forEach(i => i.classList.remove('active'));
                this.classList.add('active');
                
                // In a real app, you would load the admin details based on data-admin attribute
                const adminId = this.getAttribute('data-admin');
                console.log('Loading admin details for ID:', adminId);
            });
        });

        // Search functionality
        searchBtn.addEventListener('click', performSearch);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });

        function performSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            
            adminItems.forEach(item => {
                const adminName = item.querySelector('.admin-name').textContent.toLowerCase();
                const adminEmail = item.querySelector('.admin-role').textContent.toLowerCase();
                
                if (adminName.includes(searchTerm) || adminEmail.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Delete admin functionality
        document.querySelectorAll('.action-btn.delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent triggering the parent click event
                const adminItem = this.closest('.admin-item');
                const adminName = adminItem.querySelector('.admin-name').textContent;
                
                if (confirm(`Are you sure you want to delete ${adminName}? This action cannot be undone.`)) {
                    // Show deleting state
                    const originalhtml = adminItem.innerhtml;
                    adminItem.innerhtml = '<div style="padding: 15px; text-align: center; width: 100%;"><i class="fas fa-spinner fa-spin"></i> Deleting...</div>';
                    
                    // Simulate API call
                    setTimeout(() => {
                        adminItem.style.opacity = '0.5';
                        setTimeout(() => {
                            adminItem.remove();
                            alert(`${adminName} has been deleted successfully.`);
                        }, 500);
                    }, 1000);
                }
            });
        });

        // Scroll reveal functionality
        const revealElements = document.querySelectorAll('.reveal-on-scroll');
        
        function checkReveal() {
            const windowHeight = window.innerHeight;
            const revealPoint = 100;
            
            revealElements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                
                if (elementTop < windowHeight - revealPoint) {
                    element.classList.add('revealed');
                }
            });
        }
        
        // Initial check
        checkReveal();
        
        // Check on scroll with throttling for better performance
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(function() {
                    checkReveal();
                    ticking = false;
                });
                ticking = true;
            }
        });
    </script>
</body>
</html>