@extends('layouts.admin')

@section('title', 'Levels')

@push('styles')
<style>
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .page-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #FFFFFF 0%, var(--primary-blue) 50%, var(--accent-cyan) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .page-header p {
        color: rgba(160, 174, 192, 0.9);
        font-size: 1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .header-actions {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 2rem;
    }

    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.75rem;
        table-layout: fixed;
    }

    .data-table thead th {
        background: rgba(0, 217, 255, 0.1);
        padding: 1rem 1.5rem;
        text-align: left;
        font-family: 'Orbitron', sans-serif;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--primary-blue);
        border: none;
        vertical-align: middle;
    }

    .data-table thead th:nth-child(1) { width: 20%; } /* Title */
    .data-table thead th:nth-child(2) { width: 10%; } /* Type */
    .data-table thead th:nth-child(3) { width: 8%; }  /* Grid */
    .data-table thead th:nth-child(4) { width: 11%; } /* Difficulty */
    .data-table thead th:nth-child(5) { width: 8%; }  /* Words */
    .data-table thead th:nth-child(6) { width: 9%; }  /* Status */
    .data-table thead th:nth-child(7) { width: 11%; } /* Created */
    .data-table thead th:nth-child(8) { width: 23%; } /* Actions */

    .data-table thead th:first-child {
        border-radius: 10px 0 0 10px;
    }

    .data-table thead th:last-child {
        border-radius: 0 10px 10px 0;
    }

    .data-table thead tr {
        display: table-row;
    }

    .data-table tbody tr {
        background: rgba(26, 31, 58, 0.4);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .data-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(26, 31, 58, 0.6) 0%, rgba(0, 217, 255, 0.1) 50%, rgba(183, 148, 244, 0.1) 100%);
        transform: translateX(5px);
        box-shadow:
            -5px 0 0 0 var(--primary-blue),
            0 5px 20px rgba(0, 217, 255, 0.3);
    }

    .data-table tbody td {
        padding: 1.25rem 1.5rem;
        border: none;
        color: var(--text-light);
        background: transparent;
    }

    .data-table tbody tr td:first-child {
        border-radius: 10px 0 0 10px;
    }

    .data-table tbody tr td:last-child {
        border-radius: 0 10px 10px 0;
    }

    .level-title {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-blue), var(--accent-purple));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        display: inline-block;
        max-width: 100%;
    }

    .data-table tbody td:nth-child(1) {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-icon {
        font-size: 7rem;
        margin-bottom: 2rem;
        animation: zoom 2s ease-in-out infinite;
        filter: drop-shadow(0 0 40px rgba(0, 217, 255, 0.5));
    }

    @keyframes zoom {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .empty-state h3 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2rem;
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-yellow));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
        font-weight: 800;
    }

    .empty-state p {
        color: var(--text-muted);
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .page-header h2 {
            font-size: 2rem;
        }

        .page-header h2::before {
            display: none;
        }

        .data-table {
            font-size: 0.85rem;
        }

        .data-table thead {
            display: none;
        }

        .data-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            padding: 1rem;
        }

        .data-table tbody td {
            display: block;
            padding: 0.5rem 0;
            border-radius: 0 !important;
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
    }
</style>
@endpush

@section('content')
<div class="page-header" data-aos="zoom-in" data-aos-duration="800">
    <h2>BATTLE ARENAS</h2>
    <p>Craft legendary challenges and epic word battles</p>
</div>

<div class="card" data-aos="fade-up" data-aos-duration="800">
    <div class="header-actions" data-aos="fade-left" data-aos-delay="100">
        <a href="{{ route('admin.levels.create') }}" class="btn btn-primary">
            ➕ Create New Level
        </a>
    </div>

    @if($levels->count() > 0)
        <table class="data-table" data-aos="fade-up" data-aos-delay="200">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Grid</th>
                    <th>Difficulty</th>
                    <th>Words</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($levels as $level)
                    <tr data-aos="fade-up" data-aos-delay="{{ 100 + ($loop->index * 50) }}">
                        <td data-label="Title"><span class="level-title">{{ $level->title }}</span></td>
                        <td data-label="Type">{{ ucfirst($level->game_type) }}</td>
                        <td data-label="Grid">
                            @if($level->game_type === 'wordle')
                                <span style="color: var(--text-muted); font-style: italic;">—</span>
                            @else
                                <span class="badge badge-info">{{ $level->grid_size }}×{{ $level->grid_size }}</span>
                            @endif
                        </td>
                        <td data-label="Difficulty">
                            @if($level->difficulty === 'easy')
                                <span class="badge badge-success">⭐ Easy</span>
                            @elseif($level->difficulty === 'medium')
                                <span class="badge badge-warning">⚔️ Med</span>
                            @else
                                <span class="badge badge-danger">🔥 Hard</span>
                            @endif
                        </td>
                        <td data-label="Words">
                            <span class="badge badge-info">{{ $level->words_count }}</span>
                        </td>
                        <td data-label="Status">
                            @if($level->is_published)
                                <span class="badge badge-success">✓ Live</span>
                            @else
                                <span class="badge badge-warning">📝 Draft</span>
                            @endif
                        </td>
                        <td data-label="Created" style="white-space: nowrap;">{{ $level->created_at->format('M d, Y') }}</td>
                        <td data-label="Actions">
                            <div class="action-buttons">
                                <a href="{{ route('game.play', $level) }}" class="btn btn-sm btn-success" target="_blank">▶️ Play</a>
                                <a href="{{ route('admin.levels.edit', $level) }}" class="btn btn-sm btn-primary">✏️ Edit</a>
                                <form action="{{ route('admin.levels.destroy', $level) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this level?')">🗑️ Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state" data-aos="zoom-in" data-aos-delay="400">
            <div class="empty-icon">🎯</div>
            <h3>NO LEVELS YET</h3>
            <p>Build your first arena and let the battles begin!</p>
            <a href="{{ route('admin.levels.create') }}" class="btn btn-primary">
                🚀 Create First Level
            </a>
        </div>
    @endif
</div>
@endsection
