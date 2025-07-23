<?php

namespace App\Http\Controllers;

use App\Models\Cell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CellController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cells = Cell::all();
        return view('cells.index', compact('cells'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('cells.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cells',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,archived',
        ]);

        $cell = Cell::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'created_by' => Auth::id(),
        ]);

        // Add the creator as a member with the role of 'leader'
        $cell->members()->attach(Auth::id(), ['role' => 'leader']);

        return redirect()->route('cells.show', $cell)
            ->with('success', 'Cell created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cell $cell)
    {
        $members = $cell->members;
        return view('cells.show', compact('cell', 'members'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cell $cell)
    {
        return view('cells.edit', compact('cell'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cell $cell)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:cells,name,' . $cell->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,archived',
        ]);

        $cell->update($validated);

        return redirect()->route('cells.show', $cell)
            ->with('success', 'Cell updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cell $cell)
    {
        $cell->delete();

        return redirect()->route('cells.index')
            ->with('success', 'Cell deleted successfully.');
    }

    /**
     * Manage members of the cell.
     */
    public function manageMembers(Cell $cell)
    {
        $nbr_members=$cell->members()->get();
        $members = $cell->members()->paginate(7);
        $users = \App\Models\User::whereNotIn('id', $members->pluck('id'))->get();

        return view('cells.members', compact('cell', 'members', 'users','nbr_members'));
    }

    /**
     * Add a member to the cell.
     */
    public function addMember(Request $request, Cell $cell)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string|max:50',
        ]);

        $cell->members()->attach($validated['user_id'], ['role' => $validated['role']]);

        return redirect()->route('cells.members', $cell)
            ->with('success', 'Member added successfully.');
    }

    /**
     * Update a member's role.
     */
    public function updateMemberRole(Request $request, Cell $cell, $userId)
    {
        $validated = $request->validate([
            'role' => 'required|string|max:50',
        ]);
        $count = $cell->members()->updateExistingPivot($userId, ['role' => $validated['role']]);

        if ($request->ajax()) {
            if ($count > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Member role updated successfully.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No member was updated. Check if the user belongs to this cell.'
                ], 404);
            }
        }


        // $cell->members()->updateExistingPivot($userId, ['role' => $validated['role']]);

        // return redirect()->route('cells.members', $cell)
        //     ->with('success', 'Member role updated successfully.');
    }

    /**
     * Remove a member from the cell.
     */
    public function removeMember(Cell $cell, $userId)
    {
        $cell->members()->detach($userId);

        return redirect()->route('cells.members', $cell)
            ->with('success', 'Member removed successfully.');
    }
}
