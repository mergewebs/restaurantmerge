<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuItemOption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuItemOptionController extends Controller
{
    public function store(Request $request, MenuItem $menuItem)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'type' => ['required', Rule::in(MenuItem::OPTION_TYPES)],
            'name' => ['required', 'string', 'max:100'],
            'price_adjustment' => ['nullable', 'numeric', 'min:-9999', 'max:9999'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['price_adjustment'] = $validated['price_adjustment'] ?? 0;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);
        $menuItem->options()->create($validated);

        return back()->with('success', 'Product option added.')->with('edit_menu_item_id', $menuItem->id);
    }

    public function destroy(MenuItem $menuItem, MenuItemOption $option)
    {
        $this->ensureAdmin();
        abort_unless($option->menu_item_id === $menuItem->id, 404);
        $option->delete();

        return back()->with('success', 'Product option removed.')->with('edit_menu_item_id', $menuItem->id);
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
