<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // View all users
    public function index() {
        // Eager load branch to avoid N+1 issue
        $users = User::with('branch')->get();
        return view('users.index', compact('users'));
    }

    // Show create form
    public function create() {
        $branches = Branch::all();
         $roles = Role::all();
        return view('users.create',  compact('branches', 'roles'));
    }

    // Store new user
    public function store(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'branch_id' => 'required',
            'role' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'branch_id' => $request->branch_id,
            'role' => $request->role,
        ]);

        // Assign Spatie role for web guard
        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show edit form
    public function edit($id) {
        $user = User::findOrFail($id);
        $branches = Branch::all();
        return view('users.edit', compact('user', 'branches'));
    }

    // Update user
  public function update(Request $request, $id) {

    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,'.$user->id,
        'branch_id' => 'required',
        'role' => 'required',
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'branch_id' => $request->branch_id,
    ]);

    // ✅ role update
    $user->syncRoles([$request->role]);

    if ($request->password) {
        $user->update([
            'password' => Hash::make($request->password)
        ]);
    }

    return redirect()->route('users.index')
        ->with('success', 'User updated successfully.');
}


    // Delete user
    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
