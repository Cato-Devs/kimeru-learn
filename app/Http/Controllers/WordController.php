<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Word;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * The words in one category: list them (anyone), and add, edit or delete
 * them (logged-in editors only; see routes/web.php).
 *
 * The {category} in every URL is found by its slug (e.g. "greetings"), and
 * {word} must belong to that category, or Laravel returns 404.
 */
class WordController extends Controller
{
    /**
     * GET /words/{category}: the word list.
     */
    public function index(Category $category): View
    {
        return view('words.index', [
            'category' => $category,
            'words' => $category->words()->orderBy('kimeru')->get(),
        ]);
    }

    /**
     * GET /words/{category}/create: the "add a word" form.
     */
    public function create(Category $category): View
    {
        return view('words.create', [
            'category' => $category,
            'word' => new Word,
        ]);
    }

    /**
     * POST /words/{category}: save a new word.
     */
    public function store(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate($this->rules($category));

        // Creating through the relationship sets category_id from the URL.
        $word = $category->words()->create($data);

        return redirect()->route('words.index', $category)
            ->with('status', "Added \"{$word->kimeru}\".");
    }

    /**
     * GET /words/{category}/{word}/edit: the edit form.
     */
    public function edit(Category $category, Word $word): View
    {
        return view('words.edit', [
            'category' => $category,
            'word' => $word,
        ]);
    }

    /**
     * PUT /words/{category}/{word}: save changes.
     */
    public function update(Request $request, Category $category, Word $word): RedirectResponse
    {
        $data = $request->validate($this->rules($category, $word));

        $word->update($data);

        return redirect()->route('words.index', $category)
            ->with('status', "Saved \"{$word->kimeru}\".");
    }

    /**
     * DELETE /words/{category}/{word}: remove a word.
     */
    public function destroy(Category $category, Word $word): RedirectResponse
    {
        $word->delete();

        return redirect()->route('words.index', $category)
            ->with('status', "Deleted \"{$word->kimeru}\".");
    }

    /**
     * The validation rules for adding and editing a word.
     *
     * The Kimeru word must be unique within this category. When editing,
     * the word being edited is ignored, so saving it unchanged is allowed.
     */
    private function rules(Category $category, ?Word $word = null): array
    {
        $unique = Rule::unique('words', 'kimeru')->where('category_id', $category->id);

        if ($word !== null) {
            $unique->ignore($word);
        }

        return [
            'kimeru' => ['required', 'string', 'max:100', $unique],
            'english' => ['required', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
