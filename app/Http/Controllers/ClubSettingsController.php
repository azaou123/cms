<?php

namespace App\Http\Controllers;

use App\Models\ClubSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClubSettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        return view('settings.index', compact('settings'));
    }
    
    /**
     * Show the general settings form.
     */
    public function showGeneralSettings()
    {
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        return view('settings.general', compact('settings'));
    }
    
    /**
     * Show the social media settings form.
     */
    public function showSocialMediaSettings()
    {
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        return view('settings.social-media', compact('settings'));
    }
    
    /**
     * Show the system settings form.
     */
    public function showSystemSettings()
    {
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        return view('settings.system', compact('settings'));
    }
    
    /**
     * Show the appearance settings form.
     */
    public function showAppearanceSettings()
    {
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        return view('settings.appearance', compact('settings'));
    }
    
    /**
     * Show the notification settings form.
     */
    public function showNotificationSettings()
    {
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        return view('settings.notifications', compact('settings'));
    }
    
    /**
     * Update the general settings.
     */
    public function updateGeneralSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_name' => 'required|string|max:255',
            'slogan' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $settings = ClubSetting::first() ?? new ClubSetting();
        $settings->fill($request->only([
            'club_name', 'slogan', 'description',
            'email', 'phone', 'address', 'city', 'state', 'postal_code', 'country'
        ]));
        
        $settings->save();
        
        return redirect()->back()->with('success', 'General settings updated successfully!');
    }
    
    /**
     * Update the social media settings.
     */
    public function updateSocialMediaSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'website_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'tiktok_url' => 'nullable|url',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $settings = ClubSetting::first() ?? new ClubSetting();
        $settings->fill($request->only([
            'website_url', 'facebook_url', 'twitter_url', 'instagram_url',
            'linkedin_url', 'youtube_url', 'tiktok_url'
        ]));
        
        $settings->save();
        
        return redirect()->back()->with('success', 'Social media settings updated successfully!');
    }
    
    /**
     * Update the system settings.
     */
    public function updateSystemSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'enable_member_registration' => 'boolean',
            'require_approval_for_registration' => 'boolean',
            'enable_events_module' => 'boolean',
            'enable_payments_module' => 'boolean',
            'enable_newsletter' => 'boolean',
            'currency' => 'required|string|size:3',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            'time_format' => 'required|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        // Handle boolean fields
        $settings->enable_member_registration = $request->has('enable_member_registration');
        $settings->require_approval_for_registration = $request->has('require_approval_for_registration');
        $settings->enable_events_module = $request->has('enable_events_module');
        $settings->enable_payments_module = $request->has('enable_payments_module');
        $settings->enable_newsletter = $request->has('enable_newsletter');
        
        $settings->fill($request->only([
            'currency', 'timezone', 'date_format', 'time_format'
        ]));
        
        $settings->save();
        
        return redirect()->back()->with('success', 'System settings updated successfully!');
    }
    
    /**
     * Update the appearance settings.
     */
    public function updateAppearanceSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo_path) {
                Storage::delete('public/' . $settings->logo_path);
            }
            
            $path = $request->file('logo')->store('club/logos', 'public');
            $settings->logo_path = $path;
        }
        
        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($settings->cover_image_path) {
                Storage::delete('public/' . $settings->cover_image_path);
            }
            
            $path = $request->file('cover_image')->store('club/covers', 'public');
            $settings->cover_image_path = $path;
        }
        
        $settings->fill($request->only([
            'primary_color', 'secondary_color'
        ]));
        
        $settings->save();
        
        return redirect()->back()->with('success', 'Appearance settings updated successfully!');
    }
    
    /**
     * Update the notification settings.
     */
    public function updateNotificationSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $settings = ClubSetting::first() ?? new ClubSetting();
        
        // Handle boolean fields
        $settings->email_notifications = $request->has('email_notifications');
        $settings->sms_notifications = $request->has('sms_notifications');
        $settings->push_notifications = $request->has('push_notifications');
        
        $settings->save();
        
        return redirect()->back()->with('success', 'Notification settings updated successfully!');
    }
}