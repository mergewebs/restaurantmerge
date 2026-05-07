<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSchedule extends Model
{
    protected $fillable = [
        'day_of_week',
        'is_closed',
        'open_time_1',
        'close_time_1',
        'open_time_2',
        'close_time_2',
        'open_time_3',
        'close_time_3',
        'max_party_size',
    ];

    protected $casts = [
        'is_closed' => 'boolean',
    ];
}
