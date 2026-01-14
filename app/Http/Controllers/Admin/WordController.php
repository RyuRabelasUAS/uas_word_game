<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Word;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function index()
    {
        $words = Word::with('category')->latest()->paginate(20);
        return view('admin.words.index', compact('words'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.words.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'word' => 'required|string|max:255',
            'clue' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        Word::create($validated);

        return redirect()->route('admin.words.index')
            ->with('success', 'Word created successfully.');
    }

    public function edit(Word $word)
    {
        $categories = Category::all();
        return view('admin.words.edit', compact('word', 'categories'));
    }

    public function update(Request $request, Word $word)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'word' => 'required|string|max:255',
            'clue' => 'required|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $word->update($validated);

        return redirect()->route('admin.words.index')
            ->with('success', 'Word updated successfully.');
    }

    public function destroy(Word $word)
    {
        $word->delete();

        return redirect()->route('admin.words.index')
            ->with('success', 'Word deleted successfully.');
    }
}
