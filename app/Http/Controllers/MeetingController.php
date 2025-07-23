<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Cell;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\MeetingInvitation;

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

        // Add attendees and send notifications
        if (isset($validated['attendees'])) {
            foreach ($validated['attendees'] as $userId) {
                $meeting->attendances()->create([
                    'user_id' => $userId,
                    'status' => 'no_response'
                ]);

                $user = User::find($userId);
                if ($user && $user->email) {
                    $user->notify(new MeetingInvitation($meeting));
                }
            }
        }

        return redirect()->route('meetings.index')
            ->with('success', 'Meeting scheduled and invitations sent.');
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

    public function generateReport(\App\Models\Meeting $meeting)
    {
        // 👉 Build prompt from meeting details
        $prompt = "Generate a concise meeting summary report in plain text. Do NOT use any Markdown formatting characters such as asterisks (*), hashtags (#), or backticks (`). The summary should be readable without any special formatting symbols. Ensure all discussion points, action items, and attendees are clearly listed in plain text. Avoid any placeholder text or assumptions.\n"
            . "Title: {$meeting->title}\n"
            . "Date: {$meeting->date->format('F d, Y')}\n"
            . "Time: {$meeting->start_time} to {$meeting->end_time}\n"
            . "Location: {$meeting->location}\n"
            . "Description: {$meeting->description}\n"
            . "Attendees:\n";

        foreach ($meeting->attendances as $a) {
            $prompt .= "- {$a->user->name} ({$a->status})";
            if ($a->notes) {
                $prompt .= " Notes: {$a->notes}";
            }
            $prompt .= "\n";
        }

        // 👉 Call Gemini API
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => env('GEMINI_API_KEY'),
            ])->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ]
                ]
            );

            if (!$response->successful()) {
                return back()->with('error', 'Gemini API error: ' . $response->body());
            }

            // 👉 Parse Gemini response
            $json = $response->json();
            $generatedContent = $json['candidates'][0]['content']['parts'][0]['text'] ?? 'No content generated.';

            // --- NEW: Post-processing to remove any lingering Markdown ---
            // Remove common Markdown formatting characters if they still appear
            $generatedContent = str_replace(['**', '*', '##', '#'], '', $generatedContent);
            $generatedContent = trim($generatedContent); // Trim any leading/trailing whitespace

        } catch (\Exception $e) {
            return back()->with('error', 'Error communicating with Gemini API: ' . $e->getMessage());
        }

        // 👉 Generate PDF with the AI content
        $pdf = Pdf::loadView('meetings.report', [
            'meeting' => $meeting,
            'content' => $generatedContent,
        ]);

        // 👉 Return PDF download
        return $pdf->download('meeting-report-' . $meeting->id . '.pdf');
    }
}