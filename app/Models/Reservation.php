<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    public const STATUSES = ['pending', 'confirmed', 'arrived', 'seated', 'cancelled', 'no_show'];

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'party_size',
        'reservation_time',
        'notes',
        'allergies',
        'restaurant_table_id',
        'status',
        'user_id'
    ];

    protected $casts = [
        'reservation_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function table()
    {
        return $this->belongsTo(RestaurantTable::class, 'restaurant_table_id');
    }
}
