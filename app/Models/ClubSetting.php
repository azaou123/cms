<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'club_name',
        'slogan',
        'logo_path',
        'cover_image_path',
        'description',
        'primary_color',
        'secondary_color',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'website_url',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
        'tiktok_url',
        'enable_member_registration',
        'require_approval_for_registration',
        'enable_events_module',
        'enable_payments_module',
        'enable_newsletter',
        'currency',
        'timezone',
        'date_format',
        'time_format',
        'email_notifications',
        'sms_notifications',
        'push_notifications',
    ];
    
    protected $casts = [
        'enable_member_registration' => 'boolean',
        'require_approval_for_registration' => 'boolean',
        'enable_events_module' => 'boolean',
        'enable_payments_module' => 'boolean',
        'enable_newsletter' => 'boolean',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'push_notifications' => 'boolean',
    ];
}