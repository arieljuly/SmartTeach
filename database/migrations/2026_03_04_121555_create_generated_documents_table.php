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
        Schema::create('generated_documents', function (Blueprint $table) {
            $table->id('document_id');
            $table->unsignedBigInteger('lesson_id');
            $table->string('document_name');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('lesson_id')->references('lesson_id')->on('lesson_plans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('generated_documents');
    }
};
