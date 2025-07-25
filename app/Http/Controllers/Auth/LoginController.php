<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated($request, $user)
    {
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Please verify your email before logging in. 
                    <a href="' . route('verification.send') . '"
                    onclick="event.preventDefault(); document.getElementById(\'resend-verification-form\').submit();"
                    class="alert-link">Resend verification email</a>.',
            ]);
        }
    }
}
