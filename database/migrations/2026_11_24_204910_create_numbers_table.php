<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One row per number, 1 to 100, with its Kimeru words.
     */
    public function up(): void
    {
        Schema::create('numbers', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('value')->unique(); // 1–100
            $table->string('kimeru');                       // e.g. "mĩrongo ĩrĩ na iyĩrĩ"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('numbers');
    }
};
