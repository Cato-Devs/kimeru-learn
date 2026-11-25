<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * The learner who played this round (null for guests).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
