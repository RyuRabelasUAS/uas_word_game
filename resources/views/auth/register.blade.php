<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Word Game</title>
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
            padding: 2rem 0;
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
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .login-container:hover {
            box-shadow:
                0 25px 70px rgba(0, 0, 0, 0.6),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .login-brand {
            padding: 4rem;
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.05) 0%, rgba(183, 148, 244, 0.05) 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .brand-content {
            position: relative;
            z-index: 2;
        }

        .brand-logo {
            font-size: 5rem;
            margin-bottom: 2rem;
        }

        .brand-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
            line-height: 1.1;
            letter-spacing: -1px;
        }

        .brand-subtitle {
            font-size: 1.1rem;
            color: var(--text-secondary);
            font-weight: 400;
            line-height: 1.6;
            margin-bottom: 3rem;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .feature-item {
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 16px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .feature-item:hover {
            background: rgba(0, 217, 255, 0.05);
            border-color: rgba(0, 217, 255, 0.2);
            transform: translateY(-2px);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            display: block;
        }

        .feature-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-family: 'Orbitron', sans-serif;
        }

        .feature-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .login-form {
            padding: 4rem 3.5rem;
            background: rgba(10, 14, 39, 0.6);
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            letter-spacing: -1px;
        }

        .form-header p {
            font-size: 0.95rem;
            color: var(--text-secondary);
            font-weight: 400;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
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
            margin-bottom: 0.6rem;
            font-weight: 600;
            color: var(--text-primary);
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
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-submit {
            width: 100%;
            padding: 1.1rem 1.5rem;
            border: 1px solid rgba(0, 217, 255, 0.3);
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.15) 0%, rgba(183, 148, 244, 0.15) 100%);
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.25) 0%, rgba(183, 148, 244, 0.25) 100%);
            border-color: rgba(0, 217, 255, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
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

            .form-row {
                grid-template-columns: 1fr;
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
                    Join our next-generation crossword gaming platform and challenge your word mastery
                </p>

                <div class="feature-grid">
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="500">
                        <span class="feature-icon">⚡</span>
                        <div class="feature-title">Instant Access</div>
                        <div class="feature-desc">No password required, start playing immediately</div>
                    </div>
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="600">
                        <span class="feature-icon">🎯</span>
                        <div class="feature-title">Track Progress</div>
                        <div class="feature-desc">Monitor your scores and compete on leaderboards</div>
                    </div>
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="700">
                        <span class="feature-icon">📊</span>
                        <div class="feature-title">Team Play</div>
                        <div class="feature-desc">Represent your company and compete with colleagues</div>
                    </div>
                    <div class="feature-item" data-aos="fade-up" data-aos-delay="800">
                        <span class="feature-icon">🏆</span>
                        <div class="feature-title">Achievements</div>
                        <div class="feature-desc">Unlock badges and climb the ranks</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="login-form">
            <div class="form-header" data-aos="fade-left" data-aos-delay="300">
                <h1>JOIN NOW</h1>
                <p>Create your account and start playing</p>
            </div>

            @if($errors->any())
                <div class="alert" data-aos="shake">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M10 0C4.48 0 0 4.48 0 10C0 15.52 4.48 20 10 20C15.52 20 20 15.52 20 10C20 4.48 15.52 0 10 0ZM11 15H9V13H11V15ZM11 11H9V5H11V11Z" fill="#fca5a5"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="form-group" data-aos="fade-left" data-aos-delay="400">
                    <label for="name" class="form-label">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        placeholder="Enter your full name"
                    >
                    @error('name')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" data-aos="fade-left" data-aos-delay="450">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email') }}"
                        required
                        placeholder="Enter your email"
                    >
                    @error('email')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" data-aos="fade-left" data-aos-delay="500">
                    <label for="mobile_number" class="form-label">Mobile Number</label>
                    <input
                        type="tel"
                        id="mobile_number"
                        name="mobile_number"
                        class="form-control"
                        value="{{ old('mobile_number') }}"
                        required
                        placeholder="Enter your mobile number"
                    >
                    @error('mobile_number')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row" data-aos="fade-left" data-aos-delay="550">
                    <div class="form-group">
                        <label for="company" class="form-label">Company</label>
                        <input
                            type="text"
                            id="company"
                            name="company"
                            class="form-control"
                            value="{{ old('company') }}"
                            required
                            placeholder="Your company"
                        >
                        @error('company')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="position" class="form-label">Position</label>
                        <input
                            type="text"
                            id="position"
                            name="position"
                            class="form-control"
                            value="{{ old('position') }}"
                            required
                            placeholder="Your position"
                        >
                        @error('position')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-submit" data-aos="fade-up" data-aos-delay="600">
                    Create Account
                </button>
            </form>

            <div class="form-footer" data-aos="fade-up" data-aos-delay="700">
                <a href="{{ route('login') }}">Already have an account? Sign in</a>
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
