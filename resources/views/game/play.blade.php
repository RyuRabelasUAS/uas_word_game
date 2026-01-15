@extends('layouts.game')

@section('title', $level->title)

@push('styles')
<style>
    .level-header {
        margin-bottom: 2rem;
        animation: fadeInDown 0.6s ease-out;
    }

    .level-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .level-title-wrapper h2 {
        font-size: 2.2rem;
        font-weight: 900;
        color: var(--primary-blue);
        margin-bottom: 0.5rem;
        letter-spacing: -1px;
    }

    .level-title-wrapper p {
        color: #64748b;
        font-size: 1.05rem;
    }

    .level-badges {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .game-container {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2.5rem;
        align-items: start;
        animation: fadeInUp 0.8s ease-out;
    }

    @media (max-width: 968px) {
        .game-container {
            grid-template-columns: 1fr;
        }
    }

    .grid-container {
        display: inline-block;
        background: white;
        padding: 1.5rem;
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(15, 26, 250, 0.15);
        border: 3px solid rgba(255, 242, 0, 0.3);
        transition: all 0.3s ease;
        max-width: 100%;
        width: fit-content;
    }

    .grid-container:hover {
        border-color: var(--primary-yellow);
        box-shadow: 0 20px 50px rgba(15, 26, 250, 0.25);
    }

    .grid {
        display: grid;
        gap: 3px;
        user-select: none;
        background: var(--primary-blue);
        padding: 3px;
        border-radius: 15px;
        width: fit-content;
        margin: 0 auto;
    }

    .cell {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.05rem;
        font-family: 'Poppins', sans-serif;
        background: #d1d5db;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border-radius: 8px;
        color: #1e293b;
        position: relative;
    }

    /* Dynamic grid sizing based on grid size */
    @media (max-width: 968px) {
        .cell {
            width: calc(min(40px, (100vw - 8rem) / var(--grid-size, 10)));
            height: calc(min(40px, (100vw - 8rem) / var(--grid-size, 10)));
            font-size: clamp(0.65rem, 1.5vw, 1.05rem);
        }
    }

    .cell-number {
        position: absolute;
        top: 2px;
        left: 2px;
        font-size: 0.6rem;
        font-weight: 600;
        color: var(--primary-blue);
        z-index: 10;
        pointer-events: none;
    }

    .cell:hover {
        background: #9ca3af;
        transform: scale(1.15);
        z-index: 10;
    }

    .cell.selected {
        background: #1e40af;
        color: #fbbf24;
        transform: scale(1.1);
    }

    .cell.found {
        background: linear-gradient(135deg, #ca8a04 0%, #eab308 100%);
        color: #1e293b;
        animation: pulse 0.5s ease;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .cell.empty {
        background: #e2e8f0;
        cursor: default;
        color: #94a3b8;
    }

    .cell.empty:hover {
        transform: none;
        background: #e2e8f0;
    }

    .cell-input {
        width: 100%;
        height: 100%;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: 700;
        font-size: 1.05rem;
        font-family: 'Poppins', sans-serif;
        color: var(--primary-blue);
        outline: none;
        text-transform: uppercase;
        padding: 0;
        cursor: text;
    }

    .cell.correct {
        background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
        color: white;
    }

    .cell.incorrect {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        color: white;
        animation: shake 0.5s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .sidebar {
        background: white;
        padding: 2rem;
        border-radius: 25px;
        box-shadow: 0 15px 40px rgba(15, 26, 250, 0.15);
        border: 3px solid rgba(255, 242, 0, 0.3);
        position: sticky;
        top: 120px;
        animation: fadeInRight 0.8s ease-out;
    }

    .sidebar h3 {
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--primary-blue);
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .progress-bar {
        height: 12px;
        background: #e5e7eb;
        border-radius: 50px;
        overflow: hidden;
        margin-bottom: 2rem;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-yellow) 0%, var(--light-yellow) 100%);
        transition: width 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border-radius: 50px;
        box-shadow: 0 2px 8px rgba(255, 242, 0, 0.6);
    }

    .stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .stats.three-cols {
        grid-template-columns: 1fr 1fr 1fr;
    }

    .timer-box {
        grid-column: 1 / -1;
        background: linear-gradient(135deg, var(--primary-yellow) 0%, var(--light-yellow) 100%);
        color: var(--primary-blue);
    }

    .stat-box {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--dark-blue) 100%);
        padding: 1.5rem;
        border-radius: 20px;
        color: white;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(15, 26, 250, 0.3);
    }

    .stat-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(15, 26, 250, 0.4);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 900;
        display: block;
        color: var(--primary-yellow);
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.95;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .word-list {
        list-style: none;
        max-height: 400px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .clue-list {
        max-height: none;
        margin-bottom: 1rem;
    }

    .word-list::-webkit-scrollbar {
        width: 8px;
    }

    .word-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .word-list::-webkit-scrollbar-thumb {
        background: var(--primary-blue);
        border-radius: 10px;
    }

    .word-list::-webkit-scrollbar-thumb:hover {
        background: var(--dark-blue);
    }

    .word-item {
        padding: 1rem;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border-left: 4px solid transparent;
        cursor: pointer;
    }

    .word-item:hover {
        transform: translateX(5px);
        background: linear-gradient(135deg, rgba(255, 242, 0, 0.2) 0%, rgba(255, 242, 0, 0.1) 100%);
        border-left-color: var(--primary-yellow);
    }

    .word-item.found {
        background: linear-gradient(135deg, var(--primary-yellow) 0%, var(--light-yellow) 100%);
        border-left-color: var(--primary-blue);
        text-decoration: line-through;
        opacity: 0.8;
        animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 0.8;
        }
    }

    .word-text {
        font-weight: 800;
        color: var(--primary-blue);
        display: block;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }

    .clue-text {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
        line-height: 1.4;
    }

    .victory-message {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0);
        background: white;
        padding: 4rem 3rem;
        border-radius: 30px;
        box-shadow: 0 30px 80px rgba(0,0,0,0.4);
        text-align: center;
        z-index: 2000;
        transition: transform 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border: 5px solid var(--primary-yellow);
        max-width: 500px;
    }

    .victory-message.show {
        transform: translate(-50%, -50%) scale(1);
        animation: bounce 0.8s ease;
    }

    @keyframes bounce {
        0%, 100% { transform: translate(-50%, -50%) scale(1); }
        50% { transform: translate(-50%, -50%) scale(1.1); }
    }

    .victory-icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        animation: spin 1s ease;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .victory-message h2 {
        font-size: 2.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-yellow) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }

    .victory-message p {
        color: #64748b;
        font-size: 1.2rem;
        margin-bottom: 2rem;
        font-weight: 500;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 26, 250, 0.8);
        backdrop-filter: blur(10px);
        z-index: 1999;
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .overlay.show {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Tablet and below */
    @media (max-width: 968px) {
        .level-header {
            margin-bottom: 1.5rem;
        }

        .level-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .level-title-wrapper h2 {
            font-size: 1.8rem;
        }

        .level-title-wrapper p {
            font-size: 0.95rem;
        }

        .sidebar {
            position: static;
            top: auto;
        }

        .stats {
            grid-template-columns: 1fr 1fr;
        }

        .timer-box {
            grid-column: 1 / -1;
        }
    }

    /* Mobile landscape and below */
    @media (max-width: 768px) {
        .level-title-wrapper h2 {
            font-size: 1.5rem;
        }

        .level-badges {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .grid-container {
            padding: 0.75rem;
            margin: 0 auto;
            max-width: 100%;
            overflow-x: auto;
        }

        .grid {
            gap: 2px;
            padding: 2px;
        }

        .cell {
            width: calc(min(32px, (100vw - 6rem) / var(--grid-size, 10)));
            height: calc(min(32px, (100vw - 6rem) / var(--grid-size, 10)));
            font-size: clamp(0.6rem, 1.2vw, 0.85rem);
        }

        .cell-number {
            font-size: clamp(0.35rem, 0.8vw, 0.5rem);
            top: 1px;
            left: 1px;
        }

        .cell-input {
            font-size: clamp(0.6rem, 1.2vw, 0.85rem);
        }

        .sidebar {
            padding: 1.5rem;
        }

        .sidebar h3 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .stat-box {
            padding: 1rem;
        }

        .stat-value {
            font-size: 2rem;
        }

        .stat-label {
            font-size: 0.8rem;
        }

        .word-list {
            max-height: 300px;
        }

        .word-item {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .word-text {
            font-size: 1rem;
        }

        .clue-text {
            font-size: 0.85rem;
        }

        .victory-message {
            padding: 2rem 1.5rem;
            max-width: 90%;
        }

        .victory-icon {
            font-size: 3rem;
        }

        .victory-message h2 {
            font-size: 1.8rem;
        }

        .victory-message p {
            font-size: 1rem;
        }
    }

    /* Small mobile devices */
    @media (max-width: 480px) {
        .card {
            padding: 1rem;
        }

        .level-title-wrapper h2 {
            font-size: 1.3rem;
        }

        .level-title-wrapper p {
            font-size: 0.85rem;
        }

        .grid-container {
            padding: 0.5rem;
            border-width: 2px;
        }

        .grid {
            gap: 1.5px;
            padding: 1.5px;
        }

        .cell {
            width: calc(min(26px, (100vw - 4rem) / var(--grid-size, 10)));
            height: calc(min(26px, (100vw - 4rem) / var(--grid-size, 10)));
            font-size: clamp(0.5rem, 1vw, 0.75rem);
            border-radius: 4px;
        }

        .cell-number {
            font-size: clamp(0.3rem, 0.6vw, 0.45rem);
        }

        .cell-input {
            font-size: clamp(0.5rem, 1vw, 0.75rem);
        }

        .sidebar {
            padding: 1rem;
            border-width: 2px;
        }

        .sidebar h3 {
            font-size: 1.1rem;
        }

        .progress-bar {
            height: 10px;
            margin-bottom: 1.5rem;
        }

        .stat-box {
            padding: 0.75rem;
            border-radius: 15px;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .stat-label {
            font-size: 0.75rem;
        }

        .word-list {
            max-height: 250px;
        }

        .word-item {
            padding: 0.6rem;
        }

        .word-text {
            font-size: 0.9rem;
        }

        .clue-text {
            font-size: 0.75rem;
        }

        .victory-message {
            padding: 1.5rem 1rem;
        }

        .victory-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .victory-message h2 {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .victory-message p {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .btn {
            padding: 0.6rem 1.2rem;
            font-size: 0.85rem;
        }
    }

    /* Extra small devices (very small phones) */
    @media (max-width: 360px) {
        .grid-container {
            padding: 0.35rem;
            border-width: 1.5px;
        }

        .grid {
            gap: 1px;
            padding: 1px;
        }

        .cell {
            width: calc((100vw - 3.5rem) / var(--grid-size, 10));
            height: calc((100vw - 3.5rem) / var(--grid-size, 10));
            font-size: clamp(0.45rem, 0.9vw, 0.65rem);
            border-radius: 3px;
        }

        .cell-number {
            font-size: clamp(0.25rem, 0.5vw, 0.4rem);
        }

        .cell-input {
            font-size: clamp(0.45rem, 0.9vw, 0.65rem);
        }

        .word-text {
            font-size: 0.85rem;
        }
    }
</style>
@endpush

@section('content')
<div class="card animate__animated animate__fadeIn">
    <div class="level-header">
        <div class="level-info">
            <div class="level-title-wrapper">
                <h2>{{ $level->title }}</h2>
                @if($level->description)
                    <p>{{ $level->description }}</p>
                @endif
            </div>
            <a href="{{ route('game.index') }}" class="btn btn-primary">← Back to Levels</a>
        </div>
        <div class="level-badges">
            @if($level->difficulty === 'easy')
                <span class="badge badge-success">Easy</span>
            @elseif($level->difficulty === 'medium')
                <span class="badge badge-warning">Medium</span>
            @else
                <span class="badge badge-danger">Hard</span>
            @endif
            <span class="badge badge-info">🎯 {{ ucfirst($level->game_type) }}</span>
            <span class="badge badge-info">📐 {{ $level->grid_size }}x{{ $level->grid_size }}</span>
        </div>
    </div>

    <div class="game-container">
        <div>
            <div class="grid-container">
                <div class="grid" id="wordGrid" style="--grid-size: {{ $level->grid_size }}; grid-template-columns: repeat({{ $level->grid_size }}, 40px);">
                    @php
                        $cellNumbers = [];
                        if($level->game_type === 'crossword' && isset($grid['placed'])) {
                            foreach($grid['placed'] as $placement) {
                                $pos = $placement['position'];
                                $cellNumbers[$pos[0] . '_' . $pos[1]] = $placement['number'];
                            }
                        }
                    @endphp
                    @foreach($grid['grid'] as $rowIndex => $row)
                        @foreach($row as $colIndex => $cell)
                            @if($level->game_type === 'crossword')
                                @php
                                    $cellKey = $rowIndex . '_' . $colIndex;
                                    $cellNumber = $cellNumbers[$cellKey] ?? null;
                                @endphp
                                <div class="cell {{ $cell === null ? 'empty' : '' }}"
                                     data-letter="{{ $cell }}"
                                     data-row="{{ $rowIndex }}"
                                     data-col="{{ $colIndex }}"
                                     @if($cell !== null) tabindex="0" @endif>
                                    @if($cell !== null)
                                        @if($cellNumber)
                                            <span class="cell-number">{{ $cellNumber }}</span>
                                        @endif
                                        <input type="text" class="cell-input" maxlength="1" />
                                    @endif
                                </div>
                            @else
                                <div class="cell {{ $cell === null ? 'empty' : '' }}" data-letter="{{ $cell }}">
                                    {{ $cell }}
                                </div>
                            @endif
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <div class="sidebar">
            <h3>📊 Progress</h3>

            <div class="progress-bar">
                <div class="progress-fill" id="progressBar" style="width: 0%"></div>
            </div>

            <div class="stats">
                <div class="stat-box timer-box">
                    <span class="stat-value" id="timer">0:00</span>
                    <span class="stat-label">⏱️ Time</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value" id="foundCount">0</span>
                    <span class="stat-label">Found</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value">{{ count($grid['words'] ?? $grid['clues']) }}</span>
                    <span class="stat-label">Total</span>
                </div>
            </div>

            @if($level->game_type === 'wordsearch')
                <h3>🔍 Words to Find</h3>
                <ul class="word-list" id="wordList">
                    @foreach($grid['words'] as $word)
                        <li class="word-item" data-word="{{ $word }}">
                            <span class="word-text">{{ $word }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                @if(isset($grid['acrossClues']) && count($grid['acrossClues']) > 0)
                    <h3>➡️ Across</h3>
                    <ul class="word-list clue-list">
                        @foreach($grid['acrossClues'] as $clue)
                            <li class="word-item clue-item" data-word="{{ $clue['word'] }}" data-number="{{ $clue['number'] }}">
                                <span class="word-text"><strong>{{ $clue['number'] }}.</strong> {{ $clue['clue'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if(isset($grid['downClues']) && count($grid['downClues']) > 0)
                    <h3 style="margin-top: 1.5rem;">⬇️ Down</h3>
                    <ul class="word-list clue-list">
                        @foreach($grid['downClues'] as $clue)
                            <li class="word-item clue-item" data-word="{{ $clue['word'] }}" data-number="{{ $clue['number'] }}">
                                <span class="word-text"><strong>{{ $clue['number'] }}.</strong> {{ $clue['clue'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endif
        </div>
    </div>
</div>

<div class="overlay" id="overlay"></div>
<div class="victory-message" id="victoryMessage">
    <div class="victory-icon">🎉</div>
    <h2>Congratulations!</h2>
    <p>You found all the words! Amazing job! 🌟</p>
    <div id="scoreDisplay" style="margin: 2rem 0; padding: 2rem; background: rgba(0, 217, 255, 0.1); border-radius: 15px; display: none;">
        <div style="font-family: 'Orbitron', sans-serif; font-size: 3.5rem; font-weight: 900; background: linear-gradient(135deg, var(--primary-yellow), var(--primary-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin-bottom: 0.5rem;" id="finalScore">0</div>
        <div style="color: var(--text-muted); font-size: 1rem; text-transform: uppercase; letter-spacing: 1px;">POINTS EARNED</div>
    </div>
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('leaderboard.index') }}" class="btn btn-primary" style="background: linear-gradient(135deg, var(--primary-yellow), #eab308); color: var(--bg-dark);">🏆 View Leaderboard</a>
        <a href="{{ route('game.index') }}" class="btn btn-primary">← Back to Levels</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const gameType = '{{ $level->game_type }}';
    const gridSize = {{ $level->grid_size }};
    const grid = document.getElementById('wordGrid');
    const levelId = {{ $level->id }};
    const csrfToken = '{{ csrf_token() }}';

    let startTime = Date.now();
    let timerInterval = null;

    // Make grid responsive
    function updateGridColumns() {
        const cells = document.querySelectorAll('.cell:not(.empty)');
        if (cells.length > 0) {
            const cellWidth = cells[0].offsetWidth;
            grid.style.gridTemplateColumns = `repeat(${gridSize}, ${cellWidth}px)`;
        }
    }

    // Update grid on load and resize
    window.addEventListener('load', updateGridColumns);
    window.addEventListener('resize', updateGridColumns);

    // Start timer
    startTimer();

    if (gameType === 'crossword') {
        // Crossword mode
        let foundWords = new Set();
        const placedWords = @json($grid['placed'] ?? []);
        const totalWords = placedWords.length;
        let currentDirection = 'across'; // Track current direction

        // Get all input cells
        const cellInputs = document.querySelectorAll('.cell-input');
        const cells = document.querySelectorAll('.cell:not(.empty)');

        // Create a map of grid positions to input elements
        const gridMap = new Map();
        cellInputs.forEach(input => {
            const cell = input.parentElement;
            const row = cell.dataset.row;
            const col = cell.dataset.col;
            gridMap.set(`${row}_${col}`, input);
        });

        // Helper function to find which word(s) a cell belongs to
        function getWordsAtCell(row, col) {
            const words = { across: null, down: null };
            placedWords.forEach(placement => {
                const [startRow, startCol] = placement.position;
                const wordLen = placement.word.length;
                const dir = placement.direction;

                if (dir === 'across') {
                    if (row === startRow && col >= startCol && col < startCol + wordLen) {
                        words.across = placement;
                    }
                } else {
                    if (col === startCol && row >= startRow && row < startRow + wordLen) {
                        words.down = placement;
                    }
                }
            });
            return words;
        }

        // Helper function to get next cell in current direction
        function getNextCell(currentRow, currentCol, direction) {
            if (direction === 'across') {
                return gridMap.get(`${currentRow}_${currentCol + 1}`);
            } else {
                return gridMap.get(`${currentRow + 1}_${currentCol}`);
            }
        }

        // Helper function to get previous cell in current direction
        function getPreviousCell(currentRow, currentCol, direction) {
            if (direction === 'across') {
                return gridMap.get(`${currentRow}_${currentCol - 1}`);
            } else {
                return gridMap.get(`${currentRow - 1}_${currentCol}`);
            }
        }

        // Handle input events
        cellInputs.forEach((input, index) => {
            const cell = input.parentElement;

            // Focus cell on click - toggle direction if cell has both across and down
            cell.addEventListener('click', () => {
                const currentRow = parseInt(cell.dataset.row);
                const currentCol = parseInt(cell.dataset.col);
                const words = getWordsAtCell(currentRow, currentCol);

                // Toggle direction if cell has both words
                if (words.across && words.down) {
                    currentDirection = currentDirection === 'across' ? 'down' : 'across';
                } else if (words.across) {
                    currentDirection = 'across';
                } else if (words.down) {
                    currentDirection = 'down';
                }

                input.focus();
            });

            // Handle keyboard input
            input.addEventListener('input', (e) => {
                const value = e.target.value.toUpperCase();
                if (value.length > 0) {
                    e.target.value = value;
                    checkCrosswordProgress();

                    // Move to next cell in current direction
                    const currentRow = parseInt(cell.dataset.row);
                    const currentCol = parseInt(cell.dataset.col);
                    const nextInput = getNextCell(currentRow, currentCol, currentDirection);
                    if (nextInput) {
                        nextInput.focus();
                    }
                }
            });

            // Handle backspace to move to previous cell
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '') {
                    const currentRow = parseInt(cell.dataset.row);
                    const currentCol = parseInt(cell.dataset.col);
                    const prevInput = getPreviousCell(currentRow, currentCol, currentDirection);
                    if (prevInput) {
                        prevInput.focus();
                    }
                }

                // Arrow key navigation
                if (e.key === 'ArrowRight') {
                    e.preventDefault();
                    currentDirection = 'across';
                    const currentRow = parseInt(cell.dataset.row);
                    const currentCol = parseInt(cell.dataset.col);
                    const nextInput = gridMap.get(`${currentRow}_${currentCol + 1}`);
                    if (nextInput) nextInput.focus();
                }
                if (e.key === 'ArrowLeft') {
                    e.preventDefault();
                    currentDirection = 'across';
                    const currentRow = parseInt(cell.dataset.row);
                    const currentCol = parseInt(cell.dataset.col);
                    const prevInput = gridMap.get(`${currentRow}_${currentCol - 1}`);
                    if (prevInput) prevInput.focus();
                }
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    currentDirection = 'down';
                    const currentRow = parseInt(cell.dataset.row);
                    const currentCol = parseInt(cell.dataset.col);
                    const belowInput = gridMap.get(`${currentRow + 1}_${currentCol}`);
                    if (belowInput) belowInput.focus();
                }
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    currentDirection = 'down';
                    const currentRow = parseInt(cell.dataset.row);
                    const currentCol = parseInt(cell.dataset.col);
                    const aboveInput = gridMap.get(`${currentRow - 1}_${currentCol}`);
                    if (aboveInput) aboveInput.focus();
                }
            });
        });

        function checkCrosswordProgress() {
            // Check each placed word
            const previousFoundSize = foundWords.size;
            placedWords.forEach(placement => {
                const word = placement.word.toUpperCase();
                const [startRow, startCol] = placement.position;
                const direction = placement.direction;
                const wordNumber = placement.number;

                let isComplete = true;
                for (let i = 0; i < word.length; i++) {
                    let row, col;
                    if (direction === 'across') {
                        row = startRow;
                        col = startCol + i;
                    } else {
                        row = startRow + i;
                        col = startCol;
                    }

                    const input = gridMap.get(`${row}_${col}`);
                    if (!input || input.value.toUpperCase() !== word[i]) {
                        isComplete = false;
                        break;
                    }
                }

                // Mark word as found if complete and highlight only complete words
                if (isComplete && !foundWords.has(word)) {
                    foundWords.add(word);
                    const wordItem = document.querySelector(`.clue-item[data-word="${word}"]`);
                    if (wordItem) {
                        wordItem.classList.add('found');
                    }

                    // Only highlight cells when entire word is correct
                    for (let i = 0; i < word.length; i++) {
                        let row, col;
                        if (direction === 'across') {
                            row = startRow;
                            col = startCol + i;
                        } else {
                            row = startRow + i;
                            col = startCol;
                        }
                        const input = gridMap.get(`${row}_${col}`);
                        if (input) {
                            const cell = input.parentElement;
                            cell.classList.add('correct');
                            cell.classList.remove('incorrect');
                        }
                    }
                }
            });

            updateProgress();

            // Check if all words are found
            if (foundWords.size === totalWords && totalWords > 0) {
                setTimeout(showVictory, 500);
            }
        }

        function updateProgress() {
            const progress = (foundWords.size / totalWords) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('foundCount').textContent = foundWords.size;
        }

        function showVictory() {
            stopTimer();
            document.getElementById('overlay').classList.add('show');
            document.getElementById('victoryMessage').classList.add('show');

            // Submit crossword score
            submitScore('crossword', totalWords);
        }

    } else {
        // Word Search mode
        let selectedCells = [];
        let foundWords = new Set();
        let isSelecting = false;
        let selectionDirection = null; // Track the direction of selection
        const wordList = @json($grid['words'] ?? array_map(fn($item) => strtoupper($item['word']), $grid['clues']));
        const totalWords = wordList.length;

        // Get all cells
        const cells = document.querySelectorAll('.cell:not(.empty)');
        const cellsArray = Array.from(cells);

        // Mouse/Touch event handlers
        grid.addEventListener('mousedown', startSelection);
        grid.addEventListener('mouseover', continueSelection);
        grid.addEventListener('mouseup', endSelection);
        grid.addEventListener('mouseleave', endSelection);

        // Touch support
        grid.addEventListener('touchstart', handleTouchStart);
        grid.addEventListener('touchmove', handleTouchMove);
        grid.addEventListener('touchend', endSelection);

        function startSelection(e) {
            if (e.target.classList.contains('cell') && !e.target.classList.contains('empty')) {
                isSelecting = true;
                selectedCells = [e.target];
                selectionDirection = null;
                e.target.classList.add('selected');
            }
        }

        function continueSelection(e) {
            if (isSelecting && e.target.classList.contains('cell') && !e.target.classList.contains('empty')) {
                if (!selectedCells.includes(e.target)) {
                    const lastCell = selectedCells[selectedCells.length - 1];

                    if (selectedCells.length === 1) {
                        // First move - establish direction
                        const direction = getDirection(lastCell, e.target);
                        if (direction) {
                            selectionDirection = direction;
                            selectedCells.push(e.target);
                            e.target.classList.add('selected');
                        }
                    } else {
                        // Continue in the same direction
                        if (isInSameDirection(lastCell, e.target, selectionDirection)) {
                            selectedCells.push(e.target);
                            e.target.classList.add('selected');
                        }
                    }
                }
            }
        }

        function endSelection() {
            if (isSelecting && selectedCells.length > 0) {
                checkWord();
                clearSelection();
            }
            isSelecting = false;
            selectionDirection = null;
        }

        function handleTouchStart(e) {
            e.preventDefault();
            const touch = e.touches[0];
            const element = document.elementFromPoint(touch.clientX, touch.clientY);
            if (element && element.classList.contains('cell') && !element.classList.contains('empty')) {
                isSelecting = true;
                selectedCells = [element];
                selectionDirection = null;
                element.classList.add('selected');
            }
        }

        function handleTouchMove(e) {
            e.preventDefault();
            if (isSelecting) {
                const touch = e.touches[0];
                const element = document.elementFromPoint(touch.clientX, touch.clientY);
                if (element && element.classList.contains('cell') && !element.classList.contains('empty')) {
                    if (!selectedCells.includes(element)) {
                        const lastCell = selectedCells[selectedCells.length - 1];

                        if (selectedCells.length === 1) {
                            // First move - establish direction
                            const direction = getDirection(lastCell, element);
                            if (direction) {
                                selectionDirection = direction;
                                selectedCells.push(element);
                                element.classList.add('selected');
                            }
                        } else {
                            // Continue in the same direction
                            if (isInSameDirection(lastCell, element, selectionDirection)) {
                                selectedCells.push(element);
                                element.classList.add('selected');
                            }
                        }
                    }
                }
            }
        }

        function getDirection(cell1, cell2) {
            const index1 = cellsArray.indexOf(cell1);
            const index2 = cellsArray.indexOf(cell2);

            const row1 = Math.floor(index1 / gridSize);
            const col1 = index1 % gridSize;
            const row2 = Math.floor(index2 / gridSize);
            const col2 = index2 % gridSize;

            const rowDiff = row2 - row1;
            const colDiff = col2 - col1;

            // Normalize to unit direction (-1, 0, or 1)
            const rowDir = rowDiff === 0 ? 0 : rowDiff / Math.abs(rowDiff);
            const colDir = colDiff === 0 ? 0 : colDiff / Math.abs(colDiff);

            // Check if it's a valid direction (horizontal, vertical, or diagonal)
            const absRowDiff = Math.abs(rowDiff);
            const absColDiff = Math.abs(colDiff);

            if (absRowDiff === 0 || absColDiff === 0 || absRowDiff === absColDiff) {
                return { rowDir, colDir };
            }

            return null;
        }

        function isInSameDirection(cell1, cell2, direction) {
            const index1 = cellsArray.indexOf(cell1);
            const index2 = cellsArray.indexOf(cell2);

            const row1 = Math.floor(index1 / gridSize);
            const col1 = index1 % gridSize;
            const row2 = Math.floor(index2 / gridSize);
            const col2 = index2 % gridSize;

            const rowDiff = row2 - row1;
            const colDiff = col2 - col1;

            // Check if the movement matches the established direction
            const rowDir = rowDiff === 0 ? 0 : rowDiff / Math.abs(rowDiff);
            const colDir = colDiff === 0 ? 0 : colDiff / Math.abs(colDiff);

            // Must be moving in the same direction
            if (rowDir !== direction.rowDir || colDir !== direction.colDir) {
                return false;
            }

            // Must be exactly one step in that direction
            const absRowDiff = Math.abs(rowDiff);
            const absColDiff = Math.abs(colDiff);

            // For diagonal, both should be 1. For horizontal/vertical, one should be 1 and other 0
            return (absRowDiff === 0 || absRowDiff === 1) && (absColDiff === 0 || absColDiff === 1) && (absRowDiff + absColDiff === 1 || (absRowDiff === 1 && absColDiff === 1));
        }

        function checkWord() {
            const word = selectedCells.map(cell => cell.dataset.letter).join('');
            const reversedWord = word.split('').reverse().join('');

            if (wordList.includes(word) && !foundWords.has(word)) {
                markWordFound(word, selectedCells);
            } else if (wordList.includes(reversedWord) && !foundWords.has(reversedWord)) {
                markWordFound(reversedWord, selectedCells);
            }
        }

        function markWordFound(word, cells) {
            foundWords.add(word);

            cells.forEach((cell, index) => {
                setTimeout(() => {
                    cell.classList.remove('selected');
                    cell.classList.add('found');
                }, index * 50);
            });

            const wordItem = document.querySelector(`[data-word="${word}"]`);
            if (wordItem) {
                setTimeout(() => {
                    wordItem.classList.add('found');
                }, cells.length * 50);
            }

            updateProgress();

            if (foundWords.size === totalWords) {
                setTimeout(showVictory, 1000);
            }
        }

        function clearSelection() {
            selectedCells.forEach(cell => {
                if (!cell.classList.contains('found')) {
                    cell.classList.remove('selected');
                }
            });
            selectedCells = [];
        }

        function updateProgress() {
            const progress = (foundWords.size / totalWords) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('foundCount').textContent = foundWords.size;
        }

        function showVictory() {
            stopTimer();
            document.getElementById('overlay').classList.add('show');
            document.getElementById('victoryMessage').classList.add('show');

            // Submit word search score
            submitScore('wordsearch', totalWords);
        }
    }

    // Timer functions
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

    // Score submission function
    async function submitScore(type, wordsCount) {
        const timeSeconds = getElapsedSeconds();

        const details = type === 'crossword'
            ? { correct_words: wordsCount }
            : { words_found: wordsCount };

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
                    game_type: type,
                    details: details
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
