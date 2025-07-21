<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Player\QuizController as PlayerQuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/player/quizzes', [PlayerQuizController::class, 'index'])->name('player.quizzes.index');
    Route::get('/player/quiz/play/{id}', [PlayerQuizController::class, 'show'])->name('player.quizzes.play');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [QuizController::class, 'index'])->name('dashboard');
    Route::get('/quizzes/create', [QuizController::class, 'createQuiz'])->name('quizzes.create');
    Route::post('/quizzes/store', [QuizController::class, 'storeQuiz'])->name('quizzes.store');

    Route::get('/quizzes/{id}/edit', [QuizController::class, 'editQuiz'])->name('quizzes.edit');
    Route::put('/quizzes/{id}/update', [QuizController::class, 'updateQuiz'])->name('quizzes.update');

    Route::delete('/quizzes/{id}/delete', [QuizController::class, 'deleteQuiz'])->name('quizzes.delete');

    Route::post('/quizzes/lesson/store', [QuizController::class, 'storeLesson'])->name('lessons.store');
});

require __DIR__.'/auth.php';
