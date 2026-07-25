<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'open';
    public const STATUS_CLOSED = 'closed';

    public const KITCHEN_STATUS_PENDING = 'pending';
    public const KITCHEN_STATUS_IN_PREPARATION = 'in_preparation';
    public const KITCHEN_STATUS_READY = 'ready';

    public const PAYMENT_STATUS_UNPAID = 'unpaid';
    public const PAYMENT_STATUS_PARTIAL = 'partial';
    public const PAYMENT_STATUS_PAID = 'paid';

    public const WORKFLOW_STATUSES = ['new', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'];
    public const ORDER_TYPES = ['dine_in', 'takeaway', 'pickup', 'delivery'];

    protected $fillable = [
        'restaurant_table_id',
        'status',
        'workflow_status',
        'order_type',
        'customer_name',
        'customer_phone',
        'notes',
        'kitchen_status',
        'payment_status',
        'total',
        'discount_amount',
        'service_charge',
        'completed_at',
        'inventory_deducted_at',
        'cancelled_at',
    ];

    protected $casts = [
        'total' => 'float',
        'discount_amount' => 'float',
        'service_charge' => 'float',
        'completed_at' => 'datetime',
        'inventory_deducted_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->payments->sum('amount');
    }

    public function getBalanceDueAttribute(): float
    {
        return max(0, (float) $this->total - $this->paid_amount);
    }

    public function recalculateTotal(): void
    {
        $subtotal = (float) $this->items()->sum('line_total');

        $this->update(['total' => max(0, $subtotal - (float) $this->discount_amount + (float) $this->service_charge)]);
    }
}
