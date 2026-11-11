<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProgressiveWebAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_exposes_its_pwa_runtime(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('/manifest.webmanifest?v=20261111-1', false)
            ->assertSee('/pwa.js?v=20261111-1', false)
            ->assertSee('rel="apple-touch-icon"', false)
            ->assertSee('data-pwa-install-button hidden', false);
    }

    public function test_manifest_is_valid_and_configured_for_standalone_installation(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('Merge', $manifest['name']);
        $this->assertSame('Merge', $manifest['short_name']);
        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame(['standalone'], $manifest['display_override']);
        $this->assertSame('/admin/dashboard?source=pwa', $manifest['start_url']);
        $this->assertSame('/admin/', $manifest['scope']);
        $this->assertSame('#271c16', $manifest['theme_color']);
        $this->assertTrue(
            collect($manifest['icons'])->contains(
                fn (array $icon): bool => str_contains($icon['purpose'] ?? '', 'maskable')
            )
        );

        foreach ($manifest['icons'] as $icon) {
            $this->assertFileExists(public_path(ltrim($icon['src'], '/')));
            [$width, $height] = getimagesize(public_path(ltrim($icon['src'], '/')));
            $this->assertSame($icon['sizes'], "{$width}x{$height}");
            $this->assertSame('image/png', $icon['type']);
        }

        $this->assertContains('192x192', array_column($manifest['icons'], 'sizes'));
        $this->assertContains('512x512', array_column($manifest['icons'], 'sizes'));
        $this->assertSame([180, 180], array_slice(getimagesize(public_path('pwa/icons/apple-touch-icon.png')), 0, 2));
    }

    public function test_public_website_has_no_pwa_runtime_or_install_metadata(): void
    {
        $response = $this->get('/')->assertOk()
            ->assertSee('restaurant-mark.svg')
            ->assertDontSee('rel="manifest"', false)
            ->assertDontSee('pwa-mobile.css')
            ->assertDontSee('pwa.js')
            ->assertDontSee('apple-mobile-web-app-capable')
            ->assertDontSee('serviceWorker');

        $this->assertStringNotContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    public function test_admin_only_downloads_page_specific_libraries_where_needed(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user)->get('/admin/dashboard')->assertOk()
            ->assertDontSee('npm/chart.js')
            ->assertDontSee('npm/flatpickr');

        $this->get('/admin/reservations/create')->assertOk()
            ->assertSee('npm/flatpickr')
            ->assertDontSee('npm/chart.js');
    }

    public function test_login_and_unauthenticated_redirects_are_not_cached(): void
    {
        $this->get('/admin/login')->assertOk()
            ->assertSee('rel="manifest"', false)
            ->assertHeader('Pragma', 'no-cache');

        $response = $this->get('/admin/dashboard')->assertRedirect('/admin/login');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
    }

    #[DataProvider('adminPages')]
    public function test_admin_pages_use_the_shared_mobile_runtime_and_do_not_cache_private_data(string $path): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->get($path)->assertOk()
            ->assertSee('/pwa-mobile.css?v=20261111-1', false)
            ->assertSee('data-admin-connection', false);

        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('private', $response->headers->get('Cache-Control'));
    }

    public static function adminPages(): array
    {
        return array_map(fn ($path) => [$path], [
            '/admin/dashboard', '/admin/orders', '/admin/reservations',
            '/admin/reservations/create', '/admin/tables', '/admin/tables/completed',
            '/admin/kitchen', '/admin/finance', '/admin/finance/categories', '/admin/menu-items',
            '/admin/inventory', '/admin/cash-register', '/admin/reports',
            '/admin/restaurant/config', '/admin/users', '/admin/profile',
            '/admin/contact-submissions', '/admin/demo-access',
        ]);
    }

    public function test_live_admin_json_is_not_cached_and_logout_keeps_normal_authentication(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->getJson('/admin/kitchen/snapshot')->assertOk();
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));

        foreach (['/admin/finance/history', '/admin/finance/stats'] as $path) {
            $response = $this->get($path)->assertRedirect();
            $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        }

        $response = $this->post('/admin/logout')->assertRedirect('/admin/login');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        $this->assertGuest();
    }
}
