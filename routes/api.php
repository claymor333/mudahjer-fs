<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;

/// {url}/api/register - register and returns bearer token
/// params -----
/// name, string max:255
/// email, string email max:255 unique:users
/// password, min:8
/// password_confirmation
Route::post('/register', [AuthController::class, 'register']);

/// {url}/api/login - logins and returns bearer token
/// params -----
/// name, string max:255
/// email
Route::post('/login', [AuthController::class, 'login']);

/// {url}/api/logout - logs out user and revokes token
/// params -----
/// none
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Get Lessons
    Route::get('/lessons/{user_id}', [QuizController::class, 'getLessons']);
    Route::get('/quizzes/{lesson_id}', [QuizController::class,'getQuizzes']);
    Route::get('/questions/{quiz_id}', [QuizController::class,'getQuestions']);  // amik semua dalam quiz

    Route::post('lessons/{lesson_id}/questions/{quiz_id}/submit', [QuizController::class,'submitQuiz']);
});
// Get Quizzes for a specific lesson
// Get Questions for a specific quiz
// Get Choices for a specific question
