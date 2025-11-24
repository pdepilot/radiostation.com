<!DOCTYPE php>
<php lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="./images/REAL_LOGO-removebg-preview.png" />
    <title>Darling FM - Admin Login</title>
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
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --neon-glow: 0 0 10px var(--accent), 0 0 20px var(--accent);
            --live-green: #00ff00;
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
            overflow: hidden;
            background: radial-gradient(ellipse at center, var(--secondary) 0%, var(--primary) 70%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0.3;
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 0.3;
            }
            90% {
                opacity: 0.3;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }

        .login-container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            height: 90vh;
            background: var(--glass);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            box-shadow: 0 0 50px rgba(255, 0, 0, 0.2);
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, rgba(255, 0, 0, 0.1), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1571330735066-03aaa9429d89?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80') center/cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 50%, rgba(255, 0, 0, 0.2) 0%, transparent 50%),
                        radial-gradient(circle at 70% 20%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 40% 80%, rgba(255, 51, 51, 0.1) 0%, transparent 50%);
            z-index: 0;
        }

        .logo-container {
            text-align: center;
            z-index: 1;
            margin-bottom: 40px;
        }

        .logo-container img {
            width: 120px;
            height: 60px;
            filter: drop-shadow(0 0 10px var(--accent));
            margin-bottom: 20px;
        }

        .logo-container h1 {
            font-family: "Orbitron", sans-serif;
            font-size: 3rem;
            margin-bottom: 10px;
            background: linear-gradient(45deg, var(--accent), var(--highlight));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 20px rgba(255, 0, 0, 0.5);
            letter-spacing: 2px;
        }

        .logo-container p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 400px;
        }

        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 40px;
            z-index: 1;
        }

        .feature {
            display: flex;
            align-items: center;
            background: rgba(0, 0, 0, 0.4);
            padding: 15px;
            border-radius: 10px;
            border-left: 3px solid var(--accent);
        }

        .feature i {
            font-size: 1.5rem;
            margin-right: 15px;
            color: var(--accent);
        }

        .feature p {
            font-size: 0.9rem;
        }

        .login-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            position: relative;
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-header h2 {
            font-family: "Orbitron", sans-serif;
            font-size: 2.2rem;
            margin-bottom: 10px;
            background: linear-gradient(45deg, var(--accent), var(--highlight));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .login-header p {
            opacity: 0.8;
            font-size: 1rem;
        }

        .login-form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--highlight);
            font-size: 0.9rem;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent);
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px 15px 15px 45px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: var(--light);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.3);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--light);
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.3s;
        }

        .password-toggle:hover {
            opacity: 1;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
            accent-color: var(--accent);
        }

        .forgot-password {
            color: var(--accent);
            text-decoration: none;
            transition: all 0.3s;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(45deg, var(--accent), var(--accent-glow));
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(255, 0, 0, 0.4);
        }

        .login-btn::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
        }

        .login-btn:hover::before {
            animation: shine 1.5s;
        }

        @keyframes shine {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 0, 0, 0.6);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            background: var(--live-green);
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 255, 0, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(0, 255, 0, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(0, 255, 0, 0);
            }
        }

        .error-message {
            color: var(--accent);
            font-size: 0.9rem;
            margin-top: 5px;
            display: none;
        }

        /* Media Queries */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            
            .login-left {
                padding: 30px 20px;
            }
            
            .features {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .login-container {
                width: 95%;
                border-radius: 15px;
            }
            
            .logo-container h1 {
                font-size: 2.2rem;
            }
            
            .login-header h2 {
                font-size: 1.8rem;
            }
            
            .login-left, .login-right {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="cyber-grid"></div>
    <div class="particles" id="particles"></div>
    
    <div class="login-container">
        <div class="login-left">
            <div class="logo-container">
                <img src="./images/REAL_LOGO-removebg-preview.png" alt="Darling FM Logo">
                <h1>DARLING FM</h1>
                <p>Revolutionary Radio Experience - Admin Portal</p>
                <div style="margin-top: 20px;">
                    <span class="pulse-dot"></span>
                    <span>Secure Admin Access</span>
                </div>
            </div>
            
            <div class="features">
                <div class="feature">
                    <i class="fas fa-broadcast-tower"></i>
                    <p>Live Stream Management</p>
                </div>
                <div class="feature">
                    <i class="fas fa-chart-line"></i>
                    <p>Advanced Analytics</p>
                </div>
                <div class="feature">
                    <i class="fas fa-music"></i>
                    <p>Content Management</p>
                </div>
                <div class="feature">
                    <i class="fas fa-users"></i>
                    <p>DJ & Staff Management</p>
                </div>
            </div>
        </div>
        
        <div class="login-right">
            <div class="login-form-container">
                <div class="login-header">
                    <h2>ADMIN LOGIN</h2>
                    <p>Access your dashboard</p>
                </div>
                
                <form class="login-form" id="loginForm">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" class="form-control" placeholder="Enter your username" required>
                        </div>
                        <div class="error-message" id="usernameError">Please enter a valid username</div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="password" class="form-control" placeholder="Enter your password" required>
                            <button type="button" class="password-toggle" id="passwordToggle">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="error-message" id="passwordError">Please enter your password</div>
                    </div>
                    
                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot Password?</a>
                    </div>
                    
                    <button type="submit" class="login-btn">
                        <i class="fas fa-sign-in-alt"></i> SIGN IN
                    </button>
                    
                    <div class="login-footer">
                        <p>Secure access to Darling FM administration panel</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Create floating particles
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random size
                const size = Math.random() * 5 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random position
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.top = `${Math.random() * 100}%`;
                
                // Random animation delay and duration
                const delay = Math.random() * 15;
                const duration = Math.random() * 10 + 15;
                particle.style.animationDelay = `${delay}s`;
                particle.style.animationDuration = `${duration}s`;
                
                particlesContainer.appendChild(particle);
            }
            
            // Password toggle functionality
            const passwordToggle = document.getElementById('passwordToggle');
            const passwordInput = document.getElementById('password');
            
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle eye icon
                this.innerphp = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
            
            // Form validation
            const loginForm = document.getElementById('loginForm');
            const usernameInput = document.getElementById('username');
            const usernameError = document.getElementById('usernameError');
            const passwordError = document.getElementById('passwordError');
            
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                let isValid = true;
                
                // Validate username
                if (usernameInput.value.trim() === '') {
                    usernameError.style.display = 'block';
                    usernameInput.style.borderColor = 'var(--accent)';
                    isValid = false;
                } else {
                    usernameError.style.display = 'none';
                    usernameInput.style.borderColor = 'var(--glass-border)';
                }
                
                // Validate password
                if (passwordInput.value.trim() === '') {
                    passwordError.style.display = 'block';
                    passwordInput.style.borderColor = 'var(--accent)';
                    isValid = false;
                } else {
                    passwordError.style.display = 'none';
                    passwordInput.style.borderColor = 'var(--glass-border)';
                }
                
                if (isValid) {
                    // Simulate login process
                    const loginBtn = loginForm.querySelector('.login-btn');
                    loginBtn.innerphp = '<i class="fas fa-spinner fa-spin"></i> AUTHENTICATING...';
                    loginBtn.disabled = true;
                    
                    // Simulate API call
                    setTimeout(() => {
                        // For demo purposes, always redirect to dashboard
                        // In a real application, you would validate credentials first
                        window.location.href = 'admin-dash.php';
                    }, 2000);
                }
            });
            
            // Input focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.querySelector('i').style.color = 'var(--accent-glow)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.querySelector('i').style.color = 'var(--accent)';
                });
            });
        });
    </script>
</body>
</php>