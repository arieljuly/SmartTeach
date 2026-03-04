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
        Schema::create('rubrics', function (Blueprint $table) {
            $table->id('rubric_id');
            $table->unsignedBigInteger('task_id');
            $table->string('rubric_title');
            $table->integer('total_score');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('task_id')->references('task_id')->on('performance_tasks')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrics');
    }
};
