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
        Schema::create('performance_tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->unsignedBigInteger('analysis_id');
            $table->string('task_name');
            $table->string('task_description');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->foreign('analysis_id')->references('analysis_id')->on('analysis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_tasks');
    }
};
