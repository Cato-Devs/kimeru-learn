<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kimeru words and phrases, each in one category.
     */
    public function up(): void
    {
        Schema::create('words', function (Blueprint $table) {
            $table->id();
            // Every word belongs to a category. A category that still has words can't be deleted.
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('kimeru');            // e.g. "Muuga"
            $table->string('english');           // e.g. "How are you?"
            $table->text('notes')->nullable();   // dialect, pronunciation, source…
            $table->timestamps();

            // The same Kimeru word can't be added twice to one category.
            $table->unique(['category_id', 'kimeru']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('words');
    }
};
