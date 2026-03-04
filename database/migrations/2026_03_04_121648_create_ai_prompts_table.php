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
        Schema::create('ai_prompts', function (Blueprint $table) {
            $table->id('prompt_id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('analysis_id');
            $table->unsignedBigInteger('user_id');
            $table->string('prompt_type');
            $table->text('prompt_version');
            $table->string('prompt_content');
            $table->integer('token_estimate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_prompts');
    }
};
