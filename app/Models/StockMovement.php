<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    public const TYPES = ['purchase', 'restock', 'waste', 'damaged', 'order', 'adjustment'];

    protected $fillable = ['target_type', 'target_id', 'movement_type', 'quantity', 'unit_cost', 'supplier_id', 'order_id', 'user_id', 'reference', 'notes', 'occurred_at'];

    protected function casts(): array
    {
        return ['quantity' => 'decimal:3', 'unit_cost' => 'decimal:4', 'occurred_at' => 'datetime'];
    }

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
}
