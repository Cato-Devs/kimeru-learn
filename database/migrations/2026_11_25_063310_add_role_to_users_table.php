<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Learners can now sign up, so users need a role:
 *   "editor"  can add, edit and delete words (created with php artisan editor:create)
 *   "learner" signs up at /register and sees their own quiz scores
 *
 * This is a NEW migration rather than an edit to the users migration, because
 * that one has already run on every copy of the database.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // New sign-ups are learners unless told otherwise.
            $table->string('role')->default('learner')->after('email');
        });

        // Before this migration there was no sign-up page, so every existing
        // account was made with editor:create. Keep them as editors.
        DB::table('users')->update(['role' => 'editor']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
