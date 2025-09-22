<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    // List all branches
    public function index()
    {
        $branches = Branch::all();
        return view('branches.index', compact('branches'));
    }

    // Show create form
    public function create()
    {
        return view('branches.create');
    }

    // Store new branch
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'prefix' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|string|in:active,inactive',
            'fee' => 'required|numeric|min:0', // Fee validation added
        ]);

        Branch::create($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch added successfully.');
    }

    // Show edit form
    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('branches.edit', compact('branch'));
    }

    // Update branch
    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'prefix' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|string|in:active,inactive',
            'fee' => 'required|numeric|min:0', // Fee validation added
        ]);

        $branch->update($request->all());

        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    // Delete branch
    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}
