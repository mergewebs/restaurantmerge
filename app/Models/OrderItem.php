<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_IN_PREPARATION = 'in_preparation';
    public const STATUS_READY = 'ready';

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'name',
        'note',
        'station',
        'allergens',
        'quantity',
        'unit_price',
        'line_total',
        'status',
    ];

    protected $casts = [
        'unit_price' => 'float',
        'line_total' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
