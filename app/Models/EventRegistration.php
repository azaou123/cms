<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'registration_date',
        'status',
    ];

    protected $casts = [
        'registration_date' => 'date',
    ];

    /**
     * Get the event that this registration belongs to.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who registered.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Register a user for an event.
     */
    public static function registerForEvent(int $eventId, int $userId, array $data = [])
    {
        return self::create(array_merge([
            'event_id' => $eventId,
            'user_id' => $userId,
            'registration_date' => now(),
            'status' => 'registered',
        ], $data));
    }

    /**
     * Cancel a registration.
     */
    public function cancelRegistration()
    {
        // Delete the registration record
        $this->delete();
        
        return $this;
    }
}