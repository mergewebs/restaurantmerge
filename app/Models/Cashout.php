<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cashout extends Model
{
    protected $fillable = [
        'date',
        'opening_cash',
        'sales_cash',
        'expenses_cash',
        'closing_cash',
        'expected_cash',
        'difference',
        'notes',
        'user_id'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
