<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LocaleController extends Controller
{
    public function update(Request $request): RedirectResponse
    {
        $availableLocales = array_keys(config('app.available_locales', ['es' => 'Español', 'en' => 'English']));

        $validated = $request->validate([
            'locale' => ['required', 'string', Rule::in($availableLocales)],
        ]);

        $request->session()->put('locale', $validated['locale']);

        return back();
    }
}
