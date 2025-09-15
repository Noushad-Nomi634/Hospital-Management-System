<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // ───────────── Table Configuration ─────────────
    protected $table = 'transactions'; // Correct table name

    protected $fillable = [
        'p_id',        // Patient ID
        'dr_id',       // Doctor ID
        'amount',      // Payment amount
        'type',        // '+' for income, '-' for expense
        'b_id',        // Branch ID
        'entery_by',   // User who entered the transaction
        'Remx',        // Remark e.g. "Checkup Fee", "Treatment Session Payment"
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ───────────── Relationships ─────────────

    // Patient who made the transaction
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'p_id');
    }

    // Doctor associated with the transaction
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'dr_id');
    }

    // Branch where transaction occurred
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'b_id');
    }

    // User who entered the transaction
    public function enteredByUser()
    {
        return $this->belongsTo(User::class, 'entery_by');
    }

    // ───────────── Helper Methods ─────────────

    // Check if transaction is income
    public function isIncome(): bool
    {
        return $this->type === '+';
    }

    // Check if transaction is expense
    public function isExpense(): bool
    {
        return $this->type === '-';
    }

    // Check if transaction was cash (based on Remx)
    public function isCash(): bool
    {
        return str_contains(strtolower($this->Remx), 'cash');
    }

    // Check if transaction was online (based on Remx)
    public function isOnline(): bool
    {
        return str_contains(strtolower($this->Remx), 'online');
    }
}
