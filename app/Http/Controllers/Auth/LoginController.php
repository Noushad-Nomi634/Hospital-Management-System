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
        $remember = (bool) $request->filled('remember');
        $role = $request->input('role');

        if ($role === 'doctor') {
            // Ensure web guard is logged out so only one guard is active
            if (Auth::guard('web')->check()) {
                Auth::guard('web')->logout();
            }
            return Auth::guard('doctor')->attempt($credentials, $remember);
        }

        // Ensure doctor guard is logged out when logging in via web
        if (Auth::guard('doctor')->check()) {
            Auth::guard('doctor')->logout();
        }
        return Auth::guard('web')->attempt($credentials, $remember);
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->role === 'doctor') {
            return redirect()->route('doctor.dashboard');
        }

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('receptionist')) {
            return redirect()->route('receptionist.dashboard');
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
