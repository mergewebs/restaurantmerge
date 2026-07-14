<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/admin/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/admin/profile', [
                'name' => 'Test User',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $this->assertSame('Test User', $user->refresh()->name);
    }

    public function test_admin_can_delete_their_account_with_their_passcode(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($user)
            ->delete('/admin/profile', [
                'passcode' => '123456',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_non_admin_cannot_see_or_delete_their_account(): void
    {
        $user = User::factory()->create(['role' => 'waiter']);

        $this->actingAs($user)
            ->get('/admin/profile')
            ->assertOk()
            ->assertDontSee('Delete account');

        $this->actingAs($user)
            ->delete('/admin/profile', [
                'passcode' => '123456',
            ])
            ->assertForbidden();

        $this->assertNotNull($user->fresh());
    }

    public function test_correct_passcode_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($user)
            ->from('/admin/profile')
            ->delete('/admin/profile', [
                'passcode' => '654321',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'passcode')
            ->assertRedirect('/admin/profile');

        $this->assertNotNull($user->fresh());
    }
}
