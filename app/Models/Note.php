<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'note_text', 'media_path', 'order'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
