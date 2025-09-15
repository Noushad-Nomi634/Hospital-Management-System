<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User; // 👈 User model import
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DoctorController extends Controller
{
    // Show all doctors
    public function index()
    {
        try {
            $doctors = Doctor::all();
            return view('doctors.index', compact('doctors'));
        } catch (\Exception $e) {
            \Log::error('Doctor index error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load doctors list.');
        }
    }

    // Show doctor detail
    public function show($id)
    {
        try {
            $doctor = Doctor::with('branch')->findOrFail($id);
            return view('doctors.show', compact('doctor'));
        } catch (\Exception $e) {
            \Log::error('Doctor show error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load doctor details.');
        }
    }

    // Show create doctor form
    public function create()
    {
        try {
            $branches = \App\Models\Branch::all();
            return view('doctors.create', compact('branches'));
        } catch (\Exception $e) {
            \Log::error('Doctor create form error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load doctor creation form.');
        }
    }

    // Store new doctor
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name'     => 'required|string|max:255',
                'last_name'      => 'required|string|max:255',
                'email'          => 'required|email|unique:doctors,email',
                'phone'          => 'nullable|string|max:20',
                'specialization' => 'required|string|max:255',
                'password'       => 'required|string|min:8',
                'branch_id'      => 'required|exists:branches,id',
                'cnic'           => 'nullable|string|max:20',
                'dob'            => 'nullable|date',
                'last_education' => 'nullable|string|max:255',
                'document'       => 'nullable|file|mimes:pdf,jpg,png,jpeg',
                'picture'        => 'nullable|image|mimes:jpg,png,jpeg',
                'status'         => 'required|in:active,inactive',
            ]);

            // 1️⃣ Create User account for doctor
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // 2️⃣ Handle uploads
            if ($request->hasFile('document')) {
                $validated['document'] = $request->file('document')->store('documents', 'public');
            }
            if ($request->hasFile('picture')) {
                $validated['picture'] = $request->file('picture')->store('pictures', 'public');
            }

            // 3️⃣ Create Doctor linked to user
            $doctor = Doctor::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'cnic' => $validated['cnic'],
                'dob' => $validated['dob'],
                'last_education' => $validated['last_education'],
                'specialization' => $validated['specialization'],
                'status' => $validated['status'],
                'branch_id' => $validated['branch_id'],
                'document' => $validated['document'] ?? null,
                'picture' => $validated['picture'] ?? null,
                'password' => Hash::make($validated['password']),
                'user_id' => $user->id, // 👈 Link to User
            ]);

            // 4️⃣ Assign doctor role
            $role = Role::firstOrCreate(['name' => 'doctor']);
            $user->assignRole($role);

            return redirect()
                ->route('doctors.index')
                ->with('success', 'Doctor created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Doctor store error: ' . $e->getMessage());
            return back()->with('error', 'Unable to create doctor. Please try again.')->withInput();
        }
    }

    // Show edit form
    public function edit($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $branches = \App\Models\Branch::all();
            return view('doctors.edit', compact('doctor', 'branches'));
        } catch (\Exception $e) {
            \Log::error('Doctor edit error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load doctor edit form.');
        }
    }

    // Update doctor
    public function update(Request $request, $id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $user = $doctor->user; // linked user

            $validated = $request->validate([
                'first_name'     => 'required|string|max:255',
                'last_name'      => 'required|string|max:255',
                'email'          => 'required|email|unique:doctors,email,' . $doctor->id,
                'phone'          => 'nullable|string|max:20',
                'specialization' => 'required|string|max:255',
                'branch_id'      => 'required|exists:branches,id',
                'cnic'           => 'nullable|string|max:20',
                'dob'            => 'nullable|date',
                'last_education' => 'nullable|string|max:255',
                'document'       => 'nullable|file|mimes:pdf,jpg,png,jpeg',
                'picture'        => 'nullable|image|mimes:jpg,png,jpeg',
                'status'         => 'required|in:active,inactive',
            ]);

            // Handle uploads
            if ($request->hasFile('document')) {
                $validated['document'] = $request->file('document')->store('documents', 'public');
            }
            if ($request->hasFile('picture')) {
                $validated['picture'] = $request->file('picture')->store('pictures', 'public');
            }

            // Update doctor
            $doctor->update($validated);

            // Update linked user email/password
            $user->update([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            ]);

            return redirect()
                ->route('doctors.index')
                ->with('success', 'Doctor updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Doctor update error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update doctor. Please try again.')->withInput();
        }
    }

    // Delete a doctor
    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->user()->delete(); // delete linked user
            $doctor->delete();

            return redirect()
                ->route('doctors.index')
                ->with('success', 'Doctor deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Doctor delete error: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete doctor. Please try again.');
        }
    }

    // Show availability page of a doctor
    public function availability($id)
    {
        try {
            $doctor = Doctor::with('availabilities')->findOrFail($id);
            return view('doctors.availability', compact('doctor'));
        } catch (\Exception $e) {
            \Log::error('Doctor availability error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load doctor availability.');
        }
    }
}
