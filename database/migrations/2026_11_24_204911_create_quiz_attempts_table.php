<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per finished quiz round.
     */
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            // Nullable because everyone plays as a guest for now.
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('quiz_type');           // e.g. "arithmetic"
            $table->unsignedSmallInteger('score'); // questions answered correctly
            $table->unsignedSmallInteger('total'); // questions in the round
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
