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
        Schema::create('questions', function (Blueprint $table) {
            $table->id('question_id');
            $table->unsignedBigInteger('question_type_id');
            $table->unsignedBigInteger('analysis_id');
            $table->string('question_text');
            $table->integer('points')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('question_type_id')->references('question_type_id')->on('question_types')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('analysis_id')->references('analysis_id')->on('analysis')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
