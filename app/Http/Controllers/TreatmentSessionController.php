<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Checkup;
use App\Models\SessionTime;
use App\Models\TreatmentSession;
use App\Models\SessionInstallment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TreatmentSessionController extends Controller
{
    public function index()
    {
        try {
            $sessions = TreatmentSession::with(['doctor', 'patient', 'sessionTimes', 'installments', 'checkup'])
                ->orderByDesc('created_at')
                ->get();

            $doctors = Doctor::all();

            return view('treatment_sessions.index', compact('sessions', 'doctors'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to load sessions: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // ✅ FIX: "date" column hata ke created_at use kiya
            $checkups = Checkup::with('patient', 'doctor')->orderBy('created_at', 'desc')->get();
            $doctors  = Doctor::all();
            $patients = $checkups->pluck('patient')->unique('id');

            return view('treatment_sessions.create', compact('checkups', 'doctors', 'patients'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to load create form: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'checkup_id'      => 'required|exists:checkups,id',
                'doctor_id'       => 'required|exists:doctors,id',
                'ss_dr'           => 'required|exists:doctors,id',
                'diagnosis'       => 'nullable|string|max:255',
                'note'            => 'nullable|string',
                'sessions'        => 'nullable|array',
                'sessions.*.date' => 'nullable|date',
                'sessions.*.time' => 'nullable|date_format:H:i',
            ]);



            $checkup = Checkup::findOrFail($request->checkup_id);

            //$sessionCount = $request->has('sessions') ? count($request->sessions) : 1;
            // $totalFee     = $request->session_fee * $sessionCount;
            // $paidAmount   = (float) $request->paid_amount;
            // $duesAmount   = $totalFee - $paidAmount;

            $session = TreatmentSession::create([
                'patient_id'    => $checkup->patient_id,
                'branch_id'     => $checkup->doctor->branch_id ?? 1,
                'checkup_id'    => $request->checkup_id,
                'doctor_id'     => $request->doctor_id,
                'ss_dr_id'      => $request->ss_dr,
                'diagnosis'     => $request->diagnosis,
                'note'          => $request->note,
                'con_status'    => 0,
                // 'session_date'  => $request->sessions[0]['date'] ?? now()->toDateString(),
            ]);


            // Mark checkup completed
            Checkup::where('id', $request->checkup_id)->update(['checkup_status' => 1]);

            // Add session times
            // if ($request->has('sessions')) {
            //     foreach ($request->sessions as $time) {
            //         if (!empty($time['date']) && !empty($time['time'])) {
            //             SessionTime::create([
            //                 'treatment_session_id' => $session->id,
            //                 'session_datetime'     => $time['date'].' '.$time['time'],
            //             ]);
            //         }
            //     }
            // }

            // Add installment
            // if ($paidAmount > 0) {
            //     SessionInstallment::create([
            //         'session_id'     => $session->id,
            //         'amount'         => $paidAmount,
            //         'payment_date'   => now(),
            //         'payment_method' => 'cash',
            //     ]);
            // }

            // Add transaction
            // DB::table('transactions')->insert([
            //     'p_id'      => $checkup->patient_id,
            //     'dr_id'     => $request->doctor_id,
            //     'amount'    => $paidAmount,
            //     'type'      => '+',
            //     'b_id'      => $checkup->doctor->branch_id ?? 1,
            //     'entery_by' => auth()->user()->id,
            //     'Remx'      => 'Treatment Session Payment',
            //     'created_at'=> now(),
            //     'updated_at'=> now(),
            // ]);

            return redirect()->route('treatment-sessions.index')
                ->with('success', '✅ Treatment session created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to create session: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $session = TreatmentSession::with(['doctor', 'patient', 'sessionTimes', 'installments', 'checkup'])
                ->findOrFail($id);

            $doctors  = Doctor::all();
            // ✅ FIX: "date" column hata ke created_at use kiya
            $checkups = Checkup::with('patient', 'doctor')->orderBy('created_at', 'desc')->get();
            $patients = $checkups->pluck('patient')->unique('id');

            return view('treatment_sessions.edit', compact('session', 'doctors', 'checkups', 'patients'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to load edit form: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'checkup_id'      => 'required|exists:checkups,id',
                'doctor_id'       => 'required|exists:doctors,id',
                'session_fee'     => 'required|numeric|min:0',
                'paid_amount'     => 'required|numeric|min:0',
                'diagnosis'       => 'nullable|string|max:255',
                'note'            => 'nullable|string',
                'sessions'        => 'nullable|array',
                'sessions.*.date' => 'nullable|date',
                'sessions.*.time' => 'nullable|date_format:H:i',
            ]);

            $session = TreatmentSession::findOrFail($id);
            $checkup = Checkup::findOrFail($request->checkup_id);

            $sessionCount = $request->has('sessions') ? count($request->sessions) : $session->session_count;
            $totalFee     = $request->session_fee * $sessionCount;
            $paidAmount   = (float) $request->paid_amount;
            $duesAmount   = $totalFee - $paidAmount;

            $session->update([
                'patient_id'    => $checkup->patient_id,
                'branch_id'     => $checkup->doctor->branch_id ?? 1,
                'checkup_id'    => $request->checkup_id,
                'doctor_id'     => $request->doctor_id,
                'session_fee'   => $request->session_fee,
                'session_count' => $sessionCount,
                'paid_amount'   => $paidAmount,
                'dues_amount'   => $duesAmount,
                'diagnosis'     => $request->diagnosis,
                'note'          => $request->note,
                'session_date'  => $request->sessions[0]['date'] ?? $session->session_date,
            ]);

            // Update session times
            if ($request->has('sessions')) {
                $session->sessionTimes()->delete();
                foreach ($request->sessions as $time) {
                    if (!empty($time['date']) && !empty($time['time'])) {
                        SessionTime::create([
                            'treatment_session_id' => $session->id,
                            'session_datetime'     => $time['date'].' '.$time['time'],
                        ]);
                    }
                }
            }

            // Update installments
            $session->installments()->delete();
            if ($paidAmount > 0) {
                SessionInstallment::create([
                    'session_id'     => $session->id,
                    'amount'         => $paidAmount,
                    'payment_date'   => now(),
                    'payment_method' => 'cash',
                ]);
            }

            // Update transaction
            DB::table('transactions')->where([
                ['p_id', '=', $session->patient_id],
                ['dr_id', '=', $request->doctor_id],
                ['Remx', '=', 'Treatment Session Payment']
            ])->update([
                'amount'     => $paidAmount,
                'b_id'       => $checkup->doctor->branch_id ?? 1,
                'updated_at' => now(),
            ]);

            return redirect()->route('treatment-sessions.index')
                ->with('success', '✅ Treatment session updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to update session: ' . $e->getMessage());
        }
    }

    public function markCompleted(Request $request, $id)
    {
        try {
            $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'work_done' => 'nullable|string|max:255',
            ]);

            $sessionTime = SessionTime::findOrFail($id);

            $sessionTime->update([
                'is_completed'           => true,
                'completed_by_doctor_id' => $request->doctor_id,
                'work_done'              => $request->work_done ?? null,
            ]);

            $sessionTime->treatmentSession->refreshStatus();

            return redirect()->back()->with('success', '✅ Session marked completed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to mark session as completed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $session = TreatmentSession::findOrFail($id);

            $session->sessionTimes()->delete();
            $session->installments()->delete();

            DB::table('transactions')->where([
                ['p_id', '=', $session->patient_id],
                ['dr_id', '=', $session->doctor_id],
                ['Remx', '=', 'Treatment Session Payment']
            ])->delete();

            $session->delete();

            return redirect()->route('treatment-sessions.index')
                ->with('success', '🗑️ Treatment session and transaction deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to delete session: ' . $e->getMessage());
        }
    }

    // Optional methods for ➕ icon session entry
    public function addEntryForm($session_id)
    {
        $session = TreatmentSession::findOrFail($session_id);
        return view('treatment_sessions.add_entry', compact('session'));
    }

    public function storeEntry(Request $request, $session_id)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        SessionTime::create([
            'treatment_session_id' => $session_id,
            'session_datetime'     => $request->date.' '.$request->time,
        ]);

        return redirect()->back()->with('success', '✅ Session entry added successfully.');
    }
}
