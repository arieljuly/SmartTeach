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
        Schema::create('rubrics_criterias', function (Blueprint $table) {
            $table->id('criteria_id');
            $table->unsignedBigInteger('rubric_id');
            $table->string('criteria_name');
            $table->string('criteria_description');
            $table->integer('score');
            $table->timestamps();

            $table->foreign('rubric_id')->references('rubric_id')->on('rubrics')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrics_criterias');
    }
};
