<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/quizzes/create', [AdminController::class, 'createQuiz'])->name('quizzes.create');
    Route::post('/quizzes/store', [AdminController::class, 'storeQuiz'])->name('quizzes.store');

    Route::get('/quizzes/{id}/edit', [AdminController::class, 'editQuiz'])->name('quizzes.edit');
    Route::put('/quizzes/{id}/update', [AdminController::class, 'updateQuiz'])->name('quizzes.update');

    Route::delete('/quizzes/{id}/delete', [AdminController::class, 'deleteQuiz'])->name('quizzes.delete');
});

require __DIR__.'/auth.php';
