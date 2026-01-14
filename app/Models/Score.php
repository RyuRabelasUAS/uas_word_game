<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    protected $fillable = [
        'user_id',
        'level_id',
        'score',
        'time_seconds',
        'game_type',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    // Scope for leaderboard - top scores
    public function scopeLeaderboard($query, $gameType = null, $limit = 10)
    {
        $query = $query->with(['user', 'level'])
            ->orderBy('score', 'desc')
            ->orderBy('time_seconds', 'asc')
            ->limit($limit);

        if ($gameType) {
            $query->where('game_type', $gameType);
        }

        return $query;
    }

    // Scope for recent scores
    public function scopeRecent($query, $limit = 10)
    {
        return $query->with(['user', 'level'])
            ->orderBy('created_at', 'desc')
            ->limit($limit);
    }
}
