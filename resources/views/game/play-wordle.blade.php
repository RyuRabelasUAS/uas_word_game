@extends('layouts.game')

@section('title', $level->title)

@push('styles')
<style>
    .level-header {
        margin-bottom: 3rem;
        text-align: center;
    }

    .level-title h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 3rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-purple) 50%, var(--primary-yellow) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        letter-spacing: 1px;
    }

    .level-title p {
        color: var(--text-muted);
        font-size: 1.1rem;
    }

    .level-badges {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        margin-top: 1rem;
    }

    .wordle-container {
        max-width: 700px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .wordle-board {
        margin-bottom: 2rem;
        padding: 2.5rem;
        background: linear-gradient(135deg, rgba(19, 24, 41, 0.95) 0%, rgba(26, 31, 58, 0.95) 100%);
        border-radius: 24px;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow:
            0 4px 20px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .wordle-grid {
        display: grid;
        gap: 8px;
        margin-bottom: 2rem;
        justify-content: center;
        width: 100%;
    }

    .wordle-row {
        display: grid;
        gap: 8px;
        grid-template-columns: repeat(var(--word-length), 1fr);
        max-width: 100%;
    }

    .wordle-tile {
        width: 100%;
        max-width: 70px;
        aspect-ratio: 1;
        min-width: 45px;
        border: 2px solid rgba(0, 217, 255, 0.3);
        background: rgba(26, 31, 58, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: clamp(1.2rem, 3vw, 2rem);
        font-weight: 900;
        font-family: 'Orbitron', sans-serif;
        text-transform: uppercase;
        color: var(--text-light);
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .wordle-tile.filled {
        border-color: var(--primary-blue);
        transform: scale(1.05);
    }

    .wordle-tile.correct {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
        animation: flip 0.5s ease;
    }

    .wordle-tile.present {
        background: linear-gradient(135deg, var(--primary-yellow) 0%, #eab308 100%);
        border-color: var(--primary-yellow);
        color: var(--bg-dark);
        animation: flip 0.5s ease;
    }

    .wordle-tile.absent {
        background: rgba(100, 116, 139, 0.5);
        border-color: #64748b;
        opacity: 0.7;
        animation: flip 0.5s ease;
    }

    .wordle-tile.shake {
        animation: shake 0.5s ease;
    }

    @keyframes flip {
        0% {
            transform: rotateX(0deg);
        }
        50% {
            transform: rotateX(90deg);
        }
        100% {
            transform: rotateX(0deg);
        }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .keyboard {
        margin-top: 2rem;
    }

    .keyboard-row {
        display: flex;
        gap: 6px;
        justify-content: center;
        margin-bottom: 8px;
    }

    .key {
        min-width: 43px;
        height: 58px;
        background: rgba(26, 31, 58, 0.8);
        border: 1px solid rgba(0, 217, 255, 0.3);
        border-radius: 8px;
        font-family: 'Orbitron', sans-serif;
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-light);
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-transform: uppercase;
    }

    .key:hover {
        background: rgba(0, 217, 255, 0.2);
        border-color: var(--primary-blue);
        transform: translateY(-2px);
    }

    .key:active {
        transform: translateY(0);
    }

    .key.wide {
        min-width: 65px;
        font-size: 0.75rem;
    }

    .key.correct {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-color: #10b981;
    }

    .key.present {
        background: linear-gradient(135deg, var(--primary-yellow) 0%, #eab308 100%);
        border-color: var(--primary-yellow);
        color: var(--bg-dark);
    }

    .key.absent {
        background: rgba(100, 116, 139, 0.3);
        border-color: #64748b;
        opacity: 0.5;
    }

    .stats-panel {
        background: linear-gradient(135deg, rgba(19, 24, 41, 0.95) 0%, rgba(26, 31, 58, 0.95) 100%);
        border-radius: 24px;
        padding: 2.5rem;
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        margin-bottom: 2rem;
        box-shadow:
            0 4px 20px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }

    .stat-box {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.08) 0%, rgba(183, 148, 244, 0.08) 100%);
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.06);
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.12) 0%, rgba(183, 148, 244, 0.12) 100%);
        border-color: rgba(0, 217, 255, 0.2);
        transform: translateY(-2px);
    }

    .stat-value {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary-yellow) 0%, var(--primary-blue) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: block;
    }

    .stat-label {
        color: var(--text-muted);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 0.5rem;
    }

    .message {
        text-align: center;
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-weight: 600;
        display: none;
    }

    .message.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    .message.error {
        background: rgba(239, 68, 68, 0.2);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: #fca5a5;
    }

    .message.success {
        background: rgba(16, 185, 129, 0.2);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #6ee7b7;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .victory-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(10, 14, 39, 0.95);
        backdrop-filter: blur(10px);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .victory-overlay.show {
        display: flex;
        animation: fadeIn 0.3s ease;
    }

    .victory-card {
        background: rgba(19, 24, 41, 0.95);
        border-radius: 30px;
        padding: 3rem;
        text-align: center;
        border: 2px solid var(--primary-blue);
        box-shadow: 0 0 80px rgba(0, 217, 255, 0.5);
        max-width: 500px;
        animation: popIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes popIn {
        0% {
            transform: scale(0.5);
            opacity: 0;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .victory-icon {
        font-size: 6rem;
        margin-bottom: 1rem;
        animation: bounce 1s ease infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .victory-card h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-purple) 50%, var(--primary-yellow) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }

    .victory-card p {
        color: var(--text-muted);
        font-size: 1.2rem;
        margin-bottom: 2rem;
    }

    .victory-stats {
        background: rgba(0, 217, 255, 0.1);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .victory-word {
        font-family: 'Orbitron', sans-serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--primary-yellow);
        letter-spacing: 3px;
        margin-bottom: 1rem;
    }

    .actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    /* Tablet and below */
    @media (max-width: 968px) {
        .level-header {
            margin-bottom: 2rem;
        }

        .level-title h2 {
            font-size: 2.5rem;
        }

        .level-title p {
            font-size: 1rem;
        }

        .level-badges {
            flex-wrap: wrap;
        }

        .stats-panel {
            padding: 2rem;
        }

        .stats-grid {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }

    /* Mobile landscape and below */
    @media (max-width: 768px) {
        .card {
            padding: 1.5rem 1rem;
        }

        .level-header {
            margin-bottom: 1.5rem;
        }

        .level-title h2 {
            font-size: 2rem;
        }

        .level-title p {
            font-size: 0.95rem;
        }

        .level-badges {
            gap: 0.5rem;
        }

        .wordle-container {
            padding: 0 0.5rem;
        }

        .stats-panel {
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }

        .stat-box {
            padding: 1rem;
        }

        .stat-value {
            font-size: 2rem;
        }

        .stat-label {
            font-size: 0.85rem;
        }

        .wordle-board {
            padding: 1.5rem 1rem;
        }

        .wordle-tile {
            max-width: 50px;
            min-width: 35px;
            font-size: clamp(1rem, 4vw, 1.5rem);
            border-width: 1.5px;
        }

        .wordle-grid {
            gap: 5px;
            margin-bottom: 1.5rem;
        }

        .wordle-row {
            gap: 5px;
        }

        .keyboard {
            margin-top: 1.5rem;
        }

        .key {
            min-width: 28px;
            height: 48px;
            font-size: 0.75rem;
        }

        .key.wide {
            min-width: 45px;
            font-size: 0.65rem;
        }

        .keyboard-row {
            gap: 4px;
            margin-bottom: 6px;
        }

        .victory-card {
            padding: 2rem;
            margin: 1rem;
            max-width: 90%;
        }

        .victory-icon {
            font-size: 4rem;
        }

        .victory-card h2 {
            font-size: 2rem;
        }

        .victory-card p {
            font-size: 1rem;
        }

        .victory-word {
            font-size: 1.5rem;
        }

        .actions {
            flex-direction: column;
        }

        .btn {
            width: 100%;
        }
    }

    /* Small mobile devices */
    @media (max-width: 480px) {
        .card {
            padding: 1rem 0.75rem;
        }

        .level-title h2 {
            font-size: 1.6rem;
        }

        .level-title p {
            font-size: 0.85rem;
        }

        .level-badges {
            font-size: 0.8rem;
        }

        .stats-panel {
            padding: 1rem;
            border-radius: 16px;
        }

        .stat-value {
            font-size: 1.6rem;
        }

        .stat-label {
            font-size: 0.75rem;
        }

        .wordle-board {
            padding: 1rem 0.75rem;
            border-radius: 16px;
        }

        .wordle-tile {
            max-width: 42px;
            min-width: 30px;
            border-radius: 6px;
        }

        .wordle-grid {
            gap: 4px;
        }

        .wordle-row {
            gap: 4px;
        }

        .keyboard {
            margin-top: 1rem;
        }

        .key {
            min-width: 24px;
            height: 42px;
            font-size: 0.7rem;
            border-radius: 6px;
        }

        .key.wide {
            min-width: 40px;
            font-size: 0.6rem;
        }

        .keyboard-row {
            gap: 3px;
            margin-bottom: 5px;
        }

        .victory-card {
            padding: 1.5rem 1rem;
            border-radius: 20px;
        }

        .victory-icon {
            font-size: 3rem;
            margin-bottom: 0.75rem;
        }

        .victory-card h2 {
            font-size: 1.6rem;
            margin-bottom: 0.75rem;
        }

        .victory-card p {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .victory-stats {
            padding: 1rem;
        }

        .victory-word {
            font-size: 1.3rem;
            letter-spacing: 2px;
        }

        .btn {
            padding: 0.7rem 1rem;
            font-size: 0.85rem;
        }
    }

    /* Extra small devices */
    @media (max-width: 360px) {
        .level-title h2 {
            font-size: 1.4rem;
        }

        .wordle-tile {
            max-width: 35px;
            min-width: 26px;
        }

        .key {
            min-width: 20px;
            height: 38px;
            font-size: 0.65rem;
        }

        .key.wide {
            min-width: 35px;
            font-size: 0.55rem;
        }

        .keyboard-row {
            gap: 2px;
        }

        .victory-word {
            font-size: 1.1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="card" data-aos="fade-up">
    <div class="level-header">
        <div class="level-title" data-aos="zoom-in">
            <h2>{{ $level->title }}</h2>
            @if($level->description)
                <p>{{ $level->description }}</p>
            @endif
        </div>
        <div class="level-badges" data-aos="fade-up" data-aos-delay="200">
            @if($level->difficulty === 'easy')
                <span class="badge badge-success">⭐ Easy</span>
            @elseif($level->difficulty === 'medium')
                <span class="badge badge-warning">⚔️ Medium</span>
            @else
                <span class="badge badge-danger">🔥 Hard</span>
            @endif
            <span class="badge badge-info">🎯 Wordle</span>
            <a href="{{ route('game.index') }}" class="btn btn-primary">← Back to Levels</a>
        </div>
    </div>

    <div class="wordle-container">
        <div class="stats-panel" data-aos="fade-up" data-aos-delay="300">
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-value" id="attemptsLeft">{{ $grid['maxAttempts'] }}</span>
                    <span class="stat-label">Attempts Left</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value" id="currentGuess">0</span>
                    <span class="stat-label">Current Guess</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value" id="timer">0:00</span>
                    <span class="stat-label">Time</span>
                </div>
            </div>
        </div>

        <div class="wordle-board" data-aos="fade-up" data-aos-delay="400">
            <div id="message" class="message"></div>

            <div class="wordle-grid" id="wordleGrid" style="--word-length: {{ $grid['wordLength'] }};">
                @for($i = 0; $i < $grid['maxAttempts']; $i++)
                    <div class="wordle-row" data-row="{{ $i }}">
                        @for($j = 0; $j < $grid['wordLength']; $j++)
                            <div class="wordle-tile" data-row="{{ $i }}" data-col="{{ $j }}"></div>
                        @endfor
                    </div>
                @endfor
            </div>

            <div class="keyboard" data-aos="fade-up" data-aos-delay="500">
                <div class="keyboard-row">
                    @foreach(['Q', 'W', 'E', 'R', 'T', 'Y', 'U', 'I', 'O', 'P'] as $letter)
                        <button class="key" data-key="{{ $letter }}">{{ $letter }}</button>
                    @endforeach
                </div>
                <div class="keyboard-row">
                    @foreach(['A', 'S', 'D', 'F', 'G', 'H', 'J', 'K', 'L'] as $letter)
                        <button class="key" data-key="{{ $letter }}">{{ $letter }}</button>
                    @endforeach
                </div>
                <div class="keyboard-row">
                    <button class="key wide" data-key="ENTER">ENTER</button>
                    @foreach(['Z', 'X', 'C', 'V', 'B', 'N', 'M'] as $letter)
                        <button class="key" data-key="{{ $letter }}">{{ $letter }}</button>
                    @endforeach
                    <button class="key wide" data-key="BACKSPACE">⌫</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="victory-overlay" id="victoryOverlay">
    <div class="victory-card">
        <div class="victory-icon" id="victoryIcon">🎉</div>
        <h2 id="victoryTitle">CONGRATULATIONS!</h2>
        <p id="victoryMessage">You've guessed the word!</p>
        <div class="victory-stats">
            <div class="victory-word" id="victoryWord"></div>
            <p style="color: var(--text-muted); margin: 0;">Guessed in <span id="victoryAttempts"></span> attempts</p>
            <div id="scoreDisplay" style="margin-top: 1.5rem; display: none;">
                <div style="font-family: 'Orbitron', sans-serif; font-size: 3rem; font-weight: 900; background: linear-gradient(135deg, var(--primary-yellow), var(--primary-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;" id="finalScore">0</div>
                <div style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.5rem;">POINTS EARNED</div>
            </div>
        </div>
        <div class="actions">
            <a href="{{ route('leaderboard.index') }}" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary-yellow), #eab308); color: var(--bg-dark);">🏆 View Leaderboard</a>
            <a href="{{ route('game.index') }}" class="btn btn-primary">← Back to Levels</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const targetWord = '{{ $grid['targetWord'] }}';
    const wordLength = {{ $grid['wordLength'] }};
    const maxAttempts = {{ $grid['maxAttempts'] }};
    const validWords = @json($grid['validWords']);
    const levelId = {{ $level->id }};
    const csrfToken = '{{ csrf_token() }}';

    let currentRow = 0;
    let currentCol = 0;
    let currentGuess = '';
    let gameOver = false;
    let startTime = Date.now();
    let timerInterval = null;

    // Start timer
    startTimer();

    // Handle keyboard input
    document.addEventListener('keydown', handleKeyPress);

    // Handle on-screen keyboard clicks
    document.querySelectorAll('.key').forEach(key => {
        key.addEventListener('click', () => {
            const letter = key.dataset.key;
            handleKey(letter);
        });
    });

    function handleKeyPress(e) {
        if (gameOver) return;

        const key = e.key.toUpperCase();

        if (key === 'ENTER') {
            handleKey('ENTER');
        } else if (key === 'BACKSPACE') {
            handleKey('BACKSPACE');
        } else if (/^[A-Z]$/.test(key)) {
            handleKey(key);
        }
    }

    function handleKey(key) {
        if (gameOver) return;

        if (key === 'ENTER') {
            submitGuess();
        } else if (key === 'BACKSPACE') {
            deleteLetter();
        } else if (currentCol < wordLength) {
            addLetter(key);
        }
    }

    function addLetter(letter) {
        if (currentCol >= wordLength) return;

        const tile = document.querySelector(`.wordle-tile[data-row="${currentRow}"][data-col="${currentCol}"]`);
        tile.textContent = letter;
        tile.classList.add('filled');
        currentGuess += letter;
        currentCol++;
    }

    function deleteLetter() {
        if (currentCol === 0) return;

        currentCol--;
        const tile = document.querySelector(`.wordle-tile[data-row="${currentRow}"][data-col="${currentCol}"]`);
        tile.textContent = '';
        tile.classList.remove('filled');
        currentGuess = currentGuess.slice(0, -1);
    }

    function submitGuess() {
        if (currentCol !== wordLength) {
            showMessage('Not enough letters', 'error');
            shakeRow(currentRow);
            return;
        }

        // Check the guess (no word list validation - accept any guess)
        checkGuess();
    }

    function checkGuess() {
        const guess = currentGuess;
        const result = [];
        const targetLetters = targetWord.split('');
        const guessLetters = guess.split('');
        const letterCount = {};

        // Count letters in target word
        targetLetters.forEach(letter => {
            letterCount[letter] = (letterCount[letter] || 0) + 1;
        });

        // First pass: mark correct positions
        guessLetters.forEach((letter, i) => {
            if (letter === targetLetters[i]) {
                result[i] = 'correct';
                letterCount[letter]--;
            } else {
                result[i] = null;
            }
        });

        // Second pass: mark present letters
        guessLetters.forEach((letter, i) => {
            if (result[i] === null) {
                if (letterCount[letter] > 0) {
                    result[i] = 'present';
                    letterCount[letter]--;
                } else {
                    result[i] = 'absent';
                }
            }
        });

        // Apply results to tiles with animation delay
        result.forEach((status, i) => {
            setTimeout(() => {
                const tile = document.querySelector(`.wordle-tile[data-row="${currentRow}"][data-col="${i}"]`);
                tile.classList.add(status);

                // Update keyboard
                const key = document.querySelector(`.key[data-key="${guessLetters[i]}"]`);
                if (key) {
                    const currentStatus = key.classList.contains('correct') ? 'correct' :
                                        key.classList.contains('present') ? 'present' : null;

                    if (status === 'correct' || (status === 'present' && currentStatus !== 'correct')) {
                        key.classList.remove('absent', 'present', 'correct');
                        key.classList.add(status);
                    } else if (status === 'absent' && !currentStatus) {
                        key.classList.add('absent');
                    }
                }
            }, i * 200);
        });

        // Wait for animations to complete, then handle result
        setTimeout(() => {
            // Check if won
            if (guess === targetWord) {
                setTimeout(() => {
                    gameOver = true;
                    showVictory(currentRow + 1);
                }, 500);
                return;
            }

            // Move to next row
            currentRow++;
            currentCol = 0;
            currentGuess = '';

            // Update stats
            document.getElementById('attemptsLeft').textContent = maxAttempts - currentRow;
            document.getElementById('currentGuess').textContent = currentRow;

            // Check if lost
            if (currentRow >= maxAttempts) {
                setTimeout(() => {
                    gameOver = true;
                    showDefeat();
                }, 500);
            }
        }, wordLength * 200 + 100);
    }

    function shakeRow(row) {
        const tiles = document.querySelectorAll(`.wordle-tile[data-row="${row}"]`);
        tiles.forEach(tile => {
            tile.classList.add('shake');
            setTimeout(() => tile.classList.remove('shake'), 500);
        });
    }

    function showMessage(text, type) {
        const message = document.getElementById('message');
        message.textContent = text;
        message.className = `message ${type} show`;

        setTimeout(() => {
            message.classList.remove('show');
        }, 2000);
    }

    function showVictory(attempts) {
        stopTimer();

        document.getElementById('victoryIcon').textContent = '🎉';
        document.getElementById('victoryTitle').textContent = 'AMAZING!';
        document.getElementById('victoryMessage').textContent = "You've mastered the word!";
        document.getElementById('victoryWord').textContent = targetWord;
        document.getElementById('victoryAttempts').textContent = attempts;
        document.getElementById('victoryOverlay').classList.add('show');

        // Submit score
        submitScore(true, attempts);
    }

    function showDefeat() {
        stopTimer();

        document.getElementById('victoryIcon').textContent = '😔';
        document.getElementById('victoryTitle').textContent = 'GAME OVER';
        document.getElementById('victoryMessage').textContent = 'Better luck next time!';
        document.getElementById('victoryWord').textContent = targetWord;
        document.getElementById('victoryAttempts').textContent = maxAttempts;
        document.getElementById('victoryOverlay').classList.add('show');

        // Submit score (failed)
        submitScore(false, maxAttempts);
    }

    function startTimer() {
        timerInterval = setInterval(() => {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            document.getElementById('timer').textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    function stopTimer() {
        if (timerInterval) {
            clearInterval(timerInterval);
        }
    }

    function getElapsedSeconds() {
        return Math.floor((Date.now() - startTime) / 1000);
    }

    async function submitScore(won, attempts) {
        const timeSeconds = getElapsedSeconds();

        try {
            const response = await fetch('{{ route('score.submit') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    level_id: levelId,
                    time_seconds: timeSeconds,
                    game_type: 'wordle',
                    details: {
                        won: won,
                        attempts_used: attempts,
                        word: targetWord
                    }
                })
            });

            if (response.ok) {
                const data = await response.json();

                // Display the score
                document.getElementById('finalScore').textContent = data.score.toLocaleString();
                document.getElementById('scoreDisplay').style.display = 'block';
            } else {
                console.error('Failed to submit score:', await response.text());
            }
        } catch (error) {
            console.error('Error submitting score:', error);
        }
    }
</script>
@endpush
