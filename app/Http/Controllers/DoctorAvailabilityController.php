<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\DoctorAvailability;
use Illuminate\Http\Request;

class DoctorAvailabilityController extends Controller
{
    // Show availability page for a doctor
    public function index($doctor_id)
    {
        $doctor = Doctor::with('availabilities')->findOrFail($doctor_id);
        return view('doctors.availability', compact('doctor'));
    }

    // Store new availability
    public function store(Request $request, $doctor_id)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        DoctorAvailability::create([
            'doctor_id' => $doctor_id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('doctors.availability', $doctor_id)->with('success', 'Availability Added');
    }

    // **Edit availability**
    public function edit($id)
    {
        $availability = DoctorAvailability::findOrFail($id);
        return view('doctors.availability_edit', compact('availability'));
    }

    // Update existing availability
    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        $availability = DoctorAvailability::findOrFail($id);
        $availability->update($request->all());

        return redirect()->route('doctors.availability', $availability->doctor_id)
                         ->with('success', 'Availability Updated');
    }

    // Delete availability
    public function destroy($id)
    {
        $availability = DoctorAvailability::findOrFail($id);
        $doctorId = $availability->doctor_id;
        $availability->delete();

        return redirect()->route('doctors.availability', $doctorId)->with('success', 'Availability Deleted');
    }
}
