<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Doctor extends  Authenticatable
{
    use HasRoles;

    protected $guard_name = 'web';
    protected $table = 'doctors';
    use Notifiable;

    protected $fillable = [
            'name', 'email', 'phone', 'specialization', 'password','branch_id',
        ];

    protected $hidden = [
        'password', 'remember_token',
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
