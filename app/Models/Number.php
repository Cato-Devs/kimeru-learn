<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A number from 1 to 100 and its Kimeru words (table: numbers).
 */
class Number extends Model
{
    protected $fillable = ['value', 'kimeru'];

    protected function casts(): array
    {
        return [
            'value' => 'integer',
        ];
    }

    /**
     * Look up the Kimeru words for several numbers in one query.
     *
     * @param  array<int>  $values  e.g. [22, 57, 79]
     * @return array<int, string> e.g. [22 => 'mĩrongo ĩrĩ na iyĩrĩ', …]
     */
    public static function wordsFor(array $values): array
    {
        $values = array_unique($values);

        $words = static::whereIn('value', $values)->pluck('kimeru', 'value')->all();

        if (count($words) !== count($values)) {
            abort(500, 'Some Kimeru numbers are missing. Run: php artisan migrate:fresh --seed');
        }

        return $words;
    }
}
