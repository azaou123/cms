<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_project', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->boolean('is_manager')->default(false);
            $table->enum('role', ['manager', 'member', 'viewer'])->default('member');
            $table->date('assigned_at')->default(now());
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Ensure a user is only added once to a project
            $table->unique(['user_id', 'project_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_project');
    }
};
