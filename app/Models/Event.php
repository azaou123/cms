<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'type',
        'cell_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the cell that owns the event.
     */
    public function cell(): BelongsTo
    {
        return $this->belongsTo(Cell::class);
    }

    /**
     * Get the registrations for the event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the users registered for this event.
     */
    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
            ->withPivot('registration_date', 'status')
            ->withTimestamps();
    }

    /**
     * Create a new event.
     */
    public static function createEvent(array $data)
    {
        return self::create($data);
    }

    /**
     * Cancel an event.
     */
    public function cancelEvent()
    {
        // Logic to cancel event - perhaps update status if you add a status field
        // For now, let's just notify registered users that the event is cancelled
        // This is a placeholder for your actual implementation
        
        // You might want to add a 'status' column to your events table
        // $this->update(['status' => 'cancelled']);
        
        return $this;
    }

    /**
     * Publish an event.
     */
    public function publishEvent()
    {
        // Logic to publish event - you might want to add a 'published' field
        // $this->update(['published' => true]);
        
        return $this;
    }
}