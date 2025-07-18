<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller


{

    /**
     * Get a list of quizzes.
     */
    public function getQuizzes(){
        $quizzes = Quiz::with('questions.choices')
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->each(function ($quiz) {
                $quiz->questions->each(function ($question) {
                    $question->choices = $question->choices->map(function ($choice) {
                        return [
                            'id' => $choice->id,
                            'text' => $choice->text,
                            'is_correct' => $choice->is_correct
                        ];
                    });
                });
            });
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
