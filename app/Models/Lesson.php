<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
    ];

    public function player()
    {
            return $this->belongsToMany(Player::class, 'players_lessons', 'lesson_id', 'player_id')
                ->withTimestamps();
    }

}


