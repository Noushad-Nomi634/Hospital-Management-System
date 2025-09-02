<?php

namespace App\Http\Controllers\Doctors;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    /**
     * Show all doctors
     */
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

    /**
     * Show create doctor form
     */
    public function create()
    {
        try {
            return view('doctors.create');
        } catch (\Exception $e) {
            \Log::error('Doctor create form error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load doctor creation form.');
        }
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
            ]);

            Doctor::create($validated);

            return redirect()
                ->route('doctors.index')
                ->with('success', 'Doctor created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Doctor store error: ' . $e->getMessage());
            return back()->with('error', 'Unable to create doctor. Please try again.')
                        ->withInput();
        }
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            return view('doctors.edit', compact('doctor'));
        } catch (\Exception $e) {
            \Log::error('Doctor edit error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load doctor edit form.');
        }
    }

    /**
     * Update doctor
     */
    public function update(Request $request, $id)
    {
        try {
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Doctor update error: ' . $e->getMessage());
            return back()->with('error', 'Unable to update doctor. Please try again.')
                        ->withInput();
        }
    }

    /**
     * Show availability page of a doctor
     */
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

    /**
     * Delete a doctor
     */
    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $doctor->delete();

            return redirect()
                ->route('doctors.index')
                ->with('success', 'Doctor deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Doctor delete error: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete doctor. Please try again.');
        }
    }
}
