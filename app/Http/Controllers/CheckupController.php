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
        $checkups = DB::table('checkups')
            ->join('patients', 'checkups.patient_id', '=', 'patients.id')
            ->join('doctors', 'checkups.doctor_id', '=', 'doctors.id')
            ->leftJoin('branches', 'checkups.branch_id', '=', 'branches.id')
            ->select(
                'checkups.*',
                'patients.name as patient_name',
                'patients.gender',
                'patients.phone as patient_phone',
                'doctors.name as doctor_name',
                'branches.name as branch_name'
            )
            ->orderBy('checkups.date', 'desc')
            ->get();

        return view('checkups.index', compact('checkups'));
    }

    // 2️⃣ Show form to create new checkup (with auto-fill)
    public function create(Request $request)
    {
        $patients = DB::table('patients')->select('id', 'name', 'phone', 'branch_id')->get();
        $doctors = DB::table('doctors')->select('id', 'name')->get();

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
    }

    // 3️⃣ Store new checkup
    public function store(Request $request)
    {
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

        Checkup::create([
            'patient_id' => $request->patient_id,
            'doctor_id'  => $request->doctor_id,
            'branch_id'  => $patient->branch_id,
            'date'       => $request->date,
            'diagnosis'  => $request->diagnosis ?? null,
            'fee'        => $checkupFee,
            'note'       => $request->note,
        ]);

        return redirect()->route('checkups.index')->with('success', 'Checkup added successfully.');
    }

    // 4️⃣ Edit form
    public function edit($id)
    {
        $checkup  = Checkup::findOrFail($id);
        $patients = DB::table('patients')->select('id', 'name')->get();
        $doctors  = DB::table('doctors')->select('id', 'name')->get();

        return view('checkups.edit', compact('checkup', 'patients', 'doctors'));
    }

    // 5️⃣ Update checkup
    public function update(Request $request, $id)
    {
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

        return redirect()->route('checkups.index')->with('success', 'Checkup updated successfully.');
    }

    // 6️⃣ Delete checkup
    public function destroy($id)
    {
        DB::table('checkups')->where('id', $id)->delete();
        return redirect()->route('checkups.index')->with('success', 'Checkup deleted successfully.');
    }

    // 7️⃣ Show checkup detail
    public function show($id)
    {
        $checkup = DB::table('checkups')
            ->join('patients', 'checkups.patient_id', '=', 'patients.id')
            ->join('doctors', 'checkups.doctor_id', '=', 'doctors.id')
            ->leftJoin('branches', 'checkups.branch_id', '=', 'branches.id')
            ->select(
                'checkups.*',
                'patients.name as patient_name',
                'patients.phone as patient_phone',
                'patients.gender',
                'doctors.name as doctor_name',
                'branches.name as branch_name'
            )
            ->where('checkups.id', $id)
            ->first();

        if (!$checkup) {
            abort(404);
        }

        return view('checkups.show', compact('checkup'));
    }

    // 8️⃣ Print slip
    public function printSlip($id)
    {
        $checkup = Checkup::with(['patient', 'doctor', 'branch'])->findOrFail($id);
        return view('checkups.print', compact('checkup'));
    }

    // 9️⃣ Ajax: Get fee based on patient's branch
    public function getCheckupFee($patientId)
    {
        $patient = DB::table('patients')->where('id', $patientId)->first();
        if (!$patient) {
            return response()->json(['fee' => 0]);
        }

        $fee = $this->getFeeByBranch($patient->branch_id);

        return response()->json(['fee' => $fee]);
    }

    // 🔟 Patient History
    public function history($patient_id)
    {
        $patient = DB::table('patients')->where('id', $patient_id)->first();
        if (!$patient) {
            abort(404, 'Patient not found.');
        }

        $history = DB::table('checkups')
            ->join('doctors', 'checkups.doctor_id', '=', 'doctors.id')
            ->leftJoin('branches', 'checkups.branch_id', '=', 'branches.id')
            ->select(
                'checkups.*',
                'doctors.name as doctor_name',
                'branches.name as branch_name'
            )
            ->where('checkups.patient_id', $patient_id)
            ->orderBy('checkups.date', 'desc')
            ->get();

        return view('checkups.history', compact('history', 'patient'));
    }

    // 🛠 Helper function: get fee by branch
    private function getFeeByBranch($branch_id)
    {
        $setting = DB::table('general_settings')->where('branch_id', $branch_id)->first();
        return $setting ? $setting->default_checkup_fee : 0;
    }
}
