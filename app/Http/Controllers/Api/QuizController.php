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
        // Logic to retrieve lessons for the user
        // This is a placeholder, implement your logic here
        $player = Player::where('user_id', $user_id)->first();


        if (!$player) {
            return response()->json([
                'status' => 'error',
                'message' => 'Player not found'
            ], 404);
        }
        // Get lessons for the player
        $lessons = $player->lessons;

        $returnArr = [
            'status' => 'success',
            'data' => $lessons
        ];
        return response()->json($returnArr);
    }
    /**
     * Get a list of quizzes.
     */
    public function getQuizzes() {}
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
