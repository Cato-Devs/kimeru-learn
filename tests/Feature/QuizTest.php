<?php

namespace Tests\Feature;

use App\Models\QuizAttempt;
use Database\Seeders\NumberSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    // Fill the numbers table before each test.
    protected bool $seed = true;

    protected string $seeder = NumberSeeder::class;

    /**
     * A round whose first question we know: 22 + 57 = 79, choices 78, 79, 69.
     */
    private function knownRound(): array
    {
        $question = ['a' => 22, 'b' => 57, 'operator' => '+', 'answer' => 79, 'choices' => [78, 79, 69], 'chosen' => null];

        return [
            'questions' => array_fill(0, 5, $question),
            'current' => 0,
            'finished' => false,
        ];
    }

    public function test_a_guest_can_open_the_quiz(): void
    {
        $this->get('/quiz')
            ->assertOk()
            ->assertSee('Question 1 of 5')
            ->assertSee('Score: 0')
            ->assertSee('Check');

        $this->assertCount(5, session('quiz.questions'));
    }

    public function test_the_question_is_shown_in_kimeru_words_only(): void
    {
        $html = $this->withSession(['quiz' => $this->knownRound()])->get('/quiz')->getContent();

        preg_match('/<p id="question"[^>]*>(.*?)<\/p>/s', $html, $match);
        $question = preg_replace('/\s+/', ' ', strip_tags($match[1]));

        $this->assertSame(' mĩrongo ĩrĩ na iyĩrĩ + mĩrongo ĩthano na mũgwanja = ? ', $question);
        $this->assertDoesNotMatchRegularExpression('/\d/', $question, 'No digits in the question');
    }

    public function test_the_three_choices_are_shown_in_kimeru(): void
    {
        $this->withSession(['quiz' => $this->knownRound()])->get('/quiz')
            ->assertSee('mĩrongo mũgwanja na inyanya')  // 78
            ->assertSee('mĩrongo mũgwanja na kenda')    // 79
            ->assertSee('mĩrongo ĩthatathũ na kenda');  // 69
    }

    public function test_the_answer_does_not_appear_in_the_page_before_checking(): void
    {
        $html = $this->withSession(['quiz' => $this->knownRound()])->get('/quiz')->getContent();

        // The only hidden field is Laravel's CSRF token: no hidden answer.
        preg_match_all('/<input[^>]*type="hidden"[^>]*name="([^"]+)"/', $html, $hidden);
        $this->assertSame(['_token'], $hidden[1]);

        // Nothing marks a choice as the correct one.
        $this->assertStringNotContainsString('✓', $html);
        $this->assertStringNotContainsString('is-right', $html);
        $this->assertStringNotContainsString('Correct', $html);

        // Each radio button posts back its own number. Apart from those three
        // equal-looking values, the answer (79) and the sum (22, 57) are nowhere.
        $withoutRadioValues = preg_replace('/value="\d+"/', '', $html);
        foreach (['79', '22', '57'] as $digits) {
            $this->assertDoesNotMatchRegularExpression("/(?<![\\w.-]){$digits}(?![\\w.-])/", $withoutRadioValues);
        }
    }

    public function test_a_right_answer_shows_correct(): void
    {
        $this->withSession(['quiz' => $this->knownRound()])
            ->post('/quiz', ['choice' => 79])
            ->assertRedirect('/quiz');

        $this->get('/quiz')
            ->assertSee('✓ Correct!')
            ->assertSee('22 + 57 = 79:')
            ->assertSee('mĩrongo mũgwanja na kenda')
            ->assertSee('Score: 1')
            ->assertSee('Next question')
            ->assertDontSee('You chose');
    }

    public function test_a_wrong_answer_shows_incorrect_and_the_right_kimeru_word(): void
    {
        $this->withSession(['quiz' => $this->knownRound()])
            ->post('/quiz', ['choice' => 78]);

        $this->get('/quiz')
            ->assertSee('✗ Incorrect.')
            ->assertDontSee('Correct!')
            ->assertSee('22 + 57 = 79:')
            ->assertSee('mĩrongo mũgwanja na kenda')            // the right answer
            ->assertSee('You chose 78:')
            ->assertSee('mĩrongo mũgwanja na inyanya')          // what they picked
            ->assertSee('Score: 0');
    }

    public function test_the_choices_are_locked_after_checking(): void
    {
        $this->withSession(['quiz' => $this->knownRound()])->post('/quiz', ['choice' => 78]);

        $this->get('/quiz')
            ->assertDontSee('name="choice"', false)  // no radio buttons any more
            ->assertSee('✗ Your choice')
            ->assertSee('✓ Correct answer');
    }

    public function test_a_question_cannot_be_answered_twice(): void
    {
        $this->withSession(['quiz' => $this->knownRound()])->post('/quiz', ['choice' => 78]);

        $this->post('/quiz', ['choice' => 79])
            ->assertRedirect('/quiz')
            ->assertSessionHas('status', 'You have already answered this question.');

        $this->assertSame(78, session('quiz.questions.0.chosen'));
    }

    public function test_only_one_of_the_three_choices_is_accepted(): void
    {
        $this->withSession(['quiz' => $this->knownRound()]);

        foreach ([['choice' => 50], ['choice' => 'abc'], ['choice' => 101], []] as $input) {
            $this->post('/quiz', $input)->assertSessionHasErrors('choice');
        }

        $this->assertNull(session('quiz.questions.0.chosen'));
    }

    public function test_next_does_not_skip_an_unanswered_question(): void
    {
        $this->withSession(['quiz' => $this->knownRound()])
            ->post('/quiz/next')
            ->assertRedirect('/quiz');

        $this->assertSame(0, session('quiz.current'));
    }

    public function test_subtraction_uses_the_minus_sign(): void
    {
        $round = $this->knownRound();
        $round['questions'][0] = ['a' => 57, 'b' => 22, 'operator' => '−', 'answer' => 35, 'choices' => [35, 36, 25], 'chosen' => null];

        $this->withSession(['quiz' => $round])->post('/quiz', ['choice' => 35]);

        $this->get('/quiz')->assertSee('57 − 22 = 35:')->assertSee('mĩrongo ĩthatũ na ithano');
    }

    public function test_a_full_round_saves_one_quiz_attempt_and_shows_the_summary(): void
    {
        $this->get('/quiz');

        // Answer 3 right and 2 wrong.
        foreach ([true, true, false, true, false] as $i => $answerRight) {
            $question = session('quiz.questions')[$i];
            $wrongChoice = array_values(array_diff($question['choices'], [$question['answer']]))[0];

            $this->post('/quiz', ['choice' => $answerRight ? $question['answer'] : $wrongChoice]);

            if ($i < 4) {
                $this->get('/quiz')->assertSee('Next question');
                $this->post('/quiz/next')->assertRedirect('/quiz');
            } else {
                $this->get('/quiz')->assertSee('See my score');
                $this->post('/quiz/next')->assertRedirect('/quiz/summary');
            }
        }

        $this->assertDatabaseCount('quiz_attempts', 1);
        $this->assertDatabaseHas('quiz_attempts', [
            'user_id' => null,
            'quiz_type' => 'arithmetic',
            'score' => 3,
            'total' => 5,
        ]);

        $this->get('/quiz/summary')
            ->assertOk()
            ->assertSee('You scored 3 / 5')
            ->assertSee('Try another quiz')
            ->assertSee('Review the numbers');

        // Pressing "See my score" again must not save a second row.
        $this->post('/quiz/next')->assertRedirect('/quiz/summary');
        $this->assertSame(1, QuizAttempt::count());

        // "Try another quiz" starts a fresh round.
        $this->get('/quiz')->assertSee('Question 1 of 5')->assertSee('Score: 0');
    }

    public function test_the_summary_is_not_available_before_the_round_is_finished(): void
    {
        $this->withSession(['quiz' => $this->knownRound()])
            ->get('/quiz/summary')
            ->assertRedirect('/quiz');
    }

    public function test_an_expired_session_starts_a_new_round_with_a_friendly_message(): void
    {
        // No quiz in the session, as if it had timed out.
        $this->post('/quiz', ['choice' => 79])
            ->assertRedirect('/quiz')
            ->assertSessionHas('status');

        $this->get('/quiz')
            ->assertSee('Your quiz session had expired, so we started a new round for you.')
            ->assertSee('Question 1 of 5');
    }
}
