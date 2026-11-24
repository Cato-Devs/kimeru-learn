<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * No users are seeded: a seeded account with a known password would let
     * anyone log in as an editor. Create editors with: php artisan editor:create
     */
    public function run(): void
    {
        $this->call([
            NumberSeeder::class,
            WordSeeder::class,
        ]);
    }
}
