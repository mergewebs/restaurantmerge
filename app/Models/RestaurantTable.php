<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantTable extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_CLEANING = 'cleaning';
    public const SERVICE_STATUSES = [self::STATUS_AVAILABLE, self::STATUS_RESERVED, self::STATUS_CLEANING];

    protected $fillable = [
        'number',
        'service_status',
        'seats',
        'area',
        'merged_into_table_id',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function openOrder()
    {
        return $this->hasOne(Order::class)
            ->where('status', 'open')
            ->orderByDesc('id');
    }

    public function parentTable()
    {
        return $this->belongsTo(self::class, 'merged_into_table_id');
    }

    public function mergedTables()
    {
        return $this->hasMany(self::class, 'merged_into_table_id');
    }

    public function getFloorOrderAttribute(): ?Order
    {
        return $this->openOrder ?: $this->parentTable?->openOrder;
    }

    public function getFloorStatusAttribute(): string
    {
        if ($this->floor_order) {
            return 'occupied';
        }

        if ($this->merged_into_table_id) {
            return 'merged';
        }

        return $this->service_status;
    }
}
