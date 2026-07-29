<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\DemoAccessSession;
use App\Services\DemoAccessTracker;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, DemoAccessTracker $tracker): RedirectResponse
    {
        $request->authenticate();

        if (! $request->user()?->isDemo()) {
            $request->user()?->forceFill([
                'last_login_at' => now(),
            ])->save();
        }

        $request->session()->regenerate();

        if ($request->user()?->isDemo()) {
            $tracker->startSession($request);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()?->isDemo()) {
            DemoAccessSession::find($request->session()->get('demo_access_session_id'))
                ?->update(['ended_at' => now(), 'last_activity_at' => now()]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
