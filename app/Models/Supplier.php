<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'contact_name', 'phone', 'email', 'notes'];

    public function ingredients()
    {
        return $this->hasMany(InventoryIngredient::class);
    }
}
