@extends('layouts.game')

@section('title', 'Leaderboard')

@push('styles')
<style>
    .page-title {
        text-align: center;
        margin-bottom: 3rem;
    }

    .page-title h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 4rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-purple) 50%, var(--primary-yellow) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        letter-spacing: 2px;
        text-shadow: 0 0 60px rgba(0, 217, 255, 0.4);
        position: relative;
    }

    .page-title h2::before {
        content: '🏆';
        position: absolute;
        left: -90px;
        animation: trophy-spin 3s ease-in-out infinite;
        filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.9));
    }

    .page-title h2::after {
        content: '🏆';
        position: absolute;
        right: -90px;
        animation: trophy-spin 3s ease-in-out infinite reverse;
        filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.9));
    }

    @keyframes trophy-spin {
        0%, 100% {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
        50% {
            transform: scale(1.3) rotate(15deg);
            opacity: 0.8;
        }
    }

    .page-title p {
        color: var(--text-muted);
        font-size: 1.3rem;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    .filter-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 3rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 1rem 2.5rem;
        background: rgba(26, 31, 58, 0.6);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(0, 217, 255, 0.3);
        border-radius: 12px;
        color: var(--text-muted);
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .filter-tab:hover {
        transform: translateY(-3px);
        border-color: var(--primary-blue);
        box-shadow: 0 8px 24px rgba(0, 217, 255, 0.3);
    }

    .filter-tab.active {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.2) 0%, rgba(183, 148, 244, 0.2) 100%);
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        box-shadow:
            0 0 30px rgba(0, 217, 255, 0.4),
            inset 0 0 20px rgba(0, 217, 255, 0.1);
    }

    .leaderboard-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 1rem;
    }

    .leaderboard-table thead th {
        padding: 1.5rem 1rem;
        text-align: left;
        font-family: 'Orbitron', sans-serif;
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--primary-blue);
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 2px solid rgba(0, 217, 255, 0.3);
    }

    .leaderboard-table thead th:first-child {
        text-align: center;
        width: 80px;
    }

    .leaderboard-table tbody {
        position: relative;
    }

    .leaderboard-row {
        background: rgba(26, 31, 58, 0.6);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(0, 217, 255, 0.2);
        transition: all 0.3s ease;
        position: relative;
    }

    .leaderboard-row:hover {
        transform: translateX(8px);
        border-color: var(--primary-blue);
        box-shadow:
            0 4px 20px rgba(0, 217, 255, 0.3),
            inset 0 0 20px rgba(0, 217, 255, 0.05);
    }

    .leaderboard-row.new-score {
        animation: score-flash 1.5s ease-in-out;
    }

    @keyframes score-flash {
        0%, 100% {
            background: rgba(26, 31, 58, 0.6);
        }
        50% {
            background: rgba(0, 217, 255, 0.3);
            box-shadow: 0 0 40px rgba(0, 217, 255, 0.6);
        }
    }

    .leaderboard-row td {
        padding: 1.5rem 1rem;
        color: var(--text-color);
    }

    .leaderboard-row td:first-child {
        border-radius: 12px 0 0 12px;
    }

    .leaderboard-row td:last-child {
        border-radius: 0 12px 12px 0;
    }

    .rank-cell {
        text-align: center;
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        font-size: 1.5rem;
    }

    .rank-1 {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.6));
    }

    .rank-2 {
        background: linear-gradient(135deg, #C0C0C0, #E8E8E8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 0 10px rgba(192, 192, 192, 0.6));
    }

    .rank-3 {
        background: linear-gradient(135deg, #CD7F32, #E6A857);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        filter: drop-shadow(0 0 10px rgba(205, 127, 50, 0.6));
    }

    .player-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .player-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-blue), var(--accent-purple));
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        font-size: 1.2rem;
        color: white;
        border: 2px solid rgba(0, 217, 255, 0.4);
    }

    .player-name {
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--text-color);
    }

    .level-name {
        color: var(--text-muted);
        font-size: 0.95rem;
    }

    .score-cell {
        font-family: 'Orbitron', sans-serif;
        font-weight: 800;
        font-size: 1.5rem;
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-yellow));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .time-cell {
        color: var(--text-muted);
        font-size: 1rem;
    }

    .game-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .game-wordsearch {
        background: rgba(0, 217, 255, 0.2);
        color: var(--primary-blue);
        border: 1px solid rgba(0, 217, 255, 0.4);
    }

    .game-crossword {
        background: rgba(183, 148, 244, 0.2);
        color: var(--accent-purple);
        border: 1px solid rgba(183, 148, 244, 0.4);
    }

    .game-wordle {
        background: rgba(255, 215, 0, 0.2);
        color: var(--primary-yellow);
        border: 1px solid rgba(255, 215, 0, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 6rem 2rem;
    }

    .empty-icon {
        font-size: 8rem;
        margin-bottom: 2rem;
        animation: float 4s ease-in-out infinite;
        filter: drop-shadow(0 0 40px rgba(255, 215, 0, 0.5));
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        50% {
            transform: translateY(-30px) rotate(10deg);
        }
    }

    .empty-state h3 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-yellow));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        font-weight: 800;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 2.5rem;
        font-size: 1.2rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .page-title h2 {
            font-size: 2.5rem;
        }

        .page-title h2::before,
        .page-title h2::after {
            display: none;
        }

        .filter-tabs {
            gap: 0.5rem;
        }

        .filter-tab {
            padding: 0.8rem 1.5rem;
            font-size: 0.85rem;
        }

        .leaderboard-table {
            font-size: 0.9rem;
        }

        .leaderboard-table thead {
            display: none;
        }

        .leaderboard-row {
            display: block;
            margin-bottom: 1rem;
            border-radius: 12px;
        }

        .leaderboard-row td {
            display: block;
            padding: 0.8rem 1rem;
            border: none !important;
        }

        .leaderboard-row td:first-child,
        .leaderboard-row td:last-child {
            border-radius: 0;
        }

        .rank-cell {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')
<div class="card" data-aos="fade-up" data-aos-duration="800">
    <div class="page-title" data-aos="zoom-in" data-aos-delay="200">
        <h2>HALL OF LEGENDS</h2>
        <p>Top players who dominate the word arena</p>
    </div>

    <div class="filter-tabs" data-aos="fade-up" data-aos-delay="300">
        <button class="filter-tab active" data-filter="all" onclick="filterLeaderboard('all')">
            🎯 All Games
        </button>
        <button class="filter-tab" data-filter="wordsearch" onclick="filterLeaderboard('wordsearch')">
            🔍 Word Search
        </button>
        <button class="filter-tab" data-filter="crossword" onclick="filterLeaderboard('crossword')">
            📝 Crossword
        </button>
        <button class="filter-tab" data-filter="wordle" onclick="filterLeaderboard('wordle')">
            🎲 Wordle
        </button>
    </div>

    <div id="leaderboard-container">
        <!-- Leaderboard content will be loaded here -->
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])
<script>
    let currentFilter = 'all';

    // Load leaderboard on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadLeaderboard();
        setupRealtimeUpdates();
    });

    function filterLeaderboard(gameType) {
        currentFilter = gameType;

        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        document.querySelector(`[data-filter="${gameType}"]`).classList.add('active');

        // Load filtered leaderboard
        loadLeaderboard();
    }

    async function loadLeaderboard() {
        const container = document.getElementById('leaderboard-container');
        container.innerHTML = '<div style="text-align: center; padding: 4rem;"><div style="font-size: 3rem;">⏳</div><p style="color: var(--text-muted); margin-top: 1rem;">Loading leaderboard...</p></div>';

        try {
            const url = new URL('{{ route('api.leaderboard') }}');
            if (currentFilter !== 'all') {
                url.searchParams.append('game_type', currentFilter);
            }
            url.searchParams.append('limit', 50);

            const response = await fetch(url);
            const data = await response.json();

            if (data.scores && data.scores.length > 0) {
                renderLeaderboard(data.scores);
            } else {
                renderEmptyState();
            }
        } catch (error) {
            console.error('Error loading leaderboard:', error);
            container.innerHTML = '<div class="empty-state"><div class="empty-icon">⚠️</div><h3>ERROR LOADING LEADERBOARD</h3><p>Please try again later.</p></div>';
        }
    }

    function renderLeaderboard(scores) {
        const container = document.getElementById('leaderboard-container');

        let html = '<table class="leaderboard-table">';
        html += '<thead><tr>';
        html += '<th>Rank</th>';
        html += '<th>Player</th>';
        html += '<th>Level</th>';
        html += '<th>Score</th>';
        html += '<th>Time</th>';
        html += '<th>Game</th>';
        html += '</tr></thead>';
        html += '<tbody>';

        scores.forEach((score, index) => {
            const rank = index + 1;
            const rankClass = rank === 1 ? 'rank-1' : rank === 2 ? 'rank-2' : rank === 3 ? 'rank-3' : '';
            const initial = score.user.name.charAt(0).toUpperCase();
            const minutes = Math.floor(score.time_seconds / 60);
            const seconds = score.time_seconds % 60;
            const timeStr = minutes > 0 ? `${minutes}m ${seconds}s` : `${seconds}s`;
            const gameClass = `game-${score.game_type}`;
            const gameIcon = score.game_type === 'wordsearch' ? '🔍' : score.game_type === 'crossword' ? '📝' : '🎲';

            html += `<tr class="leaderboard-row" data-score-id="${score.id}">`;
            html += `<td class="rank-cell ${rankClass}">${rank === 1 ? '🥇' : rank === 2 ? '🥈' : rank === 3 ? '🥉' : rank}</td>`;
            html += `<td><div class="player-info"><div class="player-avatar">${initial}</div><div class="player-name">${score.user.name}</div></div></td>`;
            html += `<td><div class="level-name">${score.level.title}</div></td>`;
            html += `<td class="score-cell">${score.score.toLocaleString()}</td>`;
            html += `<td class="time-cell">${timeStr}</td>`;
            html += `<td><span class="game-badge ${gameClass}">${gameIcon} ${score.game_type}</span></td>`;
            html += '</tr>';
        });

        html += '</tbody></table>';

        container.innerHTML = html;
    }

    function renderEmptyState() {
        const container = document.getElementById('leaderboard-container');
        const filterText = currentFilter === 'all' ? 'any game' : currentFilter;

        container.innerHTML = `
            <div class="empty-state" data-aos="zoom-in">
                <div class="empty-icon">🏆</div>
                <h3>NO SCORES YET</h3>
                <p>Be the first to dominate ${filterText} and claim your spot on the leaderboard!</p>
                <a href="{{ route('game.index') }}" class="btn btn-primary">🎮 Start Playing</a>
            </div>
        `;
    }

    function setupRealtimeUpdates() {
        // Check if Echo is available (loaded from bootstrap.js)
        if (typeof window.Echo === 'undefined') {
            console.warn('Echo not loaded. Real-time updates disabled.');
            // Fallback: poll every 30 seconds
            setInterval(loadLeaderboard, 30000);
            return;
        }

        // Listen to the leaderboard channel for new scores
        window.Echo.channel('leaderboard')
            .listen('.score.submitted', (data) => {
                console.log('🎮 New score submitted in real-time:', data);
                handleNewScore(data);
            });

        console.log('✅ Real-time leaderboard updates enabled via Reverb');

        // Fallback: poll every 60 seconds as backup
        setInterval(loadLeaderboard, 60000);
    }

    // Function to handle new score submissions in real-time
    function handleNewScore(scoreData) {
        // Only update if the new score matches current filter
        if (currentFilter !== 'all' && scoreData.game_type !== currentFilter) {
            return;
        }

        // Reload the leaderboard to show updated rankings
        loadLeaderboard();

        // Find the new score row and add animation
        setTimeout(() => {
            const newRow = document.querySelector(`[data-score-id="${scoreData.id}"]`);
            if (newRow) {
                newRow.classList.add('new-score');
                setTimeout(() => {
                    newRow.classList.remove('new-score');
                }, 1500);
            }
        }, 500);
    }
</script>
@endpush
