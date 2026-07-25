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

    protected $fillable = [
        'restaurant_table_id',
        'status',
        'kitchen_status',
        'total',
    ];

    protected $casts = [
        'total' => 'float',
    ];

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function recalculateTotal(): void
    {
        $this->update([
            'total' => $this->items()->sum('line_total'),
        ]);
    }
}
