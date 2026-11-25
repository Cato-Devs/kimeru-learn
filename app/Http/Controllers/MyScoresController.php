<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * A logged-in user's own quiz history. Each user only ever sees their own rows.
 */
class MyScoresController extends Controller
{
    /**
     * GET /my-scores
     */
    public function index(Request $request): View
    {
        // quizAttempts() is the relationship in the User model, so this only
        // reads rows where user_id is this user's id.
        // Newest first; id breaks ties between rounds finished in the same second.
        $attempts = $request->user()->quizAttempts()->latest()->latest('id')->get();

        return view('my-scores', [
            'attempts' => $attempts,
            'best' => $attempts->max('score'),
            'average' => $attempts->isEmpty() ? null : round($attempts->avg('score'), 1),
            'total' => $attempts->first()?->total,
        ]);
    }
}
