<?php

namespace Database\Seeders;

use App\Models\Cashout;
use App\Models\Expense;
use App\Models\Income;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\RestaurantSchedule;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PresentationDemoSeeder extends Seeder
{
    private const DEMO_PASSWORD = 'password';

    public function run(): void
    {
        $users = $this->seedUsers();
        $this->seedSchedule();
        $this->seedMenu();
        $this->seedTablesAndOrders();
        $this->seedReservations($users['owner']);
        $this->seedFinance($users['owner']);
    }

    private function seedUsers(): array
    {
        $accounts = [
            'superadmin' => [
                'name' => 'Demo Super Admin',
                'email' => 'superadmin@example.com',
                'role' => 'superadmin',
            ],
            'owner' => [
                'name' => 'Demo Owner',
                'email' => 'owner@example.com',
                'role' => 'owner',
            ],
            'waiter' => [
                'name' => 'Demo Waiter',
                'email' => 'waiter@example.com',
                'role' => 'waiter',
            ],
        ];

        return collect($accounts)
            ->mapWithKeys(function (array $account, string $key) {
                $user = User::where('email', $account['email'])->first();

                if (! $user) {
                    $user = new User();
                }

                $user->fill([
                    'name' => $account['name'],
                    'email' => $account['email'],
                    'password' => Hash::make(self::DEMO_PASSWORD),
                    'role' => $account['role'],
                ])->save();

                $user->forceFill(['email_verified_at' => now()])->save();

                return [$key => $user];
            })
            ->all();
    }

    private function seedSchedule(): void
    {
        foreach (range(0, 6) as $day) {
            RestaurantSchedule::updateOrCreate(
                ['day_of_week' => $day],
                [
                    'is_closed' => $day === 1,
                    'open_time_1' => $day === 1 ? null : '12:00',
                    'close_time_1' => $day === 1 ? null : '16:00',
                    'open_time_2' => $day === 1 ? null : '19:00',
                    'close_time_2' => $day === 1 ? null : '23:30',
                    'open_time_3' => null,
                    'close_time_3' => null,
                    'max_party_size' => 12,
                ]
            );
        }
    }

    private function seedMenu(): void
    {
        $items = [
            ['Loaded Nachos', 'Starters', 8.90, false, null],
            ['Crispy Chicken Bites', 'Starters', 7.50, false, null],
            ['Classic Smash Burger', 'Smash Burgers', 11.90, false, null],
            ['Truffle Double Smash', 'Smash Burgers', 14.50, false, null],
            ['Tartufata', 'Smash Burgers', 13.90, false, 'Double smash burger with premium black truffle paste, melted Swiss cheese, caramelized onions, and truffle mayo on a toasted brioche bun.'],
            ['Smash Burger', 'Smash Burgers', 10.90, false, 'Perfectly seasoned double smash patty with cheddar cheese, crisp lettuce, tomato, pickles, and our signature burger sauce.'],
            ['Red Onion', 'Smash Burgers', 11.50, false, 'Double smash patty with caramelized red onions, melted cheddar, pickles, and dynamic smoky barbecue sauce.'],
            ['SOBOKU', 'Smash Burgers', 10.50, false, 'A simple, clean-tasting single smash patty with cheddar cheese, yellow mustard, and pickles inside a soft bun.'],
            ['Spicy Korean Rice Bowl', 'Rice Bowls', 12.80, false, null],
            ['Mediterranean Rice Bowl', 'Rice Bowls', 11.70, false, null],
            ['KOYA SALAD', 'Rice Bowls', 9.80, false, 'Fresh mix of crisp greens, cherry tomatoes, edamame, pickled cabbage, avocado, and ginger sesame dressing.'],
            ['KATSUDON', 'Rice Bowls', 12.50, false, 'Crispy chicken panko cutlet, simmered eggs, onions, and sweet-savory dashi broth served over steamed rice.'],
            ['House Fries', 'Sides', 4.20, false, null],
            ['Sweet Potato Fries', 'Sides', 4.90, false, null],
            ['Cheesecake Jar', 'Homemade Desserts', 5.50, false, null],
            ['Chocolate Brownie', 'Homemade Desserts', 5.20, false, null],
            ['Still Water', 'Drinks', 2.00, false, null],
            ['Homemade Lemonade', 'Drinks', 3.50, false, null],
            ['Local Lager', 'Beers', 3.80, false, null],
            ['House IPA', 'Beers', 4.50, false, null],
            ['Mojito', 'Cocktails', 7.90, false, null],
            ['Espresso', 'Coffee', 1.70, false, null],
            ['Lunch Combo', 'Starters', 15.90, true, 'Burger or rice bowl, side, and drink.'],
            ['Family Takeaway Pack', 'Smash Burgers', 39.90, true, 'Four burgers, two large fries, and four drinks.'],
        ];

        foreach ($items as $index => [$name, $category, $price, $isPackage, $details]) {
            MenuItem::updateOrCreate(
                ['name' => $name],
                [
                    'category' => $category,
                    'price' => $price,
                    'is_active' => true,
                    'is_package' => $isPackage,
                    'package_details' => $details,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }

    private function seedTablesAndOrders(): void
    {
        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 10000] as $number) {
            RestaurantTable::firstOrCreate(['number' => $number]);
        }

        foreach ([2, 5, 10000] as $number) {
            $table = RestaurantTable::where('number', $number)->first();
            $table?->orders()->where('status', Order::STATUS_OPEN)->delete();
        }

        $this->createOrder(2, Order::TYPE_TABLE, Order::KITCHEN_STATUS_PENDING, [
            ['Classic Smash Burger', 2, 11.90, 'No onion on one burger', OrderItem::STATUS_PENDING],
            ['House Fries', 2, 4.20, null, OrderItem::STATUS_PENDING],
            ['Homemade Lemonade', 2, 3.50, null, OrderItem::STATUS_PENDING],
        ]);

        $this->createOrder(5, Order::TYPE_TABLE, Order::KITCHEN_STATUS_IN_PREPARATION, [
            ['Truffle Double Smash', 1, 14.50, 'Medium well', OrderItem::STATUS_IN_PREPARATION],
            ['Spicy Korean Rice Bowl', 1, 12.80, null, OrderItem::STATUS_PENDING],
            ['Local Lager', 2, 3.80, null, OrderItem::STATUS_READY],
        ]);

        $this->createOrder(10000, Order::TYPE_TAKEAWAY, Order::KITCHEN_STATUS_READY, [
            ['Family Takeaway Pack', 1, 39.90, null, OrderItem::STATUS_READY],
            ['Cheesecake Jar', 2, 5.50, null, OrderItem::STATUS_READY],
        ], 'Lucia Martin', '+34 600 123 456');
    }

    private function createOrder(
        int $tableNumber,
        string $type,
        string $kitchenStatus,
        array $items,
        ?string $customerName = null,
        ?string $customerPhone = null
    ): void {
        $table = RestaurantTable::where('number', $tableNumber)->firstOrFail();
        $order = $table->orders()->create([
            'order_type' => $type,
            'status' => Order::STATUS_OPEN,
            'kitchen_status' => $kitchenStatus,
            'customer_name' => $customerName,
            'customer_phone' => $customerPhone,
            'total' => 0,
        ]);

        foreach ($items as [$name, $quantity, $unitPrice, $note, $status]) {
            $order->items()->create([
                'name' => $name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $quantity * $unitPrice,
                'note' => $note,
                'status' => $status,
            ]);
        }

        $order->recalculateTotal();
    }

    private function seedReservations(User $owner): void
    {
        Reservation::whereIn('customer_email', [
            'ana.silva@example.com',
            'marco.rossi@example.com',
            'nora.wilson@example.com',
            'lucas.garcia@example.com',
        ])->delete();

        $reservations = [
            ['Ana Silva', '+34 611 222 333', 'ana.silva@example.com', 4, now()->setTime(13, 30), 'Window table if available.', 'confirmed'],
            ['Marco Rossi', '+34 622 333 444', 'marco.rossi@example.com', 2, now()->setTime(20, 15), 'Anniversary dinner.', 'confirmed'],
            ['Nora Wilson', '+34 633 444 555', 'nora.wilson@example.com', 6, now()->addDay()->setTime(21, 0), 'Needs one high chair.', 'confirmed'],
            ['Lucas Garcia', '+34 644 555 666', 'lucas.garcia@example.com', 3, now()->subDay()->setTime(14, 0), 'Completed lunch booking.', 'completed'],
        ];

        foreach ($reservations as [$name, $phone, $email, $partySize, $time, $notes, $status]) {
            Reservation::create([
                'customer_name' => $name,
                'customer_phone' => $phone,
                'customer_email' => $email,
                'party_size' => $partySize,
                'reservation_time' => $time,
                'notes' => $notes,
                'status' => $status,
                'user_id' => $owner->id,
                'submission_token' => str($email . $time->toDateTimeString())->slug() . '-demo',
            ]);
        }
    }

    private function seedFinance(User $owner): void
    {
        Income::where('description', 'like', 'Demo:%')->delete();
        Expense::where('description', 'like', 'Demo:%')->delete();
        Cashout::where('notes', 'like', 'Demo:%')->delete();

        $today = Carbon::today();
        $incomeRows = [
            [620.50, 'Sales', 'cash', 'Demo: lunch table service', $today],
            [410.80, 'Sales', 'card', 'Demo: dinner table service', $today],
            [82.40, 'Sales', 'card', 'Demo: public takeaway orders', $today],
            [58.00, 'Services', 'cash', 'Demo: private room deposit', $today->copy()->subDay()],
            [534.20, 'Sales', 'card', 'Demo: yesterday evening service', $today->copy()->subDay()],
            [478.60, 'Sales', 'cash', 'Demo: weekday service', $today->copy()->subDays(2)],
        ];

        foreach ($incomeRows as [$amount, $type, $method, $description, $date]) {
            Income::create(compact('amount', 'type', 'method', 'description') + [
                'date' => $date,
                'user_id' => $owner->id,
            ]);
        }

        $expenseRows = [
            [146.30, 'Food', 'card', 'Demo: fresh produce delivery', $today],
            [72.10, 'Beverages', 'cash', 'Demo: local drinks restock', $today],
            [38.50, 'Supplies', 'cash', 'Demo: takeaway packaging', $today],
            [210.00, 'Payroll', 'card', 'Demo: extra weekend shift', $today->copy()->subDay()],
            [95.70, 'Utilities', 'card', 'Demo: electricity estimate', $today->copy()->subDays(2)],
        ];

        foreach ($expenseRows as [$amount, $category, $method, $description, $date]) {
            Expense::create(compact('amount', 'category', 'method', 'description') + [
                'date' => $date,
                'user_id' => $owner->id,
            ]);
        }

        Cashout::create([
            'date' => $today,
            'opening_cash' => 250,
            'sales_cash' => 620.50,
            'expenses_cash' => 110.60,
            'closing_cash' => 759.90,
            'expected_cash' => 759.90,
            'difference' => 0,
            'notes' => 'Demo: balanced cash drawer for presentation.',
            'user_id' => $owner->id,
        ]);
    }
}
