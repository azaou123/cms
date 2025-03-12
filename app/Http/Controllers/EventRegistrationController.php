<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRegistrationController extends Controller
{
    /**
     * Register the current user for an event.
     */
    public function register(Event $event)
    {
        // Check if user is already registered
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingRegistration) {
            return redirect()->route('events.show', $event)
                ->with('info', 'You are already registered for this event.');
        }
        
        EventRegistration::registerForEvent($event->id, Auth::id());
        
        return redirect()->route('events.show', $event)
            ->with('success', 'You have successfully registered for this event!');
    }
    
    /**
     * Cancel the user's registration for an event.
     */
    public function cancel(EventRegistration $registration)
    {
        // Ensure user can only cancel their own registration
        if ($registration->user_id !== Auth::id()) {
            return redirect()->route('events.show', $registration->event_id)
                ->with('error', 'You are not authorized to cancel this registration.');
        }
        
        $registration->cancelRegistration();
        
        return redirect()->route('events.show', $registration->event_id)
            ->with('success', 'Your registration has been cancelled.');
    }
    
    /**
     * Display a list of events the user is registered for.
     */
    public function myEvents()
    {
        $registrations = EventRegistration::with('event.cell')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);
            
        return view('events.my-events', compact('registrations'));
    }
    
    /**
     * Update the status of an event registration (for admins).
     */
    public function updateStatus(Request $request, EventRegistration $registration)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['registered', 'attended', 'cancelled', 'no_show'])],
        ]);
        
        $registration->update(['status' => $validated['status']]);
        
        return redirect()->back()
            ->with('success', 'Registration status updated successfully.');
    }
}