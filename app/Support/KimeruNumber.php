<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Builds the Kimeru words for a number from 1 to 100.
 *
 * Used by the NumberSeeder to fill the `numbers` table. The rest of the app
 * reads the words from that table, not from here.
 */
class KimeruNumber
{
    /** 1–9 */
    public const UNITS = [
        1 => 'ĩmwe',
        2 => 'iyĩrĩ',
        3 => 'ithatũ',
        4 => 'inya',
        5 => 'ithano',
        6 => 'ithanthatũ',
        7 => 'mũgwanja',
        8 => 'inyanya',
        9 => 'kenda',
    ];

    /** 10, 20, … 90 */
    public const TENS = [
        10 => 'ĩkũmi',
        20 => 'mĩrongo ĩrĩ',
        30 => 'mĩrongo ĩthatũ',
        40 => 'mĩrongo ĩna',
        50 => 'mĩrongo ĩthano',
        60 => 'mĩrongo ĩthatathũ',
        70 => 'mĩrongo mũgwanja',
        80 => 'mĩrongo ĩnaana',
        90 => 'mĩrongo kenda',
    ];

    public const HUNDRED = 'ĩgana';

    public static function toWords(int $n): string
    {
        if ($n < 1 || $n > 100) {
            throw new InvalidArgumentException("Only numbers from 1 to 100 are supported, got {$n}.");
        }

        if ($n === 100) {
            return self::HUNDRED;
        }

        $tens = intdiv($n, 10) * 10; // 57 → 50
        $unit = $n % 10;             // 57 → 7

        if ($tens === 0) {
            return self::UNITS[$unit];          // 1–9
        }

        if ($unit === 0) {
            return self::TENS[$tens];           // 10, 20, … 90
        }

        return self::TENS[$tens].' na '.self::UNITS[$unit]; // 57 → "mĩrongo ĩthano na mũgwanja"
    }
}
