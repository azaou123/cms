<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClubSetting;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Redirect based on auth status
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function redirectToApp()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return redirect()->route('home');
        }
        $settings = ClubSetting::first();
        return view('welcome', compact('settings'));
    }

    /**
     * Show the application dashboard (for authenticated users).
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}
