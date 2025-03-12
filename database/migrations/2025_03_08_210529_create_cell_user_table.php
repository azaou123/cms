<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cell_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cell_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('member'); // e.g., 'leader', 'member', 'secretary'
            $table->timestamps();

            $table->unique(['cell_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cell_user');
    }
};