<?php

namespace App\Http\Controllers;

use App\Http\Requests\TableOrderSyncRequest;
use App\Models\MenuItem;
use App\Models\Income;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use App\Models\CashRegisterSession;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TableManagementController extends Controller
{
    private const KITCHEN_VERSION_CACHE_KEY = 'kitchen_board_version';

    public function __construct(private readonly InventoryService $inventoryService)
    {
    }

    public function index()
    {
        $tables = RestaurantTable::with([
            'openOrder.items' => fn ($query) => $query->latest(),
            'parentTable.openOrder.items' => fn ($query) => $query->latest(),
            'mergedTables',
        ])->orderBy('area')->orderBy('number')->get();
        $availableOrders = Order::with('table')->where('status', Order::STATUS_OPEN)->whereNull('restaurant_table_id')->latest()->get();
        $areas = $tables->groupBy('area');

        return view('tables.index', compact('tables', 'areas', 'availableOrders'));
    }

    public function orderPage(RestaurantTable $table)
    {
        $table->load([
            'openOrder.items' => fn ($query) => $query->latest(),
        ]);

        $menuItemsCollection = MenuItem::query()
            ->where('is_active', true)
            ->where('availability_status', 'available')
            ->orderedByCategory()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'category',
                'description',
                'image_path',
                'image_display_path',
                'image_thumbnail_path',
                'price',
                'dine_in_price',
                'station',
                'allergens',
            ]);

        $menuItems = $menuItemsCollection->groupBy('category');
        $menuItemsFlat = $menuItemsCollection->map(fn (MenuItem $item) => [
            'id' => $item->id,
            'name' => $item->name,
            'category' => $item->category,
            'description' => $item->description,
            'image_url' => $item->image_url,
            'image_thumbnail_url' => $item->image_thumbnail_url,
            'price' => $item->priceFor('dine_in'),
        ])->values()->all();
        $initialOrder = $this->tableOrderPayload($table);

        return view('tables.order', compact('table', 'menuItems', 'menuItemsFlat', 'initialOrder'));
    }

    public function kitchen()
    {
        return view('tables.kitchen', [
            'initialOrders' => $this->kitchenOrdersPayload(),
            'initialKitchenVersion' => $this->getKitchenVersion(),
        ]);
    }

    public function kitchenSnapshot(Request $request): JsonResponse
    {
        $sinceVersion = (int) $request->query('since_version', 0);
        $currentVersion = $this->getKitchenVersion();

        if ($sinceVersion >= $currentVersion) {
            return response()->json([
                'version' => $currentVersion,
                'changed' => false,
                'orders' => [],
            ]);
        }

        return response()->json([
            'version' => $currentVersion,
            'changed' => true,
            'orders' => $this->kitchenOrdersPayload(),
        ]);
    }

    public function kitchenStream(Request $request): StreamedResponse
    {
        $lastSeenVersion = (int) $request->query('last_version', 0);

        return response()->stream(function () use ($lastSeenVersion) {
            $connectionStartedAt = time();
            $currentVersion = $lastSeenVersion;
            $lastKeepAlive = time();

            while (! connection_aborted() && (time() - $connectionStartedAt) < 55) {
                $latestVersion = $this->getKitchenVersion();

                if ($latestVersion !== $currentVersion) {
                    echo "event: kitchen.update\n";
                    echo 'data: ' . json_encode(['version' => $latestVersion]) . "\n\n";

                    @ob_flush();
                    flush();

                    $currentVersion = $latestVersion;
                }

                if ((time() - $lastKeepAlive) >= 15) {
                    echo ": keep-alive\n\n";
                    @ob_flush();
                    flush();
                    $lastKeepAlive = time();
                }

                sleep(1);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-transform',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no',
        ]);
    }

    public function completedOrders()
    {
        $orders = Order::with([
            'table',
            'items' => fn ($query) => $query->latest(),
        ])
            ->where('status', Order::STATUS_CLOSED)
            ->latest()
            ->paginate(20);

        return view('tables.completed', compact('orders'));
    }

    public function storeTable(Request $request)
    {
        $validated = $request->validate([
            'number' => ['required', 'integer', 'min:1', 'max:999'],
            'seats' => ['nullable', 'integer', 'min:1', 'max:30'],
            'area' => ['nullable', 'string', 'max:60'],
        ]);

        $existingTable = RestaurantTable::where('number', $validated['number'])->first();
        if ($existingTable) {
            $existingTable->load('openOrder');

            if (! $existingTable->openOrder) {
                return redirect()
                    ->route('tables.order', $existingTable)
                    ->with('info', "Table {$existingTable->number} is ready for a new order.");
            }

            return redirect()
                ->route('tables.index')
                ->with('info', "Table {$existingTable->number} already has an active order.");
        }

        $table = RestaurantTable::create([
            'number' => $validated['number'],
            'seats' => $validated['seats'] ?? 4,
            'area' => $validated['area'] ?? 'Main Room',
        ]);

        return redirect()->route('tables.order', $table)->with('success', "Table {$table->number} is ready.");
    }

    public function updateFloorStatus(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate(['service_status' => ['required', Rule::in(RestaurantTable::SERVICE_STATUSES)]]);
        if ($table->floor_order && $validated['service_status'] !== RestaurantTable::STATUS_AVAILABLE) {
            return back()->with('error', 'An occupied table cannot be marked reserved or cleaning.');
        }
        $table->update(['service_status' => $validated['service_status']]);

        return back()->with('success', "Table {$table->number} is now {$validated['service_status']}.");
    }

    public function assignOrder(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate(['order_id' => ['required', 'exists:orders,id']]);
        $target = $table->parentTable ?: $table;
        if ($target->openOrder()->exists()) {
            return back()->with('error', "Table {$target->number} already has an active order.");
        }
        $order = Order::where('status', Order::STATUS_OPEN)->findOrFail($validated['order_id']);
        $order->update(['restaurant_table_id' => $target->id, 'order_type' => 'dine_in']);
        $target->update(['service_status' => RestaurantTable::STATUS_AVAILABLE]);

        return back()->with('success', "Order #{$order->id} assigned to table {$target->number}.");
    }

    public function moveOrder(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate(['target_table_id' => ['required', 'exists:restaurant_tables,id', Rule::notIn([$table->id])]]);
        $order = $table->openOrder;
        if (! $order) {
            return back()->with('error', "Table {$table->number} has no active order to move.");
        }
        $target = RestaurantTable::with('openOrder')->findOrFail($validated['target_table_id']);
        $target = $target->parentTable ?: $target;
        if ($target->openOrder) {
            return back()->with('error', "Table {$target->number} is occupied.");
        }
        $order->update(['restaurant_table_id' => $target->id]);
        $table->update(['service_status' => RestaurantTable::STATUS_CLEANING]);
        $target->update(['service_status' => RestaurantTable::STATUS_AVAILABLE]);

        return back()->with('success', "Order moved from table {$table->number} to table {$target->number}.");
    }

    public function mergeTable(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate(['target_table_id' => ['required', 'exists:restaurant_tables,id', Rule::notIn([$table->id])]]);
        $target = RestaurantTable::with('openOrder')->findOrFail($validated['target_table_id']);
        if ($table->merged_into_table_id || $target->merged_into_table_id) {
            return back()->with('error', 'Choose two unmerged tables.');
        }

        DB::transaction(function () use ($table, $target) {
            $sourceOrder = $table->openOrder;
            $targetOrder = $target->openOrder;
            if ($sourceOrder && $targetOrder) {
                $sourceOrder->items()->update(['order_id' => $targetOrder->id]);
                $sourceOrder->payments()->update(['order_id' => $targetOrder->id]);
                $targetOrder->recalculateTotal();
                $paid = (float) $targetOrder->payments()->sum('amount');
                $targetOrder->update(['payment_status' => $paid <= 0 ? Order::PAYMENT_STATUS_UNPAID : ($paid >= $targetOrder->total ? Order::PAYMENT_STATUS_PAID : Order::PAYMENT_STATUS_PARTIAL)]);
                $sourceOrder->update(['status' => Order::STATUS_CLOSED, 'workflow_status' => 'cancelled', 'cancelled_at' => now(), 'total' => 0]);
            } elseif ($sourceOrder) {
                $sourceOrder->update(['restaurant_table_id' => $target->id]);
            }
            $table->update(['merged_into_table_id' => $target->id, 'service_status' => RestaurantTable::STATUS_AVAILABLE]);
        });

        return back()->with('success', "Tables {$table->number} and {$target->number} are now joined.");
    }

    public function unmergeTable(RestaurantTable $table)
    {
        if (! $table->merged_into_table_id) {
            return back()->with('error', 'This table is not part of a group.');
        }
        $table->update(['merged_into_table_id' => null, 'service_status' => RestaurantTable::STATUS_CLEANING]);

        return back()->with('success', "Table {$table->number} was separated and marked for cleaning.");
    }

    public function storeItem(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:300'],
        ]);

        $order = $this->ensureOpenOrder($table);

        $quantity = (int) $validated['quantity'];
        $unitPrice = (float) $validated['unit_price'];

        $order->items()->create([
            'name' => $validated['name'],
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $quantity * $unitPrice,
            'note' => isset($validated['note']) ? trim((string) $validated['note']) : null,
            'status' => OrderItem::STATUS_PENDING,
        ]);

        $order->recalculateTotal();
        $order->update(['kitchen_status' => Order::KITCHEN_STATUS_PENDING]);
        $this->touchKitchenBoard();

        return redirect()->route('tables.order', $table)->with('success', "Item added to table {$table->number}.");
    }

    public function quickAddMenuItem(Request $request, RestaurantTable $table)
    {
        $validated = $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $menuItem = MenuItem::where('is_active', true)->where('availability_status', 'available')->findOrFail($validated['menu_item_id']);

        $order = $this->ensureOpenOrder($table);

        $quantity = (int) $validated['quantity'];
        $unitPrice = $menuItem->priceFor('dine_in');

        $existingItem = $order->items()
            ->where('name', $menuItem->name)
            ->where('unit_price', $unitPrice)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            $existingItem->update([
                'quantity' => $newQuantity,
                'line_total' => $newQuantity * $unitPrice,
                'status' => OrderItem::STATUS_PENDING,
                'station' => $menuItem->station,
                'allergens' => $menuItem->allergens,
            ]);
        } else {
            $order->items()->create([
                'menu_item_id' => $menuItem->id,
                'name' => $menuItem->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $quantity * $unitPrice,
                'station' => $menuItem->station,
                'allergens' => $menuItem->allergens,
                'status' => OrderItem::STATUS_PENDING,
            ]);
        }

        $order->recalculateTotal();
        $order->update(['kitchen_status' => Order::KITCHEN_STATUS_PENDING]);
        $this->touchKitchenBoard();

        return redirect()->route('tables.order', $table)->with('success', "{$menuItem->name} added to table {$table->number}.");
    }

    public function updateItem(Request $request, RestaurantTable $table, OrderItem $item)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'unit_price' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:300'],
        ]);

        $order = $table->openOrder;
        if (!$order || $item->order_id !== $order->id) {
            abort(404);
        }

        $quantity = (int) $validated['quantity'];
        $unitPrice = (float) $validated['unit_price'];

        $item->update([
            'name' => $validated['name'],
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'line_total' => $quantity * $unitPrice,
            'note' => isset($validated['note']) ? trim((string) $validated['note']) : null,
            'status' => OrderItem::STATUS_PENDING,
        ]);

        $order->recalculateTotal();
        $order->update(['kitchen_status' => Order::KITCHEN_STATUS_PENDING]);
        $this->touchKitchenBoard();

        return redirect()->route('tables.order', $table)->with('success', 'Item updated.');
    }

    public function destroyItem(RestaurantTable $table, OrderItem $item)
    {
        $order = $table->openOrder;
        if (!$order || $item->order_id !== $order->id) {
            abort(404);
        }

        $item->delete();
        $order->recalculateTotal();
        $order->update(['kitchen_status' => Order::KITCHEN_STATUS_PENDING]);
        $this->touchKitchenBoard();

        return redirect()->route('tables.order', $table)->with('success', 'Item deleted.');
    }

    public function closeOrder(RestaurantTable $table)
    {
        /** @var Order|null $order */
        $order = $table->openOrder;

        if (!$order) {
            return redirect()->route('tables.index')->with('error', 'This table has no active order.');
        }

        DB::transaction(function () use ($order, $table) {
            $order->update([
                'status' => Order::STATUS_CLOSED,
                'workflow_status' => 'completed',
                'payment_status' => Order::PAYMENT_STATUS_PAID,
                'completed_at' => now(),
            ]);

            if ((float) $order->total > 0) {
                $registerSession = CashRegisterSession::where('status', 'open')->latest('opened_at')->first();
                $order->payments()->create([
                    'cash_register_session_id' => $registerSession?->id,
                    'transaction_type' => 'payment',
                    'method' => 'cash',
                    'amount' => (float) $order->total,
                    'user_id' => auth()->id(),
                    'paid_at' => now(),
                ]);
                Income::create([
                    'amount' => (float) $order->total,
                    'type' => 'sale',
                    'method' => 'cash',
                    'description' => "Table #{$table->number} - Order #{$order->id}",
                    'date' => now()->toDateString(),
                    'user_id' => auth()->id(),
                ]);
            }
        });
        $this->inventoryService->deductCompletedOrder($order);
        $this->touchKitchenBoard();

        return redirect()->route('tables.index')->with('success', "Order for table {$table->number} has been closed and saved in completed orders.");
    }

    public function deleteOrder(RestaurantTable $table)
    {
        abort_unless(auth()->user()?->mayCancelOrders(), 403);
        /** @var Order|null $order */
        $order = $table->openOrder;

        if (! $order) {
            return redirect()->route('tables.index')->with('error', 'This table has no active order to delete.');
        }

        $order->delete();
        $this->touchKitchenBoard();

        return redirect()->route('tables.index')->with('success', "Active order for table {$table->number} was deleted.");
    }

    public function sendToKitchen(RestaurantTable $table)
    {
        /** @var Order|null $order */
        $order = $table->openOrder;

        if (! $order) {
            return redirect()->route('tables.index')->with('error', 'This table has no active order.');
        }

        $order->touch();
        $this->touchKitchenBoard();

        return redirect()->route('tables.order', $table)->with('success', "Order for table {$table->number} sent to kitchen.");
    }

    public function updateKitchenOrderStatus(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,in_preparation,ready,completed'],
        ]);

        if ($order->status !== Order::STATUS_OPEN) {
            return response()->json(['message' => 'Only active orders can be updated.'], 422);
        }

        if ($validated['status'] === 'completed') {
            $order->update(['kitchen_status' => 'completed']);
            $order->items()->update(['status' => OrderItem::STATUS_READY]);
            $this->touchKitchenBoard();
            return response()->json(['message' => 'Kitchen ticket completed.', 'order_id' => $order->id, 'completed' => true]);
        }

        $order->update([
            'kitchen_status' => $validated['status'],
        ]);
        $order->items()->update([
            'status' => $validated['status'],
        ]);

        $this->touchKitchenBoard();

        return response()->json([
            'message' => 'Order status updated.',
            'order_id' => $order->id,
            'status' => $order->kitchen_status,
        ]);
    }

    public function updateKitchenItemStatus(Request $request, OrderItem $item): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,in_preparation,ready'],
        ]);

        $order = $item->order()->first();

        if (! $order || $order->status !== Order::STATUS_OPEN) {
            return response()->json(['message' => 'Only items from active orders can be updated.'], 422);
        }

        $item->update([
            'status' => $validated['status'],
        ]);

        $order->update([
            'kitchen_status' => $this->resolveKitchenStatusFromItems($order),
        ]);

        $this->touchKitchenBoard();

        return response()->json([
            'message' => 'Item status updated.',
            'order_id' => $order->id,
            'item_id' => $item->id,
            'item_status' => $item->status,
            'order_status' => $order->kitchen_status,
        ]);
    }

    public function syncOrder(TableOrderSyncRequest $request, RestaurantTable $table): JsonResponse
    {
        $validated = $request->validated();
        $deviceId = (string) $validated['device_id'];
        $actions = $validated['actions'];
        $processedActionIds = [];
        $localRefMap = [];

        DB::transaction(function () use ($table, $deviceId, $actions, &$processedActionIds, &$localRefMap): void {
            $order = $this->ensureOpenOrder($table);

            foreach ($actions as $action) {
                $actionId = (string) $action['id'];
                $type = (string) $action['type'];
                $payload = $action['payload'] ?? [];

                $alreadyProcessed = DB::table('order_sync_actions')
                    ->where('order_id', $order->id)
                    ->where('device_id', $deviceId)
                    ->where('action_id', $actionId)
                    ->exists();

                if ($alreadyProcessed) {
                    $processedActionIds[] = $actionId;
                    continue;
                }

                $this->applySyncAction($order, $type, $payload, $localRefMap);

                DB::table('order_sync_actions')->insert([
                    'order_id' => $order->id,
                    'device_id' => $deviceId,
                    'action_id' => $actionId,
                    'created_at' => now(),
                ]);

                $processedActionIds[] = $actionId;
            }

            $order->recalculateTotal();
            $order->update([
                'kitchen_status' => Order::KITCHEN_STATUS_PENDING,
            ]);
        });

        $this->touchKitchenBoard();

        return response()->json([
            'processed_action_ids' => $processedActionIds,
            'local_ref_map' => $localRefMap,
            'order' => $this->tableOrderPayload($table->fresh()),
        ]);
    }

    public function destroyTable(RestaurantTable $table)
    {
        $hasActiveOrder = $table->openOrder()->exists();
        $tableNumber = $table->number;
        $table->delete();

        if ($hasActiveOrder) {
            $this->touchKitchenBoard();
        }

        return redirect()->route('tables.index')->with('success', "Table {$tableNumber} was deleted.");
    }

    private function applySyncAction(Order $order, string $type, array $payload, array &$localRefMap): void
    {
        if ($type === 'add_menu_item') {
            $menuItem = MenuItem::query()
                ->where('is_active', true)
                ->where('availability_status', 'available')
                ->findOrFail((int) $payload['menu_item_id']);
            $unitPrice = $menuItem->priceFor('dine_in');
            $quantity = max(1, (int) ($payload['quantity'] ?? 1));
            $note = $this->sanitizeNote($payload['note'] ?? null);
            $localRef = isset($payload['local_ref']) ? (string) $payload['local_ref'] : null;

            $targetItem = $localRef ? $this->resolveOrderItemRef($order, $localRef, $localRefMap) : null;

            if (! $targetItem) {
                $targetItem = $order->items()
                    ->where('name', $menuItem->name)
                    ->where('unit_price', $unitPrice)
                    ->where(function ($query) use ($note) {
                        if ($note === null) {
                            $query->whereNull('note');
                        } else {
                            $query->where('note', $note);
                        }
                    })
                    ->first();
            }

            if ($targetItem) {
                $newQuantity = (int) $targetItem->quantity + $quantity;
                $targetItem->update([
                    'quantity' => $newQuantity,
                    'line_total' => $newQuantity * (float) $targetItem->unit_price,
                    'status' => OrderItem::STATUS_PENDING,
                ]);
            } else {
                $targetItem = $order->items()->create([
                    'menu_item_id' => $menuItem->id,
                    'name' => $menuItem->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $quantity * $unitPrice,
                    'note' => $note,
                    'station' => $menuItem->station,
                    'allergens' => $menuItem->allergens,
                    'status' => OrderItem::STATUS_PENDING,
                ]);
            }

            if ($localRef) {
                $localRefMap[$localRef] = $targetItem->id;
            }

            return;
        }

        if ($type === 'add_custom_item') {
            $quantity = max(1, (int) $payload['quantity']);
            $unitPrice = (float) $payload['unit_price'];
            $name = trim((string) $payload['name']);
            $note = $this->sanitizeNote($payload['note'] ?? null);
            $localRef = isset($payload['local_ref']) ? (string) $payload['local_ref'] : null;

            $item = $order->items()->create([
                'name' => $name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $quantity * $unitPrice,
                'note' => $note,
                'status' => OrderItem::STATUS_PENDING,
            ]);

            if ($localRef) {
                $localRefMap[$localRef] = $item->id;
            }

            return;
        }

        if ($type === 'set_item_quantity') {
            $item = $this->resolveOrderItemRef($order, $payload['item_ref'], $localRefMap);
            if (! $item) {
                return;
            }

            $quantity = max(1, (int) $payload['quantity']);
            $item->update([
                'quantity' => $quantity,
                'line_total' => $quantity * (float) $item->unit_price,
                'status' => OrderItem::STATUS_PENDING,
            ]);

            return;
        }

        if ($type === 'set_item_note') {
            $item = $this->resolveOrderItemRef($order, $payload['item_ref'], $localRefMap);
            if (! $item) {
                return;
            }

            $item->update([
                'note' => $this->sanitizeNote($payload['note'] ?? null),
                'status' => OrderItem::STATUS_PENDING,
            ]);

            return;
        }

        if ($type === 'remove_item') {
            $item = $this->resolveOrderItemRef($order, $payload['item_ref'], $localRefMap);
            if ($item) {
                $item->delete();
            }
        }
    }

    private function resolveOrderItemRef(Order $order, mixed $itemRef, array $localRefMap): ?OrderItem
    {
        if (is_numeric($itemRef)) {
            return $order->items()->whereKey((int) $itemRef)->first();
        }

        if (! is_string($itemRef)) {
            return null;
        }

        $trimmedRef = trim($itemRef);

        if (isset($localRefMap[$trimmedRef])) {
            return $order->items()->whereKey((int) $localRefMap[$trimmedRef])->first();
        }

        if (str_starts_with($trimmedRef, 's:')) {
            return $order->items()->whereKey((int) substr($trimmedRef, 2))->first();
        }

        return null;
    }

    private function sanitizeNote(mixed $note): ?string
    {
        if (! is_string($note)) {
            return null;
        }

        $clean = trim($note);

        return $clean === '' ? null : mb_substr($clean, 0, 300);
    }

    private function kitchenOrdersPayload(): array
    {
        $menuDetails = MenuItem::query()->get(['name', 'station', 'allergens'])
            ->keyBy(fn (MenuItem $item) => mb_strtolower(trim($item->name)));

        return Order::with([
            'table:id,number',
            'items' => fn ($query) => $query->orderBy('created_at'),
        ])
            ->where('status', Order::STATUS_OPEN)
            ->where('kitchen_status', '!=', 'completed')
            ->whereHas('items')
            ->orderBy('created_at')
            ->get()
            ->map(function (Order $order) use ($menuDetails) {
                $kitchenStatus = $order->kitchen_status ?: Order::KITCHEN_STATUS_PENDING;
                $items = $order->items
                    ->map(function (OrderItem $item) use ($menuDetails) {
                    $menuItem = $menuDetails->get(mb_strtolower(trim((string) $item->name)));
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'quantity' => (int) $item->quantity,
                        'status' => $item->status ?: OrderItem::STATUS_PENDING,
                        'station' => $item->station ?: ($menuItem?->station ?? 'kitchen'),
                        'note' => $item->note,
                        'allergens' => $item->allergens ?: $menuItem?->allergens,
                    ];
                    })
                    ->values()
                    ->all();

                return [
                    'id' => $order->id,
                    'source_label' => $order->table
                        ? 'Table #'.$order->table->number
                        : ($order->customer_name ?: ucfirst(str_replace('_', ' ', $order->order_type)).' #'.$order->id),
                    'table_number' => $order->table?->number,
                    'customer_name' => $order->customer_name,
                    'order_type' => $order->order_type,
                    'notes' => $order->notes,
                    'kitchen_status' => $kitchenStatus,
                    'kitchen_status_label' => $this->orderKitchenStatusLabel($kitchenStatus),
                    'created_at' => optional($order->created_at)->toIso8601String(),
                    'created_at_label' => optional($order->created_at)->format('H:i'),
                    'items' => $items,
                    'items_total' => count($items),
                ];
            })
            ->filter(fn (array $order): bool => ($order['items_total'] ?? 0) > 0)
            ->values()
            ->all();
    }

    private function orderKitchenStatusLabel(string $status): string
    {
        return match ($status) {
            Order::KITCHEN_STATUS_IN_PREPARATION => 'In Preparation',
            Order::KITCHEN_STATUS_READY => 'Ready',
            default => 'Pending',
        };
    }

    private function resolveKitchenStatusFromItems(Order $order): string
    {
        $kitchenItems = $order->items()->get();

        if ($kitchenItems->isEmpty()) {
            return Order::KITCHEN_STATUS_PENDING;
        }

        $statuses = $kitchenItems->pluck('status')
            ->map(fn ($status) => is_string($status) ? $status : OrderItem::STATUS_PENDING)
            ->all();

        $allReady = count(array_filter($statuses, fn ($status) => $status === OrderItem::STATUS_READY)) === count($statuses);
        if ($allReady) {
            return Order::KITCHEN_STATUS_READY;
        }

        $allPending = count(array_filter($statuses, fn ($status) => $status === OrderItem::STATUS_PENDING)) === count($statuses);
        if ($allPending) {
            return Order::KITCHEN_STATUS_PENDING;
        }

        return Order::KITCHEN_STATUS_IN_PREPARATION;
    }

    private function getKitchenVersion(): int
    {
        $version = Cache::get(self::KITCHEN_VERSION_CACHE_KEY);

        if ($version === null) {
            Cache::forever(self::KITCHEN_VERSION_CACHE_KEY, 1);

            return 1;
        }

        return (int) $version;
    }

    private function touchKitchenBoard(): int
    {
        if (! Cache::has(self::KITCHEN_VERSION_CACHE_KEY)) {
            Cache::forever(self::KITCHEN_VERSION_CACHE_KEY, 1);
        }

        return (int) Cache::increment(self::KITCHEN_VERSION_CACHE_KEY);
    }

    private function ensureOpenOrder(RestaurantTable $table): Order
    {
        $existingOrder = $table->openOrder;
        if ($existingOrder) {
            return $existingOrder;
        }

        return $table->orders()->create([
            'assigned_user_id' => auth()->id(),
            'status' => Order::STATUS_OPEN,
            'kitchen_status' => Order::KITCHEN_STATUS_PENDING,
            'total' => 0,
        ]);
    }

    private function tableOrderPayload(RestaurantTable $table): array
    {
        $table->load([
            'openOrder.items' => fn ($query) => $query->orderBy('created_at'),
        ]);

        $order = $table->openOrder;

        return [
            'id' => $order?->id,
            'status' => $order?->status ?? Order::STATUS_OPEN,
            'kitchen_status' => $order?->kitchen_status ?? Order::KITCHEN_STATUS_PENDING,
            'total' => (float) ($order?->total ?? 0),
            'items' => $order
                ? $order->items->map(function (OrderItem $item) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'quantity' => (int) $item->quantity,
                        'unit_price' => (float) $item->unit_price,
                        'line_total' => (float) $item->line_total,
                        'note' => $item->note,
                    ];
                })->values()->all()
                : [],
        ];
    }

}
