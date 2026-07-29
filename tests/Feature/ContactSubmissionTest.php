<?php

namespace Tests\Feature;

use App\Mail\ContactSubmissionNotification;
use App\Models\ContactSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_stores_a_submission(): void
    {
        Mail::fake();

        $this->post(route('contact-submissions.store'), [
            'full_name' => 'Ana Garcia', 'email' => 'ana@example.com', 'telephone' => '+34 600 123 456',
        ])->assertRedirect(route('home').'#contact')->assertSessionHas('contact_success');

        $this->assertDatabaseHas('contact_submissions', [
            'full_name' => 'Ana Garcia', 'email' => 'ana@example.com', 'telephone' => '+34 600 123 456',
        ]);

        Mail::assertSent(ContactSubmissionNotification::class, function (ContactSubmissionNotification $mail): bool {
            return $mail->hasTo('mergewebs@gmail.com')
                && $mail->hasReplyTo('ana@example.com')
                && $mail->submission->full_name === 'Ana Garcia';
        });
    }

    public function test_contact_form_validates_all_fields(): void
    {
        $this->from(route('home'))->post(route('contact-submissions.store'), [
            'full_name' => '', 'email' => 'invalid', 'telephone' => '',
        ])->assertRedirect(route('home'))->assertSessionHasErrors(['full_name', 'email', 'telephone']);

        $this->assertSame(0, ContactSubmission::count());
    }

    public function test_contact_form_supports_submission_without_a_page_reload(): void
    {
        Mail::fake();

        $this->postJson(route('contact-submissions.store'), [
            'full_name' => 'Ana Garcia',
            'email' => 'ana@example.com',
            'telephone' => '+34 600 123 456',
        ])->assertCreated()->assertJson([
            'message' => 'Thanks! Your enquiry has been sent. We will contact you shortly.',
        ]);

        $this->assertDatabaseHas('contact_submissions', ['email' => 'ana@example.com']);
        Mail::assertSent(ContactSubmissionNotification::class);
    }

    public function test_administrators_can_view_contact_submissions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        ContactSubmission::create(['full_name' => 'Ana Garcia', 'email' => 'ana@example.com', 'telephone' => '+34 600 123 456']);

        $this->actingAs($admin)->get(route('contact-submissions.index'))
            ->assertOk()->assertSeeText('Ana Garcia')->assertSeeText('ana@example.com')->assertSeeText('+34 600 123 456');
    }

    public function test_contact_submissions_are_not_available_to_non_administrators(): void
    {
        $this->get(route('contact-submissions.index'))->assertRedirect(route('login'));

        foreach (['waiter', 'demo'] as $role) {
            $this->actingAs(User::factory()->create(['role' => $role]))
                ->get(route('contact-submissions.index'))->assertForbidden();
        }
    }
}
