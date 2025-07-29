<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlayerController extends Controller
{
    public function addExp(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'exp' => 'required|integer|min:1',
        ]);

        $player = Player::where('user_id', $request->user()->id)->firstOrFail();
        $lessonId = $validated['lesson_id'];
        $expToAdd = $validated['exp'];

        // Attach only if not already attached
        if (! $player->lessons()->where('lesson_id', $lessonId)->exists()) {
            $player->lessons()->attach($lessonId, ['progress' => 0]);
        }

        // Get current progress
        $pivot = $player->lessons()->where('lesson_id', $lessonId)->first()->pivot;
        $currentProgress = $pivot->progress;

        // Calculate new progress (incremented, capped at 100)
        $newProgress = min($currentProgress + $expToAdd, 100);
        $lessonCompleted = false;

        // Update the pivot progress by incrementing it
        $player->lessons()->updateExistingPivot($lessonId, [
            'progress' => $newProgress
        ]);

        // Level up if progress reaches 100 for the first time
        if ($currentProgress < 100 && $newProgress >= 100) {
            $player->level += 1;
            $lessonCompleted = true;
        }

        // Always increment player EXP
        $player->exp += $expToAdd;
        $player->save();

        return response()->json([
            'message' => 'Progress and EXP incremented.',
            'total_exp' => $player->exp,
            'level' => $player->level,
            'lesson_progress' => $newProgress,
            'lesson_completed' => $lessonCompleted,
        ]);
    }
}
