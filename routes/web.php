<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\QuizController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
