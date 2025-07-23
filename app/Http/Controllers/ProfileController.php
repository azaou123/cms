<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Cell;
use App\Models\project;


class ProfileController extends Controller
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
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    /**
     * Show the form for editing the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $profilePicturePath = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->profile_picture = $profilePicturePath;
        }

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        $user->bio = $validated['bio'];
        $user->save();

        return redirect()->route('profile.show')->with('status', 'Profile updated successfully!');
    }


    public function showsmembres(){
        $members=User::paginate(7);
        $cells = Cell::all();
        $projects=Project::all();
        $rols=User::select();

        return view('members/show_members',compact('members','rols','projects','cells',));
    }


    public function filtermembres(Request $request){



    $id_cell = $request->cell;
    $id_project = $request->project;
    $roll = $request->roll;

    // More efficient approach using query builder
    $query = User::query();

    // Filter by cell if provided
    if ($id_cell) {
        $query->whereHas('cells', function($q) use ($id_cell) {
            $q->where('cells.id', $id_cell);
        });
    }

    // Filter by project if provided
    if ($id_project) {
        $query->whereHas('projects', function($q) use ($id_project) {
            $q->where('projects.id', $id_project);
        });
    }

   // Filter by role if provided (assuming role is stored in pivot table)
    if ($roll) {
        $query->whereHas('cells', function($q) use ($roll) {
            $q->where('cell_user.role', $roll);
        });
    }

    $members = $query->paginate(7);
    //dd($members);


    // Get all data needed for the view
    $cells = Cell::all();
    $projects = Project::all();
    $rols=User::select();
    // $roles = User::distinct()->pluck('role'); // Or however you store roles

    return view('members/show_members', compact('members', 'rols', 'projects', 'cells'));
}





}
