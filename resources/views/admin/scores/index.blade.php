@extends('layouts.admin')

@section('title', 'User Scores & Analytics')

@section('content')
<div class="admin-container">
    <div class="page-header-vault">
        <h2 class="vault-title">USER SCORES & ANALYTICS</h2>
        <p class="vault-subtitle">View, filter, and export user game scores</p>
    </div>

    <!-- Filters Section -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.scores.index') }}" id="filterForm">
            <div class="filters-grid">
                <!-- Game Type Filter -->
                <div class="form-group">
                    <label for="game_type">Game Type</label>
                    <select name="game_type" id="game_type" class="form-control">
                        <option value="">All Games</option>
                        <option value="crossword" {{ request('game_type') == 'crossword' ? 'selected' : '' }}>Crossword</option>
                        <option value="wordsearch" {{ request('game_type') == 'wordsearch' ? 'selected' : '' }}>Word Search</option>
                        <option value="wordle" {{ request('game_type') == 'wordle' ? 'selected' : '' }}>Wordle</option>
                    </select>
                </div>

                <!-- Level Filter -->
                <div class="form-group">
                    <label for="level_id">Level</label>
                    <select name="level_id" id="level_id" class="form-control">
                        <option value="">All Levels</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ request('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->title }} ({{ ucfirst($level->game_type) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div class="form-group">
                    <label for="date_from">Date From</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>

                <!-- Date To -->
                <div class="form-group">
                    <label for="date_to">Date To</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
                    <small class="filter-hint">
                        <i class="fas fa-info-circle"></i> Leave dates empty to export all scores
                    </small>
                </div>

                <!-- User Search -->
                <div class="form-group">
                    <label for="user_search">Search User</label>
                    <input type="text" name="user_search" id="user_search" class="form-control"
                           placeholder="Name or email..." value="{{ request('user_search') }}">
                </div>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn-filter btn-apply">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
                <a href="{{ route('admin.scores.index') }}" class="btn-filter btn-clear">
                    <i class="fas fa-times-circle"></i> Clear Filters
                </a>
                <button type="button" onclick="exportScores()" class="btn-filter btn-export" id="exportBtn">
                    <i class="fas fa-file-excel"></i> Export to Excel
                </button>
                <button type="button" onclick="confirmResetScores()" class="btn-filter btn-danger" id="resetBtn">
                    <i class="fas fa-trash-alt"></i> Reset All Scores
                </button>
            </div>
        </form>
    </div>

    <!-- Stats Summary -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-content">
                <h3>{{ number_format($scores->total()) }}</h3>
                <p>Total Scores</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <h3>{{ number_format($scores->pluck('user_id')->unique()->count()) }}</h3>
                <p>Unique Players</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-gamepad"></i>
            </div>
            <div class="stat-content">
                <h3>{{ number_format($scores->avg('score') ?: 0, 0) }}</h3>
                <p>Average Score</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3>{{ gmdate('i:s', $scores->avg('time_seconds') ?: 0) }}</h3>
                <p>Avg Completion Time</p>
            </div>
        </div>
    </div>

    <!-- Scores Table -->
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Player</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Game Type</th>
                    <th>Score</th>
                    <th>Time</th>
                    <th>Completed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($scores as $score)
                <tr>
                    <td data-label="ID"><span class="badge badge-id">#{{ $score->id }}</span></td>
                    <td data-label="Player">
                        <div class="user-info">
                            <div class="user-avatar">{{ substr($score->user->name, 0, 1) }}</div>
                            <span>{{ $score->user->name }}</span>
                        </div>
                    </td>
                    <td data-label="Email" class="text-muted">{{ $score->user->email }}</td>
                    <td data-label="Level">
                        <div class="level-badge">{{ $score->level->title }}</div>
                    </td>
                    <td data-label="Game Type">
                        <span class="game-type-badge game-type-{{ $score->game_type }}">
                            {{ ucfirst($score->game_type) }}
                        </span>
                    </td>
                    <td data-label="Score">
                        <span class="score-badge">{{ number_format($score->score) }}</span>
                    </td>
                    <td data-label="Time" class="text-muted">
                        {{ gmdate('i:s', $score->time_seconds) }}
                    </td>
                    <td data-label="Completed" class="text-muted">
                        {{ $score->created_at->format('M d, Y H:i') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 3rem;">
                        <i class="fas fa-inbox" style="font-size: 3rem; opacity: 0.3; margin-bottom: 1rem;"></i>
                        <p style="opacity: 0.6;">No scores found matching your filters</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($scores->hasPages())
    <div class="pagination-container">
        {{ $scores->appends(request()->query())->links() }}
    </div>
    @endif
</div>

<!-- Reset Confirmation Modal -->
<div id="resetModal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-icon-warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3>Reset All Scores</h3>
        </div>
        <div class="modal-body">
            <p class="modal-warning-text">
                <strong>⚠️ WARNING:</strong> This will permanently delete ALL score records from the database.
            </p>
            <ul class="modal-warning-list">
                <li><i class="fas fa-check-circle"></i> All user accounts will remain intact</li>
                <li><i class="fas fa-times-circle"></i> All game scores will be permanently lost</li>
                <li><i class="fas fa-ban"></i> This action cannot be undone</li>
            </ul>
            <p class="modal-confirmation-text">
                Are you absolutely sure you want to continue?
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeResetModal()" class="modal-btn modal-btn-cancel">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" onclick="executeReset()" class="modal-btn modal-btn-danger">
                <i class="fas fa-trash-alt"></i> Yes, Delete All Scores
            </button>
        </div>
    </div>
</div>

<style>
.page-header-vault {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem 0;
}

.vault-title {
    font-family: 'Orbitron', sans-serif;
    font-size: 3.5rem;
    font-weight: 900;
    letter-spacing: 8px;
    margin: 0 0 1rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-transform: uppercase;
    position: relative;
    display: inline-block;
}

.vault-subtitle {
    font-size: 1.1rem;
    color: var(--text-muted);
    margin: 0;
    font-weight: 400;
    letter-spacing: 0.5px;
}

.filter-card {
    background: var(--bg-card);
    border: 1px solid rgba(0, 217, 255, 0.15);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.filters-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.filter-hint {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-muted);
    font-style: italic;
    opacity: 0.8;
}

.filter-hint i {
    color: var(--primary-blue);
    margin-right: 0.25rem;
    font-size: 0.9rem;
}

.filter-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    flex-wrap: wrap;
    padding-top: 1.5rem;
    margin-top: 0.5rem;
    border-top: 1px solid rgba(0, 217, 255, 0.15);
}

.btn-filter {
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    text-decoration: none;
    position: relative;
    overflow: hidden;
    font-family: 'Inter', sans-serif;
}

.btn-filter::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-filter:hover::before {
    left: 100%;
}

.btn-filter i {
    font-size: 1rem;
}

.btn-apply {
    background: linear-gradient(135deg, var(--primary-blue) 0%, #0088cc 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(0, 217, 255, 0.3);
}

.btn-apply:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 217, 255, 0.5);
    background: linear-gradient(135deg, #00e5ff 0%, var(--primary-blue) 100%);
}

.btn-apply:active {
    transform: translateY(-1px);
}

.btn-clear {
    background: rgba(239, 68, 68, 0.1);
    color: #fca5a5;
    border: 2px solid rgba(239, 68, 68, 0.3);
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.15);
}

.btn-clear:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.5);
    color: #ff6b6b;
}

.btn-clear:active {
    transform: translateY(-1px);
}

.btn-export {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-export:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5);
    background: linear-gradient(135deg, #34d399 0%, #10b981 100%);
}

.btn-export:active {
    transform: translateY(-1px);
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.btn-danger:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
    background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
}

.btn-danger:active {
    transform: translateY(-1px);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--bg-card);
    border: 1px solid rgba(0, 217, 255, 0.15);
    border-radius: 20px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
    border-color: rgba(0, 217, 255, 0.3);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: var(--text-light);
}

.stat-content p {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-blue), var(--accent-purple));
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
}

.badge-id {
    background: rgba(0, 217, 255, 0.2);
    color: var(--primary-blue);
    padding: 0.25rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
}

.level-badge {
    display: inline-block;
    padding: 0.35rem 0.9rem;
    background: rgba(183, 148, 244, 0.2);
    color: var(--accent-purple);
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
}

.game-type-badge {
    display: inline-block;
    padding: 0.35rem 0.9rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.game-type-crossword {
    background: rgba(255, 215, 0, 0.2);
    color: var(--primary-yellow);
}

.game-type-wordsearch {
    background: rgba(0, 217, 255, 0.2);
    color: var(--primary-blue);
}

.game-type-wordle {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.score-badge {
    display: inline-block;
    padding: 0.35rem 0.9rem;
    background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(0, 217, 255, 0.2));
    color: var(--text-light);
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.95rem;
}

.text-muted {
    color: var(--text-muted);
}

.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Reset Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-container {
    background: var(--bg-card);
    border: 2px solid rgba(239, 68, 68, 0.3);
    border-radius: 24px;
    max-width: 550px;
    width: 90%;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 100px rgba(239, 68, 68, 0.2);
    animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.modal-header {
    padding: 2rem 2rem 1.5rem;
    text-align: center;
    border-bottom: 1px solid rgba(239, 68, 68, 0.2);
}

.modal-icon-warning {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 8px 30px rgba(239, 68, 68, 0.4);
    animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 8px 30px rgba(239, 68, 68, 0.4);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 12px 40px rgba(239, 68, 68, 0.6);
    }
}

.modal-header h3 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-light);
    font-family: 'Orbitron', sans-serif;
}

.modal-body {
    padding: 2rem;
}

.modal-warning-text {
    margin: 0 0 1.5rem;
    font-size: 1.05rem;
    color: var(--text-light);
    line-height: 1.6;
}

.modal-warning-text strong {
    color: #ef4444;
}

.modal-warning-list {
    list-style: none;
    padding: 0;
    margin: 0 0 1.5rem;
}

.modal-warning-list li {
    padding: 0.75rem 1rem;
    margin-bottom: 0.5rem;
    background: rgba(0, 0, 0, 0.3);
    border-radius: 10px;
    border-left: 3px solid rgba(239, 68, 68, 0.5);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
    color: var(--text-muted);
}

.modal-warning-list li i {
    font-size: 1.1rem;
}

.modal-warning-list li:nth-child(1) i {
    color: #10b981;
}

.modal-warning-list li:nth-child(2) i,
.modal-warning-list li:nth-child(3) i {
    color: #ef4444;
}

.modal-confirmation-text {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-light);
    text-align: center;
}

.modal-footer {
    padding: 1.5rem 2rem 2rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    background: rgba(0, 0, 0, 0.2);
}

.modal-btn {
    padding: 0.875rem 1.75rem;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: inline-flex;
    align-items: center;
    gap: 0.6rem;
    font-family: 'Inter', sans-serif;
}

.modal-btn i {
    font-size: 1rem;
}

.modal-btn-cancel {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-light);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.modal-btn-cancel:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.modal-btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(239, 68, 68, 0.3);
}

.modal-btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5);
    background: linear-gradient(135deg, #f87171 0%, #ef4444 100%);
}

.modal-btn-danger:active,
.modal-btn-cancel:active {
    transform: translateY(0);
}

/* Mobile Responsive Styles */
@media (max-width: 768px) {
    .vault-title {
        font-size: 2rem;
        letter-spacing: 4px;
    }

    .vault-subtitle {
        font-size: 0.95rem;
    }

    .filters-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .filter-actions {
        flex-direction: column;
    }

    .btn-filter {
        width: 100%;
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .stat-card {
        padding: 1.25rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }

    .stat-content h3 {
        font-size: 1.5rem;
    }

    .stat-content p {
        font-size: 0.85rem;
    }

    /* Mobile table styles */
    .data-table thead {
        display: none;
    }

    .data-table tbody tr {
        display: block;
        margin-bottom: 1.5rem;
        padding: 1.25rem;
        border: 1px solid rgba(0, 217, 255, 0.15);
        border-radius: 16px;
    }

    .data-table tbody td {
        display: block;
        padding: 0.75rem 0;
        border: none;
        text-align: left;
    }

    .data-table tbody td::before {
        content: attr(data-label);
        font-weight: bold;
        display: block;
        margin-bottom: 0.5rem;
        color: var(--primary-blue);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .user-info {
        flex-direction: row;
    }

    .badge-id,
    .level-badge,
    .game-type-badge,
    .score-badge {
        display: inline-block;
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .vault-title {
        font-size: 1.5rem;
        letter-spacing: 2px;
    }

    .filter-card {
        padding: 1.5rem;
    }

    .btn-filter {
        padding: 0.75rem 1.25rem;
        font-size: 0.9rem;
    }

    .modal-container {
        width: 95%;
    }

    .modal-header,
    .modal-body,
    .modal-footer {
        padding: 1.5rem;
    }

    .modal-icon-warning {
        width: 60px;
        height: 60px;
        font-size: 2rem;
    }

    .modal-header h3 {
        font-size: 1.5rem;
    }
}

@media (max-width: 360px) {
    .vault-title {
        font-size: 1.25rem;
        letter-spacing: 1px;
    }

    .vault-subtitle {
        font-size: 0.85rem;
    }

    .filter-card {
        padding: 1rem;
    }

    .btn-filter {
        padding: 0.65rem 1rem;
        font-size: 0.85rem;
    }
}
</style>

<script>
// Store all levels for filtering
const allLevels = @json($levels);

// Filter levels based on game type selection
document.getElementById('game_type').addEventListener('change', function() {
    const gameType = this.value;
    const levelSelect = document.getElementById('level_id');
    const currentSelection = levelSelect.value;

    // Clear current options except "All Levels"
    levelSelect.innerHTML = '<option value="">All Levels</option>';

    // Filter and add levels
    const filteredLevels = gameType
        ? allLevels.filter(level => level.game_type === gameType)
        : allLevels;

    filteredLevels.forEach(level => {
        const option = document.createElement('option');
        option.value = level.id;
        option.textContent = `${level.title} (${level.game_type.charAt(0).toUpperCase() + level.game_type.slice(1)})`;

        // Restore previous selection if it's still valid
        if (level.id == currentSelection) {
            option.selected = true;
        }

        levelSelect.appendChild(option);
    });
});

function exportScores() {
    const exportBtn = document.getElementById('exportBtn');
    const originalContent = exportBtn.innerHTML;

    // Set loading state
    exportBtn.disabled = true;
    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
    exportBtn.style.opacity = '0.7';
    exportBtn.style.cursor = 'not-allowed';

    // Get current filter values directly from form fields
    const params = new URLSearchParams();

    const gameType = document.getElementById('game_type').value;
    if (gameType) params.append('game_type', gameType);

    const levelId = document.getElementById('level_id').value;
    if (levelId) params.append('level_id', levelId);

    const dateFrom = document.getElementById('date_from').value;
    if (dateFrom) params.append('date_from', dateFrom);

    const dateTo = document.getElementById('date_to').value;
    if (dateTo) params.append('date_to', dateTo);

    const userSearch = document.getElementById('user_search').value;
    if (userSearch) params.append('user_search', userSearch);

    const exportUrl = '{{ route("admin.scores.export") }}?' + params.toString();

    // Create a hidden iframe for download
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = exportUrl;
    document.body.appendChild(iframe);

    // Reset button state after download starts (2 seconds delay)
    setTimeout(() => {
        exportBtn.disabled = false;
        exportBtn.innerHTML = originalContent;
        exportBtn.style.opacity = '1';
        exportBtn.style.cursor = 'pointer';

        // Remove iframe after download
        setTimeout(() => {
            document.body.removeChild(iframe);
        }, 1000);
    }, 2000);
}

function confirmResetScores() {
    document.getElementById('resetModal').style.display = 'flex';
}

function closeResetModal() {
    document.getElementById('resetModal').style.display = 'none';
}

function executeReset() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.scores.reset") }}';
    form.innerHTML = '@csrf';
    document.body.appendChild(form);
    form.submit();
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modal = document.getElementById('resetModal');
    if (event.target === modal) {
        closeResetModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeResetModal();
    }
});
</script>
@endsection
