<?php

namespace App\Services;

use App\Models\MenuItem;
use App\Models\Order;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function deductCompletedOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $lockedOrder = Order::query()->lockForUpdate()->findOrFail($order->id);
            if ($lockedOrder->inventory_deducted_at) {
                return;
            }

            $lockedOrder->load('items');
            foreach ($lockedOrder->items as $orderItem) {
                $menuItem = $orderItem->menu_item_id
                    ? MenuItem::with('recipeIngredients')->find($orderItem->menu_item_id)
                    : MenuItem::with('recipeIngredients')->whereRaw('LOWER(name) = ?', [mb_strtolower($orderItem->name)])->first();

                if (! $menuItem) {
                    continue;
                }

                $soldQuantity = (int) $orderItem->quantity;
                if ($menuItem->stock_quantity !== null) {
                    $menuItem->update(['stock_quantity' => max(0, $menuItem->stock_quantity - $soldQuantity)]);
                    $this->record('product', $menuItem->id, 'order', -$soldQuantity, $lockedOrder->id, "Completed order #{$lockedOrder->id}");
                }

                foreach ($menuItem->recipeIngredients as $ingredient) {
                    $used = (float) $ingredient->pivot->quantity * $soldQuantity;
                    $ingredient->update(['stock_quantity' => max(0, (float) $ingredient->stock_quantity - $used)]);
                    $this->record('ingredient', $ingredient->id, 'order', -$used, $lockedOrder->id, "{$menuItem->name} × {$soldQuantity}");
                }
            }

            $lockedOrder->update(['inventory_deducted_at' => now()]);
        });
    }

    private function record(string $targetType, int $targetId, string $type, float $quantity, int $orderId, string $notes): void
    {
        StockMovement::create([
            'target_type' => $targetType,
            'target_id' => $targetId,
            'movement_type' => $type,
            'quantity' => $quantity,
            'order_id' => $orderId,
            'notes' => $notes,
            'occurred_at' => now(),
        ]);
    }
}
