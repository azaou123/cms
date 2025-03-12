<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Cell;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['cell', 'users'])->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        $cells = Cell::all();
        $users = User::all();
        return view('projects.create', compact('cells', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,in_progress,on_hold,completed,archived',
            'budget' => 'nullable|numeric',
            'cell_id' => 'required|exists:cells,id',
            'manager_id' => 'required|exists:users,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
        ]);

        $project = Project::create($request->except(['manager_id', 'team_members']));

        // Add manager
        $project->users()->attach($request->manager_id, [
            'is_manager' => true,
            'role' => 'manager',
            'assigned_at' => now(),
        ]);

        // Add team members if any
        if ($request->has('team_members')) {
            foreach ($request->team_members as $memberId) {
                if ($memberId != $request->manager_id) {
                    $project->users()->attach($memberId, [
                        'is_manager' => false,
                        'role' => 'member',
                        'assigned_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully');
    }

    public function show(Project $project)
    {
        // Get all users that are not associated with the current project
        // $usersNotInProject = User::whereDoesntHave('projects', function($query) use ($project) {
        //     $query->where('projects.id', $project->id);
        // })->get();
        $usersNotInProject = User::all();
        return view('projects.show', compact('project', 'usersNotInProject'));
    }

    public function edit(Project $project)
    {
        $cells = Cell::all();
        $users = User::all();
        $manager = $project->manager()->first(); 
        $teamMembers = $project->teamMembers()->pluck('users.id')->toArray();
        
        return view('projects.edit', compact('project', 'cells', 'users', 'manager', 'teamMembers'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:planning,in_progress,on_hold,completed,archived',
            'budget' => 'nullable|numeric',
            'cell_id' => 'required|exists:cells,id',
            'manager_id' => 'required|exists:users,id',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
        ]);

        $project->update($request->except(['manager_id', 'team_members']));

        // Update project members
        $project->users()->detach();

        // Add manager
        $project->users()->attach($request->manager_id, [
            'is_manager' => true,
            'role' => 'manager',
            'assigned_at' => now(),
        ]);

        // Add team members if any
        if ($request->has('team_members')) {
            foreach ($request->team_members as $memberId) {
                if ($memberId != $request->manager_id) {
                    $project->users()->attach($memberId, [
                        'is_manager' => false,
                        'role' => 'member',
                        'assigned_at' => now(),
                    ]);
                }
            }
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        $project->users()->detach();
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully');
    }

    // Additional method to add/remove team members
    public function updateTeam(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'action' => 'required|in:add,remove',
            'role' => 'required_if:action,add|in:manager,member,viewer',
        ]);

        if ($request->action == 'add') {
            $isManager = $request->role == 'manager';
            
            // If adding a manager, remove the current manager first
            if ($isManager) {
                $project->users()->wherePivot('is_manager', true)->detach();
            }
            
            $project->users()->syncWithoutDetaching([
                $request->user_id => [
                    'is_manager' => $isManager,
                    'role' => $request->role,
                    'assigned_at' => now(),
                    'status' => 'active'
                ]
            ]);
            
            $message = 'Team member added successfully';
        } else {
            $project->users()->detach($request->user_id);
            $message = 'Team member removed successfully';
        }

        return back()->with('success', $message);
    }


    public function search(Request $request)
    {
        $query = User::query();
        
        // Filter by search query (name)
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // Filter by project or role if needed
        if ($request->has('project_id')) {
            $query->whereHas('projects', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }

        // Paginate results to limit the number of users sent at once
        $users = $query->paginate(10);

        return response()->json([
            'results' => $users->items(),
            'pagination' => [
                'more' => $users->hasMorePages()
            ]
        ]);
    }
}