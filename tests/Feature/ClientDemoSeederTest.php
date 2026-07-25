<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ClientDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_demo_seeds_the_entire_application_without_changing_users(): void
    {
        User::factory()->create(['role'=>'owner','name'=>'Existing Client Owner']);
        $before=User::query()->count();
        $this->seed(ClientDemoSeeder::class);

        $this->assertSame($before,User::query()->count());
        $this->assertDatabaseHas('users',['name'=>'Existing Client Owner']);
        foreach ([
            'menu_items'=>8,'restaurant_tables'=>8,'reservations'=>8,'orders'=>9,'order_items'=>9,
            'order_payments'=>7,'suppliers'=>5,'inventory_ingredients'=>8,'stock_movements'=>8,
            'cash_register_sessions'=>5,'incomes'=>8,'expenses'=>8,'cashouts'=>5,
            'staff_schedules'=>7,'staff_attendances'=>5,'restaurant_exceptions'=>5,
        ] as $table=>$minimum) {
            $this->assertGreaterThanOrEqual($minimum,\DB::table($table)->count(),$table.' should contain demo records.');
        }
    }
}
