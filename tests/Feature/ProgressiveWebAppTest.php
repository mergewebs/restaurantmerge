<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProgressiveWebAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_website_exposes_installable_pwa_metadata_and_install_control(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('/manifest.webmanifest?v=20260717-4', false)
            ->assertSee('apple-mobile-web-app-capable', false)
            ->assertSee('viewport-fit=cover', false)
            ->assertSee('data-pwa-install-button', false);
    }

    public function test_admin_exposes_pwa_runtime_without_an_install_control(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('/manifest.webmanifest?v=20260717-4', false)
            ->assertDontSee('data-pwa-install-button', false);
    }

    public function test_manifest_is_valid_and_configured_for_standalone_installation(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.webmanifest')), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('standalone', $manifest['display']);
        $this->assertSame(['standalone'], $manifest['display_override']);
        $this->assertSame('/?source=pwa', $manifest['start_url']);
        $this->assertSame('/', $manifest['scope']);
        $this->assertSame('#17110c', $manifest['theme_color']);
        $this->assertContains('maskable', array_column($manifest['icons'], 'purpose'));

        foreach ($manifest['icons'] as $icon) {
            $this->assertFileExists(public_path(ltrim($icon['src'], '/')));
        }
    }

    public function test_service_worker_only_handles_safe_get_static_assets(): void
    {
        $serviceWorker = file_get_contents(public_path('sw.js'));

        $this->assertStringContainsString("request.method !== 'GET'", $serviceWorker);
        $this->assertStringContainsString("request.mode === 'navigate'", $serviceWorker);
        $this->assertStringContainsString('PRIVATE_PATH.test(url.pathname)', $serviceWorker);
        $this->assertStringContainsString('SAFE_STATIC_PATH.test(url.pathname)', $serviceWorker);
        $this->assertStringContainsString("key.startsWith(CACHE_PREFIX)", $serviceWorker);
        $this->assertStringNotContainsString("caches.match('/", $serviceWorker);
    }
}
