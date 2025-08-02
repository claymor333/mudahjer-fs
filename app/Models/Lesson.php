<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'required_level',
    ];

    public function player()
    {
            return $this->belongsToMany(Player::class, 'lesson_player', 'lesson_id', 'player_id')
                ->withTimestamps();
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function notes()
    {
        return $this->hasManyThrough(
            Note::class,
            Quiz::class,
            'lesson_id',   // Foreign key on quizzes table
            'quiz_id',     // Foreign key on notes table
            'id',          // Local key on lessons table
            'id'           // Local key on quizzes table
        );
    }
}


