<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
            ->assertSee('/manifest.webmanifest?v=20260725-3', false)
            ->assertDontSee('data-pwa-install-button', false);
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
