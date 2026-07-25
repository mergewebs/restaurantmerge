<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuCategoryController extends Controller
{
    public function store(Request $request)
    {
        $this->ensureAdmin();
        $validated = $this->validateCategory($request);
        MenuCategory::create($validated);

        return back()->with('success', 'Category added.');
    }

    public function update(Request $request, MenuCategory $menuCategory)
    {
        $this->ensureAdmin();
        $oldName = $menuCategory->name;
        $validated = $this->validateCategory($request, $menuCategory);
        $menuCategory->update($validated);
        $menuCategory->items()->where('category', $oldName)->update(['category' => $menuCategory->name]);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(MenuCategory $menuCategory)
    {
        $this->ensureAdmin();

        if ($menuCategory->items()->exists()) {
            return back()->with('error', 'Move or delete the products in this category first.');
        }

        $menuCategory->delete();

        return back()->with('success', 'Category deleted.');
    }

    private function validateCategory(Request $request, ?MenuCategory $category = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('menu_categories')->ignore($category)],
            'description' => ['nullable', 'string', 'max:160'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
