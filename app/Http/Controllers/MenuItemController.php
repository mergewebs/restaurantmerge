<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\MenuCategory;
use App\Services\ProductImageService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuItemController extends Controller
{
    public function __construct(private readonly ProductImageService $productImageService)
    {
    }

    public function index()
    {
        $this->ensureAdmin();

        $categories = MenuCategory::query()->orderBy('sort_order')->orderBy('name')->get();
        $categoryOptions = $categories->where('is_active', true)->pluck('name');

        $menuItems = MenuItem::with('options')->orderedByCategory()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->groupBy('category');

        return view('menu-items.index', compact('menuItems', 'categoryOptions', 'categories'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();

        $validated = $this->validateMenuItem($request);
        $menuItem = MenuItem::create($validated);

        if ($request->hasFile('image')) {
            $this->productImageService->replaceUploadedImage($menuItem, $request->file('image'));
        }

        return redirect()->route('menu-items.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->ensureAdmin();

        $validated = $this->validateMenuItem($request);
        $menuItem->update($validated);

        if ($request->boolean('remove_image')) {
            $this->productImageService->removeImage($menuItem);
        }

        if ($request->hasFile('image')) {
            $this->productImageService->replaceUploadedImage($menuItem, $request->file('image'));
        }

        return redirect()->route('menu-items.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->ensureAdmin();

        $this->productImageService->deleteGeneratedImages($menuItem, deleteOriginal: true);
        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Product deleted successfully.');
    }

    private function validateMenuItem(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', 'string', 'max:80', Rule::exists('menu_categories', 'name')->where('is_active', true)],
            'description' => ['nullable', 'string', 'max:2000'],
            'ingredients' => ['nullable', 'string', 'max:2000'],
            'allergens' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'dine_in_price' => ['nullable', 'numeric', 'min:0'],
            'takeaway_price' => ['nullable', 'numeric', 'min:0'],
            'delivery_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['nullable', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'availability_status' => ['nullable', Rule::in(MenuItem::AVAILABILITY_STATUSES)],
            'item_kind' => ['nullable', Rule::in(MenuItem::ITEM_KINDS)],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['stock_quantity'] = $validated['stock_quantity'] ?? null;
        $validated['low_stock_threshold'] = $validated['low_stock_threshold'] ?? 5;
        $validated['description'] = $validated['description'] ?? null;
        $validated['ingredients'] = $validated['ingredients'] ?? null;
        $validated['allergens'] = $validated['allergens'] ?? null;
        $validated['availability_status'] = $validated['availability_status'] ?? 'available';
        $validated['item_kind'] = $validated['item_kind'] ?? 'regular';
        $validated['menu_category_id'] = MenuCategory::where('name', $validated['category'])->value('id');

        unset($validated['image'], $validated['remove_image']);

        return $validated;
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
