<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DemoAccessSession extends Model
{
    protected $fillable = [
        'demo_ip_address_id', 'session_hash', 'started_at', 'last_activity_at', 'ended_at', 'entry_page',
    ];

    protected function casts(): array
    {
        return ['started_at' => 'datetime', 'last_activity_at' => 'datetime', 'ended_at' => 'datetime'];
    }

    public function ipAddress(): BelongsTo
    {
        return $this->belongsTo(DemoIpAddress::class, 'demo_ip_address_id');
    }

    public function history(): HasMany
    {
        return $this->hasMany(DemoAccessHistory::class);
    }
}
