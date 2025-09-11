<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Checkup;

class CheckupController extends Controller
{
    // 1️⃣ Show all checkups
    public function index()
    {
        try {
            $checkups = DB::table('checkups')
                ->join('patients', 'checkups.patient_id', '=', 'patients.id')
                ->join('doctors', 'checkups.doctor_id', '=', 'doctors.id')
                ->leftJoin('branches', 'checkups.branch_id', '=', 'branches.id')
                ->select(
                    'checkups.*',
                    'patients.name as patient_name',
                    'patients.gender',
                    'patients.phone as patient_phone',
                    DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) as doctor_name"),
                    'branches.name as branch_name'
                )
                ->orderBy('checkups.date', 'desc')
                ->get();

            return view('checkups.index', compact('checkups'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to load checkups: ' . $e->getMessage());
        }
    }

    // 2️⃣ Show create form
    public function create(Request $request)
    {
        try {
            $patients = DB::table('patients')->select('id', 'name', 'phone', 'branch_id')->get();
            $doctors = DB::table('doctors')
                ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))
                ->get();

            $selectedPatient = null;
            $fee = 0;

            if ($request->has('patient_id')) {
                $selectedPatient = DB::table('patients')->where('id', $request->patient_id)->first();
                if ($selectedPatient) {
                    $fee = $this->getFeeByBranch($selectedPatient->branch_id);
                }
            }

            return view('checkups.create', [
                'patients'        => $patients,
                'doctors'         => $doctors,
                'selectedPatient' => $selectedPatient,
                'fee'             => $fee,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to load create form: ' . $e->getMessage());
        }
    }

    // 3️⃣ Store new checkup with transaction
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id'  => 'required|exists:doctors,id',
                'date'       => 'required|date',
                'note'       => 'nullable|string',
            ]);

            $patient = DB::table('patients')->where('id', $request->patient_id)->first();
            if (!$patient) {
                return back()->with('error', 'Patient not found.');
            }

            $checkupFee = $this->getFeeByBranch($patient->branch_id);

            // 1️⃣ Save Checkup
            $checkup = Checkup::create([
                'patient_id' => $request->patient_id,
                'doctor_id'  => $request->doctor_id,
                'branch_id'  => $patient->branch_id,
                'date'       => $request->date,
                'diagnosis'  => $request->diagnosis ?? null,
                'fee'        => $checkupFee,
                'note'       => $request->note,
            ]);

            // 2️⃣ Save Transaction
            DB::table('transactions')->insert([
                'p_id'       => $request->patient_id,
                'dr_id'      => $request->doctor_id,
                'amount'     => $checkupFee,
                'type'       => '+',
                'b_id'       => $patient->branch_id,
                'entery_by'  => auth()->user()->id,
                'Remx'       => 'Checkup Fee',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 3️⃣ Update Branch Balance
            DB::table('branches')->where('id', $patient->branch_id)->increment('balance', $checkupFee);

            DB::commit();
            return redirect()->route('checkups.index')->with('success', '✅ Checkup added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', '❌ Error saving checkup: ' . $e->getMessage());
        }
    }

    // 4️⃣ Edit form
    public function edit($id)
    {
        try {
            $checkup  = Checkup::findOrFail($id);
            $patients = DB::table('patients')->select('id', 'name')->get();
            $doctors = DB::table('doctors')
                ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))
                ->get();

            return view('checkups.edit', compact('checkup', 'patients', 'doctors'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to load edit form: ' . $e->getMessage());
        }
    }

    // 5️⃣ Update checkup
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'doctor_id'  => 'required|exists:doctors,id',
                'date'       => 'required|date',
                'fee'        => 'required|numeric|min:0',
                'note'       => 'nullable|string',
            ]);

            DB::table('checkups')->where('id', $id)->update([
                'patient_id' => $request->patient_id,
                'doctor_id'  => $request->doctor_id,
                'date'       => $request->date,
                'diagnosis'  => $request->diagnosis ?? null,
                'fee'        => $request->fee,
                'note'       => $request->note,
                'updated_at' => now(),
            ]);

            return redirect()->route('checkups.index')->with('success', '✅ Checkup updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error updating checkup: ' . $e->getMessage());
        }
    }

    // 6️⃣ Delete checkup
    public function destroy($id)
    {
        try {
            DB::table('checkups')->where('id', $id)->delete();
            return redirect()->route('checkups.index')->with('success', '🗑️ Checkup deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error deleting checkup: ' . $e->getMessage());
        }
    }

    // 7️⃣ Show detail
    public function show($id)
    {
        try {
            $checkup = DB::table('checkups')
                ->join('patients', 'checkups.patient_id', '=', 'patients.id')
                ->join('doctors', 'checkups.doctor_id', '=', 'doctors.id')
                ->leftJoin('branches', 'checkups.branch_id', '=', 'branches.id')
                ->select(
                    'checkups.*',
                    'patients.name as patient_name',
                    'patients.phone as patient_phone',
                    'patients.gender',
                    DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) as doctor_name"),
                    'branches.name as branch_name'
                )
                ->where('checkups.id', $id)
                ->first();

            if (!$checkup) abort(404);

            return view('checkups.show', compact('checkup'));
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error loading checkup details: ' . $e->getMessage());
        }
    }

    // 8️⃣ Print slip
    public function printSlip($id)
    {
        try {
            $checkup = Checkup::with(['patient', 'doctor', 'branch'])->findOrFail($id);
            return view('checkups.print', compact('checkup'));
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error printing slip: ' . $e->getMessage());
        }
    }

    // 9️⃣ Ajax: Get fee by branch
    public function getCheckupFee($patientId)
    {
        try {
            $patient = DB::table('patients')->where('id', $patientId)->first();
            $fee = $patient ? $this->getFeeByBranch($patient->branch_id) : 0;
            return response()->json(['fee' => $fee]);
        } catch (\Exception $e) {
            return response()->json(['fee' => 0, 'error' => $e->getMessage()]);
        }
    }

    // 🔟 Patient History
    public function history($patient_id)
    {
        try {
            $patient = DB::table('patients')->where('id', $patient_id)->first();
            if (!$patient) abort(404, 'Patient not found.');

            $history = DB::table('checkups')
                ->join('doctors', 'checkups.doctor_id', '=', 'doctors.id')
                ->leftJoin('branches', 'checkups.branch_id', '=', 'branches.id')
                ->select(
                    'checkups.*',
                    DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) as doctor_name"),
                    'branches.name as branch_name'
                )
                ->where('checkups.patient_id', $patient_id)
                ->orderBy('checkups.date', 'desc')
                ->get();

            return view('checkups.history', compact('history', 'patient'));
        } catch (\Exception $e) {
            return back()->with('error', '❌ Error fetching patient history: ' . $e->getMessage());
        }
    }

    // 🛠 Helper: get fee by branch
    private function getFeeByBranch($branch_id)
    {
        $setting = DB::table('general_settings')->where('branch_id', $branch_id)->first();
        return $setting ? $setting->default_checkup_fee : 0;
    }
}
