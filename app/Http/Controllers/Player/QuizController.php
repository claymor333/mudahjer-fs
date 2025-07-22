<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    public function index()
    {
        // fetch all lessons with their related quiz
        $lessons = Lesson::with('quizzes')->get();

        // log the data as an array, not the Eloquent collection directly
        Log::info('Fetching lessons for quizzes', [
            'lessons' => $lessons->toArray()
        ]);

        return view('player.quizzes.index', compact('lessons'));
    }

    public function show($id)
    {
        $quiz = Quiz::with([
            'lesson',
            'questions' => function ($query) {
                $query->orderBy('id', 'asc')
                    ->with(['choices' => function ($q) {
                        $q->orderBy('id', 'asc');
                    }]);
            },
            'notes' => function ($query) {
                $query->orderBy('id', 'asc');
            }
        ])->findOrFail($id);

        return view('player.quizzes.show', compact('quiz', 'id'));
    }

    public function takeQuiz($id)
    {
        // Logic to handle taking a quiz
        return view('player.quizzes.take', compact('id'));
    }
}
