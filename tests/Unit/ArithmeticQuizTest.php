<?php

namespace Tests\Unit;

use App\Support\ArithmeticQuiz;
use PHPUnit\Framework\TestCase;

/**
 * The questions are random, so each test generates many of them
 * to make sure the rules hold every time, not just once.
 */
class ArithmeticQuizTest extends TestCase
{
    private const RUNS = 2000;

    public function test_every_number_in_a_question_is_between_1_and_100(): void
    {
        for ($i = 0; $i < self::RUNS; $i++) {
            $q = ArithmeticQuiz::makeQuestion();

            foreach (['a', 'b', 'answer'] as $key) {
                $this->assertGreaterThanOrEqual(1, $q[$key], "$key too small");
                $this->assertLessThanOrEqual(100, $q[$key], "$key too big");
            }
        }
    }

    public function test_the_stored_answer_is_actually_the_right_answer(): void
    {
        for ($i = 0; $i < self::RUNS; $i++) {
            $q = ArithmeticQuiz::makeQuestion();

            $this->assertSame(ArithmeticQuiz::solve($q['a'], $q['operator'], $q['b']), $q['answer']);
        }
    }

    public function test_subtraction_never_goes_below_1(): void
    {
        $subtractions = 0;

        for ($i = 0; $i < self::RUNS; $i++) {
            $q = ArithmeticQuiz::makeQuestion();

            if ($q['operator'] === ArithmeticQuiz::SUBTRACT) {
                $subtractions++;
                $this->assertGreaterThan($q['b'], $q['a'], 'a must be bigger than b');
                $this->assertGreaterThanOrEqual(1, $q['answer']);
                $this->assertLessThanOrEqual(99, $q['answer']);
            }
        }

        $this->assertGreaterThan(0, $subtractions, 'No subtraction questions were generated');
    }

    public function test_both_addition_and_subtraction_appear(): void
    {
        $operators = [];

        for ($i = 0; $i < 200; $i++) {
            $operators[ArithmeticQuiz::makeQuestion()['operator']] = true;
        }

        $this->assertEqualsCanonicalizing([ArithmeticQuiz::ADD, ArithmeticQuiz::SUBTRACT], array_keys($operators));
    }

    public function test_every_question_has_exactly_3_distinct_choices_including_the_answer(): void
    {
        for ($i = 0; $i < self::RUNS; $i++) {
            $q = ArithmeticQuiz::makeQuestion();

            $this->assertCount(3, $q['choices']);
            $this->assertCount(3, array_unique($q['choices']), 'Choices must be different');
            $this->assertContains($q['answer'], $q['choices']);
        }
    }

    public function test_choices_are_near_misses_within_1_to_100_for_every_possible_answer(): void
    {
        // Check every answer 1–100 (including the edges 1 and 100) many times.
        foreach (range(1, 100) as $answer) {
            for ($i = 0; $i < 50; $i++) {
                $choices = ArithmeticQuiz::makeChoices($answer);

                $this->assertCount(3, array_unique($choices));
                $this->assertContains($answer, $choices);

                foreach ($choices as $choice) {
                    $this->assertGreaterThanOrEqual(1, $choice);
                    $this->assertLessThanOrEqual(100, $choice);

                    if ($choice !== $answer) {
                        $this->assertContains($choice - $answer, ArithmeticQuiz::NEAR_MISS_OFFSETS);
                    }
                }
            }
        }
    }

    public function test_the_right_answer_is_not_always_in_the_same_position(): void
    {
        $positions = [];

        for ($i = 0; $i < 300; $i++) {
            $positions[array_search(50, ArithmeticQuiz::makeChoices(50), true)] = true;
        }

        $this->assertCount(3, $positions, 'The answer should appear first, second and third');
    }

    public function test_a_round_has_5_questions(): void
    {
        $this->assertCount(5, ArithmeticQuiz::makeRound());
    }
}
