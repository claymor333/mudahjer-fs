<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerStreak extends Model
{
    protected $fillable = [
        'player_id', 'submitted_at', 'lesson_id', 'quiz_id'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
