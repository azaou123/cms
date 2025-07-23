<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cell;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MembersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = User::query()->with(['cell', 'projects']);

        // Apply cell filter
        if ($request->filled('cell')) {
            $query->where('cell_id', $request->cell);
        }

        // Apply project filter
        if ($request->filled('project')) {
            $query->whereHas('projects', function ($q) use ($request) {
                $q->where('projects.id', $request->project);
            });
        }

        // Apply search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate(6)->withQueryString();
        $cells = Cell::all();
        $projects = Project::all();

        if ($request->ajax()) {
            return view('users.partials.table', compact('users'))->render();
        }

        return view('users.users', compact('users', 'cells', 'projects'));
    }

    public function showMembers(Request $request)
    {
        $query = User::query()->with(['cell', 'projects']);

        // Apply cell filter for showMembers
        if ($request->filled('cell')) {
            $query->where('cell_id', $request->cell);
        }

        $members = $query->paginate(7)->withQueryString();
        $cells = Cell::all();
        $projects = Project::all();

        if ($request->ajax()) {
            return view('members.partials.table', compact('members'))->render();
        }

        return view('members.show_members', compact('members', 'cells', 'projects'));
    }

    public function show(User $user)
    {
        $user->load(['cell', 'projects']);
        return view('users.show', compact('user'));
    }

    public function search(Request $request)
    {
        $query = User::query()->with(['cell', 'projects']);

        // Apply cell filter
        if ($request->filled('cell')) {
            $query->where('cell_id', $request->cell);
        }

        // Apply project filter
        if ($request->filled('project')) {
            $query->whereHas('projects', function ($q) use ($request) {
                $q->where('projects.id', $request->project);
            });
        }

        // Apply search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate(6)->withQueryString();

        return view('users.partials.table', compact('users'))->render();
    }
}