<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'choices_type', 'lesson_id'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class)->orderBy('order');
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
