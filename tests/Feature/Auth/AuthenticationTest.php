<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/admin/login');

        $response
            ->assertStatus(200)
            ->assertSee('autocomplete="current-password"', false)
            ->assertSee('value="123456"', false)
            ->assertSee('Show passcode')
            ->assertSee('Demo passcode:')
            ->assertDontSee('Autofill demo passcode');
    }

    public function test_users_can_authenticate_using_a_six_digit_passcode(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/admin/login', [
            'passcode' => '123456',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_an_invalid_passcode(): void
    {
        User::factory()->create();

        $response = $this->from('/admin/login')->post('/admin/login', [
            'passcode' => '654321',
        ]);

        $response
            ->assertRedirect('/admin/login')
            ->assertSessionHasErrors('passcode');

        $this->assertGuest();
    }

    public function test_admin_pages_stay_protected_by_authentication(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/admin/login');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/admin/logout');

        $this->assertGuest();
        $response->assertRedirect('/admin/login');
    }
}
