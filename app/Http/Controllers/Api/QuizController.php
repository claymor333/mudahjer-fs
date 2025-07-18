<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Player;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller


{

    /**
     * Get a list of lessons.
     */
    public function getLessons($user_id)
    {
        $player = Player::where('user_id', $user_id)->first();

        if (!$player) {
            return response()->json([
                'status' => 'error',
                'message' => 'Player not found'
            ], 404);
        }

        $lessons = $player->lessons;

        $total = $lessons->count();
        $completed = $lessons->where('pivot.completed', true)->count();
        $percentage = $total > 0 ? round(($completed / $total) * 100, 2) : 0;

        // Add completed and progress info to each lesson
        $lessonsData = $lessons->map(function ($lesson) {
            return [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'completed' => (bool) $lesson->pivot->completed,
                'progress' => $lesson->pivot->progress,
                'completed_at' => $lesson->pivot->completed_at,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $lessonsData,
            'percentage_completed' => $percentage,
            'total_lessons' => $total,
            'completed_lessons' => $completed,
        ]);
    }
    /**
     * Get a list of quizzes.
     */
    public function getQuizzes($lesson_id)
    {
        $quizzes = Quiz::where('lesson_id', $lesson_id)->get();
        $this_lesson = Lesson::find($lesson_id);
        if ($quizzes->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No quizzes found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $quizzes,
            'lesson' => [
                'id' => $this_lesson->id,
                'title' => $this_lesson->title,
                'description' => $this_lesson->description,
            ]
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
