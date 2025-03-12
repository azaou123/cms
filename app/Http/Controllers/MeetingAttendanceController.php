<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\MeetingAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingAttendanceController extends Controller
{
    public function updateAttendance(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'status' => 'required|in:attending,not_attending,maybe',
            'notes' => 'nullable|string',
        ]);

        $attendance = MeetingAttendance::updateOrCreate(
            ['meeting_id' => $meeting->id, 'user_id' => Auth::id()],
            [
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null
            ]
        );

        return redirect()->back()->with('success', 'Attendance status updated.');
    }

    public function manageAttendance(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'attendances' => 'required|array',
            'attendances.*.user_id' => 'required|exists:users,id',
            'attendances.*.status' => 'required|in:attending,not_attending,maybe,no_response',
            'attendances.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['attendances'] as $attendanceData) {
            MeetingAttendance::updateOrCreate(
                [
                    'meeting_id' => $meeting->id, 
                    'user_id' => $attendanceData['user_id']
                ],
                [
                    'status' => $attendanceData['status'],
                    'notes' => $attendanceData['notes'] ?? null
                ]
            );
        }

        return redirect()->back()->with('success', 'Attendance records updated.');
    }
}