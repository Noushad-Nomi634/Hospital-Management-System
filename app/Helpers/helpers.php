<?php
use App\Models\Doctor;
use App\Models\Patient;
<<<<<<< Updated upstream
use Carbon\Carbon;
=======
>>>>>>> Stashed changes

if (! function_exists('example_helper')) {
    function example_helper()
    {
        return 'This is an example helper function.';
    }
}

function doctor_get_name($id)
{
    $doctor = Doctor::find($id);
     return $doctor ? $doctor->name : 'Unknown Doctor';
}

function patient_get_name($id)
{
    $patient =Patient::find($id);
    return $patient ? $patient->name : 'Unknown Patient';
}

function patient_get_mr($id)
{
    $patient =Patient::find($id);
    return $patient ? $patient->mr : 'Unknown MR';
}

function bank_get_name($id)
{
    if (!$id || $id == '0') {
        return 'Cash';
    } else {
        $bank = DB::table('banks')->where('id', $id)->first();
        $name =  $bank->bank_name . ' | (' . $bank->account_no . ') | ' . $bank->account_title;
        return $name ? $name : 'Unknown Bank';
    }
}

function format_date($date)
{
    return Carbon::parse($date)->format('d/m/Y - h:i A');
}
