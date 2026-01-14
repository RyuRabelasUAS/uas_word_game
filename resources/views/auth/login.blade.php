<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Word Game Admin</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-yellow: #FFD700;
            --primary-blue: #00D9FF;
            --dark-bg: #0A0E27;
            --card-bg: #131829;
            --hover-bg: #1A1F3A;
            --text-primary: #FFFFFF;
            --text-secondary: #A0AEC0;
            --accent-cyan: #00FFF0;
            --accent-purple: #B794F4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--dark-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .animated-bg {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
            background:
                radial-gradient(circle at 20% 30%, rgba(0, 217, 255, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(183, 148, 244, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(255, 215, 0, 0.08) 0%, transparent 50%);
            animation: bgShift 20s ease-in-out infinite;
        }

        @keyframes bgShift {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 1100px;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            background: linear-gradient(135deg, rgba(19, 24, 41, 0.95) 0%, rgba(26, 31, 58, 0.95) 100%);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .login-container:hover {
            transform: translateY(-4px);
            box-shadow:
                0 30px 80px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(0, 217, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            border-color: rgba(0, 217, 255, 0.2);
        }

        .login-brand {
            padding: 4rem;
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.05) 0%, rgba(183, 148, 244, 0.05) 100%);
            position: relative;
            overflow: hidden;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-brand::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(0, 217, 255, 0.1) 0%, transparent 70%);
            animation: rotateGradient 15s linear infinite;
        }

        @keyframes rotateGradient {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.8;
        }

        .brand-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #FFFFFF 0%, var(--primary-blue) 50%, var(--accent-cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 1rem;
            color: rgba(160, 174, 192, 0.9);
            font-weight: 400;
            line-height: 1.6;
            margin-bottom: 3rem;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .feature-item {
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-item:hover {
            background: rgba(0, 217, 255, 0.05);
            border-color: rgba(0, 217, 255, 0.3);
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        }

        .feature-icon {
            font-size: 2rem;
            margin-bottom: 0.75rem;
            display: block;
            opacity: 0.8;
        }

        .feature-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .feature-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .login-form {
            padding: 4rem 3.5rem;
            background: rgba(10, 14, 39, 0.4);
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            color: #FFFFFF;
            margin-bottom: 0.75rem;
            letter-spacing: -0.3px;
        }

        .form-header p {
            font-size: 0.9rem;
            color: rgba(160, 174, 192, 0.9);
            font-weight: 400;
        }

        .credentials-box {
            background: rgba(0, 217, 255, 0.05);
            border: 1px solid rgba(0, 217, 255, 0.2);
            padding: 1.25rem 1.5rem;
            border-radius: 16px;
            margin-bottom: 2rem;
        }

        .credentials-title {
            font-weight: 600;
            color: rgba(0, 217, 255, 0.9);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .credential-item {
            color: rgba(160, 174, 192, 0.9);
            margin: 0.5rem 0;
            font-family: 'Monaco', 'Courier New', monospace;
            font-size: 0.85rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #FFFFFF;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 1rem 1.25rem;
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 10px;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-primary);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-blue);
            background: rgba(0, 217, 255, 0.05);
            box-shadow: 0 0 0 4px rgba(0, 217, 255, 0.1), 0 0 20px rgba(0, 217, 255, 0.2);
            transform: translateY(-2px);
        }

        .form-control::placeholder {
            color: var(--text-secondary);
        }

        .form-error {
            color: #fca5a5;
            font-size: 0.8rem;
            margin-top: 0.4rem;
            font-weight: 500;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
        }

        .checkbox-wrapper input {
            width: 18px;
            height: 18px;
            margin-right: 0.6rem;
            cursor: pointer;
            accent-color: var(--primary-blue);
        }

        .checkbox-wrapper label {
            color: var(--text-secondary);
            cursor: pointer;
            margin: 0;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .forgot-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-link:hover {
            color: var(--accent-cyan);
            text-shadow: 0 0 10px rgba(0, 217, 255, 0.5);
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.15) 0%, rgba(183, 148, 244, 0.15) 100%);
            color: white;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.25) 0%, rgba(183, 148, 244, 0.25) 100%);
            border-color: rgba(0, 217, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 217, 255, 0.25);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .form-footer {
            margin-top: 2rem;
            text-align: center;
        }

        .form-footer a {
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .form-footer a:hover {
            color: var(--primary-blue);
        }

        @media (max-width: 1024px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 500px;
            }

            .login-brand {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .login-form {
                padding: 2.5rem 2rem;
            }

            .form-header h1 {
                font-size: 2rem;
            }

            .brand-title {
                font-size: 2rem;
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div id="particles-js"></div>
    <div class="animated-bg"></div>

    <div class="login-container" data-aos="fade-up" data-aos-duration="800">
        <!-- Brand Section -->
        <div class="login-brand">
            <div class="brand-content">
                <img src="{{ asset('images/logo.png') }}" alt="UAS Logo" data-aos="zoom-in" data-aos-delay="200" style="height: 100px; width: auto; margin-bottom: 1.5rem; filter: drop-shadow(0 0 30px rgba(0, 217, 255, 0.8));">
                <h1 class="brand-title" data-aos="fade-right" data-aos-delay="300">WORD NEXUS</h1>
                <p class="brand-subtitle" data-aos="fade-right" data-aos-delay="400">
                    Next-generation crossword gaming platform with advanced analytics and seamless management
                </p>

                <div class="feature-grid">
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="500">
                        <span class="feature-icon">⚡</span>
                        <div class="feature-title">Lightning Fast</div>
                        <div class="feature-desc">Real-time updates and instant puzzle generation</div>
                    </div>
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="600">
                        <span class="feature-icon">🎯</span>
                        <div class="feature-title">Smart AI</div>
                        <div class="feature-desc">AI-powered word placement and difficulty scaling</div>
                    </div>
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="700">
                        <span class="feature-icon">📊</span>
                        <div class="feature-title">Analytics</div>
                        <div class="feature-desc">Deep insights into player behavior and patterns</div>
                    </div>
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="800">
                        <span class="feature-icon">🔒</span>
                        <div class="feature-title">Ultra Secure</div>
                        <div class="feature-desc">Enterprise-grade encryption and protection</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="login-form">
            <div class="form-header" data-aos="fade-left" data-aos-delay="300">
                <h1>SIGN IN</h1>
                <p>Enter your email to access your account</p>
            </div>

            <div class="credentials-box" data-aos="fade-left" data-aos-delay="400" id="admin-credentials-box" style="display: none;">
                <div class="credentials-title">
                    🔑 Demo Admin Access
                </div>
                <div class="credential-item">Email: admin@example.com</div>
                <div class="credential-item">Password: password</div>
            </div>

            @if($errors->any())
                <div class="alert" data-aos="shake">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V13H11V15ZM11 11H9V5H11V11Z" fill="#fca5a5"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" id="login-form">
                @csrf

                <div class="form-group" data-aos="fade-left" data-aos-delay="500">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="Enter your email"
                    >
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" id="password-group" data-aos="fade-left" data-aos-delay="600" style="display: none;">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Enter your password"
                    >
                    @error('password')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row" data-aos="fade-left" data-aos-delay="700">
                    <div class="checkbox-wrapper">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit" data-aos="fade-up" data-aos-delay="800" id="submit-btn">
                    Sign In
                </button>
            </form>

            <div class="form-footer" data-aos="fade-up" data-aos-delay="900">
                <a href="{{ route('register') }}">Don't have an account? Register</a>
                <br>
                <a href="{{ route('game.index') }}">← Return to game portal</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });

        // Check if email is admin and show password field
        const emailInput = document.getElementById('email');
        const passwordGroup = document.getElementById('password-group');
        const passwordInput = document.getElementById('password');
        const adminCredentialsBox = document.getElementById('admin-credentials-box');
        const loginForm = document.getElementById('login-form');
        const submitBtn = document.getElementById('submit-btn');

        let checkTimeout;
        let isChecking = false;
        let emailChecked = false;
        let isAdmin = false;
        let canSubmit = false; // Flag to control actual form submission

        async function checkEmail(email) {
            isChecking = true;
            try {
                const response = await fetch('{{ route('api.check-email') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();
                isAdmin = data.is_admin;
                emailChecked = true;

                if (data.is_admin) {
                    passwordGroup.style.display = 'block';
                    passwordInput.required = true;
                    adminCredentialsBox.style.display = 'block';
                    // Focus on password field
                    setTimeout(() => passwordInput.focus(), 100);
                } else {
                    passwordGroup.style.display = 'none';
                    passwordInput.required = false;
                    adminCredentialsBox.style.display = 'none';
                }
            } catch (error) {
                console.error('Error checking email:', error);
            } finally {
                isChecking = false;
            }
        }

        emailInput.addEventListener('input', function() {
            clearTimeout(checkTimeout);
            emailChecked = false; // Reset when email changes
            const email = this.value.trim();

            if (email.length > 0 && email.includes('@')) {
                // Show checking state
                submitBtn.textContent = 'Checking...';
                submitBtn.disabled = true;

                checkTimeout = setTimeout(async () => {
                    await checkEmail(email);
                    submitBtn.textContent = 'Sign In';
                    submitBtn.disabled = false;
                }, 500);
            } else {
                passwordGroup.style.display = 'none';
                passwordInput.required = false;
                adminCredentialsBox.style.display = 'none';
                submitBtn.textContent = 'Sign In';
                submitBtn.disabled = false;
            }
        });

        // Handle form submission - ALWAYS prevent default and validate first
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault(); // Always prevent default submission

            const email = emailInput.value.trim();

            // Validate email exists
            if (!email) {
                emailInput.focus();
                return;
            }

            // If email hasn't been checked, check it first
            if (!emailChecked) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Checking...';
                await checkEmail(email);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Sign In';
            }

            // After checking, validate based on user type
            if (isAdmin) {
                // Admin must have password
                if (!passwordInput.value || passwordInput.value.trim() === '') {
                    passwordInput.focus();
                    return;
                }
            }

            // All validation passed, submit the form
            submitBtn.textContent = 'Signing In...';
            submitBtn.disabled = true;

            // Use a small timeout to allow button text to update
            setTimeout(() => {
                loginForm.submit();
            }, 100);
        });

        // Check on page load if there's an old email value
        if (emailInput.value) {
            // Immediately check the email on page load (no delay)
            checkEmail(emailInput.value);
        }

        // If there's a password error, show the password field immediately
        @if($errors->has('password'))
            passwordGroup.style.display = 'block';
            passwordInput.required = true;
            adminCredentialsBox.style.display = 'block';
            emailChecked = true;
            isAdmin = true;
        @endif

        // Initialize Particles.js
        particlesJS('particles-js', {
            particles: {
                number: { value: 80, density: { enable: true, value_area: 800 } },
                color: { value: '#00D9FF' },
                shape: { type: 'circle' },
                opacity: {
                    value: 0.3,
                    random: true,
                    anim: { enable: true, speed: 1, opacity_min: 0.1, sync: false }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: { enable: true, speed: 2, size_min: 0.1, sync: false }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#00D9FF',
                    opacity: 0.2,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: { enable: true, mode: 'grab' },
                    onclick: { enable: true, mode: 'push' },
                    resize: true
                },
                modes: {
                    grab: { distance: 140, line_linked: { opacity: 0.5 } },
                    push: { particles_nb: 4 }
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>
