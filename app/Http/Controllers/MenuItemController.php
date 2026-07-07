<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuItemController extends Controller
{
    public function index()
    {
        $this->ensureAdmin();

        $categoryOptions = MenuItem::CATEGORY_OPTIONS;

        $menuItems = MenuItem::orderedByCategory()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('menu-items.index', compact('menuItems', 'categoryOptions'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $validated = $this->validateMenuItem($request);

        MenuItem::create($validated);

        return redirect()->route('menu-items.index')->with('success', 'Menu item created.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->ensureAdmin();

        $validated = $this->validateMenuItem($request);

        $menuItem->update($validated);

        return redirect()->route('menu-items.index')->with('success', 'Menu item updated.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->ensureAdmin();

        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Menu item deleted.');
    }

    private function validateMenuItem(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80', Rule::in(MenuItem::CATEGORY_OPTIONS)],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'is_package' => ['nullable', 'boolean'],
            'package_details' => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_package'] = $request->boolean('is_package');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['description'] = $validated['description'] ?? null;
        $validated['package_details'] = $validated['is_package']
            ? ($validated['package_details'] ?? null)
            : null;

        return $validated;
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
