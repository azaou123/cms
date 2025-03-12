<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add phone field after email
            $table->string('phone')->nullable()->after('email');
            
            // Add profile picture
            $table->string('profile_picture')->nullable()->after('phone');
            
            // Add bio
            $table->text('bio')->nullable()->after('profile_picture');
            
            // Add join date
            $table->date('join_date')->default(DB::raw('CURRENT_DATE'))->after('bio');
            
            // Add status (active, inactive, pending)
            $table->enum('status', ['active', 'inactive', 'pending'])->default('pending')->after('join_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'profile_picture', 'bio', 'join_date', 'status']);
        });
    }
};