<?php

namespace Database\Seeders;

use App\Models\Cashout;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\RestaurantSchedule;
use App\Models\RestaurantTable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PresentationDemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = $this->admin();
        $this->seedSchedule();
        $this->seedTablesAndOrders();
        $this->seedReservations($admin);
        $this->seedFinance($admin);
    }

    private function admin(): User
    {
        $name = (string) config('access.admin.name', 'Admin');
        $admin = User::query()->where('name', $name)->where('role', 'admin')->first();

        if (! $admin) {
            $this->call(AdminAccessSeeder::class);
            $admin = User::query()->where('name', $name)->where('role', 'admin')->firstOrFail();
        }

        return $admin;
    }

    private function seedSchedule(): void
    {
        foreach (range(0, 6) as $day) {
            RestaurantSchedule::updateOrCreate(
                ['day_of_week' => $day],
                [
                    'is_closed' => in_array($day, [0, 1], true),
                    'open_time_1' => match (true) {
                        in_array($day, [2, 3, 4], true) => '18:00',
                        in_array($day, [5, 6], true) => '13:00',
                        default => null,
                    },
                    'close_time_1' => match (true) {
                        in_array($day, [2, 3, 4], true) => '23:00',
                        in_array($day, [5, 6], true) => '16:30',
                        default => null,
                    },
                    'open_time_2' => in_array($day, [5, 6], true) ? '19:30' : null,
                    'close_time_2' => in_array($day, [5, 6], true) ? '23:30' : null,
                    'open_time_3' => null,
                    'close_time_3' => null,
                    'max_party_size' => 12,
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
            ['KOYA SMASH SPECIAL', 2, 13.99, 'No onion on one burger', OrderItem::STATUS_PENDING],
            ['PATATAS FRITAS', 2, 3.50, null, OrderItem::STATUS_PENDING],
            ['NESTEA', 2, 2.60, null, OrderItem::STATUS_PENDING],
        ]);

        $this->createOrder(5, Order::TYPE_TABLE, Order::KITCHEN_STATUS_IN_PREPARATION, [
            ['TARTUFATA', 1, 12.50, 'Medium well', OrderItem::STATUS_IN_PREPARATION],
            ['ADOBO', 1, 10.00, null, OrderItem::STATUS_PENDING],
            ['ESTRELLA DAMM', 2, 2.40, null, OrderItem::STATUS_READY],
        ]);

        $this->createOrder(10000, Order::TYPE_TAKEAWAY, Order::KITCHEN_STATUS_READY, [
            ['KOYA SMASH SPECIAL', 1, 13.99, null, OrderItem::STATUS_READY],
            ['MANGO FLOAT', 2, 5.90, null, OrderItem::STATUS_READY],
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

    private function seedReservations(User $admin): void
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
                'user_id' => $admin->id,
                'submission_token' => str($email.$time->toDateTimeString())->slug().'-demo',
            ]);
        }
    }

    private function seedFinance(User $admin): void
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
                'user_id' => $admin->id,
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
                'user_id' => $admin->id,
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
            'user_id' => $admin->id,
        ]);
    }
}
