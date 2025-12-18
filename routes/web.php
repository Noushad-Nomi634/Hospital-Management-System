<?php


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Doctors\DoctorDashboardController;
use App\Http\Controllers\Doctors\DoctorController;

use App\Http\Controllers\PatientController;
use App\Http\Controllers\CheckupController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreatmentSessionController;
use App\Http\Controllers\SessionInstallmentController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\SessionController;
//use App\Http\Controllers\SessionDetailController;
use App\Http\Controllers\PaymentOutstandingController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeeSalaryController;
use App\Http\Controllers\SessionTimeController;
use App\Http\Controllers\DoctorAvailabilityController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReceptionistDashboardController;
use App\Http\Controllers\PaymentTransactionController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\BankLedgerController;
use App\Http\Controllers\IncomeReportController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\ExpenseTypeController;
use App\Http\Controllers\ExpenseController;





Auth::routes();

// Clear all cache route
Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "All caches cleared successfully!";
})->name('clear');


// ✅ Manager Dashboard Routes
Route::prefix('manager')
    ->middleware(['auth:web', 'role:manager'])
    ->name('manager.')
    ->group(function () {
        Route::get('dashboard', [App\Http\Controllers\Manager\ManagerDashboardController::class, 'index'])
            ->name('dashboard');
    });



// For admin only
Route::prefix('admin')->middleware(['auth:web', 'role:admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');





    // Add more admin-specific routes here
});

// For Doctores Dashboard only
Route::prefix('doctor')->middleware(['auth:doctor', 'role:doctor'])->name('doctor.')->group(function () {

    // Dashboard
    Route::get('dashboard', [DoctorDashboardController::class, 'index'])
        ->middleware('permission:view_dashboard')
        ->name('dashboard');

// Feedback (view-only)
    Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->middleware('permission:view feedback');

       //feedback List routes
        Route::get('/feedback/doctor-list', [FeedbackController::class, 'doctorList'])
    ->middleware('permission:view feedback')
    ->name('feedback.doctor-list');

Route::get('/feedback/patient-list', [FeedbackController::class, 'patientList'])
    ->middleware('permission:view feedback')
    ->name('feedback.patient-list');



    // Appointments
  Route::get('appointments', [CheckupController::class, 'index'])
       ->middleware('permission:manage_appointments')
       ->name('appointments.index');

    // Sessions
    Route::get('sessions', [SessionController::class, 'index'])
        ->middleware('permission:manage_sessions')
        ->name('sessions.index');

         // Ongoing Sessions Route
    Route::get('/ongoing-sessions/{status}', [TreatmentSessionController::class, 'OngoingSessionsOnly'])->name('ongoing-sessions');
    Route::get('/session-details/{id}', [TreatmentSessionController::class, 'sessionDetails'])->name('session-details');

    // Completed Sessions Route
    Route::post('/sessions/mark-completed', [SessionTimeController::class, 'updateSectionCompleted'])->name('sessions.mark-completed');




    // Feedback (view only)
    Route::get('feedback', [FeedbackController::class, 'index'])
        ->middleware('permission:view feedback')
        ->name('feedback.index');

});


// For all authenticated users
Route::middleware(['auth', 'role:admin|receptionist|manager'])->group(function () {
    //patients
    Route::get('/patients', [PatientController::class, 'index']);
    Route::get('/patients/create', [PatientController::class, 'create']);
    Route::post('/patients', [PatientController::class, 'store']);
    Route::get('/patients/{id}/edit', [PatientController::class, 'edit']);
    Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.card');
    Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');


    //Update Satisfactory session Route
    Route::get('/doctor-consultations/{id}/status-view', [TreatmentSessionController::class, 'viewssStatus'])->name('doctor-consultations.status-view');
    Route::post('/doctor-consultations/update-status', [TreatmentSessionController::class, 'updateStatus'])->name('doctor-consultations.update-status');

     //Doctor Consultation Route
    Route::get('/doctor-consultations/{status}', [TreatmentSessionController::class, 'index'])->name('doctor-consultations.index');

    // Enrollment Session Route
    Route::get('/enrollments/{status}', [TreatmentSessionController::class, 'showEnrollments'])->name('enrollments');
    Route::get('/treatment-sessions/sessions/{session_id}', [TreatmentSessionController::class, 'showOngoingSessions'])
    ->name('treatment-sessions.sessions');
    // Enrollment Update Route
    Route::put('/treatment-sessions/{id}/enrollment-update', [TreatmentSessionController::class, 'enrollmentUpdate'])
        ->name('treatment-sessions.enrollmentUpdate');

    // Ongoing Sessions Route
    Route::get('/ongoing-sessions/{status}', [TreatmentSessionController::class, 'OngoingSessionsOnly'])->name('ongoing-sessions');
    Route::get('/session-details/{id}', [TreatmentSessionController::class, 'sessionDetails'])->name('session-details');

    // Completed Sessions Route
    Route::post('/sessions/mark-completed', [SessionTimeController::class, 'updateSectionCompleted'])->name('sessions.mark-completed');


    //Accounts Payments Routes
    Route::get('/payments/outstanding-invoices', [PaymentOutstandingController::class, 'index'])->name('accounts.payments');
    Route::get('//payments/completed-invoices', [PaymentOutstandingController::class, 'completedInvoices'])->name('accounts.completed-invoices');

    // Invoice Ledger Route
    Route::get('/invoice-ledger/{session_id}', [PaymentOutstandingController::class, 'invoiceLedger'])->name('invoice.ledger');

// Patient Ledger2
Route::get('/patient-invoice-ledger/{session_id}', [PaymentOutstandingController::class, 'invoiceLedgerr'])
    ->name('invoice.ledgerr');

    // New custom print route
Route::get('/consultations/print-custom/{id}', [CheckupController::class, 'printSlipCustom'])->name('consultations.print.custom');

    // Process Return Payment (Refund)
Route::post('/payments/return', [PaymentOutstandingController::class, 'returnPayment'])->name('payments.returnPayment');


    // Add payment to invoice
    Route::post('/invoice-ledger/add-payment', [PaymentOutstandingController::class, 'addPayment'])->name('invoice.add-payment');


    //Return Payments Route
    // page that lists returned payments (you already have)
    Route::get('/payments/return-payments', [PaymentOutstandingController::class, 'returnPayments'])
        ->name('payments.return-payments');

     // Checkup Invoice & Refund
Route::get('/checkups/invoice/{checkup_id}', [PaymentOutstandingController::class, 'invoiceLedgerCheckup'])->name('checkups.invoice');
Route::post('/checkups/refund', [PaymentOutstandingController::class, 'returnCheckupPayment'])->name('checkups.refund');

// Payment transfer page
Route::get('/transfer', [PaymentTransactionController::class, 'index'])->name('transfer.index');

// Transfer store karne ke liye
Route::post('/transfer', [PaymentTransactionController::class, 'store'])->name('transfer.store');

// AJAX routes for balances
Route::get('/transfer/get-bank-balance/{id}', [PaymentTransactionController::class, 'getBankBalance'])->name('transfer.getBankBalance');
Route::get('/transfer/get-branch-balance/{id}', [PaymentTransactionController::class, 'getBranchBalance'])->name('transfer.getBranchBalance');

//Branch Ledger
Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index');
Route::get('/ledger/filter', [LedgerController::class, 'filter'])->name('ledger.filter');

//Bank Ledger
Route::get('bank-ledger', [BankLedgerController::class, 'index'])->name('bankledger.index');
Route::get('bank-ledger/filter', [BankLedgerController::class, 'filter'])->name('bankledger.filter');

// Income Report
Route::get('income-report', [IncomeReportController::class, 'index'])->name('income.report');

// Feedback List Pages (branch-wise)
Route::get('/feedback/doctor-list', [FeedbackController::class, 'doctorFeedbackList'])->name('feedback.doctor-list');
Route::get('/feedback/patient-list', [FeedbackController::class, 'patientFeedbackList'])->name('feedback.patient-list');

// Doctor Feedback
Route::get('/feedback/doctor/{sessionId}', [FeedbackController::class, 'doctorFeedbackForm'])->name('feedback.doctor');
Route::post('/feedback/doctor-submit', [FeedbackController::class, 'doctorFeedbackSubmit'])->name('feedback.doctor-submit');

// Patient Feedback
Route::get('/feedback/patient/{session_id}', [FeedbackController::class, 'patientFeedbackForm']);
Route::post('/feedback/patient-submit', [FeedbackController::class, 'patientFeedbackSubmit']);

    // AJAX: search patients (used by your patient search input)
    Route::get('/payments/search-patient', [PaymentOutstandingController::class, 'searchPatient'])
        ->name('payments.search-patient');

    // AJAX: fetch payments HTML partial for a patient (used when clicking View Payments)
    Route::get('/payments/fetch-patient-payments', [PaymentOutstandingController::class, 'fetchPatientPayments'])
        ->name('payments.fetch-patient-payments');

});




Route::post('/sessions/{id}/complete', [SessionTimeController::class, 'markCompleted'])->name('sessions.complete');
Route::delete('/sessions/{id}', [SessionTimeController::class, 'destroy'])->name('sessions.destroy');

// Employees
Route::get('employees', [EmployeeController::class, 'index']);
Route::get('employees/create', [EmployeeController::class, 'create']);
Route::post('employees', [EmployeeController::class, 'store']);

// Salaries
Route::get('salaries', [EmployeeSalaryController::class, 'index']);
Route::get('salaries/create', [EmployeeSalaryController::class, 'create']);
Route::post('salaries', [EmployeeSalaryController::class, 'store']);
Route::post('/salaries/{id}/pay', [EmployeeSalaryController::class, 'markAsPaid'])->name('salaries.pay');
// For modal-based salary mark as paid with adjustments
Route::post('/salaries/mark-paid', [EmployeeSalaryController::class, 'markPaidWithAdjustment'])->name('salaries.markPaid');





Route::get('/payments/outstandings', [\App\Http\Controllers\PaymentOutstandingController::class, 'index']);


// Show form to add a new session datetime (for ➕ icon-- abx )
Route::get('/treatment-sessions/{session_id}/add-entry', [TreatmentSessionController::class, 'addEntryForm'])->name('treatment-sessions.add-entry');

// Handle form POST to store new session datetime
Route::post('/treatment-sessions/{session_id}/store-entry', [TreatmentSessionController::class, 'storeEntry'])->name('treatment-sessions.store-entry');


//Route::get('/session-details/create/{session}', [SessionDetailController::class, 'create'])->name('session-details.create');
Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');


Route::get('/settings/general', [GeneralSettingController::class, 'index'])->name('settings.index');
Route::post('/settings/general', [GeneralSettingController::class, 'update'])->name('settings.update');


Route::get('/checkups/print/{id}', [CheckupController::class, 'printSlip'])->name('checkups.print');


Route::get('general-settings', [GeneralSettingController::class, 'index'])->name('general-settings.index');
Route::get('general-settings/{id}/edit', [GeneralSettingController::class, 'edit'])->name('general-settings.edit');
Route::put('general-settings/{id}/update', [GeneralSettingController::class, 'update'])->name('general-settings.update');


Route::get('/installments/create/{session_id}', [SessionInstallmentController::class, 'create'])->name('installments.create');
Route::post('/installments/store', [SessionInstallmentController::class, 'store'])->name('installments.store');



Route::get('/treatment-sessions/create', [TreatmentSessionController::class, 'create'])->name('treatment-sessions.create');
Route::post('/treatment-sessions', [TreatmentSessionController::class, 'store'])->name('treatment-sessions.store');

// Mark session complete (manual doctor name)
Route::post('/sessions/{id}/complete', [TreatmentSessionController::class, 'markCompleted'])->name('sessions.complete');

Route::get('/treatment-sessions/{id}/edit', [TreatmentSessionController::class, 'edit'])->name('treatment-sessions.edit');
Route::put('/treatment-sessions/{id}', [TreatmentSessionController::class, 'update'])->name('treatment-sessions.update');
Route::delete('/treatment-sessions/{id}', [TreatmentSessionController::class, 'destroy'])->name('treatment-sessions.destroy');

// ✅ Treatment Session Summary
Route::get('/treatment-sessions/summary', [TreatmentSessionController::class, 'sessionSummary'])
    ->name('treatment-sessions.summary');

Route::get('/treatment-sessions/{id}', [TreatmentSessionController::class, 'show'])->name('treatment-sessions.show');




Route::get('/checkups', [CheckupController::class, 'index'])->name('checkups.index');
Route::get('/checkups/create', [CheckupController::class, 'create']);
// Checkup ID ke sath session create karne ka route
Route::get('/treatment-sessions/create/{checkup}', [TreatmentSessionController::class, 'createWithCheckup'])
    ->name('treatment-sessions.createWithCheckup');


Route::get('/patients/{id}/checkup-fee', [CheckupController::class, 'getCheckupFee']);


// Consultations Routes
Route::get('/consultations', [CheckupController::class, 'index'])->name('consultations.index');
Route::get('/consultations/create', [CheckupController::class, 'create'])->name('consultations.create');
Route::post('/consultations', [CheckupController::class, 'store'])->name('consultations.store');
Route::get('/consultations/{id}/edit', [CheckupController::class, 'edit'])->name('consultations.edit');
Route::put('/consultations/{id}', [CheckupController::class, 'update'])->name('consultations.update');
Route::delete('/consultations/{id}', [CheckupController::class, 'destroy'])->name('consultations.destroy');
Route::get('/consultations/{id}', [CheckupController::class, 'show'])->name('consultations.show');

// ✅ Print consultation slip
Route::get('/consultations/{id}/print', [CheckupController::class, 'printSlip'])->name('consultations.print');

// Patient History
Route::get('/consultations/history/{patient_id}', [CheckupController::class, 'history'])->name('consultations.history');





// Doctor CRUD
Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/create', [DoctorController::class, 'create'])->name('doctors.create');
Route::post('/doctors/store', [DoctorController::class, 'store'])->name('doctors.store');
Route::get('/doctors/{id}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('doctors.update');
Route::get('/doctors/{id}', [DoctorController::class, 'show'])->name('doctors.show');


Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->name('doctors.destroy');


Route::get('/doctors/{doctor}/availability', [DoctorAvailabilityController::class, 'index'])
    ->name('doctors.availability.index');

Route::post('/doctors/{doctor}/availability/store', [DoctorAvailabilityController::class, 'store'])
    ->name('doctors.availability.store');

Route::post('/doctors/{doctor}/availability/generate-next-month', [DoctorAvailabilityController::class, 'generateNextMonth'])
    ->name('doctors.availability.generateNextMonth');

Route::delete('/doctors/{doctor}/availability/delete-month', [DoctorAvailabilityController::class, 'deleteMonth'])
    ->name('doctors.availability.deleteMonth');


    //Branches
Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');
Route::get('/branches/create', [BranchController::class, 'create'])->name('branches.create');
Route::post('/branches/store', [BranchController::class, 'store'])->name('branches.store');
Route::get('/branches/edit/{id}', [BranchController::class, 'edit'])->name('branches.edit');
Route::put('/branches/update/{id}', [BranchController::class, 'update'])->name('branches.update');
Route::delete('/branches/delete/{id}', [BranchController::class, 'destroy'])->name('branches.destroy');

//Bank
Route::get('/banks', [BankController::class, 'index'])->name('banks.index');
Route::get('/banks/create', [BankController::class, 'create'])->name('banks.create');
Route::post('/banks', [BankController::class, 'store'])->name('banks.store');
Route::get('/banks/{id}', [BankController::class, 'show'])->name('banks.show');
Route::get('/banks/{id}/edit', [BankController::class, 'edit'])->name('banks.edit');
Route::put('/banks/{id}', [BankController::class, 'update'])->name('banks.update');
Route::delete('/banks/{id}', [BankController::class, 'destroy'])->name('banks.destroy');


//users
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/create', [UserController::class, 'create'])->name('users.create');
Route::post('users/store', [UserController::class, 'store'])->name('users.store');
Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
Route::post('users/update/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');

// For admin and receptionist
Route::middleware(['role:receptionist'])->group(function () {
  Route::get('/receptionist-dashboard', [ReceptionistDashboardController::class, 'index'])->name('receptionist.dashboard');

    // Appointments Module
    Route::get('/appointments', [App\Http\Controllers\AppointmentController::class, 'index'])->middleware('permission:view appointments');
    Route::get('/appointments/create', [App\Http\Controllers\AppointmentController::class, 'create'])->middleware('permission:create appointments');
    Route::post('/appointments/store', [App\Http\Controllers\AppointmentController::class, 'store'])->middleware('permission:create appointments');

    // Consultation (view only)
    Route::get('/consultations', [App\Http\Controllers\ConsultationController::class, 'index'])->middleware('permission:view consultation');

    // Enrollment
    Route::get('/enrollments', [App\Http\Controllers\EnrollmentController::class, 'index'])->middleware('permission:view enrollment');
    Route::get('/enrollments/create', [App\Http\Controllers\EnrollmentController::class, 'create'])->middleware('permission:create enrollment');

    // Feedback (view-only)
    Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->middleware('permission:view feedback');

    // Payments
    Route::get('/payments', [App\Http\Controllers\PaymentController::class, 'index'])->middleware('permission:view payments');
    Route::get('/payments/create', [App\Http\Controllers\PaymentController::class, 'create'])->middleware('permission:create payments');
});


// EXPENSE TYPES
Route::get('/expense-types', [ExpenseTypeController::class, 'index'])->name('expense.types');
Route::post('/expense-types/store', [ExpenseTypeController::class, 'store'])->name('expense.types.store');

// EXPENSES
Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
Route::post('/expenses/store', [ExpenseController::class, 'store'])->name('expenses.store');
//testing





Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('{any}', [HomeController::class, 'root'])->where('any', '.*');
