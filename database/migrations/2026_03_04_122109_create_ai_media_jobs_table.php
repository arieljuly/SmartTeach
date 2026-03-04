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
        Schema::create('ai_media_jobs', function (Blueprint $table) {
            $table->id('media_id');
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('prompt_id');
            $table->unsignedBigInteger('user_id');
            $table->string('media_type');
            $table->string('media_url');
            $table->string('media_name');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->decimal('file_size', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_media_jobs');
    }
};
