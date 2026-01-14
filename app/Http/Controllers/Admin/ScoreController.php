<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\Level;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScoresExport;

class ScoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Score::with(['user', 'level']);

        // Apply filters
        if ($request->filled('game_type')) {
            $query->where('game_type', $request->game_type);
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('user_search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_search . '%')
                  ->orWhere('email', 'like', '%' . $request->user_search . '%');
            });
        }

        $scores = $query->latest()->paginate(50);
        $levels = Level::select('id', 'title', 'game_type')->get();

        return view('admin.scores.index', compact('scores', 'levels'));
    }

    public function export(Request $request)
    {
        $query = Score::with(['user', 'level']);

        // Apply same filters as index
        if ($request->filled('game_type')) {
            $query->where('game_type', $request->game_type);
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('user_search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->user_search . '%')
                  ->orWhere('email', 'like', '%' . $request->user_search . '%');
            });
        }

        $scores = $query->latest()->get();

        // Build filter information for export
        $filters = [];

        if ($request->filled('game_type')) {
            $filters['game_type'] = $request->game_type;
        }

        if ($request->filled('level_id')) {
            $level = Level::find($request->level_id);
            if ($level) {
                $filters['level_name'] = $level->title;
            }
        }

        if ($request->filled('date_from')) {
            $filters['date_from'] = $request->date_from;
        }

        if ($request->filled('date_to')) {
            $filters['date_to'] = $request->date_to;
        }

        if ($request->filled('user_search')) {
            $filters['user_search'] = $request->user_search;
        }

        // Build filename with filters
        $filenameParts = ['scores'];

        if ($request->filled('game_type')) {
            $filenameParts[] = $request->game_type;
        }

        if ($request->filled('level_id')) {
            $level = Level::find($request->level_id);
            if ($level) {
                $filenameParts[] = str_replace(' ', '_', $level->title);
            }
        }

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $dateRange = [];
            if ($request->filled('date_from')) {
                $dateRange[] = 'from_' . $request->date_from;
            }
            if ($request->filled('date_to')) {
                $dateRange[] = 'to_' . $request->date_to;
            }
            $filenameParts[] = implode('_', $dateRange);
        }

        $filename = implode('_', $filenameParts) . '_' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new ScoresExport($scores, $filters), $filename);
    }

    public function resetAll(Request $request)
    {
        // Delete all scores but keep users
        $deletedCount = Score::count();
        Score::truncate();

        return redirect()->route('admin.scores.index')
            ->with('success', "Successfully deleted {$deletedCount} score records. All users remain intact.");
    }
}
