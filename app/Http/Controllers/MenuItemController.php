<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $validated['image_path'] = $this->storeImage($request->file('image'));

        MenuItem::create($validated);

        return redirect()->route('menu-items.index')->with('success', 'Product created successfully.');
    }

    public function update(Request $request, MenuItem $menuItem)
    {
        $this->ensureAdmin();

        $validated = $this->validateMenuItem($request);

        if ($request->boolean('remove_image')) {
            $this->deleteImage($menuItem->image_path);
            $validated['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            $newImagePath = $this->storeImage($request->file('image'));

            if ($newImagePath !== null) {
                $this->deleteImage($menuItem->image_path);
                $validated['image_path'] = $newImagePath;
            }
        }

        $menuItem->update($validated);

        return redirect()->route('menu-items.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(MenuItem $menuItem)
    {
        $this->ensureAdmin();

        $this->deleteImage($menuItem->image_path);
        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Product deleted successfully.');
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
            'image' => ['nullable', 'image', 'max:5120'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_package'] = $request->boolean('is_package');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['description'] = $validated['description'] ?? null;
        $validated['package_details'] = $validated['is_package']
            ? ($validated['package_details'] ?? null)
            : null;

        unset($validated['image'], $validated['remove_image']);

        return $validated;
    }

    private function storeImage(?UploadedFile $file): ?string
    {
        if ($file === null) {
            return null;
        }

        $extension = $file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg';
        $filename = Str::uuid()->toString() . '.' . strtolower($extension);

        Storage::disk('product-images')->putFileAs('', $file, $filename);

        return $filename;
    }

    private function deleteImage(?string $imagePath): void
    {
        if (blank($imagePath)) {
            return;
        }

        Storage::disk('product-images')->delete($imagePath);
    }

    private function ensureAdmin(): void
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
    }
}
