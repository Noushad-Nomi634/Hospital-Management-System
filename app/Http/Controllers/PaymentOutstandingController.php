<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\TreatmentSession;
use App\Models\Transaction;
use App\Models\Bank;
use Illuminate\Support\Facades\DB;


class PaymentOutstandingController extends Controller
{
      public function index()
    {
        // Sirf woh sessions jinka payment remaining ho
    //    $outstandings = TreatmentSession::with(['patient', 'installments'])
    //         ->get()
    //         ->filter(function ($session) {
    //             return $session->remainingAmount() > 0;
    //         });
        $outstandings = TreatmentSession:: get()->where('dues_amount', '>', 0);
        return view('payments.outstandings', compact('outstandings'));
    }

    public function completedInvoices()
    {
        $outstandings = TreatmentSession:: get()->where('dues_amount', '=', 0);
        return view('payments.outstandings', compact('outstandings'));
    }

    public function invoiceLedger($session_id)
    {
        $session = TreatmentSession::with(['patient', 'transactions' => function($query) {
            $query->where('payment_type', 2); // 'sessions' ke jagah 2 use kiya hai
        }])->findOrFail($session_id);

        $transactions = $session->transactions;

        $total_amount = $session->session_fee; // session total
        $paid_amount = $transactions->sum('amount');
        $remaining_amount = $total_amount - $paid_amount;

        $banks = Bank::all();

        return view('payments.invoice_ledger', compact('session', 'transactions', 'total_amount', 'paid_amount', 'remaining_amount', 'banks'));
    }

    public function addPayment(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
            'session_id' => 'required|exists:treatment_sessions,id',
            'amount' => 'required|numeric|min:1',
            'remark' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:100',
            ]);

            $session = TreatmentSession::findOrFail($request->session_id);
            $remaining = $session->remainingAmount();

            if ($request->amount > $remaining) {
                return redirect()->back()->with('error', 'Payment amount exceeds remaining balance.');
            }

            // Transaction create karna

                handleGeneralTransaction(
                branch_id: $session->branch_id,
                bank_id: $request->payment_method,
                patient_id: $session->patient_id,
                doctor_id: $session->doctor_id,
                type: '+',
                amount: $request->amount ?? 0,
                note: $request->remark ?? 'Payment for Treatment Session #' . $session->id,
                invoice_id: $request->session_id,
                payment_type: 2,
                entry_by: auth()->id()
            );


            // TreatmentSession ka paid aur dues update karna
            $session->paid_amount += $request->amount;
            $session->dues_amount = max(0, $session->session_fee - $session->paid_amount);

            // If dues become 0, mark status as paid
            if ($session->dues_amount == 0) {
                $session->payment_status = 'paid';
            }

            $session->save();

            DB::commit();
            return redirect()->back()->with('success', 'Payment added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Add Payment error: ' . $e->getMessage());
            info(['Add Payment error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Unable to add payment. Please try again.');
        }
    }

    public function returnPayments()
    {
        $returnedPayments = Transaction::where('payment_type', 3) // Assuming '3' indicates returned payments
            ->with(['patient', 'bank'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('payments.search_patient', compact('returnedPayments'));
    }

    // AJAX endpoint used by patient search input
    public function searchPatient(Request $request)
    {
        $q = $request->get('q', '');

        $patients = Patient::query()
            ->where('name', 'like', "%{$q}%")
            ->orWhere('mr', 'like', "%{$q}%")
            ->limit(20)
            ->get()
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'mr' => $p->mr,
                    'name' => $p->name,
                    'phone' => $p->phone,
                    // eager counts or calculated values
                    'total_appointments' => $p->checkups()->count(),
                    'total_sessions' => $p->enrollments()->count(),
                ];
            });

        return response()->json(['data' => $patients]);
    }

    // AJAX endpoint to return the payments table partial HTML
    public function fetchPatientPayments(Request $request)
    {
        $patientId = $request->get('id');
        $payments = Transaction::where('patient_id', $patientId)
            ->orderBy('created_at', 'desc')
            ->get();

        // return a blade partial (resources/views/payments/_payments_table.blade.php)
        $html = view('payments._payments_table', compact('payments'))->render();

        return response()->json(['html' => $html]);
    }
}
