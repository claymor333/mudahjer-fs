<?php

namespace App\Http\Controllers;

use App\Models\Choice;
use App\Models\Lesson;
use App\Models\LessonPlayerQuiz;
use App\Models\Player;
use App\Models\PlayerStreak;
use App\Models\Question;
use Clockwork\Request\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $player = Player::where('user_id', $user->id)->first();

        $categories = Lesson::count();
        $signs = Question::count();

        $totalQuizzes = LessonPlayerQuiz::where('player_id', $player->id)
            ->where('is_completed', true)
            ->whereMonth('created_at', now()->month)
            ->count();

        $completed = LessonPlayerQuiz::where('player_id', $player->id)
            ->where('is_completed', true)
            ->get();

        $answeredQuizzes =  LessonPlayerQuiz::where('player_id', $player->id)->get();

        $totalAnswers = 0;
        $correctAnswers = 0;

        foreach ($answeredQuizzes as $lpq) {
            $answers = $lpq->answers_json;

            foreach ($answers as $questionId => $choiceId) {
                $correctChoiceId = Choice::where('question_id', $questionId)
                    ->where('is_correct', true)
                    ->value('id');

                if ($correctChoiceId && $choiceId == $correctChoiceId) {
                    $correctAnswers++;
                }

                $totalAnswers++;
            }
        }

        $accuracy = $totalAnswers > 0
            ? round(($correctAnswers / $totalAnswers) * 100)
            : 0;

        // Level progress
        $level = $player->level ?? 1;
        $lessons = Lesson::where('required_level', $level)->get();

        $inProgressCount = 0;
        $canAdvance = false;

        foreach ($lessons as $lesson) {
            $lp = DB::table('lesson_player')
                ->where('player_id', $player->id)
                ->where('lesson_id', $lesson->id)
                ->first();

            if ($lp && $lp->progress >= 100) {
                $inProgressCount++;
                $canAdvance = true;
            }
        }

        // 🔥 Streak Calculation
        $streakDays = 0;
        $dates = PlayerStreak::where('player_id', $player->id)
            ->orderByDesc('submitted_at')
            ->pluck('submitted_at')
            ->map(fn($d) => $d->startOfDay())
            ->unique()
            ->values();

        $today = now()->startOfDay();

        foreach ($dates as $index => $date) {
            if ($index === 0 && $date->diffInDays($today) > 1) {
                // Not today or yesterday, streak is broken
                break;
            }

            if ($index === 0 || $date->diffInDays($dates[$index - 1]) === 1) {
                $streakDays++;
            } elseif ($index === 0 && $date->isSameDay($today)) {
                $streakDays++;
            } else {
                break;
            }
        }


        return view('dashboard', compact(
            'categories',
            'signs',
            'totalQuizzes',
            'accuracy',
            'level',
            'inProgressCount',
            'canAdvance',
            'streakDays'
        ));
    }
}
