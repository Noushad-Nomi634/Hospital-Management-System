<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors'; 

    protected $fillable = [
        'name',
        'email',
        'phone',
        'specialization',
        'branch_id',   // ✅ Doctor linked with Branch
    ];

    // ───────────────────────────────
    // Relationships
    // ───────────────────────────────

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function availabilities()
    {
        return $this->hasMany(DoctorAvailability::class);
    }

    public function performances()
    {
        return $this->hasMany(DoctorPerformance::class);
    }

    public function checkups()
    {
        return $this->hasMany(Checkup::class, 'doctor_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'doctor_id');
    }

    public function treatmentSessions()
    {
        return $this->hasMany(TreatmentSession::class, 'doctor_id');
    }

    public function completedSessions()
    {
        return $this->hasMany(SessionTime::class, 'completed_by_doctor_id');
    }
}