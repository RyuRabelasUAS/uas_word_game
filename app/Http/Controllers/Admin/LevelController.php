<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Word;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $levels = Level::withCount('words')->latest()->get();
        return view('admin.levels.index', compact('levels'));
    }

    public function create()
    {
        $words = Word::with('category')->get();
        return view('admin.levels.create', compact('words'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'grid_size' => 'required|integer|min:10|max:30',
            'game_type' => 'required|in:wordsearch,crossword,wordle',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_published' => 'boolean',
            'words' => 'required|array|min:1',
            'words.*' => 'exists:words,id',
        ]);

        // Additional validation for Wordle
        if ($validated['game_type'] === 'wordle') {
            if (count($validated['words']) !== 1) {
                return back()->withErrors(['words' => 'Wordle levels must have exactly 1 word.'])->withInput();
            }
        } else {
            // For non-Wordle games, require at least 3 words
            if (count($validated['words']) < 3) {
                return back()->withErrors(['words' => 'Word Search and Crossword levels require at least 3 words.'])->withInput();
            }
        }

        $level = Level::create($validated);
        $level->words()->attach($validated['words']);

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level created successfully.');
    }

    public function edit(Level $level)
    {
        $level->load('words');
        $words = Word::with('category')->get();
        return view('admin.levels.edit', compact('level', 'words'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'grid_size' => 'required|integer|min:10|max:30',
            'game_type' => 'required|in:wordsearch,crossword,wordle',
            'difficulty' => 'required|in:easy,medium,hard',
            'is_published' => 'boolean',
            'words' => 'required|array|min:1',
            'words.*' => 'exists:words,id',
        ]);

        // Additional validation for Wordle
        if ($validated['game_type'] === 'wordle') {
            if (count($validated['words']) !== 1) {
                return back()->withErrors(['words' => 'Wordle levels must have exactly 1 word.'])->withInput();
            }
        } else {
            // For non-Wordle games, require at least 3 words
            if (count($validated['words']) < 3) {
                return back()->withErrors(['words' => 'Word Search and Crossword levels require at least 3 words.'])->withInput();
            }
        }

        $level->update($validated);
        $level->words()->sync($validated['words']);

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level updated successfully.');
    }

    public function destroy(Level $level)
    {
        $level->delete();

        return redirect()->route('admin.levels.index')
            ->with('success', 'Level deleted successfully.');
    }
}
