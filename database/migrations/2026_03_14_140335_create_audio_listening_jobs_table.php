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
        Schema::create('audio_listening_jobs', function (Blueprint $table) {
            $table->id('audio_id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('user_id');
            $table->string('audio_type'); // 'script', 'dialogue', 'vocabulary', 'story'
            $table->text('original_text');
            $table->string('audio_url')->nullable();
            $table->string('audio_filename')->nullable();
            $table->string('voice_type')->default('nova'); // OpenAI voices: alloy, echo, fable, onyx, nova, shimmer
            $table->float('speed')->default(1.0); // For children, might want slower speed
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('duration_seconds')->nullable();
            $table->integer('file_size')->nullable();
            $table->json('metadata')->nullable(); // Store additional info like section references
            $table->timestamps();
            
            $table->foreign('lesson_id')->references('lesson_id')->on('lesson_plans')->onDelete('cascade');
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_listening_jobs');
    }
};
