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
    Route::get('/admin', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/admin/quizzes', [AdminController::class, 'storeQuiz'])->name('quizzes.store');
    Route::get('/admin/quizzes/create', [AdminController::class, 'createQuiz'])->name('quizzes.create');
    Route::post('/admin/quizzes/store-wizard', [AdminController::class, 'storeQuizWithQuestions'])->name('quizzes.store-wizard');
});

require __DIR__.'/auth.php';
