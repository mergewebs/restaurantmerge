<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItemOption extends Model
{
    protected $fillable = ['type', 'name', 'price_adjustment', 'is_active', 'sort_order'];

    protected function casts(): array
    {
        return ['price_adjustment' => 'decimal:2', 'is_active' => 'boolean', 'sort_order' => 'integer'];
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
