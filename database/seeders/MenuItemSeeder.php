<?php

namespace Database\Seeders;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Starters', 'Entrantes', "Acompa\u{00F1}amientos", 'Smash Burger', 'Bol de Arroz',
            'Postres de la Casa', 'Bebidas', 'Cafes', 'Cocteles', 'Cervezas', 'Packages',
        ] as $sortOrder => $name) {
            MenuCategory::updateOrCreate(
                ['name' => $name],
                ['sort_order' => $sortOrder, 'is_active' => true],
            );
        }

        $items = [
            ['Entrantes', 1, 'HOUSE SALAD', 7.50, 'lechuga, wakame, sonomono, col valencia, cebolla crujiente y vinagreta de la casa'],
            ['Entrantes', 2, "GYOZAS FRITAS IB\u{00C9}RICAS (5 UDS.)", 10.25, "Ib\u{00E9}rico, shiitake, cebolla Japonesa, aceite de s\u{00E9}samo y salsa de mayonesa hoisin"],
            ['Entrantes', 3, 'EDAMAME AL VAPOR', 4.75, "Sal maldon, aceite de s\u{00E9}samo y shichimi"],
            ['Entrantes', 4, 'SWEET & SPICY WINGS', 7.95, 'Alitas de pollo, gomashio, cebolla japonesa y salsa teriyaki'],
            ['Entrantes', 5, 'EBI FRY (5 UDS.)', 7.75, 'Tempura de gambas y salsa de miel wasabi'],
            ['Entrantes', 6, 'ROLLITOS (5 UDS.)', 8.99, 'Rollito de ternera y verdura con tartufata'],
            ['Entrantes', 7, 'KROQUETAS (5 UDS.)', 10.00, 'Croquetas de ternera con rabo de toro'],
            ['Entrantes', 8, 'TACOS DE CARILLADA DE TERNERA', 11.99, 'Galtas de ternera, vino tinto y bresa al fuego lento con arroz blanco'],
            ['Entrantes', 9, 'BIL-BAO', 9.99, '2 mini pan bao relleno de cochinita pibil'],
            ['Entrantes', 10, 'KROQUETAS DE BERENJENA CON TRUFA (POR UNIDAD)', 2.50, 'Berenjena asada empanada de panko japonesa con trufa, miso rojo katsuobushi y pure'],
            ['Entrantes', 11, 'POP-BILLAS', 16.50, "Pulpo hervido salteado de salsa kimchi, patatas con aji amarillo y chimichurri japon\u{00E9}s"],
            ['Entrantes', 12, 'PIPARRA ROLL (POR UNIDAD)', 2.99, "Chilli verde relleno de ternera y sofrito con queso rallado y champi\u{00F1}ones al wok. Acabado con queso rallado con miel, salsa ahumada de la casa e hilos crujientes de boniato"],

            ["Acompa\u{00F1}amientos", 1, 'BONIATO FRITO', 4.30, "Sal de katsuobushi con perejil y or\u{00E9}gano"],
            ["Acompa\u{00F1}amientos", 2, 'PATATAS FRITAS', 3.50, null],
            ["Acompa\u{00F1}amientos", 3, 'MEZCLUM FRIES', 4.50, 'Patatas y boniato'],
            ["Acompa\u{00F1}amientos", 4, 'BRAVAS', 4.00, 'Con triple salsa de la casa'],

            ['Smash Burger', 1, 'HOUSE SMASH SPECIAL', 14.50, 'Pan cronut, rabo de toro, ternera, queso havarti, lechuga, cebolla crujiente y salsa casera'],
            ['Smash Burger', 2, 'WAGYU', 12.95, 'Pan brioche, Wagyu, pepino, queso cheddar y salsa de la casa'],
            ['Smash Burger', 3, 'KIMCHI BURGER', 10.40, 'Pollo frito, kimchi casero, salsa hoisin, lechuga y cebolla Japonesa'],
            ['Smash Burger', 4, 'HAWAIIANA', 11.20, "Ternera, lechuga, pi\u{00F1}a, queso cheddar, bacon, salsa barbacoa y cebolla crujiente"],
            ['Smash Burger', 5, 'TARTUFATA', 12.50, "Ternera, champi\u{00F1}ones salteados, queso havarti con salsa trufa y perejil"],
            ['Smash Burger', 6, 'VEGAN', 13.00, 'pan vegana o sin gl\u{00FA}ten, carne vegana, lechuga, cebolla encurtida y pepino encurtido y salsa miso'],
            ['Smash Burger', 7, 'ZORSADA', 11.95, 'Pan brioche, ternera, zorza y sobrasada de Lugo con sofrito de cebolla, queso cheddar y huevo frito'],
            ['Smash Burger', 8, 'RED ONION', 10.75, 'Ternera, cebolla caramelizada, queso cheddar, col morada y cebolla encurtida'],
            ['Smash Burger', 9, 'KRISPY GARLIC CHICKEN', 10.20, 'Pollo rebozado, lechuga, queso cheddar, cebolla crujiente y salsa agridulce'],
            ['Smash Burger', 10, 'CANGRE BURGER', 11.99, "Pan brioche, cangrejo c\u{00E1}scara blanda rebosado de panko japon\u{00E9}s con encurtida de pepino y cebolla roja con salsa ahumada de la casa"],
            ['Smash Burger', 11, 'CHIQUI BURGER', 7.00, 'Pan brioche, una patty, queso cheddar con salsa de la casa con patatas incluidas'],
            ['Smash Burger', 12, 'SOBOKU', 13.99, "Osobuco de ternera a baja temperatura, r\u{00FA}cula, berenjena, setas de shimeji y queso havarti con salsa de la casa"],

            ['Bol de Arroz', 1, 'SALMON SUPREME', 13.80, "Salm\u{00F3}n a la plancha, arroz, salsa teriyaki de pescado, pepino y tomate concasse"],
            ['Bol de Arroz', 2, 'ADOBO', 10.50, 'Laminas de panceta cocinado a baja temperatura con salsa teriyaki casero de miso y jengibre encortido'],
            ['Bol de Arroz', 3, 'KATSUDON', 11.75, "Lomo empanado, salsa tonkatsu, s\u{00E9}samo, pepino y cebolla encurtida *con huevo frito*"],

            ['Postres de la Casa', 1, 'MANGO FLOAT', 5.90, 'Tarta de helado de mango fresco, galleta y crema de leche'],
            ['Postres de la Casa', 2, 'BROWNIE DE PISTACHIO', 5.85, 'Harina de almendra, pistachio y helado de vainilla'],
            ['Postres de la Casa', 3, 'MATCHA CHEESECAKE', 6.00, 'Tarta de queso con matcha'],
            ['Postres de la Casa', 4, 'TARTA DE CHOCOLATE', 5.50, 'Chocolate negro, azucar moreno, huevo, leche y almendras'],
            ['Postres de la Casa', 5, 'KOULANT', 5.25, 'Coulant de chocolate con helado de vainilla'],

            ['Bebidas', 1, 'VERMUT DE LA CASA', 3.50, null],
            ['Bebidas', 2, 'VINO BLANCO', 3.85, null],
            ['Bebidas', 3, 'VINO TINTO', 3.60, null],
            ['Bebidas', 4, 'COPA DE SANGRIA', 4.50, null],
            ['Bebidas', 5, '1L SANGRIA', 13.00, null],
            ['Bebidas', 6, 'COPA DE CERVEZA', 2.40, null],
            ['Bebidas', 7, 'CLARA', 2.40, null],
            ['Bebidas', 8, 'PINTA', 5.75, null],
            ['Bebidas', 9, 'NESTEA', 2.60, null],
            ['Bebidas', 10, 'AQUARIUS', 2.60, null],
            ['Bebidas', 11, 'FANTA', 2.40, "LIM\u{00D3}N / NARANJA"],
            ['Bebidas', 12, 'COCA COLA', 2.40, 'NORMAL / ZERO'],
            ['Bebidas', 13, 'AGUA GRANDE 50CL', 2.30, null],
            ['Bebidas', 14, 'AGUA CON GAS 33CL', 2.00, null],
            ['Bebidas', 15, 'ZUMO DE FRUTAS', 2.60, null],
            ['Bebidas', 16, 'TINTO VERANO', 3.99, null],

            ['Cafes', 1, "CAF\u{00C9} SOLO", 1.40, null],
            ['Cafes', 2, "CAF\u{00C9} CORTADO", 1.50, null],
            ['Cafes', 3, "CAF\u{00C9} CON LECHE", 1.60, null],
            ['Cafes', 4, "CAF\u{00C9} AMERICANO", 1.60, null],
            ['Cafes', 5, "CAF\u{00C9} TRIF\u{00C1}SICO", 2.80, null],
            ['Cafes', 6, 'INFUSIONES', 2.30, null],
            ['Cafes', 7, 'TE VERDE o NEGRO', 2.65, null],
            ['Cafes', 8, 'CARAJILLO DE BAILEY\'S', 2.70, null],
            ['Cafes', 9, 'CARAJILLO DE WHISKY/BRANDY', 2.70, null],
            ['Cafes', 10, 'CHUPITO DE WHISKY/BRANDY', 3.00, null],
            ['Cafes', 11, 'CHUPITO DE ALMENDRA', 3.00, null],

            ['Cocteles', 1, "MOJITO(Menta/fresa/pi\u{00F1}a colada)", 8.50, "BACARDI, SIF\u{00D3}N Y MENTA"],
            ['Cocteles', 2, 'TEQUILA SUNRISE', 8.50, 'ZUMO DE NARANJA, TEQUILA & GRANADINA'],
            ['Cocteles', 3, 'NEGRONI', 8.50, 'MARTINI ROSSO, CAMPARI, GIN'],
            ['Cocteles', 4, 'VODKA 7', 8.50, 'VODKA & REFRESCO DE 7UP'],
            ['Cocteles', 5, 'GIN TONIC', 8.00, 'SEA GRAM O BEEFEATER'],

            ['Cervezas', 1, 'ESTRELLA DAMM', 2.40, null],
            ['Cervezas', 2, 'ESTRELLA FREE DAMM', 2.60, null],
            ['Cervezas', 3, 'ESTRELLA FREE DAMM TOSTADA', 2.50, null],
            ['Cervezas', 4, 'TURIA', 2.60, null],
            ['Cervezas', 5, 'VOLLDAMN', 2.60, null],
        ];

        foreach ($items as [$category, $sortOrder, $name, $price, $description]) {
            $menuItem = $this->findExistingItem($category, $sortOrder, $name) ?? new MenuItem;

            $menuItem->fill(array_merge([
                'category' => $category,
                'sort_order' => $sortOrder,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'is_active' => true,
            ], $this->isPiparraRoll($name) ? [
                'image_path' => 'originals/103/piparral-roll.png',
                'image_display_path' => 'optimized/103/piparral-roll.webp',
                'image_thumbnail_path' => 'thumbnails/103/piparral-roll.webp',
            ] : []));
            $menuItem->save();

            $duplicateIds = MenuItem::query()
                ->whereKeyNot($menuItem->id)
                ->whereIn('category', $this->matchingCategories($category))
                ->get()
                ->filter(fn (MenuItem $item) => $item->sort_order === $sortOrder || $this->normalizeName($item->name) === $this->normalizeName($name))
                ->pluck('id');

            if ($duplicateIds->isNotEmpty()) {
                MenuItem::query()->whereKey($duplicateIds)->delete();
            }
        }
    }

    private function findExistingItem(string $category, int $sortOrder, string $name): ?MenuItem
    {
        $itemsInCategory = MenuItem::query()
            ->whereIn('category', $this->matchingCategories($category))
            ->get();

        $normalizedName = $this->normalizeName($name);

        return $itemsInCategory->first(
            fn (MenuItem $item) => $this->normalizeName($item->name) === $normalizedName
        ) ?? $itemsInCategory->firstWhere('sort_order', $sortOrder);
    }

    private function normalizeName(string $name): string
    {
        return Str::upper(Str::ascii(trim($name)));
    }

    private function matchingCategories(string $category): array
    {
        return match ($category) {
            'Starters' => ['Starters', 'Entrantes'],
            'Entrantes' => ['Entrantes', 'Starters'],
            "Acompa\u{00F1}amientos" => ["Acompa\u{00F1}amientos", 'Acompa?amientos'],
            default => [$category],
        };
    }

    private function isPiparraRoll(string $name): bool
    {
        return Str::startsWith($this->normalizeName($name), 'PIPARRA ROLL');
    }
}
