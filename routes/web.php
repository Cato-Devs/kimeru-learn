<?php

use App\Http\Controllers\NumberController;
use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');

Route::get('/numbers', [NumberController::class, 'index'])->name('numbers');

// Arithmetic quiz (guests). The round is kept in the session, see QuizController.
Route::get('/quiz', [QuizController::class, 'show'])->name('quiz');
Route::post('/quiz', [QuizController::class, 'check'])->name('quiz.check');
Route::post('/quiz/next', [QuizController::class, 'next'])->name('quiz.next');
Route::get('/quiz/summary', [QuizController::class, 'summary'])->name('quiz.summary');
