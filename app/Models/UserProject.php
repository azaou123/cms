<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserProject extends Pivot
{
    protected $table = 'user_project';

    protected $fillable = [
        'user_id',
        'project_id',
        'is_manager',
        'role',
        'assigned_at',
        'status',
    ];

    protected $casts = [
        'is_manager' => 'boolean',
        'assigned_at' => 'datetime',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
