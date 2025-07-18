<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    //
    protected $fillable = [
        'user_id',
        'username',
        'avatar',
        'level',
        'exp',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lessons()
    {
        return $this->belongsToMany(Lesson::class, 'players_lessons', 'player_id', 'lesson_id')
            ->withPivot('completed', 'progress', 'completed_at')
            ->withTimestamps();
    }
}
