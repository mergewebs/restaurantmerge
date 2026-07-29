<?php

namespace Tests\Feature;

use App\Models\DemoAccessHistory;
use App\Models\DemoAccessSession;
use App\Models\DemoIpAddress;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoAccessAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private const AGENT = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 Version/17.0 Mobile/15E148 Safari/604.1';

    public function test_demo_login_creates_one_ip_and_one_session_then_records_html_pages(): void
    {
        User::factory()->create(['role' => 'demo', 'passcode' => '123456']);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.12'])
            ->withHeaders(['User-Agent' => self::AGENT, 'CF-IPCountry' => 'ES', 'X-Vercel-IP-City' => 'Madrid'])
            ->post('/admin/login', ['passcode' => '123456'])->assertRedirect('/admin/dashboard');

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.12'])
            ->withHeader('User-Agent', self::AGENT)->get(route('dashboard'))->assertOk();
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.12'])
            ->withHeader('User-Agent', self::AGENT)->get(route('orders.index'))->assertOk();
        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.12'])
            ->withHeaders(['User-Agent' => self::AGENT, 'X-Requested-With' => 'XMLHttpRequest'])
            ->get(route('dashboard'))->assertOk();

        $ip = DemoIpAddress::sole();
        $this->assertSame('203.0.113.12', $ip->ip_address);
        $this->assertSame(1, $ip->visit_count);
        $this->assertSame('Safari', $ip->browser);
        $this->assertSame('Mobile', $ip->device_type);
        $this->assertSame('iOS', $ip->operating_system);
        $this->assertSame('ES', $ip->country);
        $this->assertSame('Madrid', $ip->city);
        $this->assertSame('/admin/orders', $ip->last_page);
        $this->assertDatabaseCount('demo_access_sessions', 1);
        $this->assertDatabaseCount('demo_access_history', 2);
    }

    public function test_a_new_demo_login_from_the_same_ip_updates_the_record_and_visit_count(): void
    {
        User::factory()->create(['role' => 'demo', 'passcode' => '123456']);
        $server = ['REMOTE_ADDR' => '198.51.100.8'];

        $this->withServerVariables($server)->post('/admin/login', ['passcode' => '123456']);
        $this->post(route('logout'));
        $this->withServerVariables($server)->post('/admin/login', ['passcode' => '123456']);

        $this->assertDatabaseCount('demo_ip_addresses', 1);
        $this->assertSame(2, DemoIpAddress::sole()->visit_count);
        $this->assertDatabaseCount('demo_access_sessions', 2);
    }

    public function test_only_real_administrators_can_view_and_filter_demo_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $record = DemoIpAddress::create([
            'ip_address' => '192.0.2.44', 'first_access_at' => now(), 'last_access_at' => now(),
            'last_active_at' => now(), 'visit_count' => 2, 'browser' => 'Chrome', 'device_type' => 'Desktop',
            'operating_system' => 'Windows', 'country' => 'ES', 'city' => 'Madrid', 'last_page' => '/admin/dashboard',
        ]);
        DemoAccessSession::create([
            'demo_ip_address_id' => $record->id, 'session_hash' => hash('sha256', 'active-session'),
            'started_at' => now(), 'last_activity_at' => now(), 'entry_page' => '/admin/dashboard',
        ]);

        $this->actingAs($admin)->get(route('demo-access.index', ['browser' => 'Chrome', 'status' => 'active']))
            ->assertOk()->assertSeeText('192.0.2.44')->assertSeeText('Returning users');
        $this->actingAs($admin)->get(route('demo-access.show', $record))
            ->assertOk()->assertSeeText('Page access history');

        foreach (['owner', 'waiter', 'demo'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->get(route('demo-access.index'))->assertForbidden();
        }
    }

    public function test_an_administrator_can_block_and_unblock_an_ip(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $record = DemoIpAddress::create([
            'ip_address' => '203.0.113.90', 'first_access_at' => now(), 'last_access_at' => now(),
            'last_active_at' => now(), 'visit_count' => 1,
        ]);

        $this->actingAs($admin)->patch(route('demo-access.block', $record))->assertRedirect();
        $this->assertTrue($record->fresh()->is_blocked);
        $this->actingAs($admin)->patch(route('demo-access.block', $record))->assertRedirect();
        $this->assertFalse($record->fresh()->is_blocked);
    }

    public function test_a_blocked_ip_gets_a_clear_message_and_cannot_start_or_continue_demo_access(): void
    {
        $demo = User::factory()->create(['role' => 'demo', 'passcode' => '123456']);
        DemoIpAddress::create([
            'ip_address' => '203.0.113.77', 'first_access_at' => now(), 'last_access_at' => now(),
            'visit_count' => 1, 'is_blocked' => true, 'blocked_at' => now(),
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.77'])
            ->post('/admin/login', ['passcode' => '123456'])
            ->assertRedirect()->assertSessionHasErrors('passcode');
        $this->assertGuest();

        $this->actingAs($demo)->withServerVariables(['REMOTE_ADDR' => '203.0.113.77'])
            ->get(route('dashboard'))->assertRedirect(route('login'))->assertSessionHasErrors('passcode');
        $this->assertGuest();
        $this->assertSame(1, DemoIpAddress::sole()->visit_count);
        $this->assertSame(0, DemoAccessSession::count());
        $this->assertSame(0, DemoAccessHistory::count());
    }
}
