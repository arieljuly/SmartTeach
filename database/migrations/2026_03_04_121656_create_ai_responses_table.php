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
        Schema::create('ai_responses', function (Blueprint $table) {
            $table->id('response_id');
            $table->unsignedBigInteger('prompt_id');
            $table->unsignedBigInteger('lesson_id');
            $table->string('raw_response_json');
            $table->boolean('parsed_success')->default(false);
            $table->integer('output_token_count');
            $table->integer('processing_time_ms');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_responses');
    }
};
