<?php

namespace Tests\Feature;

use App\Models\RestaurantTable;
use App\Models\User;
use Database\Seeders\AdminAccessSeeder;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\PresentationDemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class AdminAccessSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_seeds_only_the_configured_admin_access(): void
    {
        config()->set('access.admin.name', 'Production Admin');
        config()->set('access.admin.passcode', '654321');

        (new AdminAccessSeeder)->setContainer($this->app)->run();

        $admin = User::query()->sole();

        $this->assertSame('Production Admin', $admin->name);
        $this->assertSame('admin', $admin->role);
        $this->assertTrue(Hash::check('654321', $admin->passcode));
    }

    public function test_the_demo_seeder_reuses_the_single_admin_account(): void
    {
        config()->set('access.admin.name', 'Demo Admin');
        config()->set('access.admin.passcode', '654321');

        (new PresentationDemoSeeder)->setContainer($this->app)->run();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name' => 'Demo Admin',
            'role' => 'admin',
        ]);
        $this->assertDatabaseMissing('users', ['role' => 'owner']);
        $this->assertDatabaseMissing('users', ['role' => 'waiter']);
        $this->assertDatabaseMissing('users', ['role' => 'superadmin']);
    }

    public function test_production_seeding_requires_an_explicit_admin_passcode(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config()->set('access.admin.passcode');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('SEED_ADMIN_PASSCODE');

        (new AdminAccessSeeder)->setContainer($this->app)->run();
    }

    public function test_the_production_database_seeder_does_not_create_demo_data(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        config()->set('access.admin.name', 'Production Admin');
        config()->set('access.admin.passcode', '654321');

        (new DatabaseSeeder)->setContainer($this->app)->run();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'name' => 'Production Admin',
            'role' => 'admin',
        ]);
        $this->assertSame(0, RestaurantTable::query()->count());
        $this->assertDatabaseCount('reservations', 0);
        $this->assertDatabaseCount('incomes', 0);
        $this->assertDatabaseCount('expenses', 0);
    }
}
