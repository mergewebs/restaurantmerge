<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        'Packages',
    ];

    public const LEGACY_IMAGE_MAP = [
        'KROQUETAS DE BERENJENA CON TRUFA (POR UNIDAD)' => 'images/kroquetas-de-berejena.PNG',
        'POP-BILLAS' => 'images/pop-billas.PNG',
        'TACOS DE CARILLADA DE TERNERA' => 'images/tacos_carrillera.png',
        'ROLLITOS (5 UDS.)' => 'images/rollitos.png',
        'GYOZAS FRITAS IBERICAS (5 UDS.)' => 'images/gyozas.png',
        'GYOZAS FRITAS IBÉRICAS (5 UDS.)' => 'images/gyozas.png',
        'EBI FRY (5 UDS.)' => 'images/EBI_FRY.png',
        'EDAMAME AL VAPOR' => 'images/EDAMAME.png',
        'KROQUETAS (5 UDS.)' => 'images/croquetas_rabo_toro.png',
        'SWEET & SPICY WINGS' => 'images/alitas.png',
        'TARTUFATA' => 'images/tartufata.png',
        'SMASH BURGER' => 'images/smash-burger.png',
        'CANGRE BURGER' => 'images/cangre_burguer.png',
        'KIMCHI BURGER' => 'images/kimchI_burguer.png',
        'ZORSADA' => 'images/ZORSADA.PNG',
        'RED ONION' => 'images/red-onion.png',
        'SOBOKU' => 'images/soboku.png',
        'FAMILY TAKEAWAY PACK' => 'images/smash-burger.png',
        'SPICY KOREAN RICE BOWL' => 'images/katsudon.png',
        'MEDITERRANEAN RICE BOWL' => 'images/koya-salad.png',
        'KOYA SALAD' => 'images/koya-salad.png',
        'KATSUDON' => 'images/katsudon.png',
        'KOYA SMASH' => 'images/smash-burger.png',
        'KOYA SMASH SPECIAL' => 'images/smash-burger.png',
    ];

    protected $fillable = [
        'name',
        'category',
        'description',
        'image_path',
        'price',
        'is_active',
        'is_package',
        'package_details',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
            'is_package' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

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

    public function getImageUrlAttribute(): ?string
    {
        if (filled($this->image_path)) {
            return Storage::disk('product-images')->url($this->image_path);
        }

        $legacyPath = self::LEGACY_IMAGE_MAP[mb_strtoupper(trim((string) $this->name))] ?? null;

        return $legacyPath ? asset($legacyPath) : null;
    }
}



