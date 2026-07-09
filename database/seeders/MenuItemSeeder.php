<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Entrantes', 1, 'KOYA SALAD', 6.50, 'lechuga, wakame, sonomono, col valencia, cebolla crujiente y vinagreta de la casa'],
            ['Entrantes', 2, "GYOZAS FRITAS IB\u{00C9}RICAS (5 uds.)", 9.75, "Ib\u{00E9}rico, shiitake, cebolla Japonesa, aceite de s\u{00E9}samo y salsa de mayonesa hoisin"],
            ['Entrantes', 3, 'EDAMAME AL VAPOR', 4.50, "Sal maldon, aceite de s\u{00E9}samo y shichimi"],
            ['Entrantes', 4, 'SWEET & SPICY WINGS', 7.75, 'Alitas de pollo, gomashio, cebolla japonesa y salsa teriyaki'],
            ['Entrantes', 5, 'EBI FRY (5 uds.)', 7.75, 'Tempura de gambas y salsa de miel wasabi'],
            ['Entrantes', 6, 'ROLLITOS (5 uds.)', 8.99, 'Rollito de ternera y verdura con tartufata'],
            ['Entrantes', 7, 'KROQUETAS (5 uds.)', 10.00, 'Croquetas de ternera con rabo de toro'],
            ['Entrantes', 8, 'TACOS DE CARILLADA DE TERNERA', 11.99, 'Galtas de ternera, vino tinto y bresa al fuego lento con arroz blanco'],
            ['Entrantes', 9, 'BIL-BAO', 9.99, '2 mini pan bao relleno de cochinita pibil'],
            ['Entrantes', 10, 'KROQUETAS de berenjena con trufa (POR UNIDAD)', 2.50, 'Berenjena asada empanada de panko japonesa con trufa, miso rojo katsuobushi y pure'],
            ['Entrantes', 11, 'POP-billas', 16.50, "Pulpo hervido salteado de salsa kimchi, patatas con aji amarillo y chimichurri japon\u{00E9}s"],
            ['Entrantes', 12, 'PIPARRA ROLL', 0.00, null],

            ["Acompa\u{00F1}amientos", 1, 'BONIATO FRITO', 4.20, 'Sal, perejil, oregano y katsubishi'],
            ["Acompa\u{00F1}amientos", 2, 'PATATAS FRITAS', 3.50, null],
            ["Acompa\u{00F1}amientos", 3, 'MEZCLUM FRIES', 4.50, 'Patatas y boniato'],
            ["Acompa\u{00F1}amientos", 4, 'BRAVAS', 4.00, 'Con triple salsa de la casa'],

            ['Smash Burger', 1, 'KOYA SMASH SPECIAL', 13.99, 'Pan cronut, rabo de toro, ternera, queso havarti, lechuga, cebolla crujiente y salsa casera'],
            ['Smash Burger', 2, 'WAGYU', 12.95, 'Pan brioche, Wagyu, pepino, queso cheddar y salsa de la casa'],
            ['Smash Burger', 3, 'KIMCHI BURGER', 10.00, 'Pollo frito, kimchi casero, salsa hoisin, lechuga y cebolla Japonesa'],
            ['Smash Burger', 4, 'HAWAIIANA', 10.99, "Ternera, lechuga, pi\u{00F1}a, queso cheddar, bacon, salsa barbacoa y cebolla crujiente"],
            ['Smash Burger', 5, 'TARTUFATA', 12.50, "Ternera, champi\u{00F1}ones salteados, queso havarti con salsa trufa y perejil"],
            ['Smash Burger', 6, 'VEGAN', 12.00, 'Pan vegano o sin gluten, carne vegana, lechuga, cebolla encurtida, pepino encurtido y salsa miso'],
            ['Smash Burger', 7, 'ZORSADA', 11.95, 'Pan brioche, ternera, zorza y sobrasada de Lugo con sofrito de cebolla, queso cheddar y huevo frito'],
            ['Smash Burger', 8, 'RED ONION', 10.25, 'Ternera, cebolla caramelizada, queso cheddar, col morada y cebolla encurtida'],
            ['Smash Burger', 9, 'KRISPY GARLIC CHICKEN', 9.99, 'Pollo rebozado, lechuga, queso cheddar, cebolla crujiente y salsa agridulce'],
            ['Smash Burger', 10, 'CANGRE BURGER', 11.99, "Pan brioche, cangrejo c\u{00E1}scara blanda rebozado de panko japon\u{00E9}s con encurtida de pepino y cebolla roja con salsa ahumada de la casa"],
            ['Smash Burger', 11, 'CHIQUI BURGER', 7.00, 'Pan brioche, una patty, queso cheddar con salsa de la casa con patatas incluidas'],
            ['Smash Burger', 12, 'SOBOKU', 13.50, "Osobuco de ternera a baja temperatura, r\u{00FA}cula, berenjena, setas de shimeji y queso havarti con salsa de la casa"],

            ['Bol de Arroz', 1, 'SALMON SUPREME', 13.00, "Salm\u{00F3}n al horno, arroz, salsa teriyaki de pescado, pepino y tomate concasse"],
            ['Bol de Arroz', 2, 'ADOBO', 10.00, 'Trocitos de panceta de cerdo marinado de soja con cebolla pochada y cocinado al wok'],
            ['Bol de Arroz', 3, 'KATSUDON', 11.25, "Lomo empanado, s\u{00E9}samo, salsa tonkatsu, cebolla crujiente y rodajas de rabanitos con huevo frito"],

            ['Postres de la Casa', 1, 'MANGO FLOAT', 5.90, 'Mango fresco, galletas y crema de leche'],
            ['Postres de la Casa', 2, 'BROWNIE DE PISTACHIO', 5.75, 'Harina de almendra y pistachio'],
            ['Postres de la Casa', 3, 'MATCHA CHEESECAKE', 6.00, 'Tarta de queso con matcha'],
            ['Postres de la Casa', 4, 'TARTA DE CHOCOLATE', 5.50, 'Chocolate negro, azucar moreno, huevo, leche, almendras y mermelada de melocoton'],
            ['Postres de la Casa', 5, 'KOULANT', 5.25, 'Coulant de chocolate con vainilla'],

            ['Bebidas', 1, 'VERMUT DE LA CASA', 3.50, null],
            ['Bebidas', 2, 'VINO BLANCO', 3.85, null],
            ['Bebidas', 3, 'VINO TINTO', 3.60, null],
            ['Bebidas', 4, 'COPA DE SANGRIA', 4.50, null],
            ['Bebidas', 5, '1 L SANGRIA', 12.75, null],
            ['Bebidas', 6, 'COPA DE CERVEZA', 2.30, null],
            ['Bebidas', 7, 'CLARA', 2.40, null],
            ['Bebidas', 8, 'PINTA', 5.50, null],
            ['Bebidas', 9, 'NESTEA', 2.60, null],
            ['Bebidas', 10, 'AQUARIUS', 2.60, null],
            ['Bebidas', 11, 'FANTA', 2.40, "LIM\u{00D3}N / NARANJA"],
            ['Bebidas', 12, 'COCA COLA', 2.40, 'NORMAL / ZERO'],
            ['Bebidas', 13, 'AGUA GRANDE 50CL', 2.20, null],
            ['Bebidas', 14, 'AGUA CON GAS 33CL', 2.00, null],
            ['Bebidas', 15, 'ZUMO DE FRUTAS', 2.50, null],
            ['Bebidas', 16, 'TINTO VERANO', 3.95, null],

            ['Cafes', 1, 'CAFE SOLO', 1.40, null],
            ['Cafes', 2, 'CAFE CORTADO', 1.50, null],
            ['Cafes', 3, 'CAFE CON LECHE', 1.60, null],
            ['Cafes', 4, 'CAFE AMERICANO', 1.50, null],
            ['Cafes', 5, 'CAFE TRIFASICO', 2.60, null],
            ['Cafes', 6, 'INFUSIONES', 2.30, null],
            ['Cafes', 7, 'TE VERDE o NEGRO', 2.65, null],
            ['Cafes', 8, 'CARAJILLO DE BAILEY\'S', 2.60, null],
            ['Cafes', 9, 'CARAJILLO DE WHISKY/BRANDY', 2.60, null],
            ['Cafes', 10, 'CHUPITO DE WHISKY/BRANDY', 2.00, null],
            ['Cafes', 11, 'CHUPITO DE ALMENDRA', 2.00, null],

            ['Cocteles', 1, 'MOJITO', 8.50, "Bacardi, sif\u{00F3}n y menta"],
            ['Cocteles', 2, 'TEQUILA SUNRISE', 8.50, 'Zumo de naranja, tequila & granadina'],
            ['Cocteles', 3, 'NEGRONI', 8.50, 'Martini rosso, Campari, gin'],
            ['Cocteles', 4, 'VODKA 7', 8.50, 'Vodka & refresco de 7 Up'],
            ['Cocteles', 5, 'GIN TONIC', 8.00, "Gin & t\u{00F3}nica"],

            ['Cervezas', 1, 'ESTRELLA DAMM', 2.40, null],
            ['Cervezas', 2, 'ESTRELLA FREE DAMM', 2.60, null],
            ['Cervezas', 3, 'ESTRELLA FREE DAMM TOSTADA', 2.50, null],
            ['Cervezas', 4, 'TURIA', 2.60, null],
            ['Cervezas', 5, 'VOLLDAMN', 2.60, null],
        ];

        foreach ($items as [$category, $sortOrder, $name, $price, $description]) {
            $menuItem = MenuItem::updateOrCreate(
                [
                    'category' => $category,
                    'sort_order' => $sortOrder,
                ],
                array_merge([
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'is_active' => true,
                ], $name === 'PIPARRA ROLL' ? [
                    'image_path' => 'originals/103/piparral-roll.png',
                    'image_display_path' => 'optimized/103/piparral-roll.webp',
                    'image_thumbnail_path' => 'thumbnails/103/piparral-roll.webp',
                ] : [])
            );

            MenuItem::query()
                ->where('category', $category)
                ->where('sort_order', $sortOrder)
                ->whereKeyNot($menuItem->id)
                ->delete();
        }
    }
}

