<?php

namespace App\Http\Controllers;

use App\Models\Number;
use App\Models\QuizAttempt;
use App\Support\ArithmeticQuiz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * The Kimeru arithmetic quiz.
 *
 * The whole round, including the correct answers, lives in the session on the
 * server. The browser only ever receives the question and choices in Kimeru,
 * and sends back the number it chose.
 *
 * Session shape (key "quiz"):
 *   [
 *     'questions' => [ ['a' => 22, 'b' => 57, 'operator' => '+', 'answer' => 79,
 *                       'choices' => [78, 79, 69], 'chosen' => null], … 5 in total ],
 *     'current'   => 0,      // index of the question on screen
 *     'finished'  => false,  // true once the score is saved
 *   ]
 */
class QuizController extends Controller
{
    private const SESSION_KEY = 'quiz';

    /**
     * GET /quiz: show the current question (and its result, once checked).
     */
    public function show(Request $request): View
    {
        $round = $request->session()->get(self::SESSION_KEY);

        // No round yet, or the last one is over: start a fresh round.
        if ($round === null || $round['finished']) {
            $round = $this->startRound($request);
        }

        $index = $round['current'];
        $total = count($round['questions']);
        $question = $round['questions'][$index];

        return view('quiz.show', [
            'question' => $question,
            'position' => $index + 1,
            'total' => $total,
            'score' => $this->score($round),
            'isLast' => $index === $total - 1,
            'words' => $this->wordsFor([$question]),
        ]);
    }

    /**
     * POST /quiz: check the learner's choice for the current question.
     */
    public function check(Request $request): RedirectResponse
    {
        $round = $request->session()->get(self::SESSION_KEY);

        if ($round === null || $round['finished']) {
            return $this->sessionExpired($request);
        }

        $index = $round['current'];
        $question = $round['questions'][$index];

        // A question can only be answered once.
        if ($question['chosen'] !== null) {
            return redirect()->route('quiz')
                ->with('status', 'You have already answered this question.');
        }

        $validated = $request->validate([
            'choice' => ['required', 'integer', 'between:1,100', Rule::in($question['choices'])],
        ], [
            'choice.required' => 'Please pick an answer, then press Check.',
            'choice.integer' => 'Please pick one of the three answers.',
            'choice.between' => 'Please pick one of the three answers.',
            'choice.in' => 'Please pick one of the three answers.',
        ]);

        // Remember what they chose. The page works out right or wrong from this.
        $round['questions'][$index]['chosen'] = (int) $validated['choice'];
        $request->session()->put(self::SESSION_KEY, $round);

        // Post/Redirect/Get: refreshing the page won't resubmit the form.
        return redirect()->route('quiz');
    }

    /**
     * POST /quiz/next: move on to the next question, or finish the round.
     */
    public function next(Request $request): RedirectResponse
    {
        $round = $request->session()->get(self::SESSION_KEY);

        if ($round === null) {
            return $this->sessionExpired($request);
        }

        if ($round['finished']) {
            return redirect()->route('quiz.summary');
        }

        $index = $round['current'];

        // No skipping: the current question must be checked first.
        if ($round['questions'][$index]['chosen'] === null) {
            return redirect()->route('quiz');
        }

        $isLast = $index === count($round['questions']) - 1;

        if (! $isLast) {
            $round['current']++;
            $request->session()->put(self::SESSION_KEY, $round);

            return redirect()->route('quiz');
        }

        // Last question done: mark the round finished and save the score once.
        $round['finished'] = true;
        $request->session()->put(self::SESSION_KEY, $round);

        QuizAttempt::create([
            'user_id' => $request->user()?->id, // null for guests
            'quiz_type' => 'arithmetic',
            'score' => $this->score($round),
            'total' => count($round['questions']),
        ]);

        return redirect()->route('quiz.summary');
    }

    /**
     * GET /quiz/summary: the final score and every question with its answer.
     */
    public function summary(Request $request): View|RedirectResponse
    {
        $round = $request->session()->get(self::SESSION_KEY);

        // Only a finished round has a summary.
        if ($round === null || ! $round['finished']) {
            return redirect()->route('quiz');
        }

        return view('quiz.summary', [
            'questions' => $round['questions'],
            'score' => $this->score($round),
            'total' => count($round['questions']),
            'words' => $this->wordsFor($round['questions']),
        ]);
    }

    /**
     * Create a new round and keep it in the session.
     */
    private function startRound(Request $request): array
    {
        $questions = array_map(
            fn (array $question) => $question + ['chosen' => null],
            ArithmeticQuiz::makeRound(),
        );

        $round = [
            'questions' => $questions,
            'current' => 0,
            'finished' => false,
        ];

        $request->session()->put(self::SESSION_KEY, $round);

        return $round;
    }

    /**
     * How many questions so far were answered correctly.
     */
    private function score(array $round): int
    {
        $correct = array_filter(
            $round['questions'],
            fn (array $question) => $question['chosen'] === $question['answer'],
        );

        return count($correct);
    }

    /**
     * Kimeru words for every number the page may show, read from the numbers table.
     *
     * @return array<int, string>
     */
    private function wordsFor(array $questions): array
    {
        $values = [];

        foreach ($questions as $question) {
            array_push($values, $question['a'], $question['b'], $question['answer'], ...$question['choices']);
        }

        return Number::wordsFor($values);
    }

    /**
     * The round is gone (e.g. the session timed out): start again, kindly.
     */
    private function sessionExpired(Request $request): RedirectResponse
    {
        $request->session()->forget(self::SESSION_KEY);

        return redirect()->route('quiz')
            ->with('status', 'Your quiz session had expired, so we started a new round for you.');
    }
}
