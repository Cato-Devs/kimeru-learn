<?php

namespace Database\Seeders;

use App\Models\Number;
use App\Support\KimeruNumber;
use Illuminate\Database\Seeder;

class NumberSeeder extends Seeder
{
    /**
     * Fill the numbers table with 1–100 and their Kimeru words.
     * Safe to run more than once: existing rows are updated, not duplicated.
     */
    public function run(): void
    {
        foreach (range(1, 100) as $n) {
            Number::updateOrCreate(
                ['value' => $n],
                ['kimeru' => KimeruNumber::toWords($n)],
            );
        }
    }
}
