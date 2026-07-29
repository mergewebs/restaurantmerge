<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmissionNotification;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactSubmissionController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()?->isAdmin() && ! $request->user()->isDemo(), 403);

        return view('contact-submissions.index', [
            'submissions' => ContactSubmission::query()->latest()->paginate(20),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'telephone' => ['required', 'string', 'max:30'],
        ]);

        $submission = ContactSubmission::create($validated);

        Mail::to(config('restaurant.contact_form_recipient'))
            ->send(new ContactSubmissionNotification($submission));

        return redirect()->to(route('home').'#contact')
            ->with('contact_success', 'Thanks for getting in touch. We will contact you shortly.');
    }
}
