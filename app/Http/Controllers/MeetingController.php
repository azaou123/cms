<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Cell;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{
    public function index()
    {
        $meetings = Meeting::with(['cell', 'project'])->latest()->paginate(10);
        return view('meetings.index', compact('meetings'));
    }

    public function create()
    {
        $cells = Cell::all();
        $projects = Project::all();
        $users = User::all();
        return view('meetings.create', compact('cells', 'projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:in-person,virtual,hybrid',
            'cell_id' => 'required|exists:cells,id',
            'project_id' => 'nullable|exists:projects,id',
            'board_id' => 'nullable|exists:boards,id',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
        ]);

        $meeting = Meeting::create($validated);

        // Add attendees if specified
        if (isset($validated['attendees'])) {
            foreach ($validated['attendees'] as $userId) {
                $meeting->attendances()->create([
                    'user_id' => $userId,
                    'status' => 'no_response'
                ]);
            }
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting scheduled successfully.');
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['cell', 'project', 'attendees']);
        return view('meetings.show', compact('meeting'));
    }

    public function edit(Meeting $meeting)
    {
        $cells = Cell::all();
        $projects = Project::all();
        $users = User::all();
        $meeting->load('attendees');
        return view('meetings.edit', compact('meeting', 'cells', 'projects', 'users'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'location' => 'nullable|string|max:255',
            'type' => 'required|in:in-person,virtual,hybrid',
            'cell_id' => 'required|exists:cells,id',
            'project_id' => 'nullable|exists:projects,id',
            'board_id' => 'nullable|exists:boards,id',
            'attendees' => 'nullable|array',
            'attendees.*' => 'exists:users,id',
        ]);

        $meeting->update($validated);

        // Update attendees if specified
        if (isset($validated['attendees'])) {
            // Get current attendees
            $currentAttendees = $meeting->attendances()->pluck('user_id')->toArray();
            
            // Add new attendees
            foreach ($validated['attendees'] as $userId) {
                if (!in_array($userId, $currentAttendees)) {
                    $meeting->attendances()->create([
                        'user_id' => $userId,
                        'status' => 'no_response'
                    ]);
                }
            }
            
            // Remove attendees not in the new list
            $meeting->attendances()->whereNotIn('user_id', $validated['attendees'])->delete();
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        return redirect()->route('meetings.index')
            ->with('success', 'Meeting deleted successfully.');
    }
}