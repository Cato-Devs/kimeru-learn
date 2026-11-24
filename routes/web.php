<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NumberController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');

Route::get('/numbers', [NumberController::class, 'index'])->name('numbers');

// Arithmetic quiz (guests). The round is kept in the session, see QuizController.
Route::get('/quiz', [QuizController::class, 'show'])->name('quiz');
Route::post('/quiz', [QuizController::class, 'check'])->name('quiz.check');
Route::post('/quiz/next', [QuizController::class, 'next'])->name('quiz.next');
Route::get('/quiz/summary', [QuizController::class, 'summary'])->name('quiz.summary');

// Word lists: anyone can read them.
Route::get('/words', [CategoryController::class, 'index'])->name('words');
Route::get('/words/{category:slug}', [WordController::class, 'index'])->name('words.index');

// Adding, editing and deleting words: logged-in editors only.
// scopeBindings() makes sure {word} belongs to {category}, otherwise 404.
Route::middleware('auth')->scopeBindings()->group(function () {
    Route::get('/words/{category:slug}/create', [WordController::class, 'create'])->name('words.create');
    Route::post('/words/{category:slug}', [WordController::class, 'store'])->name('words.store');
    Route::get('/words/{category:slug}/{word}/edit', [WordController::class, 'edit'])->name('words.edit');
    Route::put('/words/{category:slug}/{word}', [WordController::class, 'update'])->name('words.update');
    Route::delete('/words/{category:slug}/{word}', [WordController::class, 'destroy'])->name('words.destroy');
});

// Editor login. "guest" sends people who are already logged in to the home page.
// "throttle:5,1" allows 5 login attempts per minute, to slow down password guessing.
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
