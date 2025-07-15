<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cell;
use App\Models\Project;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Contracts\Service\Attribute\Required;

class membresController extends Controller
{


       public function __construct()
        {
            $this->middleware('auth');
        }

    public function showsmembres(){
        $members=User::paginate(7);
        $cells = Cell::all();
        $projects=Project::all();

        return view('members/show_members',compact('members','cells','projects'));
    }

    public function cellfilter(Request $request){
        $id=$request->cells;
    }

}
