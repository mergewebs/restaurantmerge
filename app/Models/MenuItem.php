<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    public const CATEGORY_OPTIONS = [
        'Entrantes',
        'Acompañamientos',
        'Smash Burger',
        'Bol de Arroz',
        'Postres de la Casa',
        'Bebidas',
        'Cafes',
        'Cocteles',
        'Cervezas',
    ];

    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'is_active',
        'is_package',
        'package_details',
        'sort_order',
    ];

    public function scopeOrderedByCategory(Builder $query): Builder
    {
        $caseParts = [];

        foreach (self::CATEGORY_OPTIONS as $index => $category) {
            $safeCategory = str_replace("'", "''", $category);
            $caseParts[] = "WHEN '{$safeCategory}' THEN {$index}";
        }

        $caseSql = 'CASE category ' . implode(' ', $caseParts) . ' ELSE 999 END';

        return $query->orderByRaw($caseSql);
    }
}
