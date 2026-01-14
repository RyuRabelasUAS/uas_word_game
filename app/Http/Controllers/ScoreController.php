<?php

namespace App\Http\Controllers;

use App\Events\ScoreSubmitted;
use App\Models\Level;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'level_id' => 'required|exists:levels,id',
            'time_seconds' => 'required|integer|min:0',
            'game_type' => 'required|in:wordsearch,crossword,wordle',
            'details' => 'required|array',
        ]);

        $level = Level::findOrFail($validated['level_id']);

        // Calculate score based on game type
        $score = $this->calculateScore(
            $validated['game_type'],
            $validated['time_seconds'],
            $validated['details'],
            $level
        );

        // Create score record
        $scoreRecord = Score::create([
            'user_id' => Auth::id(),
            'level_id' => $validated['level_id'],
            'score' => $score,
            'time_seconds' => $validated['time_seconds'],
            'game_type' => $validated['game_type'],
            'details' => $validated['details'],
        ]);

        // Broadcast the score submission
        event(new ScoreSubmitted($scoreRecord));

        return response()->json([
            'success' => true,
            'score' => $score,
            'scoreRecord' => $scoreRecord->load(['user', 'level']),
        ]);
    }

    private function calculateScore(string $gameType, int $timeSeconds, array $details, Level $level): int
    {
        return match($gameType) {
            'wordsearch' => $this->calculateWordSearchScore($timeSeconds, $details, $level),
            'crossword' => $this->calculateCrosswordScore($timeSeconds, $details, $level),
            'wordle' => $this->calculateWordleScore($timeSeconds, $details),
            default => 0,
        };
    }

    private function calculateWordSearchScore(int $timeSeconds, array $details, Level $level): int
    {
        $baseScore = 1000;
        $wordsFound = $details['words_found'] ?? 0;
        $totalWords = $level->words()->count();

        // Time bonus: max 500 points, decreases by 2 points per second
        $timeBonus = max(0, 500 - ($timeSeconds * 2));

        // Words bonus: 50 points per word found
        $wordsBonus = $wordsFound * 50;

        // Completion bonus: 300 points if all words found
        $completionBonus = ($wordsFound === $totalWords) ? 300 : 0;

        return (int) ($baseScore + $timeBonus + $wordsBonus + $completionBonus);
    }

    private function calculateCrosswordScore(int $timeSeconds, array $details, Level $level): int
    {
        $baseScore = 1500;
        $correctWords = $details['correct_words'] ?? 0;
        $totalWords = $level->words()->count();

        // Time bonus: max 750 points, decreases by 3 points per second
        $timeBonus = max(0, 750 - ($timeSeconds * 3));

        // Accuracy bonus: up to 500 points based on percentage correct
        $accuracyBonus = $totalWords > 0 ? (($correctWords / $totalWords) * 500) : 0;

        // Perfect bonus: 400 points if all words correct
        $perfectBonus = ($correctWords === $totalWords) ? 400 : 0;

        return (int) ($baseScore + $timeBonus + $accuracyBonus + $perfectBonus);
    }

    private function calculateWordleScore(int $timeSeconds, array $details): int
    {
        $baseScore = 2000;
        $attemptsUsed = $details['attempts_used'] ?? 6;
        $won = $details['won'] ?? false;

        if (!$won) {
            // Failed to guess - minimal score
            return 100;
        }

        // Attempts bonus: fewer attempts = more points
        // 1 attempt = 1800, 2 = 1500, 3 = 1200, 4 = 900, 5 = 600, 6 = 300
        $attemptsBonus = max(0, (7 - $attemptsUsed) * 300);

        // Speed bonus: max 300 points, decreases by 1 point per second
        $speedBonus = max(0, 300 - $timeSeconds);

        return (int) ($baseScore + $attemptsBonus + $speedBonus);
    }

    public function leaderboard(Request $request)
    {
        // Check if this is an API request
        if ($request->is('api/*')) {
            $gameType = $request->query('game_type');
            $limit = min($request->query('limit', 10), 50); // Max 50 entries

            $scores = Score::leaderboard($gameType, $limit)->get();

            return response()->json([
                'scores' => $scores,
                'game_type' => $gameType,
            ]);
        }

        // Return the view for regular web requests
        return view('leaderboard.index');
    }

    public function recent(Request $request)
    {
        $limit = min($request->query('limit', 10), 20); // Max 20 entries

        $scores = Score::recent($limit)->get();

        return response()->json([
            'scores' => $scores,
        ]);
    }
}
