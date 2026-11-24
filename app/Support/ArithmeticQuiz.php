<?php

namespace App\Support;

/**
 * Generates arithmetic questions for the quiz.
 *
 * Plain PHP with no database, so it is easy to unit test. Questions only
 * contain numbers; the controller looks up the Kimeru words for them.
 */
class ArithmeticQuiz
{
    public const QUESTIONS_PER_ROUND = 5;

    public const ADD = '+';

    public const SUBTRACT = '−'; // the minus sign U+2212, not the hyphen "-"

    /** How far a wrong choice may be from the answer. */
    public const NEAR_MISS_OFFSETS = [-10, -2, -1, 1, 2, 10];

    /**
     * A full round of questions.
     *
     * @return array<int, array{a: int, b: int, operator: string, answer: int, choices: array<int>}>
     */
    public static function makeRound(int $count = self::QUESTIONS_PER_ROUND): array
    {
        $questions = [];

        for ($i = 0; $i < $count; $i++) {
            $questions[] = self::makeQuestion();
        }

        return $questions;
    }

    /**
     * One question, randomly addition or subtraction.
     *
     * Every number involved (a, b and the answer) is between 1 and 100,
     * because Kimeru Learn has words for 1–100 only (there is no zero).
     *
     * @return array{a: int, b: int, operator: string, answer: int, choices: array<int>}
     */
    public static function makeQuestion(): array
    {
        if (random_int(0, 1) === 0) {
            // Addition: pick the answer first (2–100), then split it into a + b.
            // Both parts are at least 1, so the smallest possible answer is 1 + 1 = 2.
            $answer = random_int(2, 100);
            $a = random_int(1, $answer - 1);
            $b = $answer - $a;
            $operator = self::ADD;
        } else {
            // Subtraction: a is bigger than b, so the answer is at least 1.
            $a = random_int(2, 100);
            $b = random_int(1, $a - 1);
            $answer = $a - $b; // 1–99
            $operator = self::SUBTRACT;
        }

        return [
            'a' => $a,
            'b' => $b,
            'operator' => $operator,
            'answer' => $answer,
            'choices' => self::makeChoices($answer),
        ];
    }

    /**
     * The correct answer plus two "near misses", in random order.
     *
     * The near misses are answer ±1, ±2 or ±10, stay between 1 and 100,
     * and are never equal to each other or to the answer.
     *
     * @return array<int> three distinct numbers
     */
    public static function makeChoices(int $answer): array
    {
        // Every near miss that stays inside 1–100.
        // Even for answer = 1 or 100 at least three are left (e.g. 2, 3, 11).
        $nearMisses = [];
        foreach (self::NEAR_MISS_OFFSETS as $offset) {
            $candidate = $answer + $offset;
            if ($candidate >= 1 && $candidate <= 100) {
                $nearMisses[] = $candidate;
            }
        }

        shuffle($nearMisses);

        $choices = [$answer, $nearMisses[0], $nearMisses[1]];
        shuffle($choices); // so the right answer isn't always first

        return $choices;
    }

    /**
     * Work out a + b or a − b.
     */
    public static function solve(int $a, string $operator, int $b): int
    {
        return $operator === self::ADD ? $a + $b : $a - $b;
    }
}
