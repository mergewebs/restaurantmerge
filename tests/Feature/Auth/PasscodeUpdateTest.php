<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasscodeUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_passcode_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/admin/profile')
            ->put('/admin/passcode', [
                'current_passcode' => '123456',
                'passcode' => '654321',
                'passcode_confirmation' => '654321',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/admin/profile');

        $this->assertTrue(Hash::check('654321', $user->refresh()->passcode));
    }

    public function test_correct_passcode_must_be_provided_to_update_passcode(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/admin/profile')
            ->put('/admin/passcode', [
                'current_passcode' => '000000',
                'passcode' => '654321',
                'passcode_confirmation' => '654321',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePasscode', 'current_passcode')
            ->assertRedirect('/admin/profile');
    }
}
