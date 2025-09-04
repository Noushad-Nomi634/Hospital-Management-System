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
            $checkups = Checkup::with('patient', 'doctor')->orderBy('date', 'desc')->get();
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
                'session_fee'     => 'required|numeric|min:0',
                'paid_amount'     => 'required|numeric|min:0',
                'diagnosis'       => 'nullable|string|max:255',
                'note'            => 'nullable|string',
                'sessions'        => 'nullable|array',
                'sessions.*.date' => 'nullable|date',
                'sessions.*.time' => 'nullable|date_format:H:i',
            ]);

            $checkup = Checkup::findOrFail($request->checkup_id);

            $sessionCount = $request->has('sessions') ? count($request->sessions) : 0;
            $totalFee     = $request->session_fee * $sessionCount;
            $paidAmount   = $request->paid_amount;
            $duesAmount   = $totalFee - $paidAmount;

            $session = TreatmentSession::create([
                'patient_id'    => $checkup->patient_id,
                'checkup_id'    => $request->checkup_id,
                'doctor_id'     => $request->doctor_id,
                'session_fee'   => $request->session_fee,
                'session_count' => $sessionCount,
                'paid_amount'   => $paidAmount,
                'dues_amount'   => $duesAmount,
                'diagnosis'     => $request->diagnosis,
                'note'          => $request->note,
            ]);

            // ✅ Session Times
            if ($request->has('sessions')) {
                foreach ($request->sessions as $time) {
                    if (!empty($time['date']) && !empty($time['time'])) {
                        $sessionDatetime = $time['date'] . ' ' . $time['time'];
                        SessionTime::create([
                            'treatment_session_id' => $session->id,
                            'session_datetime'     => $sessionDatetime,
                        ]);
                    }
                }
            }

            // ✅ Installment record
            if ($paidAmount > 0) {
                SessionInstallment::create([
                    'session_id'     => $session->id,
                    'amount'         => $paidAmount,
                    'payment_date'   => now(),
                    'payment_method' => 'cash',
                ]);
            }

            // ✅ Transaction table entry
            DB::table('transactions')->insert([
                'p_id'      => $checkup->patient_id,
                'dr_id'     => $request->doctor_id,
                'amount'    => $paidAmount,
                'type'      => '+',
                'b_id'      => $checkup->branch_id ?? 1,
                'entery_by' => auth()->user()->id,
                'Remx'      => 'Treatment Session Payment',
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);

            return redirect()->route('treatment-sessions.index')
                ->with('success', '✅ Treatment session created successfully with transaction.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to create session: ' . $e->getMessage());
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

            $sessionCount = $request->has('sessions') ? count($request->sessions) : 0;
            $totalFee     = $request->session_fee * $sessionCount;
            $paidAmount   = $request->paid_amount;
            $duesAmount   = $totalFee - $paidAmount;

            $session->update([
                'patient_id'    => $checkup->patient_id,
                'checkup_id'    => $request->checkup_id,
                'doctor_id'     => $request->doctor_id,
                'session_fee'   => $request->session_fee,
                'session_count' => $sessionCount,
                'paid_amount'   => $paidAmount,
                'dues_amount'   => $duesAmount,
                'diagnosis'     => $request->diagnosis,
                'note'          => $request->note,
            ]);

            // ✅ Update session times
            if ($request->has('sessions')) {
                $session->sessionTimes()->delete();
                foreach ($request->sessions as $time) {
                    if (!empty($time['date']) && !empty($time['time'])) {
                        $sessionDatetime = $time['date'] . ' ' . $time['time'];
                        SessionTime::create([
                            'treatment_session_id' => $session->id,
                            'session_datetime'     => $sessionDatetime,
                        ]);
                    }
                }
            }

            // ✅ Update transaction
            DB::table('transactions')->where([
                ['p_id', '=', $session->patient_id],
                ['dr_id', '=', $request->doctor_id],
                ['Remx', '=', 'Treatment Session Payment']
            ])->update([
                'amount'     => $paidAmount,
                'updated_at' => now(),
            ]);

            return redirect()->route('treatment-sessions.index')
                ->with('success', '✅ Treatment session updated successfully with transaction.');
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
                'is_completed'            => true,
                'completed_by_doctor_id'  => $request->doctor_id,
                'work_done'               => $request->work_done ?? null,
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

            // ✅ Delete session times
            $session->sessionTimes()->delete();

            // ✅ Delete installments
            $session->installments()->delete();

            // ✅ Delete transaction
            DB::table('transactions')->where([
                ['p_id', '=', $session->patient_id],
                ['dr_id', '=', $session->doctor_id],
                ['Remx', '=', 'Treatment Session Payment']
            ])->delete();

            // ✅ Delete session
            $session->delete();

            return redirect()->route('treatment-sessions.index')
                ->with('success', '🗑️ Treatment session and transaction deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Failed to delete session: ' . $e->getMessage());
        }
    }
}
