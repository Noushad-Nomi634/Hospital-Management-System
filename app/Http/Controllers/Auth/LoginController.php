<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Show login form with dynamic roles.
     */
    public function showLoginForm()
    {
        $roles = Role::all(); // DB se sab roles fetch
        return view('auth.login', compact('roles'));
    }

    /**
     * Handle post-login actions.
     */
    protected function authenticated(Request $request, $user)
    {
        // Role dropdown validation
        if ($request->filled('role') && !$user->hasRole($request->role)) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'role' => 'You are not authorized to login as this role.'
            ]);
        }

        // Role-based redirects
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('doctor')) {
            return redirect()->route('doctor.dashboard');
        }

        if ($user->hasRole('receptionist')) {
            return redirect()->route('receptionist.dashboard');
        }

        if ($user->hasRole('manager')) {
            return redirect()->route('manager.dashboard');
        }

        if ($user->hasRole('accountant')) {
            return redirect()->route('accountant.dashboard');
        }

        if ($user->hasRole('pharmacist')) {
            return redirect()->route('pharmacist.dashboard');
        }

        // default
        return redirect()->route('dashboard');
    }

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
