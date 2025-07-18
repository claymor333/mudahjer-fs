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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/quizzes', [QuizController::class,'getQuizzes'])->middleware('auth:sanctum');