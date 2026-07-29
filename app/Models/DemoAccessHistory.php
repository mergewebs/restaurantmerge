<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DemoAccessHistory extends Model
{
    protected $table = 'demo_access_history';

    protected $fillable = ['demo_ip_address_id', 'demo_access_session_id', 'accessed_at', 'page', 'route_name'];

    protected function casts(): array
    {
        return ['accessed_at' => 'datetime'];
    }

    public function ipAddress(): BelongsTo
    {
        return $this->belongsTo(DemoIpAddress::class, 'demo_ip_address_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(DemoAccessSession::class, 'demo_access_session_id');
    }
}
