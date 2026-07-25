<?php

namespace App\Http\Controllers;

use App\Models\InventoryIngredient;
use App\Models\MenuItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function index()
    {
        $this->ensureAdmin();
        $ingredients = InventoryIngredient::with('supplier')->orderBy('name')->get();
        $products = MenuItem::with('recipeIngredients')->orderBy('name')->get();
        $suppliers = Supplier::withCount('ingredients')->orderBy('name')->get();
        $movements = StockMovement::with(['supplier', 'order', 'user'])->latest('occurred_at')->limit(80)->get();
        $ingredientNames = $ingredients->pluck('name', 'id');
        $productNames = $products->pluck('name', 'id');
        $movements->each(function (StockMovement $movement) use ($ingredientNames, $productNames) {
            $movement->target_name = $movement->target_type === 'ingredient'
                ? ($ingredientNames[$movement->target_id] ?? 'Deleted ingredient')
                : ($productNames[$movement->target_id] ?? 'Deleted product');
        });
        $lowIngredientCount = $ingredients->filter(fn ($item) => (float) $item->stock_quantity <= (float) $item->low_stock_threshold)->count();
        $lowProductCount = $products->filter(fn ($item) => $item->stock_quantity !== null && $item->stock_quantity <= $item->low_stock_threshold)->count();

        return view('inventory.index', compact('ingredients', 'products', 'suppliers', 'movements', 'lowIngredientCount', 'lowProductCount'));
    }

    public function storeIngredient(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:inventory_ingredients,name'],
            'unit' => ['required', Rule::in(['unit', 'kg', 'g', 'l', 'ml', 'portion'])],
            'stock_quantity' => ['nullable', 'numeric', 'min:0'], 'low_stock_threshold' => ['nullable', 'numeric', 'min:0'],
            'cost_per_unit' => ['nullable', 'numeric', 'min:0'], 'supplier_id' => ['nullable', 'exists:suppliers,id'],
        ]);
        InventoryIngredient::create($validated + ['is_active' => true]);
        return back()->with('success', 'Ingredient added.');
    }

    public function updateIngredient(Request $request, InventoryIngredient $ingredient)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('inventory_ingredients', 'name')->ignore($ingredient)],
            'unit' => ['required', Rule::in(['unit', 'kg', 'g', 'l', 'ml', 'portion'])],
            'low_stock_threshold' => ['required', 'numeric', 'min:0'], 'cost_per_unit' => ['nullable', 'numeric', 'min:0'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'], 'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        $ingredient->update($validated);
        return back()->with('success', 'Ingredient updated.');
    }

    public function storeSupplier(Request $request)
    {
        $this->ensureAdmin();
        Supplier::create($this->supplierData($request));
        return back()->with('success', 'Supplier added.');
    }

    public function updateSupplier(Request $request, Supplier $supplier)
    {
        $this->ensureAdmin();
        $supplier->update($this->supplierData($request));
        return back()->with('success', 'Supplier updated.');
    }

    public function storeMovement(Request $request)
    {
        $this->ensureAdmin();
        $validated = $request->validate([
            'target_type' => ['required', Rule::in(['ingredient', 'product'])], 'target_id' => ['required', 'integer'],
            'movement_type' => ['required', Rule::in(['purchase', 'restock', 'waste', 'damaged', 'adjustment'])],
            'quantity' => ['required', 'numeric', 'not_in:0'], 'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'], 'reference' => ['nullable', 'string', 'max:100'], 'notes' => ['nullable', 'string', 'max:500'],
        ]);
        $target = $validated['target_type'] === 'ingredient' ? InventoryIngredient::findOrFail($validated['target_id']) : MenuItem::findOrFail($validated['target_id']);
        $quantity = (float) $validated['quantity'];
        if (in_array($validated['movement_type'], ['purchase', 'restock'], true)) $quantity = abs($quantity);
        if (in_array($validated['movement_type'], ['waste', 'damaged'], true)) $quantity = -abs($quantity);

        DB::transaction(function () use ($target, $validated, $quantity) {
            $newStock = max(0, (float) ($target->stock_quantity ?? 0) + $quantity);
            if ($target instanceof MenuItem) $newStock = (int) round($newStock);
            $target->update(['stock_quantity' => $newStock]);
            StockMovement::create([...$validated, 'quantity' => $quantity, 'user_id' => auth()->id(), 'occurred_at' => now()]);
        });
        return back()->with('success', 'Stock movement recorded.');
    }

    public function storeRecipe(Request $request, MenuItem $menuItem)
    {
        $this->ensureAdmin();
        $validated = $request->validate(['ingredient_id' => ['required', 'exists:inventory_ingredients,id'], 'quantity' => ['required', 'numeric', 'gt:0']]);
        $menuItem->recipeIngredients()->syncWithoutDetaching([$validated['ingredient_id'] => ['quantity' => $validated['quantity']]]);
        $menuItem->recipeIngredients()->updateExistingPivot($validated['ingredient_id'], ['quantity' => $validated['quantity']]);
        return back()->with('success', 'Recipe usage saved.');
    }

    public function destroyRecipe(MenuItem $menuItem, InventoryIngredient $ingredient)
    {
        $this->ensureAdmin();
        $menuItem->recipeIngredients()->detach($ingredient->id);
        return back()->with('success', 'Ingredient removed from recipe.');
    }

    private function supplierData(Request $request): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:120'], 'contact_name' => ['nullable', 'string', 'max:120'], 'phone' => ['nullable', 'string', 'max:40'], 'email' => ['nullable', 'email', 'max:150'], 'notes' => ['nullable', 'string', 'max:500']]);
    }

    private function ensureAdmin(): void { abort_unless(auth()->user()?->isAdmin(), 403); }
}
