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
        Schema::create('lesson_plans', function (Blueprint $table) {
            $table->id('lesson_id');
            $table->unsignedBigInteger('user_id'); // for the teacher
            $table->string('file_name');
            $table->string('file_path');
            $table->enum('status', ['upload', 'approved', 'rejected','archived','pending'])->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_plans');
    }
};
