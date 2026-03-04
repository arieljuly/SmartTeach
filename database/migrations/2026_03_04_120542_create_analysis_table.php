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
        Schema::create('analysis', function (Blueprint $table) {
            $table->id('analysis_id');
            $table->unsignedBigInteger('lesson_id');
            $table->string('topic_summary');
            $table->string('key_concepts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analysis');
    }
};
