<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    // Show all banks
    public function index()
    {
        $banks = Bank::all();
        return view('banks.index', compact('banks'));
    }

    // Show create form
    public function create()
    {
        return view('banks.create');
    }

    // Store new bank
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required',
            'account_no' => 'required',
            'account_title' => 'required',
            'balance' => 'required|numeric',
        ]);

        Bank::create($request->all());

        return redirect()->route('banks.index')->with('success', 'Bank added successfully.');
    }

    // Show details
    public function show($id)
    {
        $bank = Bank::findOrFail($id);
        return view('banks.show', compact('bank'));
    }

    // Show edit form
    public function edit($id)
    {
        $bank = Bank::findOrFail($id);
        return view('banks.edit', compact('bank'));
    }

    // Update bank
    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required',
            'account_no' => 'required',
            'account_title' => 'required',
            'balance' => 'required|numeric',
        ]);

        $bank = Bank::findOrFail($id);
        $bank->update($request->all());

        return redirect()->route('banks.index')->with('success', 'Bank updated successfully.');
    }

    // Delete bank
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully.');
    }
}
