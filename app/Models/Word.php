<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A Kimeru word or phrase with its English meaning (table: words).
 */
class Word extends Model
{
    // category_id is not fillable: the controller sets it from the URL,
    // so a form can't move a word into a different category.
    protected $fillable = ['kimeru', 'english', 'notes'];

    /**
     * Each word belongs to one category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
