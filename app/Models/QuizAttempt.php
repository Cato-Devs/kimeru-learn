<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * One finished quiz round (table: quiz_attempts).
 */
class QuizAttempt extends Model
{
    protected $fillable = ['user_id', 'quiz_type', 'score', 'total'];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'total' => 'integer',
        ];
    }
}
