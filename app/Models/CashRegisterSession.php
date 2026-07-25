<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashRegisterSession extends Model
{
    protected $fillable = ['opened_by', 'closed_by', 'opening_float', 'expected_cash', 'actual_cash', 'difference', 'opened_at', 'closed_at', 'status', 'notes'];

    protected function casts(): array
    {
        return ['opening_float' => 'decimal:2', 'expected_cash' => 'decimal:2', 'actual_cash' => 'decimal:2', 'difference' => 'decimal:2', 'opened_at' => 'datetime', 'closed_at' => 'datetime'];
    }

    public function opener() { return $this->belongsTo(User::class, 'opened_by'); }
    public function closer() { return $this->belongsTo(User::class, 'closed_by'); }
    public function payments() { return $this->hasMany(OrderPayment::class); }

    public function calculateExpectedCash(): float
    {
        return (float) $this->opening_float + (float) $this->payments()->where('method', 'cash')->sum('amount');
    }
}
