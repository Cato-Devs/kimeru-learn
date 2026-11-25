<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MyScoresController;
use App\Http\Controllers\NumberController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\WordController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');

Route::get('/numbers', [NumberController::class, 'index'])->name('numbers');

// Arithmetic quiz (anyone). The round is kept in the session, see QuizController.
// Learners who are logged in get their score saved to My scores.
Route::get('/quiz', [QuizController::class, 'show'])->name('quiz');
Route::post('/quiz', [QuizController::class, 'check'])->name('quiz.check');
Route::post('/quiz/next', [QuizController::class, 'next'])->name('quiz.next');
Route::get('/quiz/summary', [QuizController::class, 'summary'])->name('quiz.summary');

// Word lists: anyone can read them.
Route::get('/words', [CategoryController::class, 'index'])->name('words');
Route::get('/words/{category:slug}', [WordController::class, 'index'])->name('words.index');

// Adding, editing and deleting words: logged-in editors only.
// "can:edit-words" refuses learners with 403 (the Gate is in AppServiceProvider).
// scopeBindings() makes sure {word} belongs to {category}, otherwise 404.
Route::middleware(['auth', 'can:edit-words'])->scopeBindings()->group(function () {
    Route::get('/words/{category:slug}/create', [WordController::class, 'create'])->name('words.create');
    Route::post('/words/{category:slug}', [WordController::class, 'store'])->name('words.store');
    Route::get('/words/{category:slug}/{word}/edit', [WordController::class, 'edit'])->name('words.edit');
    Route::put('/words/{category:slug}/{word}', [WordController::class, 'update'])->name('words.update');
    Route::delete('/words/{category:slug}/{word}', [WordController::class, 'destroy'])->name('words.destroy');
});

// A learner's own quiz history.
Route::get('/my-scores', [MyScoresController::class, 'index'])->middleware('auth')->name('my-scores');

// Sign-up (learners) and login (learners and editors).
// "guest" sends people who are already logged in to the home page.
// "throttle:5,1" allows 5 attempts per minute, to slow down password guessing and spam sign-ups.
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
