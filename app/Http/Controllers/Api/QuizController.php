<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonPlayerQuiz;
use App\Models\Player;
use App\Models\PlayerLessonProgress;
use App\Models\PlayerStreak;
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
    public function getQuestions($quiz_id)
    {
        $quiz = Quiz::with([
            'questions.choices',
            'notes'
        ])->findOrFail($quiz_id);

        $data = [
            'quiz_id'       => $quiz->id,
            'lesson_id'     => $quiz->lesson_id,
            'title'         => $quiz->title,
            'description'   => $quiz->description,
            'choices_type'  => $quiz->choices_type,
            'notes'         => $quiz->notes->map(function ($note) {
                return [
                    'note_id'       => $note->id,
                    'note_text'     => $note->note_text,
                    'note_media'    => $note->media_path,
                ];
            }),
            'questions'     => $quiz->questions->map(function ($question) {
                return [
                    'question_id'   => $question->id,
                    'question_text' => $question->question_text,
                    'media_path'    => $question->media_path,
                    'choices'       => $question->choices->map(function ($choice) {
                        return [
                            'choice_id'     => $choice->id,
                            'question_id'   => $choice->question_id,
                            'choice_text'   => $choice->choice_text,
                            'choice_media'  => $choice->choice_media,
                            'is_correct'    => $choice->is_correct,
                        ];
                    }),
                ];
            }),
        ];

        return response()->json([
            'status' => 'success',
            'data'   => $data,
        ]);
    }

    public function submitQuiz(Request $request)
    {
        $player = Player::where('user_id', $request->user()->id)->first();

        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'quiz_id' => 'required|exists:quizzes,id',
            'answers' => 'required|array', // question_id => choice_id
            'is_completed' => 'boolean',
        ]);

        // Save lesson–quiz submission
        LessonPlayerQuiz::create([
            'player_id' => $player->id,
            'lesson_id' => $validated['lesson_id'],
            'quiz_id' => $validated['quiz_id'],
            'is_completed' => $validated['is_completed'] ?? false,
            'answers_json' => $validated['answers'],
        ]);

        // Optionally mark lesson as completed
        if (!empty($validated['is_completed'])) {
            PlayerLessonProgress::updateOrCreate(
                [
                    'player_id' => $player->id,
                    'lesson_id' => $validated['lesson_id'],
                ],
                [
                    'is_completed' => true,
                    'completed_at' => now(),
                ]
            );
        }

        // Log streak
        PlayerStreak::create([
            'player_id' => $player->id,
            'lesson_id' => $validated['lesson_id'],
            'quiz_id' => $validated['quiz_id'],
            'submitted_at' => now(),
        ]);

        return response()->json(['status' => 'success']);
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
