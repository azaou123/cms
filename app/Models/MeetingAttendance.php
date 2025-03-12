<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'meeting_id',
        'user_id',
        'status',
        'notes',
    ];

    // Relationships
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function recordAttendance($status, $notes = null)
    {
        $this->status = $status;
        $this->notes = $notes;
        $this->save();
        
        return $this;
    }

    public function excuseAbsence($notes)
    {
        $this->status = 'not_attending';
        $this->notes = $notes;
        $this->save();
        
        return $this;
    }
}