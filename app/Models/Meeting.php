<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'location',
        'type',
        'cell_id',
        'project_id',
        'board_id',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function cell()
    {
        return $this->belongsTo(Cell::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function attendances()
    {
        return $this->hasMany(MeetingAttendance::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'meeting_attendances')
            ->withPivot('status', 'notes')
            ->withTimestamps();
    }

    // Helper methods
    public function scheduleMeeting()
    {
        // Logic to schedule a meeting
        $this->save();
        
        return $this;
    }

    public function cancelMeeting()
    {
        // Logic to cancel a meeting
        $this->delete();
        
        return true;
    }

    public function reschedule($date, $startTime, $endTime)
    {
        $this->date = $date;
        $this->start_time = $startTime;
        $this->end_time = $endTime;
        $this->save();
        
        return $this;
    }
}