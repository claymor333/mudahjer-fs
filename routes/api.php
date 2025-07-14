<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


/// {url}/api/register - logins and returns bearer token
Route::post('/register', [AuthController::class, 'register']);

/// {url}/api/login - logins and returns bearer token
Route::post('/login', [AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
