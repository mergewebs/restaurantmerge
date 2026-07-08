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
        $response->assertSee('EN');

        // Switch to English
        $response = $this->withSession(['locale' => 'en'])->get('/');
        $response->assertStatus(200);
        $response->assertSee('ES');
    }
}
