<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantException extends Model
{
    protected $fillable = [
        'date',
        'is_closed',
        'open_time_1',
        'close_time_1',
        'open_time_2',
        'close_time_2',
        'open_time_3',
        'close_time_3',
        'max_party_size',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];
}
