<?php

namespace Tests\Feature;

use App\Models\QuizAttempt;
use App\Models\User;
use Database\Seeders\NumberSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyScoresTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Runs before every test. The quiz needs the numbers table (1–100).
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(NumberSeeder::class);
    }

    private function attempt(?User $user, int $score): QuizAttempt
    {
        return QuizAttempt::create([
            'user_id' => $user?->id,
            'quiz_type' => 'arithmetic',
            'score' => $score,
            'total' => 5,
        ]);
    }

    /**
     * Play a whole round, answering every question correctly.
     */
    private function playARound(): void
    {
        $this->get('/quiz');

        foreach (range(0, 4) as $i) {
            $this->post('/quiz', ['choice' => session('quiz.questions')[$i]['answer']]);
            $this->post('/quiz/next');
        }
    }

    public function test_guests_are_sent_to_login(): void
    {
        $this->get('/my-scores')->assertRedirect('/login');
    }

    public function test_a_learner_with_no_rounds_is_invited_to_play(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/my-scores')
            ->assertOk()
            ->assertSee("You haven't finished a quiz yet.", false)
            ->assertSee('Take the quiz');
    }

    public function test_a_finished_round_is_saved_to_the_logged_in_learner(): void
    {
        $learner = User::factory()->create();

        $this->actingAs($learner);
        $this->playARound();

        $this->assertDatabaseHas('quiz_attempts', ['user_id' => $learner->id, 'score' => 5, 'total' => 5]);

        $this->get('/quiz/summary')->assertSee('Saved to');
        $this->get('/my-scores')->assertSee('5 / 5')->assertSee('Arithmetic');
    }

    public function test_a_guest_round_is_saved_without_a_user(): void
    {
        $this->playARound();

        $this->assertDatabaseHas('quiz_attempts', ['user_id' => null, 'score' => 5]);
        $this->get('/quiz/summary')->assertSee('to keep your scores next time');
    }

    public function test_the_page_shows_rounds_best_and_average(): void
    {
        $learner = User::factory()->create();
        foreach ([2, 5, 3] as $score) {
            $this->attempt($learner, $score);
        }

        $this->actingAs($learner)
            ->get('/my-scores')
            ->assertSeeInOrder(['Rounds played', '3', 'Best score', '5 / 5', 'Average', '3.3 / 5'])
            // newest first: 3, then 5, then 2
            ->assertSeeInOrder(['3 / 5', '5 / 5', '2 / 5']);
    }

    public function test_learners_only_see_their_own_scores(): void
    {
        $me = User::factory()->create();
        $someoneElse = User::factory()->create();

        $this->attempt($me, 1);
        $this->attempt($someoneElse, 4);
        $this->attempt(null, 4); // a guest

        $this->actingAs($me)
            ->get('/my-scores')
            ->assertSeeInOrder(['Rounds played', '1'])
            ->assertSee('1 / 5')
            ->assertDontSee('4 / 5');
    }

    public function test_deleting_a_user_keeps_their_scores_as_anonymous(): void
    {
        $learner = User::factory()->create();
        $attempt = $this->attempt($learner, 4);

        $learner->delete();

        // nullOnDelete() in the quiz_attempts migration: the row stays, user_id becomes null.
        $this->assertNull($attempt->fresh()->user_id);
    }
}
