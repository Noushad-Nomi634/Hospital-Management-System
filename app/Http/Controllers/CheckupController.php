<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Checkup;

class CheckupController extends Controller
{
    /**
     * 1️⃣ Show all checkups
     */
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
                    'patients.mr',
                    'patients.phone as patient_phone',
                    DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) as doctor_name"),
                    'branches.name as branch_name'
                )
                ->orderBy('checkups.id', 'desc')
                ->get();

                //echo "<pre>"; print_r($checkups); echo "</pre>"; exit;

            return view('consultations.index', [
                'checkups'      => $checkups,
                'consultations' => $checkups,
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to load checkups: ' . $e->getMessage());
        }
    }

    /**
     * 2️⃣ Show create form
     */
    public function create(Request $request)
    {
        try {
            $patients = DB::table('patients')->select('id', 'name', 'mr', 'phone', 'branch_id')->get();
            $doctors  = DB::table('doctors')
                ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))
                ->get();

            $banks = DB::table('banks')->get();

            return view('consultations.create', compact('patients', 'doctors', 'banks'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to load create form: ' . $e->getMessage());
        }
    }

    /**
     * 3️⃣ Store new checkup (Fully Safe Version)
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'patient_id'     => 'required|exists:patients,id',
                'doctor_id'      => 'required|exists:doctors,id',
                'fee'            => 'required|numeric|min:0',
                'paid_amount'    => 'nullable|numeric|min:0',
                'payment_method' => 'nullable|string',
            ]);

            DB::beginTransaction();

            $paymentMethod = 'Cash';
            if ($request->payment_method != '' && $request->payment_method != '0') {
                $paymentMethod = 'bank_transfer';
            }

            $patient = DB::table('patients')->where('id', $request->patient_id)->first();
            if (!$patient) {
                return back()->with('error', '❌ Patient not found.');
            }

            // Create Checkup
            $checkup = Checkup::create([
                'patient_id'     => $request->patient_id,
                'doctor_id'      => $request->doctor_id,
                'branch_id'      => $patient->branch_id,
                'fee'            => $request->fee ?? 0,
                'paid_amount'    => $request->paid_amount ?? 0,
                'payment_method' => $request->payment_method ?? null,
                'status'         => 'completed',
            ]);

            // Insert Transaction (Safe auth check)
            DB::table('transactions')->insert([
                'invoice_id'    => $checkup->id,
                'bank_id'     => $request->payment_method,
                'payment_method'=> $paymentMethod,
                'p_id'       => $request->patient_id,
                'dr_id'      => $request->doctor_id,
                'amount'     => $request->paid_amount ?? 0,
                'type'       => '+',
                'b_id'       => $patient->branch_id,
                'entery_by'  => auth()->check() ? auth()->user()->id : null,
                'Remx'       => 'Checkup Fee',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert into branch_transactions
            // No need to insert into branches table, just update the balance (already done below)
            // Increment Branch Balance
            if ($request->payment_method == 1) {
                DB::table('branches')->where('id', $patient->branch_id)
                    ->increment('balance', $request->paid_amount ?? 0);
            } elseif ($request->payment_method >= 1) {
                DB::table('banks')->where('id', $request->payment_method)
                    ->increment('balance', $request->paid_amount ?? 0);
            }

            DB::commit();
            return redirect()->route('consultations.print', $checkup->id)->with('success', '✅ Checkup added successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            echo "<pre>"; print_r($e->getMessage()); echo "</pre>"; exit;
            return back()->with('error', '❌ Error saving checkup: ' . $e->getMessage());
        }
    }

    /**
     * 4️⃣ Edit form
     */
    public function edit($id)
    {
        $checkup  = Checkup::findOrFail($id);
        $patients = DB::table('patients')->select('id', 'name')->get();
        $doctors  = DB::table('doctors')
            ->select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))
            ->get();

        return view('consultations.edit', [
            'checkup'       => $checkup,
            'consultation'  => $checkup,
            'patients'      => $patients,
            'doctors'       => $doctors,
        ]);
    }

    /**
     * 5️⃣ Update checkup
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'doctor_id'      => 'required|exists:doctors,id',
            'fee'            => 'required|numeric|min:0',
            'paid_amount'    => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string',
        ]);

        DB::table('checkups')->where('id', $id)->update([
            'patient_id'     => $request->patient_id,
            'doctor_id'      => $request->doctor_id,
            'fee'            => $request->fee,
            'paid_amount'    => $request->paid_amount ?? 0,
            'payment_method' => $request->payment_method ?? null,
            'updated_at'     => now(),
        ]);

        return redirect()->route('checkups.index')->with('success', '✅ Checkup updated successfully.');
    }

    /**
     * 6️⃣ Delete checkup
     */
    public function destroy($id)
    {
        DB::table('checkups')->where('id', $id)->delete();
        return redirect()->route('checkups.index')->with('success', '🗑️ Checkup deleted successfully.');
    }

    /**
     * 7️⃣ Show detail
     */
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
                DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) as doctor_name"),
                'branches.name as branch_name'
            )
            ->where('checkups.id', $id)
            ->first();

        if (!$checkup) abort(404);

        return view('consultations.show', [
            'checkup'      => $checkup,
            'consultation' => $checkup,
        ]);
    }

    /**
     * 8️⃣ Ajax: Get fee by branch
     */
    public function getCheckupFee($patientId)
    {
        // Join patients with branches to get branch fee
            $data = DB::table('patients')
                ->leftJoin('branches', 'patients.branch_id', '=', 'branches.id')
                ->where('patients.id', $patientId)
                ->select('branches.fee')
                ->first();

            // If branch or fee is missing, return 0
            $fee = $data && $data->fee ? $data->fee : 0;

            return response()->json(['fee' => $fee]);
    }


    /**
     * 🔟 Patient History
     */
    public function history($patient_id)
    {
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
            ->orderBy('checkups.id', 'desc')
            ->get();

        return view('consultations.history', [
            'history'       => $history,
            'patient'       => $patient,
            'consultations' => $history,
        ]);
    }

    /**
     * 11️⃣ Print Checkup Slip
     */
    public function printSlip($id)
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
                'patients.age as patient_age',
                'patients.mr as patient_mr',

                DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) as doctor_name"),
                'branches.name as branch_name'
            )
            ->where('checkups.id', $id)
            ->first();

            //Branches Table show
            $branches = DB::table('branches')->get();



        if (!$checkup) abort(404, 'Checkup not found.');

        return view('consultations.print', [
            'checkup' => $checkup,
            'branches' => $branches,
        ]);
    }

    /**
     * 🛠 Helper: Get fee by branch
     */
    private function getFeeByBranch($branch_id)
    {
        $setting = DB::table('general_settings')->where('branch_id', $branch_id)->first();
        return $setting ? $setting->default_checkup_fee : 0;
    }
}
