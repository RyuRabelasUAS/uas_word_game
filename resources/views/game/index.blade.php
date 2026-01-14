@extends('layouts.game')

@section('title', 'Select a Level')

@push('styles')
<style>
    .page-title {
        text-align: center;
        margin-bottom: 5rem;
        position: relative;
    }

    .page-title h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, #FFFFFF 0%, var(--primary-blue) 50%, var(--accent-cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        letter-spacing: -0.5px;
        line-height: 1.2;
    }

    .page-title p {
        color: rgba(160, 174, 192, 0.9);
        font-size: 1.15rem;
        font-weight: 400;
        letter-spacing: 0.3px;
        max-width: 600px;
        margin: 0 auto;
    }

    .levels-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 2rem;
    }

    .level-card {
        background: linear-gradient(135deg, rgba(19, 24, 41, 0.95) 0%, rgba(26, 31, 58, 0.95) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        padding: 0;
        transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        box-shadow:
            0 4px 20px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .level-card.completed {
        opacity: 0.75;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(26, 31, 58, 0.95) 100%);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .completed-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(52, 211, 153, 0.15));
        backdrop-filter: blur(10px);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #34d399;
        padding: 0.6rem 1.2rem;
        border-radius: 0 24px 0 20px;
        font-weight: 600;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        z-index: 10;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .level-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.03) 0%, rgba(183, 148, 244, 0.03) 100%);
        opacity: 0;
        transition: opacity 0.5s ease;
    }

    .level-card:hover::before {
        opacity: 1;
    }

    .level-card:hover {
        transform: translateY(-8px);
        box-shadow:
            0 20px 40px rgba(0, 0, 0, 0.4),
            0 0 0 1px rgba(0, 217, 255, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.15);
        border-color: rgba(0, 217, 255, 0.3);
    }

    .level-card-header {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.08) 0%, rgba(183, 148, 244, 0.08) 100%);
        padding: 2rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        position: relative;
    }

    .level-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }

    .level-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        color: #FFFFFF;
        margin: 0;
        line-height: 1.3;
        letter-spacing: -0.3px;
    }

    .level-card-body {
        padding: 2rem;
    }

    .level-description {
        color: rgba(160, 174, 192, 0.9);
        margin-bottom: 1.5rem;
        line-height: 1.65;
        font-size: 0.95rem;
    }

    .level-meta {
        display: flex;
        gap: 0.6rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .level-meta .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 100px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.3s ease;
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

    .level-card .btn {
        width: 100%;
        padding: 1rem;
        font-size: 0.95rem;
        font-weight: 600;
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.15) 0%, rgba(183, 148, 244, 0.15) 100%);
        border: 1px solid rgba(0, 217, 255, 0.3);
        color: #FFFFFF;
        border-radius: 12px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .level-card .btn:hover {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.25) 0%, rgba(183, 148, 244, 0.25) 100%);
        border-color: rgba(0, 217, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 217, 255, 0.25);
    }

    .btn-primary {
        background: linear-gradient(135deg, rgba(0, 217, 255, 0.2) 0%, rgba(183, 148, 244, 0.2) 100%);
        border: 1px solid rgba(0, 217, 255, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 6rem 2rem;
    }

    .empty-icon {
        font-size: 6rem;
        margin-bottom: 2rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2rem;
        color: #FFFFFF;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .empty-state p {
        color: rgba(160, 174, 192, 0.8);
        margin-bottom: 2rem;
        font-size: 1rem;
        line-height: 1.6;
    }

    @media (max-width: 768px) {
        .page-title h2 {
            font-size: 2.5rem;
        }

        .levels-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .level-card-header,
        .level-card-body {
            padding: 1.5rem;
        }

        .level-title {
            font-size: 1.3rem;
        }
    }
</style>
@endpush

@section('content')
<div class="card" data-aos="fade-up" data-aos-duration="800">
    <div class="page-title" data-aos="zoom-in" data-aos-delay="200">
        <h2>CHOOSE YOUR CHALLENGE</h2>
        <p>Select a level and dominate the word arena!</p>
    </div>

    <!-- Quick Leaderboard Preview -->
    <div class="leaderboard-preview" data-aos="fade-up" data-aos-delay="300" style="background: rgba(19, 24, 41, 0.6); backdrop-filter: blur(10px); border: 1px solid rgba(0, 217, 255, 0.2); border-radius: 20px; padding: 2rem; margin-bottom: 3rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-family: 'Orbitron', sans-serif; font-size: 1.5rem; font-weight: 800; background: linear-gradient(135deg, var(--primary-yellow), var(--primary-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; margin: 0;">🏆 TOP PLAYERS</h3>
            <a href="{{ route('leaderboard.index') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.9rem;">View Full Leaderboard →</a>
        </div>
        <div id="topScores" style="display: grid; gap: 1rem;">
            <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                <div style="font-size: 2rem; margin-bottom: 0.5rem;">⏳</div>
                Loading top players...
            </div>
        </div>
    </div>

    @if($levels->count() > 0)
        <div class="levels-grid">
            @foreach($levels as $level)
                <div class="level-card {{ $level->is_completed ? 'completed' : '' }}" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    @if($level->is_completed)
                        <div class="completed-badge">
                            ✓ Completed
                        </div>
                    @endif

                    <div class="level-card-header">
                        <div class="level-header">
                            <h3 class="level-title">{{ $level->title }}</h3>
                            @if($level->difficulty === 'easy')
                                <span class="badge badge-success">⭐ Easy</span>
                            @elseif($level->difficulty === 'medium')
                                <span class="badge badge-warning">⚔️ Medium</span>
                            @else
                                <span class="badge badge-danger">🔥 Hard</span>
                            @endif
                        </div>
                    </div>

                    <div class="level-card-body">
                        @if($level->description)
                            <p class="level-description">{{ $level->description }}</p>
                        @endif

                        <div class="level-meta">
                            @if($level->game_type !== 'wordle')
                                <span class="badge badge-info">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="9" y1="3" x2="9" y2="21"></line>
                                        <line x1="15" y1="3" x2="15" y2="21"></line>
                                        <line x1="3" y1="9" x2="21" y2="9"></line>
                                        <line x1="3" y1="15" x2="21" y2="15"></line>
                                    </svg>
                                    {{ $level->grid_size }}x{{ $level->grid_size }}
                                </span>
                            @endif
                            <span class="badge badge-info">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                </svg>
                                {{ $level->words_count }} {{ $level->game_type === 'wordle' ? 'word' : 'words' }}
                            </span>
                            <span class="badge badge-info">🎯 {{ ucfirst($level->game_type) }}</span>
                        </div>

                        @if($level->is_completed)
                            <button class="btn btn-primary" style="opacity: 0.5; cursor: not-allowed;" disabled>
                                ✓ Completed
                            </button>
                        @elseif(!Auth::check())
                            <a href="{{ route('login') }}" class="btn btn-primary">
                                Login to Play
                            </a>
                        @else
                            <a href="{{ route('game.play', $level) }}" class="btn btn-primary">
                                Play Now →
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state" data-aos="zoom-in" data-aos-delay="400">
            <div class="empty-icon">🎮</div>
            <h3>NO LEVELS AVAILABLE YET</h3>
            @auth
                @if(auth()->user()->is_admin)
                    <p>Fire up the admin panel and create your first epic challenge!</p>
                    <a href="{{ route('admin.levels.index') }}" class="btn btn-primary" data-aos="fade-up" data-aos-delay="600">
                        🚀 Launch Admin Panel
                    </a>
                @else
                    <p>No levels are available at the moment. Check back soon!</p>
                @endif
            @else
                <p>No levels are available at the moment. Check back soon!</p>
            @endauth
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Load top 5 scores on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadTopScores();
    });

    async function loadTopScores() {
        try {
            const response = await fetch('{{ route('api.leaderboard') }}?limit=5');
            const data = await response.json();

            const container = document.getElementById('topScores');

            if (data.scores && data.scores.length > 0) {
                let html = '';
                data.scores.forEach((score, index) => {
                    const rank = index + 1;
                    const medal = rank === 1 ? '🥇' : rank === 2 ? '🥈' : rank === 3 ? '🥉' : `#${rank}`;
                    const gameIcon = score.game_type === 'wordsearch' ? '🔍' : score.game_type === 'crossword' ? '📝' : '🎲';
                    const minutes = Math.floor(score.time_seconds / 60);
                    const seconds = score.time_seconds % 60;
                    const timeStr = minutes > 0 ? `${minutes}m ${seconds}s` : `${seconds}s`;

                    html += `
                        <div style="display: flex; align-items: center; gap: 1.5rem; padding: 1rem 1.5rem; background: rgba(26, 31, 58, 0.6); border-radius: 15px; border: 1px solid rgba(0, 217, 255, 0.2); transition: all 0.3s ease;" onmouseover="this.style.borderColor='var(--primary-blue)'; this.style.transform='translateX(5px)'" onmouseout="this.style.borderColor='rgba(0, 217, 255, 0.2)'; this.style.transform='translateX(0)'">
                            <div style="font-size: 1.5rem; min-width: 40px; text-align: center;">${medal}</div>
                            <div style="flex: 1;">
                                <div style="font-weight: 700; color: var(--text-light); margin-bottom: 0.25rem;">${score.user.name}</div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);">${gameIcon} ${score.level.title}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-family: 'Orbitron', sans-serif; font-size: 1.3rem; font-weight: 800; background: linear-gradient(135deg, var(--primary-yellow), var(--primary-blue)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">${score.score.toLocaleString()}</div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">⏱️ ${timeStr}</div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            } else {
                container.innerHTML = `
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">🎮</div>
                        <p>No scores yet! Be the first to play and claim the top spot!</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading top scores:', error);
            document.getElementById('topScores').innerHTML = `
                <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">⚠️</div>
                    <p>Unable to load scores. Please try again later.</p>
                </div>
            `;
        }
    }
</script>
@endpush
