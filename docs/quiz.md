# The arithmetic quiz

The quiz at `/quiz` helps learners practise reading Kimeru numbers from 1 to 100 by adding and subtracting with them. Anyone can play as a guest; no login is needed.

## What a round looks like

- A round has **5 questions**. Each one is randomly **addition** or **subtraction**.
  - Addition: `a + b`, with an answer from 2 to 100 (both parts are at least 1, because Kimeru Learn has no word for zero).
  - Subtraction: `a − b`, where `a` is bigger than `b`, so the answer is from 1 to 99.
- The question is written **only in Kimeru words**, for example:
  *mĩrongo ĩrĩ na iyĩrĩ + mĩrongo ĩthano na mũgwanja = ?* (22 + 57)
- The learner picks one of **3 answers written in Kimeru**: the right answer plus two "near misses" (the answer ±1, ±2 or ±10), all between 1 and 100, in random order.
- After pressing **Check**, the result appears on the same question:
  - ✓ Correct! 22 + 57 = 79: *mĩrongo mũgwanja na kenda*
  - ✗ Incorrect. 22 + 57 = 79: *mĩrongo mũgwanja na kenda*, plus "You chose 78: *mĩrongo mũgwanja na inyanya*"

  The chosen answer and the correct answer are both highlighted, and the choices are locked.
- **Next question** moves on ("See my score" on the last one). The page always shows "Question 2 of 5" and the running score.
- The **summary** shows "You scored X / 5" and all 5 questions with ✓/✗, each in digits and in Kimeru. The score is saved as one row in `quiz_attempts`.

## How it works

When a learner opens the quiz, the server creates a round of five random questions and stores them, with their correct answers, in the learner's **session**. The session is a small store on the server, linked to the browser by a cookie. The page only receives the question and three choices written in Kimeru, so the correct answer is never sent to the browser.

When the learner picks a choice and presses **Check**, the browser sends that one number to the server in a form protected by a CSRF token. The server checks that it is one of the three choices offered, compares it with the answer stored in the session, records the choice, and redirects back to the quiz page. That page now shows whether the answer was right, the sum in digits and the correct answer in Kimeru. Once a choice is recorded, the server refuses any other answer to that question.

**Next question** moves the session on to the next question. After the fifth, the server marks the round as finished, saves the score once in the `quiz_attempts` table and shows a summary of all five questions. If the session expires partway through, the server starts a fresh round and tells the learner why. Because every step is an ordinary form submission, the quiz works without JavaScript.

## Routes

| Method | URL | Controller method | What it does |
|---|---|---|---|
| GET | `/quiz` | `QuizController@show` | Shows the current question, or its result once checked. Starts a new round if there is none or the last one is finished. |
| POST | `/quiz` | `QuizController@check` | Checks the chosen answer and stores it in the session |
| POST | `/quiz/next` | `QuizController@next` | Moves to the next question; after the last one, saves the score |
| GET | `/quiz/summary` | `QuizController@summary` | Shows the final score and all 5 questions |
| GET | `/numbers` | `NumberController@index` | Lists 1–100 in Kimeru ("Review the numbers") |

After each POST, the server redirects to a GET page (the Post/Redirect/Get pattern), so refreshing the page never submits an answer twice.

## What is kept in the session

```php
'quiz' => [
    'questions' => [
        ['a' => 22, 'b' => 57, 'operator' => '+', 'answer' => 79,
         'choices' => [78, 79, 69], 'chosen' => null],   // chosen is set after Check
        // … 5 questions in total
    ],
    'current'  => 0,      // which question is on screen (0–4)
    'finished' => false,  // true once the score has been saved
]
```

## Security

- **The answer never reaches the browser.** It stays in the session. The page has no hidden answer field and no answer in JavaScript, and nothing marks which choice is right. Each radio button only sends back its own number.
- **Answers are checked on the server.** The input must be an integer from 1 to 100 and one of that question's three choices.
- **CSRF protection:** every form uses `@csrf`.
- **No re-answering:** once a question is checked, the server refuses any other answer to it, and "Next" can't skip an unanswered question.
- **The score is saved once.** The round is marked finished when it is saved, so pressing "See my score" twice doesn't create a second row.
- **Expired sessions:** if the session times out, Laravel would normally show "419 Page Expired". For quiz pages, `bootstrap/app.php` starts a new round instead and shows "Your quiz session had expired, so we started a new round for you."

## Files

| File | Purpose |
|---|---|
| `app/Support/KimeruNumber.php` | Builds the Kimeru words for 1–100 (used by the seeder) |
| `app/Support/ArithmeticQuiz.php` | Makes the random questions and answer choices (plain PHP, no database) |
| `app/Http/Controllers/QuizController.php` | The quiz flow: show, check, next, summary |
| `app/Models/Number.php`, `app/Models/QuizAttempt.php` | The `numbers` and `quiz_attempts` tables |
| `database/seeders/NumberSeeder.php` | Fills `numbers` with 1–100 |
| `resources/views/quiz/show.blade.php` | The question page, before and after Check |
| `resources/views/quiz/summary.blade.php` | The final score page |
| `bootstrap/app.php` | The friendly restart when the session expires |

## Tests

Run them with `php artisan test`.

- `tests/Unit/ArithmeticQuizTest.php` generates thousands of random questions and checks that:
  - every number is from 1 to 100, and the stored answer is really correct
  - subtraction never goes below 1
  - there are always exactly 3 distinct choices that include the answer, and every wrong choice is a near miss
- `tests/Unit/KimeruNumberTest.php` checks the Kimeru words, e.g. 22, 57, 79 and 100.
- `tests/Feature/QuizTest.php` checks that:
  - a guest can open the quiz, and the question has no digits
  - the answer isn't in the page before checking
  - a right answer shows "Correct!", and a wrong one shows "Incorrect." and the right Kimeru words
  - answers can't be changed or skipped, and invalid input is rejected
  - a full round saves exactly one `quiz_attempts` row
  - an expired session starts a new round
