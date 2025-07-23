<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerLessonProgress extends Model
{
    protected $fillable = [
        'player_id', 'lesson_id', 'is_completed', 'completed_at'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
