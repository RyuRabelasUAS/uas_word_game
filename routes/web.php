<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\ScoreController as AdminScoreController;
use App\Http\Controllers\Admin\WordController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ScoreController;
use Illuminate\Support\Facades\Route;

// Game Routes
Route::get('/', [GameController::class, 'index'])->name('game.index');

// Play route requires authentication to track completion
Route::middleware('auth')->group(function () {
    Route::get('/play/{level}', [GameController::class, 'play'])->name('game.play');
});

// Score Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::post('/score/submit', [ScoreController::class, 'submit'])->name('score.submit');
});

// Leaderboard Routes (Public)
Route::get('/leaderboard', [ScoreController::class, 'leaderboard'])->name('leaderboard.index');
Route::get('/api/scores/leaderboard', [ScoreController::class, 'leaderboard'])->name('api.leaderboard');
Route::get('/api/scores/recent', [ScoreController::class, 'recent'])->name('api.scores.recent');

// Session Test Route (for debugging)
Route::get('/test-session', function () {
    return view('test-session');
});
Route::post('/test-session', function () {
    return redirect('/test-session')->with('success', 'Form submitted successfully! CSRF is working.');
});

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/api/check-email', [AuthController::class, 'checkEmail'])->name('api.check-email');

// Admin Routes (Protected)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.levels.index');
    });

    Route::resource('categories', CategoryController::class);
    Route::resource('words', WordController::class);
    Route::resource('levels', LevelController::class);

    // Score management
    Route::get('scores', [AdminScoreController::class, 'index'])->name('scores.index');
    Route::get('scores/export', [AdminScoreController::class, 'export'])->name('scores.export');
    Route::post('scores/reset', [AdminScoreController::class, 'resetAll'])->name('scores.reset');
});
