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
        Schema::create('club_settings', function (Blueprint $table) {
            $table->id();
            
            // Basic information
            $table->string('club_name')->nullable();
            $table->string('slogan')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->text('description')->nullable();
            $table->string('primary_color')->nullable()->default('#3490dc');
            $table->string('secondary_color')->nullable()->default('#38c172');
            
            // Contact information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            
            // Social media
            $table->string('website_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('tiktok_url')->nullable();
            
            // System settings
            $table->boolean('enable_member_registration')->default(true);
            $table->boolean('require_approval_for_registration')->default(true);
            $table->boolean('enable_events_module')->default(true);
            $table->boolean('enable_payments_module')->default(true);
            $table->boolean('enable_newsletter')->default(true);
            $table->string('currency')->default('USD');
            $table->string('timezone')->default('UTC');
            $table->string('date_format')->default('Y-m-d');
            $table->string('time_format')->default('H:i');
            
            // Notification settings
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('push_notifications')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_settings');
    }
};