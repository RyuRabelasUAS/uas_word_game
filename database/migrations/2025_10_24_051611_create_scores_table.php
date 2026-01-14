<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->integer('score'); // Final score
            $table->integer('time_seconds'); // Time taken in seconds
            $table->string('game_type'); // wordsearch, crossword, wordle
            $table->json('details')->nullable(); // Extra details (words found, attempts, etc.)
            $table->timestamps();

            // Add indexes for faster queries
            $table->index(['game_type', 'score']);
            $table->index(['level_id', 'score']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
