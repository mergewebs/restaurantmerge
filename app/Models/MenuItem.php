<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class MenuItem extends Model
{
    use HasFactory;

    public const AVAILABILITY_STATUSES = ['available', 'sold_out'];

    public const ITEM_KINDS = ['regular', 'lunch_menu', 'promotion', 'seasonal'];

    public const OPTION_TYPES = ['size', 'add_on', 'extra', 'variation'];

    public const CATEGORY_OPTIONS = [
        'Starters',
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
        'PIPARRA ROLL' => 'products/thumbnails/103/piparral-roll.webp',
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
        'BIL-BAO' => 'images/BIL-BAO.png',
        'BRAVAS' => 'images/bravas.png',
        'TARTUFATA' => 'images/tartufata.png',
        'SMASH BURGER' => 'images/smash-burger.png',
        'CANGRE BURGER' => 'images/cangre_burguer.png',
        'KIMCHI BURGER' => 'images/kimchI_burguer.png',
        'ZORSADA' => 'images/ZORSADA.PNG',
        'RED ONION' => 'images/red-onion.png',
        'SOBOKU' => 'images/soboku.png',
        'SPICY KOREAN RICE BOWL' => 'images/katsudon.png',
        'MEDITERRANEAN RICE BOWL' => 'images/house-salad.png',
        'HOUSE SALAD' => 'images/house-salad.png',
        'SALMON SUPREME' => 'images/Salmon_supreme.png',
        'ADOBO' => 'images/adobo.png',
        'KATSUDON' => 'images/katsudon.png',
        'HOUSE SMASH' => 'images/smash-burger.png',
        'HOUSE SMASH SPECIAL' => 'images/smash-burger.png',
    ];

    protected $fillable = [
        'name',
        'category',
        'menu_category_id',
        'description',
        'ingredients',
        'allergens',
        'image_path',
        'image_display_path',
        'image_thumbnail_path',
        'price',
        'dine_in_price',
        'takeaway_price',
        'delivery_price',
        'stock_quantity',
        'low_stock_threshold',
        'availability_status',
        'item_kind',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'dine_in_price' => 'decimal:2',
            'takeaway_price' => 'decimal:2',
            'delivery_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeOrderedByCategory(Builder $query): Builder
    {
        return $query
            ->orderByRaw('COALESCE((SELECT sort_order FROM menu_categories WHERE menu_categories.id = menu_items.menu_category_id), 999)')
            ->orderBy('category');
    }

    public function menuCategory(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(MenuItemOption::class)->orderBy('type')->orderBy('sort_order')->orderBy('name');
    }

    public function priceFor(string $orderType): float
    {
        $channelPrice = match ($orderType) {
            'dine_in' => $this->dine_in_price,
            'takeaway', 'pickup' => $this->takeaway_price,
            'delivery' => $this->delivery_price,
            default => null,
        };

        return (float) ($channelPrice ?? $this->price);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (filled($this->image_display_path)) {
            return Storage::disk('product-images')->url($this->image_display_path);
        }

        if (filled($this->image_path)) {
            return Storage::disk('product-images')->url($this->image_path);
        }

        $legacyPath = $this->legacyImageAssetPath();

        return $legacyPath ? asset($legacyPath) : null;
    }

    public function getImageThumbnailUrlAttribute(): ?string
    {
        if (filled($this->image_thumbnail_path)) {
            return Storage::disk('product-images')->url($this->image_thumbnail_path);
        }

        return $this->image_url;
    }

    public function legacyImageAssetPath(): ?string
    {
        return self::LEGACY_IMAGE_MAP[mb_strtoupper(trim((string) $this->name))] ?? null;
    }
}
