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


Auth::routes();

// Clear all cache route
Route::get('/clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return "All caches cleared successfully!";
})->name('clear');

// For admin only
Route::prefix('admin')->middleware(['auth:web', 'role:admin'])->name('admin.')->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    // Add more admin-specific routes here
});

// For Doctores only
Route::prefix('doctor')->middleware(['auth:doctor', 'role:doctor'])->name('doctor.')->group(function () {
    Route::get('dashboard', [DoctorDashboardController::class, 'index'])->name('dashboard');
    // Add more admin-specific routes here
});

// Doctor Dashboard (for logged-in doctors only)


//For receptionist only
Route::middleware(['auth', 'role:Receptionist'])->group(function () {
    //Route::get('/receptionist/dashboard', [DashboardController::class, 'receptionistIndex']);
});


// For all authenticated users
Route::middleware(['auth'])->group(function () {
    //patients
    Route::get('/patients', [PatientController::class, 'index']);
    Route::get('/patients/create', [PatientController::class, 'create']);
    Route::post('/patients', [PatientController::class, 'store']);
    Route::get('/patients/{id}/edit', [PatientController::class, 'edit']);
    Route::put('/patients/{id}', [PatientController::class, 'update'])->name('patients.update');
    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('patients.card');
    Route::delete('/patients/{id}', [PatientController::class, 'destroy'])->name('patients.destroy');
    Route::post('/patients', [PatientController::class, 'store'])->name('patients.store');
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


Route::get('/treatment-sessions', [TreatmentSessionController::class, 'index'])->name('treatment-sessions.index');
Route::get('/treatment-sessions/create', [TreatmentSessionController::class, 'create'])->name('treatment-sessions.create');
Route::post('/treatment-sessions', [TreatmentSessionController::class, 'store'])->name('treatment-sessions.store');

// Mark session complete (manual doctor name)
Route::post('/sessions/{id}/complete', [TreatmentSessionController::class, 'markCompleted'])->name('sessions.complete');

Route::get('/treatment-sessions/{id}/edit', [TreatmentSessionController::class, 'edit'])->name('treatment-sessions.edit');
Route::put('/treatment-sessions/{id}', [TreatmentSessionController::class, 'update'])->name('treatment-sessions.update');
Route::delete('/treatment-sessions/{id}', [TreatmentSessionController::class, 'destroy'])->name('treatment-sessions.destroy');
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

//Receptionist Dashboard
Route::get('/receptionist-dashboard', [ReceptionistDashboardController::class, 'index'])
    ->name('receptionist.dashboard')
    ->middleware('auth');


 Route::get('/treatment-sessions/sessions/{session_id}', [TreatmentSessionController::class, 'showOngoingSessions'])
    ->name('treatment-sessions.sessions');
// Enrollment Update Route
Route::put('/treatment-sessions/{id}/enrollment-update', [TreatmentSessionController::class, 'enrollmentUpdate'])
    ->name('treatment-sessions.enrollmentUpdate');
    
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('{any}', [HomeController::class, 'root'])->where('any', '.*');
