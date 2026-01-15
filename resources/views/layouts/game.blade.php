<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Word Game')</title>
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
            --dark-blue: #0088CC;
            --light-yellow: #FFE55C;
            --bg-dark: #0A0E27;
            --bg-card: #131829;
            --bg-hover: #1A1F3A;
            --text-light: #FFFFFF;
            --text-muted: #A0AEC0;
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
            background: var(--bg-dark);
            min-height: 100vh;
            color: var(--text-light);
            position: relative;
            overflow-x: hidden;
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
                radial-gradient(circle at 20% 30%, rgba(0, 217, 255, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(183, 148, 244, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 50% 50%, rgba(255, 215, 0, 0.05) 0%, transparent 50%);
            animation: bgShift 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes bgShift {
            0%, 100% { opacity: 0.5; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }

        .header {
            background: rgba(19, 24, 41, 0.9);
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(0, 217, 255, 0.1);
            padding: 1.5rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0, 217, 255, 0.2);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-title-container {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .header-logo {
            height: 60px;
            width: auto;
            filter: drop-shadow(0 0 20px rgba(0, 217, 255, 0.6));
            animation: logo-glow 3s ease-in-out infinite;
            transition: transform 0.3s ease;
        }

        .header-logo:hover {
            transform: scale(1.1) rotate(5deg);
        }

        @keyframes logo-glow {
            0%, 100% {
                filter: drop-shadow(0 0 20px rgba(0, 217, 255, 0.6));
            }
            50% {
                filter: drop-shadow(0 0 30px rgba(0, 217, 255, 0.9)) drop-shadow(0 0 40px rgba(255, 215, 0, 0.5));
            }
        }

        .header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.2rem;
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-purple) 50%, var(--primary-yellow) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 1px;
            text-shadow: 0 0 40px rgba(0, 217, 255, 0.3);
            position: relative;
            margin: 0;
        }

        .header h1::after {
            content: '🎮';
            position: absolute;
            right: -50px;
            top: -8px;
            font-size: 2rem;
            animation: float 3s ease-in-out infinite;
            filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.6));
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(10deg); }
        }

        .nav {
            display: flex;
            gap: 1rem;
        }

        .nav a {
            color: var(--text-light);
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0, 217, 255, 0.2);
            background: rgba(0, 217, 255, 0.05);
            font-size: 0.95rem;
        }

        .nav a::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-purple));
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav a:hover::before {
            left: 0;
        }

        .nav a:hover {
            transform: translateY(-3px);
            border-color: var(--primary-blue);
            box-shadow: 0 8px 20px rgba(0, 217, 255, 0.4);
        }

        .nav-logout-btn {
            color: var(--text-light);
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow: hidden;
        }

        .nav-logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(220, 38, 38, 0.3));
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav-logout-btn:hover::before {
            left: 0;
        }

        .nav-logout-btn:hover {
            transform: translateY(-3px);
            border-color: rgba(239, 68, 68, 0.6);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
            background: rgba(239, 68, 68, 0.15);
        }

        .nav-logout-btn:active {
            transform: translateY(-1px);
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto 4rem;
            padding: 0 2rem;
            position: relative;
            z-index: 2;
            min-height: calc(100vh - 200px);
        }

        .card {
            background: transparent;
            padding: 3rem 2rem;
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: 'Orbitron', sans-serif;
            text-align: center;
            position: relative;
            overflow: hidden;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn:hover::before {
            left: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-purple) 100%);
            color: white;
            box-shadow: 0 0 30px rgba(0, 217, 255, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 40px rgba(0, 217, 255, 0.6);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--primary-yellow) 0%, var(--light-yellow) 100%);
            color: var(--bg-dark);
            box-shadow: 0 0 30px rgba(255, 215, 0, 0.4);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 40px rgba(255, 215, 0, 0.6);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.15);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-warning {
            background: rgba(251, 191, 36, 0.15);
            color: #fcd34d;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-info {
            background: rgba(0, 217, 255, 0.15);
            color: var(--primary-blue);
            border: 1px solid rgba(0, 217, 255, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 968px) {
            .header {
                padding: 1.25rem 1.5rem;
            }

            .header-content {
                flex-direction: column;
                gap: 1.5rem;
                align-items: flex-start;
            }

            .logo-title-container {
                width: 100%;
                justify-content: space-between;
            }

            .nav {
                width: 100%;
                flex-wrap: wrap;
            }

            .nav a, .nav-logout-btn {
                flex: 1 1 auto;
                min-width: fit-content;
                text-align: center;
            }

            .container {
                margin: 1.5rem auto 3rem;
                padding: 0 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .header {
                padding: 1rem;
            }

            .header-logo {
                height: 45px;
            }

            .logo-title-container {
                gap: 0.75rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .header h1::after {
                right: -35px;
                top: -5px;
                font-size: 1.3rem;
            }

            .nav {
                gap: 0.5rem;
                flex-direction: row;
            }

            .nav a, .nav-logout-btn {
                padding: 0.65rem 1rem;
                font-size: 0.85rem;
                border-radius: 8px;
            }

            .card {
                padding: 2rem 1.5rem;
            }

            .container {
                padding: 0 1rem;
                margin: 1rem auto 2rem;
            }

            .btn {
                padding: 0.8rem 1.8rem;
                font-size: 0.9rem;
            }

            .badge {
                padding: 0.4rem 0.8rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 0.75rem;
            }

            .header-logo {
                height: 38px;
            }

            .header h1 {
                font-size: 1.2rem;
            }

            .header h1::after {
                right: -30px;
                top: -3px;
                font-size: 1.1rem;
            }

            .nav {
                flex-direction: column;
                gap: 0.4rem;
            }

            .nav a, .nav-logout-btn {
                width: 100%;
                padding: 0.6rem 0.8rem;
                font-size: 0.8rem;
            }

            .card {
                padding: 1.5rem 1rem;
            }

            .container {
                padding: 0 0.75rem;
            }

            .btn {
                padding: 0.7rem 1.5rem;
                font-size: 0.85rem;
                border-radius: 10px;
            }

            .badge {
                padding: 0.35rem 0.7rem;
                font-size: 0.7rem;
            }
        }

        @media (max-width: 360px) {
            .header-logo {
                height: 32px;
            }

            .header h1 {
                font-size: 1rem;
            }

            .header h1::after {
                display: none;
            }

            .nav a, .nav-logout-btn {
                font-size: 0.75rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div id="particles-js"></div>
    <div class="animated-bg"></div>

    <header class="header" data-aos="fade-down" data-aos-duration="800">
        <div class="header-content">
            <div class="logo-title-container">
                <img src="{{ asset('images/logo.png') }}" alt="UAS Logo" class="header-logo">
                <h1>WORD NEXUS</h1>
            </div>
            <nav class="nav">
                <a href="{{ route('game.index') }}">🎮 Select Game</a>
                <a href="{{ route('leaderboard.index') }}">🏆 Leaderboard</a>
                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.levels.index') }}">🔧 Admin Panel</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="nav-logout-btn">🚪 Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">🔑 Login</a>
                @endauth
            </nav>
        </div>
    </header>

    <div class="container">
        @if(session('error'))
            <div class="flash-message error" style="background: rgba(239, 68, 68, 0.2); border: 2px solid rgba(239, 68, 68, 0.5); color: #fca5a5; padding: 1.5rem 2rem; border-radius: 15px; margin-bottom: 2rem; font-weight: 600; display: flex; align-items: center; gap: 1rem; animation: slideDown 0.5s ease;" data-aos="fade-down">
                <span style="font-size: 1.5rem;">⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="flash-message success" style="background: rgba(16, 185, 129, 0.2); border: 2px solid rgba(16, 185, 129, 0.5); color: #6ee7b7; padding: 1.5rem 2rem; border-radius: 15px; margin-bottom: 2rem; font-weight: 600; display: flex; align-items: center; gap: 1rem; animation: slideDown 0.5s ease;" data-aos="fade-down">
                <span style="font-size: 1.5rem;">✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100
        });

        // Initialize Particles.js
        particlesJS('particles-js', {
            particles: {
                number: { value: 60, density: { enable: true, value_area: 800 } },
                color: { value: '#00D9FF' },
                shape: { type: 'circle' },
                opacity: {
                    value: 0.25,
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
                    opacity: 0.15,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 1.5,
                    direction: 'none',
                    random: true,
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
                    grab: { distance: 120, line_linked: { opacity: 0.4 } },
                    push: { particles_nb: 3 }
                }
            },
            retina_detect: true
        });
    </script>
</body>
</html>
