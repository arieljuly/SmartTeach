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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('action'); // CREATE, UPDATE, DELETE, LOGIN, LOGOUT, ARCHIVE, RESTORE
            $table->string('module'); // User Management, Lesson Plans, Authentication, etc.
            $table->text('description');
            $table->string('ip_address', 45)->nullable(); // Supports IPv4 and IPv6
            $table->text('user_agent')->nullable();
            $table->json('old_data')->nullable(); // Store old data for updates
            $table->json('new_data')->nullable(); // Store new data for creates/updates
            $table->timestamps();
            
            // Foreign key constraint
            $table->foreign('user_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('set null');
                  
            // Indexes for faster queries
            $table->index('action');
            $table->index('module');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
