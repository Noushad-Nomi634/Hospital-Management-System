<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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

    protected function attemptLogin(Request $request)
    {
         $credentials = $request->only('email', 'password');
        $role = $request->input('role');

        if ($role === 'doctor') {
            if (Auth::guard('doctor')->attempt($credentials)) {
                return redirect()->intended('/dr/dashboard');
            }
        } else {
            if (Auth::guard('web')->attempt($credentials)) {
                return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->role === 'doctor') {
            return redirect()->route('dr.dashboard');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('receptionist')) {
            return redirect()->route('dashboard');
        }

        if ($user->hasRole('accountant')) {
            return redirect()->route('dashboard');
        }

        if ($user->hasRole('pharmacist')) {
            return redirect()->route('dashboard');
        }

        // default user dashboard
        return redirect()->route('dashboard');
    }



    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

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
}