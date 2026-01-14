@extends('layouts.admin')

@section('title', 'Create Level')

@section('content')
<div class="card">
    <div class="card-header">
        <h2>Create New Level</h2>
        <a href="{{ route('admin.levels.index') }}" class="btn btn-primary">Back to Levels</a>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.levels.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="title">Level Title *</label>
            <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g., Beginner Programming Terms">
            @error('title')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" class="form-control" rows="2" placeholder="Brief description of this level">{{ old('description') }}</textarea>
            @error('description')
                <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="game_type">Game Type *</label>
                <select id="game_type" name="game_type" class="form-control" required>
                    <option value="wordsearch" {{ old('game_type', 'wordsearch') == 'wordsearch' ? 'selected' : '' }}>Word Search</option>
                    <option value="crossword" {{ old('game_type') == 'crossword' ? 'selected' : '' }}>Crossword</option>
                    <option value="wordle" {{ old('game_type') == 'wordle' ? 'selected' : '' }}>Wordle</option>
                </select>
                @error('game_type')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" id="gridSizeGroup">
                <label for="grid_size">Grid Size *</label>
                <select id="grid_size" name="grid_size" class="form-control">
                    <option value="10" {{ old('grid_size') == '10' ? 'selected' : '' }}>10x10 (Small)</option>
                    <option value="15" {{ old('grid_size', '15') == '15' ? 'selected' : '' }}>15x15 (Medium)</option>
                    <option value="20" {{ old('grid_size') == '20' ? 'selected' : '' }}>20x20 (Large)</option>
                    <option value="25" {{ old('grid_size') == '25' ? 'selected' : '' }}>25x25 (Extra Large)</option>
                </select>
                @error('grid_size')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
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

            <div class="form-group">
                <label for="is_published">Status</label>
                <select id="is_published" name="is_published" class="form-control">
                    <option value="0" {{ old('is_published', '0') == '0' ? 'selected' : '' }}>Draft</option>
                    <option value="1" {{ old('is_published') == '1' ? 'selected' : '' }}>Published</option>
                </select>
                @error('is_published')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label id="wordsLabel">Select Words (minimum 3 required) *</label>
            <div id="wordleHint" style="display: none; background: linear-gradient(135deg, rgba(0, 217, 255, 0.1) 0%, rgba(183, 148, 244, 0.1) 100%); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid var(--primary-blue);">
                <strong style="color: var(--primary-blue);">📝 Wordle Requirements:</strong>
                <ul style="margin: 0.5rem 0 0 1.5rem; color: var(--text-muted);">
                    <li>Select exactly <strong>1 word</strong></li>
                    <li>Word can be any length (4-7 letters recommended)</li>
                    <li>Players will have 6 attempts to guess it</li>
                </ul>
            </div>
            @error('words')
                <div class="form-error">{{ $message }}</div>
            @enderror

            @if($words->count() > 0)
                <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; border-radius: 4px; padding: 1rem;" id="wordsList">
                    @php $currentCategory = null; @endphp
                    @foreach($words as $word)
                        @if($currentCategory !== $word->category->name)
                            @php $currentCategory = $word->category->name; @endphp
                            <div class="category-header" style="font-weight: 600; color: #2c3e50; margin-top: {{ $loop->first ? '0' : '1rem' }}; margin-bottom: 0.5rem; padding-top: {{ $loop->first ? '0' : '1rem' }}; border-top: {{ $loop->first ? 'none' : '1px solid #ecf0f1' }};">
                                {{ $currentCategory }}
                            </div>
                        @endif
                        <div class="word-item" data-word-length="{{ strlen($word->word) }}" style="margin-bottom: 0.5rem; padding-left: 1rem;">
                            <label style="display: flex; align-items: center; cursor: pointer;">
                                <input type="checkbox" class="word-checkbox" name="words[]" value="{{ $word->id }}"
                                    {{ (is_array(old('words')) && in_array($word->id, old('words'))) ? 'checked' : '' }}
                                    style="margin-right: 0.5rem;">
                                <input type="radio" class="word-radio" name="wordle_word" value="{{ $word->id }}"
                                    {{ (is_array(old('words')) && in_array($word->id, old('words'))) ? 'checked' : '' }}
                                    style="margin-right: 0.5rem; display: none;">
                                <span><strong>{{ strtoupper($word->word) }}</strong> ({{ strlen($word->word) }} letters) - {{ $word->clue }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: #e74c3c; padding: 1rem; background: #fee; border-radius: 4px;">
                    No words available. Please <a href="{{ route('admin.words.create') }}" style="color: #c0392b; text-decoration: underline;">create some words</a> first.
                </p>
            @endif
        </div>

            <div class="form-group" style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.05);">
                <button type="submit" class="btn btn-success" style="margin-right: 1rem;">Create Level</button>
                <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('admin.levels.index') }}'">Cancel</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const gameTypeSelect = document.getElementById('game_type');
    const wordsList = document.getElementById('wordsList');
    const wordsLabel = document.getElementById('wordsLabel');
    const wordleHint = document.getElementById('wordleHint');
    const gridSizeGroup = document.getElementById('gridSizeGroup');
    const gridSizeSelect = document.getElementById('grid_size');

    function updateWordSelection() {
        const gameType = gameTypeSelect.value;
        const isWordle = gameType === 'wordle';

        // Show/hide grid size selector (Wordle doesn't need a grid)
        if (isWordle) {
            gridSizeGroup.style.display = 'none';
            gridSizeSelect.removeAttribute('required');
            // Set a default value for submission even though it won't be used
            gridSizeSelect.value = '15';
        } else {
            gridSizeGroup.style.display = '';
            gridSizeSelect.setAttribute('required', 'required');
        }

        // Show/hide Wordle hint
        wordleHint.style.display = isWordle ? 'block' : 'none';

        // Update label
        wordsLabel.textContent = isWordle ? 'Select Word (exactly 1 required) *' : 'Select Words (minimum 3 required) *';

        // Get all word items
        const wordItems = document.querySelectorAll('.word-item');
        const categoryHeaders = document.querySelectorAll('.category-header');

        if (isWordle) {
            // Show all words for Wordle, use radio buttons
            wordItems.forEach(item => {
                const checkbox = item.querySelector('.word-checkbox');
                const radio = item.querySelector('.word-radio');

                item.style.display = '';
                checkbox.style.display = 'none';
                radio.style.display = '';
                // Sync radio with checkbox state
                if (checkbox.checked) {
                    radio.checked = true;
                }
            });

            // Show all category headers
            categoryHeaders.forEach(header => {
                header.style.display = '';
            });
        } else {
            // Show all words for other game types
            wordItems.forEach(item => {
                const checkbox = item.querySelector('.word-checkbox');
                const radio = item.querySelector('.word-radio');

                item.style.display = '';
                checkbox.style.display = '';
                radio.style.display = 'none';
                // Sync checkbox with radio state
                if (radio.checked) {
                    checkbox.checked = true;
                }
            });

            // Show all category headers
            categoryHeaders.forEach(header => {
                header.style.display = '';
            });
        }
    }

    // Handle radio button selection for Wordle (sync with hidden checkbox and create hidden input)
    document.querySelectorAll('.word-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                // Uncheck all checkboxes
                document.querySelectorAll('.word-checkbox').forEach(cb => cb.checked = false);
                // Check the corresponding checkbox
                const checkbox = this.parentElement.querySelector('.word-checkbox');
                checkbox.checked = true;
            }
        });
    });

    // Listen for game type changes
    gameTypeSelect.addEventListener('change', updateWordSelection);

    // Initialize on page load
    updateWordSelection();
</script>
@endpush
@endsection
