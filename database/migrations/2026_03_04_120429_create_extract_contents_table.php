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
        Schema::create('extract_contents', function (Blueprint $table) {
            $table->id('content_id');
            $table->unsignedBigInteger('lesson_id');
            $table->string('extracted_text');
            $table->timestamps();

            $table->foreign('lesson_id')->references('lesson_id')->on('lesson_plans')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extract_contents');
    }
};
