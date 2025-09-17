<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
    use HasRoles, Notifiable;

    protected $guard_name = 'doctor';
    protected $table = 'doctors';

    protected $fillable = [
        'branch_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialization',
        'password',
        'cnic',
        'dob',
        'last_education',
        'document', // file path
        'picture',  // file path
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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

    // ───────────────────────────────
    // Accessors for full name
    // ───────────────────────────────
  public function getNameAttribute()
{
    return trim($this->first_name . ' ' . $this->last_name);
}

    }

