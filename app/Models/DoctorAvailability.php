<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAvailability extends Model
{
    protected $fillable = [
        'doctor_id',
        'date',
        'day_of_week',
        'start_time',
        'end_time',
        'is_leave',
    ];

    protected $casts = [
        'date' => 'date',
        'is_leave' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
