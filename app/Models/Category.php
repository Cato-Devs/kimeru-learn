<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A group of words, e.g. Greetings (table: categories).
 */
class Category extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * A category has many words.
     */
    public function words(): HasMany
    {
        return $this->hasMany(Word::class);
    }
}
