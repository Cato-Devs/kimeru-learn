<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class WordSeeder extends Seeder
{
    /**
     * Create the categories and the few words we have so far.
     * Safe to run more than once: nothing is duplicated, and words
     * that editors added through the site are left alone.
     */
    public function run(): void
    {
        $greetings = Category::firstOrCreate(
            ['slug' => 'greetings'],
            ['name' => 'Greetings', 'description' => 'Greet family and elders politely.'],
        );

        Category::firstOrCreate(
            ['slug' => 'foods'],
            ['name' => 'Foods', 'description' => 'Name the foods you eat every day.'],
        );

        // From the Wikipedia "Meru language" sample phrases. Still to be checked
        // with a fluent speaker. More words are added by editors through the site.
        $greetings->words()->firstOrCreate(
            ['kimeru' => 'Muuga'],
            ['english' => 'How are you?', 'notes' => 'Source: Wikipedia sample phrases. To be checked with a fluent speaker.'],
        );
    }
}
