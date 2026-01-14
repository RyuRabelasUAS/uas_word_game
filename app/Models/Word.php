<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    protected $fillable = ['category_id', 'word', 'clue', 'difficulty'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class)->withPivot('position_x', 'position_y', 'direction')->withTimestamps();
    }
}
