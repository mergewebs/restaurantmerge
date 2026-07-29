<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DemoIpAddress extends Model
{
    protected $fillable = [
        'ip_address', 'first_access_at', 'last_access_at', 'visit_count', 'browser',
        'device_type', 'operating_system', 'country', 'city', 'last_page',
        'is_blocked', 'blocked_at', 'last_active_at',
    ];

    protected function casts(): array
    {
        return [
            'first_access_at' => 'datetime',
            'last_access_at' => 'datetime',
            'last_active_at' => 'datetime',
            'blocked_at' => 'datetime',
            'is_blocked' => 'boolean',
            'visit_count' => 'integer',
        ];
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(DemoAccessSession::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(DemoAccessHistory::class);
    }

    public function activeSessions(): HasMany
    {
        return $this->sessions()->whereNull('ended_at')->where('last_activity_at', '>=', now()->subMinutes(15));
    }

    public function isActive(): bool
    {
        if ($this->is_blocked) {
            return false;
        }

        if (array_key_exists('active_sessions_exists', $this->attributes)) {
            return (bool) $this->active_sessions_exists;
        }

        return $this->activeSessions()->exists();
    }
}
