<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cell extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
    ];

    /**
     * Get the user who created the cell.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the members of this cell.
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'cell_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Get the projects that belong to this cell.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}