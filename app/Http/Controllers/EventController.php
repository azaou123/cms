<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $events = Event::with('cell')->latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $cells = Cell::all();
        return view('events.create', compact('cells'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(['workshop', 'seminar', 'conference', 'meeting', 'other'])],
            'cell_id' => 'required|exists:cells,id',
        ]);

        $event = Event::createEvent($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->load(['cell', 'registrations.user']);
        
        // Check if current user is registered
        $userRegistered = false;
        $registration = null;
        
        if (Auth::check()) {
            $registration = $event->registrations()
                ->where('user_id', Auth::id())
                ->first();
            $userRegistered = $registration !== null;
        }
        
        return view('events.show', compact('event', 'userRegistered', 'registration'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $cells = Cell::all();
        return view('events.edit', compact('event', 'cells'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'type' => ['required', Rule::in(['workshop', 'seminar', 'conference', 'meeting', 'other'])],
            'cell_id' => 'required|exists:cells,id',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
    
    /**
     * Cancel the specified event.
     */
    public function cancel(Event $event)
    {
        $event->cancelEvent();
        return redirect()->route('events.show', $event)
            ->with('success', 'Event has been cancelled.');
    }
    
    /**
     * Publish the specified event.
     */
    public function publish(Event $event)
    {
        $event->publishEvent();
        return redirect()->route('events.show', $event)
            ->with('success', 'Event has been published.');
    }
}