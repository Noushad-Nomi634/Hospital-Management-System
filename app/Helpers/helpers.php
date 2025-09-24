<?php
use App\Models\Doctor;

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
