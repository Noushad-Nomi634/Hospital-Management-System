<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Branch;

class PatientController extends Controller
{
    /**
     * Display a listing of patients.
     */
    public function index(Request $request)
    {
        $query = Patient::with('branch');

        // Filter by patient ID if provided
        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $patients = $query->get();

        return view('patients.indexx', compact('patients'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        $branches = Branch::all();
        return view('patients.create', compact('branches'));
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'gender'        => 'required|in:Male,Female,Other',
            'guardian_name' => 'required|string|max:255',
            'age'           => 'required|numeric',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'branch_id'     => 'required|exists:branches,id',
        ]);

        Patient::create($request->only(
            'name',
            'gender',           // ✅ Gender included
            'guardian_name',
            'age',
            'phone',
            'address',
            'branch_id'
        ));

        return redirect('/patients')->with('success', 'Patient added successfully!');
    }

    /**
     * Show the form for editing a patient.
     */
    public function edit($id)
    {
        $patient  = Patient::findOrFail($id);
        $branches = Branch::all();

        return view('patients.edit', compact('patient', 'branches'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'gender'        => 'required|in:Male,Female,Other',
            'guardian_name' => 'required|string|max:255',
            'age'           => 'required|numeric',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:500',
            'branch_id'     => 'required|exists:branches,id',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update($request->only(
            'name',
            'gender',           // ✅ Gender included
            'guardian_name',
            'age',
            'phone',
            'address',
            'branch_id'
        ));

        return redirect('/patients')->with('success', 'Patient updated successfully!');
    }

    /**
     * Display the specified patient with branch and checkups.
     */
    public function show($id)
    {
        $patient = Patient::with('branch', 'checkups')->findOrFail($id);
        // echo "<pre>";
        // print_r($patient->toArray());
        // echo "</pre>";
        // exit();
        return view('patients.show', compact('patient'));
    }

    /**
     * Remove the specified patient from storage.
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect('/patients')->with('success', 'Patient deleted successfully!');
    }
}

