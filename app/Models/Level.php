<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $fillable = ['title', 'description', 'grid_size', 'game_type', 'difficulty', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function words()
    {
        return $this->belongsToMany(Word::class)->withPivot('position_x', 'position_y', 'direction')->withTimestamps();
    }
}
