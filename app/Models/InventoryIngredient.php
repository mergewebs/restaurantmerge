<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryIngredient extends Model
{
    protected $fillable = ['name', 'unit', 'stock_quantity', 'low_stock_threshold', 'cost_per_unit', 'supplier_id', 'is_active'];

    protected function casts(): array
    {
        return ['stock_quantity' => 'decimal:3', 'low_stock_threshold' => 'decimal:3', 'cost_per_unit' => 'decimal:4', 'is_active' => 'boolean'];
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function menuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'menu_item_ingredients')->withPivot('quantity')->withTimestamps();
    }
}
