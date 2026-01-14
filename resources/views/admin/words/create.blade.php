@extends('layouts.admin')

@section('title', 'Create Word')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Create New Word</h2>
        <a href="{{ route('admin.words.index') }}" class="btn btn-primary">Back to Words</a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.words.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="category_id">Category *</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="word">Word *</label>
                <input type="text" id="word" name="word" class="form-control" value="{{ old('word') }}" required placeholder="e.g., LARAVEL">
                @error('word')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="clue">Clue *</label>
                <textarea id="clue" name="clue" class="form-control" rows="3" required placeholder="A hint to help players guess the word">{{ old('clue') }}</textarea>
                @error('clue')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="difficulty">Difficulty *</label>
                <select id="difficulty" name="difficulty" class="form-control" required>
                    <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ old('difficulty', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
                @error('difficulty')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                <button type="submit" class="btn btn-success" style="margin-right: 1rem;">Create Word</button>
                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('admin.words.index') }}'">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
