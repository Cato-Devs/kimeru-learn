<?php

namespace App\Http\Controllers;

use App\Models\Number;
use Illuminate\View\View;

class NumberController extends Controller
{
    /**
     * GET /numbers: list 1–100 with their Kimeru words.
     */
    public function index(): View
    {
        return view('numbers.index', [
            'numbers' => Number::orderBy('value')->get(),
        ]);
    }
}
