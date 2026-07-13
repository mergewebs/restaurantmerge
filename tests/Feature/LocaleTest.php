<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    public function test_can_switch_locale_via_post(): void
    {
        $response = $this->post('/locale', [
            'locale' => 'en',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('locale', 'en');
    }

    public function test_invalid_locale_is_rejected(): void
    {
        $response = $this->post('/locale', [
            'locale' => 'fr',
        ]);

        $response->assertSessionHasErrors('locale');
    }

    public function test_homepage_respects_session_locale(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ES');

        $response = $this->withSession(['locale' => 'es'])->get('/');
        $response->assertStatus(200);
        $response->assertSee('EN');
    }

    public function test_homepage_auto_detects_spanish_browser_locale(): void
    {
        $response = $this->withHeader('Accept-Language', 'es-ES,es;q=0.9,en;q=0.8')->get('/');

        $response->assertStatus(200);
        $response->assertSee('EN');
    }

    public function test_homepage_defaults_to_english_for_non_spanish_browser_locale(): void
    {
        $response = $this->withHeader('Accept-Language', 'fr-FR,fr;q=0.9,de;q=0.8')->get('/');

        $response->assertStatus(200);
        $response->assertSee('ES');
    }
}
