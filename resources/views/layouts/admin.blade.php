<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Word Game</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Orbitron:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.7);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0d0d1a 0%, #1a1a2e 100%);
            color: var(--text-light);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .header {
            background: rgba(19, 24, 41, 0.9);
            backdrop-filter: blur(20px);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5), 0 0 0 1px rgba(0, 217, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 1px solid rgba(0, 217, 255, 0.2);
        }

        .header h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, #FFFFFF 0%, var(--primary-blue) 50%, var(--accent-cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInDown 0.6s ease-out;
            letter-spacing: -0.3px;
        }

        .nav {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .nav a {
            color: var(--text-light);
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 600;
            border: 1px solid rgba(0, 217, 255, 0.2);
            background: rgba(0, 217, 255, 0.05);
            font-size: 0.95rem;
        }

        .nav a:hover {
            transform: translateY(-3px);
            border-color: var(--primary-blue);
            box-shadow: 0 8px 20px rgba(0, 217, 255, 0.4);
            background: rgba(0, 217, 255, 0.1);
        }

        .nav a.active {
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.2) 0%, rgba(183, 148, 244, 0.2) 100%);
            border-color: rgba(0, 217, 255, 0.4);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-light);
            font-weight: 500;
            animation: fadeInRight 0.6s ease-out;
        }

        .logout-form {
            display: inline;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #f87171;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .btn-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
            background: rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem 4rem 2rem;
            animation: fadeIn 0.6s ease-out;
            min-height: calc(100vh - 200px);
        }

        .card {
            background: linear-gradient(135deg, rgba(19, 24, 41, 0.95) 0%, rgba(26, 31, 58, 0.95) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 0;
            margin-bottom: 2rem;
            transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
            box-shadow:
                0 4px 20px rgba(0, 0, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(0, 217, 255, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            border-color: rgba(0, 217, 255, 0.3);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 2rem;
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.08) 0%, rgba(183, 148, 244, 0.08) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .card-header h2 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #FFFFFF;
            letter-spacing: -0.3px;
            margin: 0;
        }

        .card-body {
            padding: 3rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 2rem;
            }
        }

        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border: 1px solid;
            border-radius: 12px;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.15) 0%, rgba(183, 148, 244, 0.15) 100%);
            border-color: rgba(0, 217, 255, 0.3);
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, rgba(0, 217, 255, 0.25) 0%, rgba(183, 148, 244, 0.25) 100%);
            border-color: rgba(0, 217, 255, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 217, 255, 0.25);
        }

        .btn-success {
            background: rgba(16, 185, 129, 0.15);
            border-color: rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .btn-success:hover {
            background: rgba(16, 185, 129, 0.25);
            border-color: rgba(16, 185, 129, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.25);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            border-color: rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(239, 68, 68, 0.25);
        }

        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            animation: fadeInDown 0.4s ease-out;
            border: 1px solid;
            border-left-width: 4px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            color: #6ee7b7;
            border-color: #10b981;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
            border-color: #ef4444;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            table-layout: auto;
        }

        th, td {
            padding: 1rem 0.75rem;
            text-align: left;
            font-size: 0.9rem;
        }

        th {
            background: rgba(0, 217, 255, 0.08);
            color: rgba(0, 217, 255, 0.9);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.75rem;
            padding: 1rem 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        th:first-child,
        td:first-child {
            padding-left: 1.5rem;
        }

        th:last-child,
        td:last-child {
            padding-right: 1.5rem;
        }

        tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        tbody tr:hover {
            background: rgba(0, 217, 255, 0.05);
        }

        td {
            color: var(--text-light);
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.85rem;
            font-weight: 600;
            color: #FFFFFF;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 1.1rem 1.4rem;
            border: 1px solid rgba(0, 217, 255, 0.2);
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
            background: rgba(255, 255, 255, 0.03);
            color: var(--text-light);
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

        /* Fix for select dropdowns - ensure readable text */
        select.form-control {
            background: var(--bg-card);
            color: var(--text-light);
        }

        select.form-control option {
            background: var(--bg-dark);
            color: var(--text-light);
            padding: 0.5rem;
        }

        .form-error {
            color: #fca5a5;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .action-buttons .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 1rem;
            border-radius: 100px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .badge-warning {
            background: rgba(251, 191, 36, 0.1);
            border-color: rgba(251, 191, 36, 0.3);
            color: #fbbf24;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .badge-info {
            background: rgba(0, 217, 255, 0.08);
            border-color: rgba(0, 217, 255, 0.2);
            color: rgba(0, 217, 255, 0.9);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .nav {
                flex-wrap: wrap;
            }

            .card {
                padding: 1.5rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            table {
                font-size: 0.8rem;
            }

            th, td {
                padding: 0.65rem 0.5rem;
                font-size: 0.8rem;
            }

            th {
                font-size: 0.7rem;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.4rem;
            }

            .action-buttons .btn-sm {
                width: 100%;
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }

            .badge {
                padding: 0.3rem 0.7rem;
                font-size: 0.75rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <img src="{{ asset('images/logo.png') }}" alt="UAS Logo" style="height: 50px; width: auto; filter: drop-shadow(0 0 15px rgba(0, 217, 255, 0.5));">
                    <h1 style="margin: 0;">Word Game - Admin Panel</h1>
                </div>
                <nav class="nav">
                    <a href="{{ route('game.index') }}">View Game</a>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a>
                    <a href="{{ route('admin.words.index') }}" class="{{ request()->routeIs('admin.words.*') ? 'active' : '' }}">Words</a>
                    <a href="{{ route('admin.levels.index') }}" class="{{ request()->routeIs('admin.levels.*') ? 'active' : '' }}">Levels</a>
                    <a href="{{ route('admin.scores.index') }}" class="{{ request()->routeIs('admin.scores.*') ? 'active' : '' }}">Scores</a>
                </nav>
            </div>
            <div class="user-info">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            </div>
        </div>
    </header>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success animate__animated animate__fadeInDown">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error animate__animated animate__fadeInDown">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

    @stack('scripts')

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            offset: 100,
            delay: 50
        });
    </script>
</body>
</html>
