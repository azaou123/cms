<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'bio',
        'join_date',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'join_date' => 'date',
    ];

    /**
     * The cells that the user is a member of.
     */
    public function cells()
    {
        return $this->belongsToMany(Cell::class, 'cell_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * The cells that the user has created.
     */
    public function createdCells()
    {
        return $this->hasMany(Cell::class, 'created_by');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project')
                    ->withPivot('is_manager', 'role', 'assigned_at', 'status')
                    ->withTimestamps();
    }

    // Helper method to get projects where user is manager
    public function managedProjects()
    {
        return $this->belongsToMany(Project::class, 'user_project')
                    ->wherePivot('is_manager', true)
                    ->withPivot('assigned_at', 'status')
                    ->withTimestamps();
    }

    // Helper method to get projects where user is a team member (not manager)
    public function teamProjects()
    {
        return $this->belongsToMany(Project::class, 'user_project')
                    ->wherePivot('is_manager', false)
                    ->withPivot('role', 'assigned_at', 'status')
                    ->withTimestamps();
    }


    public function meetingAttendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_attendances')
            ->withPivot('status', 'notes')
            ->withTimestamps();
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withPivot('last_read_at')
            ->withTimestamps();
    }


    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function isOnline()
    {
        return $this->status === 'online'; // Or use a timestamp-based check, depending on how you handle status
    }



    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the events that the user has registered for.
     */
    public function registeredEvents()
    {
        return $this->belongsToMany(Event::class, 'event_registrations')
            ->withPivot('registration_date', 'status')
            ->withTimestamps();
    }
}