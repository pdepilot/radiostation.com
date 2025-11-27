<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Darling FM - Revenue Analytics</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="./images/REAL_LOGO-removebg-preview.png" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        /* Revenue-specific styles */
        .revenue-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .time-filter {
            display: flex;
            gap: 10px;
        }

        .filter-btn {
            padding: 8px 16px;
            border-radius: 6px;
            background: var(--glass);
            border: 1px solid var(--glass-border);
            color: var(--light);
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: "Exo 2", sans-serif;
        }

        .filter-btn.active {
            background: var(--accent);
            border-color: var(--accent);
        }

        .filter-btn:hover:not(.active) {
            background: rgba(255, 255, 255, 0.1);
        }

        .revenue-overview {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .revenue-stat-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid var(--glass-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .revenue-stat-card::before {
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

        .revenue-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .revenue-stat-card:hover::before {
            opacity: 1;
        }

        .revenue-stat-icon {
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

        .revenue-stat-info {
            overflow: hidden;
        }

        .revenue-stat-info h3 {
            font-size: 0.9rem;
            color: var(--light);
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .revenue-stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .revenue-stat-change {
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

        .chart-btn:hover {
            background: var(--accent);
            color: white;
        }

        .chart-container {
            height: 300px;
            position: relative;
        }

        .revenue-sources {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .sources-card {
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

        .sources-list {
            list-style: none;
        }

        .source-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--glass-border);
        }

        .source-item:last-child {
            border-bottom: none;
        }

        .source-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
        }

        .source-info {
            flex: 1;
        }

        .source-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .source-meta {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .source-bar {
            height: 8px;
            border-radius: 4px;
            margin: 0 15px;
            flex: 1;
            background: rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .source-fill {
            height: 100%;
            border-radius: 4px;
        }

        .source-value {
            font-weight: 600;
            min-width: 80px;
            text-align: right;
        }

        .transactions-section {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid var(--glass-border);
            margin-bottom: 30px;
        }

        .transactions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .transactions-list {
            list-style: none;
            max-height: 400px;
            overflow-y: auto;
        }

        .transaction-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid var(--glass-border);
            transition: all 0.3s ease;
        }

        .transaction-item:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 16px;
        }

        .transaction-info {
            flex: 1;
        }

        .transaction-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .transaction-meta {
            font-size: 0.8rem;
            opacity: 0.7;
            display: flex;
            gap: 15px;
        }

        .transaction-amount {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .amount-positive {
            color: var(--success);
        }

        .amount-negative {
            color: var(--accent);
        }

        .subscriptions-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .subscription-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid var(--glass-border);
        }

        .subscription-metrics {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .subscription-metric {
            text-align: center;
            padding: 15px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }

        .subscription-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subscription-label {
            font-size: 0.8rem;
            opacity: 0.7;
        }

        .export-section {
            display: flex;
            justify-content: flex-end;
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
            .charts-section {
                grid-template-columns: 1fr;
            }
            
            .revenue-sources {
                grid-template-columns: 1fr;
            }
            
            .subscriptions-section {
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
            .revenue-overview {
                grid-template-columns: 1fr 1fr;
            }
            
            .subscription-metrics {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .revenue-overview {
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
        .revenue-stat-card,
        .chart-card,
        .sources-card,
        .transactions-section,
        .subscription-card {
            animation: fadeIn 0.5s ease forwards;
        }

        .revenue-stat-card:nth-child(2) {
            animation-delay: 0.1s;
        }
        .revenue-stat-card:nth-child(3) {
            animation-delay: 0.2s;
        }
        .revenue-stat-card:nth-child(4) {
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
                <li><a href="admin-playlist.php"><i class="fas fa-play-circle"></i> <span>Playlists</span></a></li>
                <li><a href="admin-news.php"><i class="fas fa-newspaper"></i> <span>News</span></a></li>
                
                <li class="menu-label">Analytics</li>
                <li><a href="admin-statistic.php"><i class="fas fa-chart-line"></i> <span>Statistics</span></a></li>
                <li><a href="admin-audience.php"><i class="fas fa-users"></i> <span>Audience</span></a></li>
                <li><a href="admin-revenue.php" class="active"><i class="fas fa-money-bill-wave"></i> <span>Revenue</span></a></li>
                
                <li class="menu-label">Settings</li>
                <li><a href="admin-setthings.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                <li><a href="admin-admins.php"><i class="fas fa-user-shield"></i> <span>Admins</span></a></li>
                <li><a href="admin-logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Revenue Analytics</h1>
                <div class="user-info">
                    <div class="user-avatar">AD</div>
                    <div>
                        <div>Admin User</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">Super Admin</div>
                    </div>
                </div>
            </div>
            
            <!-- Time Filter -->
            <div class="revenue-header reveal-on-scroll">
                <h2>Financial Overview</h2>
                <div class="time-filter">
                    <button class="filter-btn active">Today</button>
                    <button class="filter-btn">Week</button>
                    <button class="filter-btn">Month</button>
                    <button class="filter-btn">Quarter</button>
                    <button class="filter-btn">Year</button>
                </div>
            </div>
            
            <!-- Revenue Overview -->
            <div class="revenue-overview">
                <div class="revenue-stat-card reveal-on-scroll">
                    <div class="revenue-stat-icon" style="background: rgba(0, 255, 0, 0.2); color: var(--live-green);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="revenue-stat-info">
                        <h3>Total Revenue</h3>
                        <div class="revenue-stat-value">$124,582</div>
                        <div class="revenue-stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 15.7% from last month
                        </div>
                    </div>
                </div>
                
                <div class="revenue-stat-card reveal-on-scroll">
                    <div class="revenue-stat-icon" style="background: rgba(0, 204, 102, 0.2); color: var(--success);">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="revenue-stat-info">
                        <h3>Ad Revenue</h3>
                        <div class="revenue-stat-value">$84,250</div>
                        <div class="revenue-stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 12.3% from last month
                        </div>
                    </div>
                </div>
                
                <div class="revenue-stat-card reveal-on-scroll">
                    <div class="revenue-stat-icon" style="background: rgba(255, 170, 0, 0.2); color: var(--warning);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="revenue-stat-info">
                        <h3>Subscription Revenue</h3>
                        <div class="revenue-stat-value">$32,450</div>
                        <div class="revenue-stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 8.9% from last month
                        </div>
                    </div>
                </div>
                
                <div class="revenue-stat-card reveal-on-scroll">
                    <div class="revenue-stat-icon" style="background: rgba(0, 153, 255, 0.2); color: var(--info);">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="revenue-stat-info">
                        <h3>Merchandise Sales</h3>
                        <div class="revenue-stat-value">$7,882</div>
                        <div class="revenue-stat-change change-positive">
                            <i class="fas fa-arrow-up"></i> 22.1% from last month
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-card reveal-on-scroll">
                    <div class="chart-header">
                        <h3>Revenue Trends</h3>
                        <div class="chart-actions">
                            <button class="chart-btn">Day</button>
                            <button class="chart-btn">Week</button>
                            <button class="chart-btn active">Month</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                
                <div class="chart-card reveal-on-scroll">
                    <div class="chart-header">
                        <h3>Revenue Distribution</h3>
                        <div class="chart-actions">
                            <button class="chart-btn active">All Time</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Revenue Sources -->
            <div class="revenue-sources">
                <div class="sources-card reveal-on-scroll">
                    <div class="card-header">
                        <h3>Revenue Sources</h3>
                    </div>
                    <ul class="sources-list">
                        <li class="source-item">
                            <div class="source-icon" style="background: rgba(0, 204, 102, 0.2); color: var(--success);">
                                <i class="fas fa-ad"></i>
                            </div>
                            <div class="source-info">
                                <div class="source-name">Advertising</div>
                                <div class="source-meta">Display & Audio Ads</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 68%; background: var(--success);"></div>
                            </div>
                            <div class="source-value">$84,250</div>
                        </li>
                        <li class="source-item">
                            <div class="source-icon" style="background: rgba(255, 170, 0, 0.2); color: var(--warning);">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="source-info">
                                <div class="source-name">Premium Subscriptions</div>
                                <div class="source-meta">Monthly & Yearly Plans</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 26%; background: var(--warning);"></div>
                            </div>
                            <div class="source-value">$32,450</div>
                        </li>
                        <li class="source-item">
                            <div class="source-icon" style="background: rgba(0, 153, 255, 0.2); color: var(--info);">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div class="source-info">
                                <div class="source-name">Merchandise</div>
                                <div class="source-meta">Apparel & Accessories</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 6%; background: var(--info);"></div>
                            </div>
                            <div class="source-value">$7,882</div>
                        </li>
                        <li class="source-item">
                            <div class="source-icon" style="background: rgba(138, 43, 226, 0.2); color: var(--revenue-purple);">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            <div class="source-info">
                                <div class="source-name">Sponsorships</div>
                                <div class="source-meta">Brand Partnerships</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 18%; background: var(--revenue-purple);"></div>
                            </div>
                            <div class="source-value">$22,450</div>
                        </li>
                    </ul>
                </div>
                
                <div class="sources-card reveal-on-scroll">
                    <div class="card-header">
                        <h3>Ad Performance</h3>
                    </div>
                    <ul class="sources-list">
                        <li class="source-item">
                            <div class="source-info">
                                <div class="source-name">Audio Ads</div>
                                <div class="source-meta">CPM: $12.50 | Fill Rate: 78%</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 78%; background: var(--success);"></div>
                            </div>
                            <div class="source-value">$45,820</div>
                        </li>
                        <li class="source-item">
                            <div class="source-info">
                                <div class="source-name">Display Ads</div>
                                <div class="source-meta">CPM: $8.20 | Fill Rate: 65%</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 65%; background: var(--warning);"></div>
                            </div>
                            <div class="source-value">$28,430</div>
                        </li>
                        <li class="source-item">
                            <div class="source-info">
                                <div class="source-name">Video Ads</div>
                                <div class="source-meta">CPM: $18.75 | Fill Rate: 42%</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 42%; background: var(--info);"></div>
                            </div>
                            <div class="source-value">$10,000</div>
                        </li>
                        <li class="source-item">
                            <div class="source-info">
                                <div class="source-name">Native Ads</div>
                                <div class="source-meta">CPM: $15.30 | Fill Rate: 58%</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 58%; background: var(--revenue-purple);"></div>
                            </div>
                            <div class="source-value">$15,200</div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="transactions-section reveal-on-scroll">
                <div class="transactions-header">
                    <h3>Recent Transactions</h3>
                    <button class="btn btn-secondary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                <ul class="transactions-list">
                    <li class="transaction-item">
                        <div class="transaction-icon" style="background: rgba(0, 204, 102, 0.2); color: var(--success);">
                            <i class="fas fa-ad"></i>
                        </div>
                        <div class="transaction-info">
                            <div class="transaction-title">Audio Ad Campaign - TechCorp</div>
                            <div class="transaction-meta">
                                <span>Completed</span>
                                <span>Today, 14:30</span>
                                <span>Invoice #TC-7842</span>
                            </div>
                        </div>
                        <div class="transaction-amount amount-positive">+$2,450</div>
                    </li>
                    <li class="transaction-item">
                        <div class="transaction-icon" style="background: rgba(255, 170, 0, 0.2); color: var(--warning);">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="transaction-info">
                            <div class="transaction-title">Premium Subscription - Annual</div>
                            <div class="transaction-meta">
                                <span>Completed</span>
                                <span>Today, 12:15</span>
                                <span>User: john_doe</span>
                            </div>
                        </div>
                        <div class="transaction-amount amount-positive">+$99.99</div>
                    </li>
                    <li class="transaction-item">
                        <div class="transaction-icon" style="background: rgba(0, 153, 255, 0.2); color: var(--info);">
                            <i class="fas fa-tshirt"></i>
                        </div>
                        <div class="transaction-info">
                            <div class="transaction-title">Merchandise Order - Hoodie</div>
                            <div class="transaction-meta">
                                <span>Shipped</span>
                                <span>Yesterday, 16:45</span>
                                <span>Order #M-4582</span>
                            </div>
                        </div>
                        <div class="transaction-amount amount-positive">+$45.00</div>
                    </li>
                    <li class="transaction-item">
                        <div class="transaction-icon" style="background: rgba(138, 43, 226, 0.2); color: var(--revenue-purple);">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <div class="transaction-info">
                            <div class="transaction-title">Sponsorship - MusicFest 2023</div>
                            <div class="transaction-meta">
                                <span>Pending</span>
                                <span>Jun 28, 2023</span>
                                <span>Contract #SP-0234</span>
                            </div>
                        </div>
                        <div class="transaction-amount amount-positive">+$5,000</div>
                    </li>
                    <li class="transaction-item">
                        <div class="transaction-icon" style="background: rgba(255, 0, 0, 0.2); color: var(--accent);">
                            <i class="fas fa-exchange-alt"></i>
                        </div>
                        <div class="transaction-info">
                            <div class="transaction-title">Ad Platform Fee - July</div>
                            <div class="transaction-meta">
                                <span>Completed</span>
                                <span>Jun 25, 2023</span>
                                <span>Platform Fee</span>
                            </div>
                        </div>
                        <div class="transaction-amount amount-negative">-$850</div>
                    </li>
                </ul>
            </div>
            
            <!-- Subscriptions Section -->
            <div class="subscriptions-section">
                <div class="subscription-card reveal-on-scroll">
                    <div class="card-header">
                        <h3>Subscription Analytics</h3>
                    </div>
                    <div class="subscription-metrics">
                        <div class="subscription-metric reveal-on-scroll">
                            <div class="subscription-value">8,452</div>
                            <div class="subscription-label">Total Subscribers</div>
                        </div>
                        <div class="subscription-metric reveal-on-scroll">
                            <div class="subscription-value">4.2%</div>
                            <div class="subscription-label">Conversion Rate</div>
                        </div>
                        <div class="subscription-metric reveal-on-scroll">
                            <div class="subscription-value">2.8%</div>
                            <div class="subscription-label">Churn Rate</div>
                        </div>
                        <div class="subscription-metric reveal-on-scroll">
                            <div class="subscription-value">$32.45</div>
                            <div class="subscription-label">ARPU</div>
                        </div>
                    </div>
                </div>
                
                <div class="subscription-card reveal-on-scroll">
                    <div class="card-header">
                        <h3>Subscription Plans</h3>
                    </div>
                    <ul class="sources-list">
                        <li class="source-item reveal-on-scroll">
                            <div class="source-info">
                                <div class="source-name">Basic Plan</div>
                                <div class="source-meta">$4.99/month | 2,145 users</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 25%; background: var(--success);"></div>
                            </div>
                            <div class="source-value">25%</div>
                        </li>
                        <li class="source-item reveal-on-scroll">
                            <div class="source-info">
                                <div class="source-name">Premium Plan</div>
                                <div class="source-meta">$9.99/month | 4,832 users</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 57%; background: var(--warning);"></div>
                            </div>
                            <div class="source-value">57%</div>
                        </li>
                        <li class="source-item reveal-on-scroll">
                            <div class="source-info">
                                <div class="source-name">Family Plan</div>
                                <div class="source-meta">$14.99/month | 1,475 users</div>
                            </div>
                            <div class="source-bar">
                                <div class="source-fill" style="width: 18%; background: var(--info);"></div>
                            </div>
                            <div class="source-value">18%</div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Export Section -->
            <div class="export-section">
                <button class="btn btn-secondary reveal-on-scroll">
                    <i class="fas fa-download"></i> Export Financial Data
                </button>
                <button class="btn btn-primary reveal-on-scroll">
                    <i class="fas fa-file-invoice-dollar"></i> Generate Revenue Report
                </button>
            </div>
        </div>
    </div>

    <script>
        // DOM Elements
        const hamburgerMenu = document.getElementById("hamburgerMenu");
        const sidebar = document.getElementById("sidebar");
        const sidebarOverlay = document.getElementById("sidebarOverlay");
        const filterBtns = document.querySelectorAll('.filter-btn');
        const chartBtns = document.querySelectorAll('.chart-btn');

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

        // Filter buttons functionality
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                // In a real app, you would update the data based on the selected filter
            });
        });

        // Chart buttons functionality
        chartBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons in the same container
                const container = this.closest('.chart-actions');
                container.querySelectorAll('.chart-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                // In a real app, you would update the chart based on the selected timeframe
            });
        });

        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Trends Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Total Revenue',
                        data: [85000, 92000, 98000, 105000, 112000, 118000, 124582],
                        borderColor: '#ff0000',
                        backgroundColor: 'rgba(255, 0, 0, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Ad Revenue',
                        data: [58000, 62000, 68000, 72000, 78000, 82000, 84250],
                        borderColor: '#00cc66',
                        backgroundColor: 'rgba(0, 204, 102, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#f5f5f5'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#f5f5f5',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: '#f5f5f5'
                            }
                        }
                    }
                }
            });

            // Revenue Distribution Chart
            const distributionCtx = document.getElementById('distributionChart').getContext('2d');
            const distributionChart = new Chart(distributionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Advertising', 'Subscriptions', 'Sponsorships', 'Merchandise'],
                    datasets: [{
                        data: [68, 26, 18, 6],
                        backgroundColor: [
                            '#00cc66',
                            '#ffaa00',
                            '#8a2be2',
                            '#0099ff'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#f5f5f5',
                                padding: 20
                            }
                        }
                    }
                }
            });
        });

        // Enhanced scroll reveal functionality
        const revealElements = document.querySelectorAll('.reveal-on-scroll');
        
        function checkReveal() {
            const windowHeight = window.innerHeight;
            const revealPoint = 100; // Reduced from 150 for earlier reveal
            
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

        // Simulate real-time revenue updates
        setInterval(() => {
            const revenueElement = document.querySelector('.revenue-stat-card:nth-child(1) .revenue-stat-value');
            const currentAmount = parseInt(revenueElement.textContent.replace('$', '').replace(',', ''));
            const randomChange = Math.floor(Math.random() * 5000) - 2000;
            const newAmount = Math.max(120000, currentAmount + randomChange);
            revenueElement.textContent = '$' + newAmount.toLocaleString();
        }, 20000);
    </script>
</body>
</html>