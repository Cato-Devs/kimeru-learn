<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * GET /words: every category with how many words it has.
     */
    public function index(): View
    {
        return view('words.categories', [
            // withCount adds a words_count number to each category in one query.
            'categories' => Category::withCount('words')->orderBy('name')->get(),
        ]);
    }
}
