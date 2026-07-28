<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\RestaurantException;
use App\Models\Supplier;
use App\Models\User;
use Database\Seeders\DemoUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class DemoModeTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_account_is_seeded_with_full_read_access(): void
    {
        $this->seed(DemoUserSeeder::class);

        $demo = User::where('role', 'demo')->sole();

        $this->assertSame('Demo User', $demo->name);
        $this->assertTrue(Hash::check('123456', $demo->passcode));
        $this->assertTrue($demo->isAdmin());
        $this->assertTrue($demo->mayDiscount());
        $this->assertTrue($demo->mayRefund());
        $this->assertTrue($demo->mayCancelOrders());

        foreach ([
            'dashboard', 'orders.index', 'tables.index', 'tables.kitchen',
            'reservations.index', 'finance.index', 'menu-items.index',
            'inventory.index', 'cash-register.index', 'reports.index',
            'users.index', 'restaurant.config', 'profile.edit',
        ] as $route) {
            $this->actingAs($demo)->get(route($route))->assertOk();
        }
    }

    public function test_demo_login_does_not_update_last_login_and_shows_the_contact_experience(): void
    {
        $this->seed(DemoUserSeeder::class);
        $demo = User::where('role', 'demo')->sole();

        $this->post('/admin/login', ['passcode' => '123456'])
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticatedAs($demo);
        $this->assertNull($demo->fresh()->last_login_at);
        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Demo mode · Read only')
            ->assertSee('Contact Us')
            ->assertSee('Request Full Access');
    }

    public function test_demo_form_requests_cannot_change_business_data(): void
    {
        $demo = User::factory()->create(['role' => 'demo']);

        $this->actingAs($demo)->post(route('inventory.suppliers.store'), [
            'name' => 'Should Not Exist',
            'contact_name' => 'Blocked',
        ])->assertRedirect()->assertSessionHas('demo_action_blocked');

        $this->assertSame(0, Supplier::count());

        $order = Order::create([
            'status' => 'open',
            'workflow_status' => 'new',
            'order_type' => 'takeaway',
            'payment_status' => 'unpaid',
            'total' => 20,
        ]);

        $this->actingAs($demo)
            ->patchJson(route('orders.status.update', $order), ['workflow_status' => 'completed'])
            ->assertStatus(423)
            ->assertJson(['demo_mode' => true]);

        $this->assertSame('new', $order->fresh()->workflow_status);
    }

    public function test_demo_cannot_change_account_permissions_or_system_settings(): void
    {
        $demo = User::factory()->create([
            'role' => 'demo',
            'name' => 'Demo User',
            'passcode' => '123456',
        ]);
        $originalPasscode = $demo->passcode;

        $this->actingAs($demo)->put(route('passcode.update'), [
            'current_passcode' => '123456',
            'passcode' => '654321',
            'passcode_confirmation' => '654321',
        ])->assertRedirect()->assertSessionHas('demo_action_blocked');

        $this->actingAs($demo)->patch(route('profile.update'), [
            'name' => 'Changed Demo',
        ])->assertRedirect()->assertSessionHas('demo_action_blocked');

        $this->actingAs($demo)->post(route('restaurant.toggle-today'))
            ->assertRedirect()->assertSessionHas('demo_action_blocked');

        $demo->refresh();
        $this->assertSame('Demo User', $demo->name);
        $this->assertSame($originalPasscode, $demo->passcode);
        $this->assertSame(0, RestaurantException::count());
    }

    public function test_demo_user_can_still_log_out(): void
    {
        $demo = User::factory()->create(['role' => 'demo']);

        $this->actingAs($demo)->post(route('logout'))->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_demo_can_explore_feature_pages_even_when_the_feature_is_disabled(): void
    {
        config()->set('features.tables', false);
        config()->set('features.kitchen', false);

        $demo = User::factory()->create(['role' => 'demo']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($demo)->get(route('tables.index'))->assertOk();
        $this->actingAs($demo)->get(route('tables.kitchen'))->assertOk();
        $this->actingAs($admin)->get(route('tables.index'))->assertNotFound();
    }

    public function test_web_middleware_blocks_demo_writes_even_without_route_level_demo_middleware(): void
    {
        Route::middleware('web')->post('/demo-write-probe', fn () => response()->noContent());
        $demo = User::factory()->create(['role' => 'demo']);

        $this->actingAs($demo)
            ->post('/demo-write-probe')
            ->assertRedirect()
            ->assertSessionHas('demo_action_blocked');
    }

    public function test_demo_page_includes_final_action_interception(): void
    {
        $demo = User::factory()->create(['role' => 'demo']);

        $this->actingAs($demo)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('finalActionPattern', false)
            ->assertSee('demoFinalAction', false);
    }

    public function test_demo_get_request_refreshes_complete_sample_data_when_enabled(): void
    {
        config()->set('demo.data_enabled', true);
        $demo = User::factory()->create(['role' => 'demo']);

        $this->actingAs($demo)->get(route('dashboard'))->assertOk();

        $this->assertTrue(Reservation::whereDate('reservation_time', today())->exists());
        $this->assertTrue(Order::whereDate('created_at', today())->exists());
        $this->assertDatabaseHas('stock_movements', ['reference' => 'CLIENT-DEMO-STOCK-1']);
        $this->assertDatabaseHas('staff_schedules', ['notes' => 'Client demo shift 1']);
    }

    public function test_demo_data_refresh_never_runs_for_real_users_or_when_disabled(): void
    {
        $demo = User::factory()->create(['role' => 'demo']);
        $owner = User::factory()->create(['role' => 'owner']);

        config()->set('demo.data_enabled', false);
        $this->actingAs($demo)->get(route('dashboard'))->assertOk();
        $this->assertDatabaseCount('reservations', 0);

        config()->set('demo.data_enabled', true);
        $this->actingAs($owner)->get(route('dashboard'))->assertOk();
        $this->assertDatabaseCount('reservations', 0);
    }
}
