<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $levels = Level::where('is_published', true)->withCount('words')->get();

        // Mark which levels the user has completed
        if (Auth::check()) {
            $completedLevelIds = Score::where('user_id', Auth::id())
                ->pluck('level_id')
                ->unique()
                ->toArray();

            $levels->each(function($level) use ($completedLevelIds) {
                $level->is_completed = in_array($level->id, $completedLevelIds);
            });
        } else {
            $levels->each(function($level) {
                $level->is_completed = false;
            });
        }

        return view('game.index', compact('levels'));
    }

    public function play(Level $level)
    {
        // Check if user has already completed this level
        if (Auth::check()) {
            $hasCompleted = Score::where('user_id', Auth::id())
                ->where('level_id', $level->id)
                ->exists();

            if ($hasCompleted) {
                return redirect()->route('game.index')
                    ->with('error', 'You have already completed this level! Try a different challenge.');
            }
        }

        $level->load('words');

        if ($level->game_type === 'wordsearch') {
            $grid = $this->generateWordSearch($level);
            return view('game.play', compact('level', 'grid'));
        } elseif ($level->game_type === 'wordle') {
            $grid = $this->generateWordle($level);
            return view('game.play-wordle', compact('level', 'grid'));
        } else {
            $grid = $this->generateCrossword($level);
            return view('game.play', compact('level', 'grid'));
        }
    }

    private function generateWordSearch(Level $level)
    {
        $size = $level->grid_size;
        $grid = array_fill(0, $size, array_fill(0, $size, ''));
        $words = $level->words->pluck('word')->map(fn($w) => strtoupper($w))->toArray();
        $placed = [];

        $directions = [
            [0, 1], [1, 0], [1, 1], [0, -1],
            [-1, 0], [-1, -1], [1, -1], [-1, 1],
        ];

        foreach ($words as $word) {
            $wordLength = strlen($word);
            $attempts = 0;
            $maxAttempts = 100;

            while ($attempts < $maxAttempts) {
                $dir = $directions[array_rand($directions)];
                $row = rand(0, $size - 1);
                $col = rand(0, $size - 1);

                if ($this->canPlaceWord($grid, $word, $row, $col, $dir, $size)) {
                    $this->placeWord($grid, $word, $row, $col, $dir);
                    $placed[] = $word;
                    break;
                }
                $attempts++;
            }
        }

        for ($i = 0; $i < $size; $i++) {
            for ($j = 0; $j < $size; $j++) {
                if ($grid[$i][$j] === '') {
                    $grid[$i][$j] = chr(rand(65, 90));
                }
            }
        }

        return ['grid' => $grid, 'words' => $words, 'placed' => $placed];
    }

    private function canPlaceWord($grid, $word, $row, $col, $dir, $size)
    {
        $len = strlen($word);
        for ($i = 0; $i < $len; $i++) {
            $newRow = $row + ($dir[0] * $i);
            $newCol = $col + ($dir[1] * $i);
            if ($newRow < 0 || $newRow >= $size || $newCol < 0 || $newCol >= $size) {
                return false;
            }
            if ($grid[$newRow][$newCol] !== '' && $grid[$newRow][$newCol] !== $word[$i]) {
                return false;
            }
        }
        return true;
    }

    private function placeWord(&$grid, $word, $row, $col, $dir)
    {
        $len = strlen($word);
        for ($i = 0; $i < $len; $i++) {
            $grid[$row + ($dir[0] * $i)][$col + ($dir[1] * $i)] = $word[$i];
        }
    }

    private function generateCrossword(Level $level)
    {
        $size = $level->grid_size;
        $grid = array_fill(0, $size, array_fill(0, $size, null));
        $words = $level->words;
        $placed = [];
        $clueNumber = 1;
        $acrossClues = [];
        $downClues = [];

        if ($words->count() > 0) {
            // Place first word horizontally in the middle
            $firstWord = strtoupper($words->first()->word);
            $startCol = (int)(($size - strlen($firstWord)) / 2);
            $row = (int)($size / 2);

            for ($i = 0; $i < strlen($firstWord); $i++) {
                $grid[$row][$startCol + $i] = $firstWord[$i];
            }

            $placed[] = [
                'word' => $firstWord,
                'clue' => $words->first()->clue,
                'position' => [$row, $startCol],
                'direction' => 'across',
                'number' => $clueNumber
            ];

            $acrossClues[] = [
                'number' => $clueNumber,
                'clue' => $words->first()->clue,
                'word' => $firstWord
            ];
            $clueNumber++;

            // Try to place remaining words by finding intersections
            foreach ($words->skip(1) as $wordObj) {
                $word = strtoupper($wordObj->word);
                $wordPlaced = false;

                // Try to find intersections with already placed words
                foreach ($placed as $placedWord) {
                    if ($wordPlaced) break;

                    $placedWordText = $placedWord['word'];
                    $placedPos = $placedWord['position'];
                    $placedDir = $placedWord['direction'];

                    // Try to intersect this new word with the placed word
                    for ($i = 0; $i < strlen($word); $i++) {
                        if ($wordPlaced) break;

                        for ($j = 0; $j < strlen($placedWordText); $j++) {
                            if ($word[$i] === $placedWordText[$j]) {
                                // Found a common letter! Try to place the word
                                $newDirection = $placedDir === 'across' ? 'down' : 'across';

                                if ($placedDir === 'across') {
                                    // Placed word is horizontal, new word will be vertical
                                    $newRow = $placedPos[0] - $i;
                                    $newCol = $placedPos[1] + $j;
                                } else {
                                    // Placed word is vertical, new word will be horizontal
                                    $newRow = $placedPos[0] + $j;
                                    $newCol = $placedPos[1] - $i;
                                }

                                if ($this->canPlaceCrosswordWord($grid, $word, $newRow, $newCol, $newDirection, $size)) {
                                    $this->placeCrosswordWord($grid, $word, $newRow, $newCol, $newDirection);

                                    $placed[] = [
                                        'word' => $word,
                                        'clue' => $wordObj->clue,
                                        'position' => [$newRow, $newCol],
                                        'direction' => $newDirection,
                                        'number' => $clueNumber
                                    ];

                                    if ($newDirection === 'across') {
                                        $acrossClues[] = [
                                            'number' => $clueNumber,
                                            'clue' => $wordObj->clue,
                                            'word' => $word
                                        ];
                                    } else {
                                        $downClues[] = [
                                            'number' => $clueNumber,
                                            'clue' => $wordObj->clue,
                                            'word' => $word
                                        ];
                                    }

                                    $clueNumber++;
                                    $wordPlaced = true;
                                    break;
                                }
                            }
                        }
                    }
                }

                // If we couldn't place the word by intersection, try random placement
                if (!$wordPlaced) {
                    $attempts = 0;
                    $maxAttempts = 200;

                    while ($attempts < $maxAttempts && !$wordPlaced) {
                        $direction = rand(0, 1) ? 'across' : 'down';

                        if ($direction === 'across') {
                            $r = rand(0, $size - 1);
                            $c = rand(0, max(0, $size - strlen($word)));
                        } else {
                            $r = rand(0, max(0, $size - strlen($word)));
                            $c = rand(0, $size - 1);
                        }

                        if ($this->canPlaceCrosswordWord($grid, $word, $r, $c, $direction, $size, true)) {
                            $this->placeCrosswordWord($grid, $word, $r, $c, $direction);

                            $placed[] = [
                                'word' => $word,
                                'clue' => $wordObj->clue,
                                'position' => [$r, $c],
                                'direction' => $direction,
                                'number' => $clueNumber
                            ];

                            if ($direction === 'across') {
                                $acrossClues[] = [
                                    'number' => $clueNumber,
                                    'clue' => $wordObj->clue,
                                    'word' => $word
                                ];
                            } else {
                                $downClues[] = [
                                    'number' => $clueNumber,
                                    'clue' => $wordObj->clue,
                                    'word' => $word
                                ];
                            }

                            $clueNumber++;
                            $wordPlaced = true;
                        }
                        $attempts++;
                    }
                }
            }
        }

        return [
            'grid' => $grid,
            'placed' => $placed,
            'acrossClues' => $acrossClues,
            'downClues' => $downClues,
            'clues' => array_merge($acrossClues, $downClues)
        ];
    }

    private function canPlaceCrosswordWord($grid, $word, $row, $col, $direction, $size, $allowNoIntersection = false)
    {
        $len = strlen($word);
        $hasIntersection = false;

        // Check if word fits in bounds
        if ($direction === 'across') {
            if ($col < 0 || $col + $len > $size || $row < 0 || $row >= $size) {
                return false;
            }
        } else {
            if ($row < 0 || $row + $len > $size || $col < 0 || $col >= $size) {
                return false;
            }
        }

        // Check before the word (must be empty)
        if ($direction === 'across') {
            if ($col > 0 && $grid[$row][$col - 1] !== null) {
                return false;
            }
        } else {
            if ($row > 0 && $grid[$row - 1][$col] !== null) {
                return false;
            }
        }

        // Check after the word (must be empty)
        if ($direction === 'across') {
            if ($col + $len < $size && $grid[$row][$col + $len] !== null) {
                return false;
            }
        } else {
            if ($row + $len < $size && $grid[$row + $len][$col] !== null) {
                return false;
            }
        }

        // Check each letter position
        for ($i = 0; $i < $len; $i++) {
            if ($direction === 'across') {
                $r = $row;
                $c = $col + $i;
            } else {
                $r = $row + $i;
                $c = $col;
            }

            $currentCell = $grid[$r][$c];

            // If cell is occupied
            if ($currentCell !== null) {
                // Must match the letter we want to place
                if ($currentCell !== $word[$i]) {
                    return false;
                }
                $hasIntersection = true;
            } else {
                // Check perpendicular cells (should be empty, except at intersections)
                if ($direction === 'across') {
                    // Check above and below
                    if (($r > 0 && $grid[$r - 1][$c] !== null) ||
                        ($r < $size - 1 && $grid[$r + 1][$c] !== null)) {
                        return false;
                    }
                } else {
                    // Check left and right
                    if (($c > 0 && $grid[$r][$c - 1] !== null) ||
                        ($c < $size - 1 && $grid[$r][$c + 1] !== null)) {
                        return false;
                    }
                }
            }
        }

        // Require at least one intersection unless it's allowed to have none
        return $hasIntersection || $allowNoIntersection;
    }

    private function placeCrosswordWord(&$grid, $word, $row, $col, $direction)
    {
        $len = strlen($word);
        for ($i = 0; $i < $len; $i++) {
            if ($direction === 'across') {
                $grid[$row][$col + $i] = $word[$i];
            } else {
                $grid[$row + $i][$col] = $word[$i];
            }
        }
    }

    private function generateWordle(Level $level)
    {
        // Get the word from the level
        $words = $level->words;

        if ($words->count() === 0) {
            return [
                'targetWord' => '',
                'wordLength' => 0,
                'maxAttempts' => 6,
                'validWords' => []
            ];
        }

        // For Wordle, there should be exactly 1 word
        $targetWord = strtoupper($words->first()->word);
        $wordLength = strlen($targetWord);

        // For Wordle, we accept any guess (no word list restriction)
        // The target word itself is always valid
        $validWords = [$targetWord];

        return [
            'targetWord' => $targetWord,
            'wordLength' => $wordLength,
            'maxAttempts' => 6,
            'validWords' => $validWords
        ];
    }
}
