<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        try {
            $employees = DB::table('employees')->get();
            return view('employees.index', compact('employees'));
        } catch (\Exception $e) {
            \Log::error('Employee index error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load employees list.');
        }
    }

    public function create()
    {
        try {
            $branches = DB::table('branches')->get();
            return view('employees.create', compact('branches'));
        } catch (\Exception $e) {
            \Log::error('Employee create form error: ' . $e->getMessage());
            return back()->with('error', 'Unable to load employee creation form.');
        }
    }

    public function store(Request $request)
    {
        try {
            // ✅ Step 1: Validate input
            $request->validate([
                'name'         => 'required|string|max:255',
                'designation'  => 'required|string|max:255',
                'branch_id'    => 'required|integer|exists:branches,id',
                'basic_salary' => 'required',
                'phone'        => 'required|string|max:20',
                'joining_date' => 'required|date',
            ]);

            // ✅ Step 2: Remove comma from salary input
            $salary = str_replace(',', '', $request->basic_salary);

            // ✅ Step 3: Insert into database
            DB::table('employees')->insert([
                'name'         => $request->name,
                'designation'  => $request->designation,
                'branch_id'    => $request->branch_id,
                'basic_salary' => $salary,
                'phone'        => $request->phone,
                'joining_date' => $request->joining_date,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            // ✅ Step 4: Redirect with success message
            return redirect('employees')->with('success', 'Employee added successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Employee store error: ' . $e->getMessage());
            return back()->with('error', 'Unable to add employee. Please try again.')
                        ->withInput();
        }
    }
}
