<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Darling FM Admin' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-dash.css') }}">
    @stack('styles')
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
        <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM Logo">
        <h2>DARLING FM ADMIN</h2>
    </div>
    
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('assets/images/REAL_LOGO-removebg-preview.png') }}" alt="Darling FM Logo">
                <h2>DARLING FM ADMIN</h2>
            </div>
            
            <ul class="sidebar-menu">
                <li class="menu-label">Main</li>
                <li><a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
                <li><a href="{{ route('admin.livestreams.index') }}" class="{{ request()->routeIs('admin.livestreams.*') ? 'active' : '' }}"><i class="fas fa-broadcast-tower"></i> <span>Live Stream</span></a></li>
                <li><a href="{{ route('admin.shows.index') }}" class="{{ request()->routeIs('admin.shows.*') ? 'active' : '' }}"><i class="fas fa-music"></i> <span>Shows</span></a></li>
                <li><a href="{{ route('admin.djs.index') }}" class="{{ request()->routeIs('admin.djs.*') ? 'active' : '' }}"><i class="fas fa-user"></i> <span>DJs</span></a></li>
                <li><a href="{{ route('admin.audience.index') }}" class="{{ request()->routeIs('admin.audience.*') ? 'active' : '' }}"><i class="fas fa-comments"></i> <span>Feedback</span></a></li>
                
                <li class="menu-label">Content</li>
                <li><a href="{{ route('admin.podcasts.index') }}" class="{{ request()->routeIs('admin.podcasts.*') ? 'active' : '' }}"><i class="fas fa-podcast"></i> <span>Podcasts</span></a></li>
                <li><a href="{{ route('admin.playlist.index') }}" class="{{ request()->routeIs('admin.playlist.*') ? 'active' : '' }}"><i class="fas fa-play-circle"></i> <span>Playlists</span></a></li>
                <li><a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}"><i class="fas fa-newspaper"></i> <span>News</span></a></li>
                
                <li class="menu-label">Analytics</li>
                <li><a href="{{ route('admin.audience.index') }}" class="{{ request()->routeIs('admin.audience.*') ? 'active' : '' }}"><i class="fas fa-users"></i> <span>Audience</span></a></li>
                <li><a href="{{ route('admin.revenue.index') }}" class="{{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave"></i> <span>Revenue</span></a></li>
                
                <li class="menu-label">Business</li>
                <li><a href="{{ route('admin.advertising.index') }}" class="{{ request()->routeIs('admin.advertising.*') ? 'active' : '' }}"><i class="fas fa-bullhorn"></i> <span>Advertising</span></a></li>
                
                <li class="menu-label">Settings</li>
                <li><a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
                <li><a href="{{ route('profile.edit') }}"><i class="fas fa-user-circle"></i> <span>Profile</span></a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>{{ $title ?? 'Dashboard Overview' }}</h1>
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <div>
                        <div>{{ auth()->user()->name }}</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">{{ ucfirst(auth()->user()->role ?? 'User') }}</div>
                    </div>
                </div>
            </div>
            
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('assets/js/admin-dash.js') }}" defer></script>
    @stack('scripts')
</body>
</html>

