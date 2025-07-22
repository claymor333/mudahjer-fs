<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPlayerQuiz extends Model
{
    protected $fillable = [
        'player_id', 'lesson_id', 'quiz_id', 'is_completed', 'answers_json'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'answers_json' => 'array',
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
