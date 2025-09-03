<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    /**
     * Show all doctors
     */
    public function index()
    {
        $doctors = Doctor::all();
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show create doctor form
     */
    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Store new doctor
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:doctors,email',
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'required|string|max:255',
            'password'       => 'required|string|min:8',

        ]);
        $validated['password'] = Hash::make($validated['password']);
        $doctor = Doctor::create($validated);
        //Doctor role assign

        $doctor->assignRole('doctor');

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('doctors.edit', compact('doctor'));
    }

    /**
     * Update doctor
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone'          => 'nullable|string|max:20',
            'specialization' => 'required|string|max:255',
        ]);

        $doctor->update($validated);

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor updated successfully!');
    }

    /**
     * Show availability page of a doctor
     */
    public function availability($id)
    {
        $doctor = Doctor::with('availabilities')->findOrFail($id);
        return view('doctors.availability', compact('doctor'));
    }

    /**
     * Delete a doctor
     */
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()
            ->route('doctors.index')
            ->with('success', 'Doctor deleted successfully!');
    }
}
