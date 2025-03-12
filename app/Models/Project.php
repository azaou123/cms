<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'budget',
        'cell_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    // Relationship with Cell
    public function cell()
    {
        return $this->belongsTo(Cell::class);
    }

    // Relationship with team members (all users in the project)
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_project')
                    ->withPivot('is_manager', 'role', 'assigned_at', 'status')
                    ->withTimestamps();
    }

    // Helper method to get only the manager
    public function manager()
    {
        return $this->belongsToMany(User::class, 'user_project')
                    ->wherePivot('is_manager', true)
                    ->withPivot('role', 'assigned_at', 'status')
                    ->withTimestamps();
    }

    // Helper method to get only team members (non-managers)
    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'user_project')
                    ->wherePivot('is_manager', false)
                    ->withPivot('role', 'assigned_at', 'status')
                    ->withTimestamps()
                    ->select('users.id'); // Explicitly select the user ID
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}