<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    public const METHODS = ['cash', 'card', 'bizum', 'online'];

    protected $fillable = ['order_id', 'cash_register_session_id', 'method', 'transaction_type', 'refunded_payment_id', 'amount', 'reference', 'user_id', 'paid_at'];

    protected $casts = [
        'amount' => 'float',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerSession() { return $this->belongsTo(CashRegisterSession::class, 'cash_register_session_id'); }
    public function refundedPayment() { return $this->belongsTo(self::class, 'refunded_payment_id'); }
    public function refunds() { return $this->hasMany(self::class, 'refunded_payment_id'); }
}
