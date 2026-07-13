<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PasscodeController extends Controller
{
    /**
     * Update the user's passcode.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePasscode', [
            'current_passcode' => ['required', 'current_password'],
            'passcode' => ['required', 'digits:6', 'confirmed'],
        ]);

        $request->user()->update([
            'passcode' => $validated['passcode'],
        ]);

        return back()->with('status', 'passcode-updated');
    }
}
