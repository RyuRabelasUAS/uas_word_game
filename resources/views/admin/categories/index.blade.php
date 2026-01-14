@extends('layouts.admin')

@section('title', 'Categories')

@push('styles')
<style>
    .page-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .page-header h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.5rem;
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--accent-purple) 50%, var(--primary-yellow) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        letter-spacing: 2px;
        position: relative;
    }

    .page-header h2::before {
        content: '📁';
        position: absolute;
        left: -70px;
        animation: float 3s ease-in-out infinite;
        filter: drop-shadow(0 0 20px rgba(255, 215, 0, 0.6));
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(-5deg); }
        50% { transform: translateY(-15px) rotate(5deg); }
    }

    .page-header p {
        color: var(--text-muted);
        font-size: 1.1rem;
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

    .category-name {
        font-family: 'Orbitron', sans-serif;
        font-size: 1.1rem;
        font-weight: 700;
        background: linear-gradient(135deg, var(--primary-blue), var(--primary-yellow));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
    }

    .empty-icon {
        font-size: 7rem;
        margin-bottom: 2rem;
        animation: bounce 2s ease-in-out infinite;
        filter: drop-shadow(0 0 40px rgba(0, 217, 255, 0.5));
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-30px) scale(1.1); }
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
            display: inline-block;
            width: 100px;
            color: var(--primary-blue);
        }
    }
</style>
@endpush

@section('content')
<div class="page-header" data-aos="zoom-in" data-aos-duration="800">
    <h2>CATEGORIES</h2>
    <p>Organize your words into powerful categories</p>
</div>

<div class="card" data-aos="fade-up" data-aos-duration="800">
    <div class="header-actions" data-aos="fade-left" data-aos-delay="100">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            ➕ Add New Category
        </a>
    </div>

    @if($categories->count() > 0)
        <table class="data-table" data-aos="fade-up" data-aos-delay="200">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Words</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr data-aos="fade-up" data-aos-delay="{{ 100 + ($loop->index * 50) }}">
                        <td data-label="Name"><span class="category-name">{{ $category->name }}</span></td>
                        <td data-label="Description">{{ Str::limit($category->description ?? 'N/A', 50) }}</td>
                        <td data-label="Words">
                            <span class="badge badge-info">{{ $category->words_count }} words</span>
                        </td>
                        <td data-label="Created" style="white-space: nowrap;">{{ $category->created_at->format('M d, Y') }}</td>
                        <td data-label="Actions">
                            <div class="action-buttons">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">✏️ Edit</a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">🗑️ Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="empty-state" data-aos="zoom-in" data-aos-delay="400">
            <div class="empty-icon">📁</div>
            <h3>NO CATEGORIES YET</h3>
            <p>Create your first category to start organizing your word collection!</p>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                🚀 Create First Category
            </a>
        </div>
    @endif
</div>
@endsection
