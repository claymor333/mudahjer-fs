<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'choice_text', 'choice_media', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
