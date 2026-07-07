<?php

namespace App\Http\Controllers;

use App\Mail\TakeawayOrderReceived;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class PublicTakeawayController extends Controller
{
    private const KITCHEN_VERSION_CACHE_KEY = 'kitchen_board_version';
    private const TAKEAWAY_TABLE_NUMBER = 10000;
    private ?bool $hasOrderTypeColumn = null;
    private ?bool $hasCustomerNameColumn = null;
    private ?bool $hasCustomerPhoneColumn = null;

    public function index()
    {
        $locale = request()->getPreferredLanguage(['es', 'en']);
        app()->setLocale($locale);

        $menuItemsCollection = MenuItem::query()
            ->where('is_active', true)
            ->orderedByCategory()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'category',
                'description',
                'price',
                'is_package',
                'package_details',
            ]);

        $menuItems = $menuItemsCollection->groupBy('category');
        $menuItemsFlat = $menuItemsCollection->map(fn (MenuItem $item) => [
            'id' => $item->id,
            'name' => $item->name,
            'category' => $item->category,
            'description' => $item->description,
            'price' => (float) $item->price,
            'is_package' => (bool) $item->is_package,
            'package_details' => $item->package_details,
        ])->values()->all();

        return view('order', [
            'menuItems' => $menuItems,
            'menuItemsFlat' => $menuItemsFlat,
            'restaurantName' => config('takeaway.brand.restaurant_name', config('app.name')),
            'brandLogoUrl' => config('takeaway.brand.logo_url'),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'items' => ['required', 'array', 'min:1', 'max:100'],
            'items.*.menu_item_id' => ['required', 'integer'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $cart = collect($validated['items'])
            ->map(function (array $item) {
                return [
                    'menu_item_id' => (int) $item['menu_item_id'],
                    'quantity' => (int) $item['quantity'],
                ];
            })
            ->groupBy('menu_item_id')
            ->map(function ($items) {
                return [
                    'menu_item_id' => (int) $items->first()['menu_item_id'],
                    'quantity' => $items->sum('quantity'),
                ];
            })
            ->values();

        $menuItemIds = $cart->pluck('menu_item_id')->all();
        $menuItemsById = MenuItem::query()
            ->where('is_active', true)
            ->whereIn('id', $menuItemIds)
            ->get()
            ->keyBy('id');

        if (count($menuItemIds) !== $menuItemsById->count()) {
            return response()->json([
                'message' => __('public.takeaway_unavailable'),
            ], 422);
        }

        $takeawayTable = $this->takeawayTable();

        $order = DB::transaction(function () use ($validated, $cart, $menuItemsById, $takeawayTable) {
            $payload = [
                'restaurant_table_id' => $takeawayTable->id,
                'status' => Order::STATUS_OPEN,
                'kitchen_status' => Order::KITCHEN_STATUS_PENDING,
                'total' => 0,
            ];

            if ($this->supportsOrderTypeColumn()) {
                $payload['order_type'] = Order::TYPE_TAKEAWAY;
            }

            if ($this->supportsCustomerNameColumn()) {
                $payload['customer_name'] = trim((string) $validated['customer_name']);
            }

            if ($this->supportsCustomerPhoneColumn()) {
                $payload['customer_phone'] = $this->sanitizePhone($validated['customer_phone'] ?? null);
            }

            $order = Order::create($payload);

            foreach ($cart as $line) {
                /** @var MenuItem $menuItem */
                $menuItem = $menuItemsById->get($line['menu_item_id']);
                $quantity = max(1, (int) $line['quantity']);
                $unitPrice = (float) $menuItem->price;

                $order->items()->create([
                    'name' => $menuItem->name,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => $quantity * $unitPrice,
                    'status' => OrderItem::STATUS_PENDING,
                ]);
            }

            $order->recalculateTotal();

            return $order->fresh('items');
        });

        $this->touchKitchenBoard();
        $this->sendEmailNotification($order);

        return response()->json([
            'message' => __('public.takeaway_success', ['order' => $order->id]),
            'order_id' => $order->id,
        ], 201);
    }

    private function takeawayTable(): RestaurantTable
    {
        return RestaurantTable::firstOrCreate([
            'number' => self::TAKEAWAY_TABLE_NUMBER,
        ]);
    }

    private function sanitizePhone(mixed $phone): ?string
    {
        if (! is_string($phone)) {
            return null;
        }

        $clean = trim($phone);

        return $clean === '' ? null : mb_substr($clean, 0, 30);
    }

    private function touchKitchenBoard(): int
    {
        if (! Cache::has(self::KITCHEN_VERSION_CACHE_KEY)) {
            Cache::forever(self::KITCHEN_VERSION_CACHE_KEY, 1);
        }

        return (int) Cache::increment(self::KITCHEN_VERSION_CACHE_KEY);
    }

    private function sendEmailNotification(Order $order): void
    {
        if (! config('takeaway.notification.email_enabled', true)) {
            return;
        }

        $rawRecipients = (string) config('takeaway.notification.email_recipients', '');
        $recipients = collect(explode(',', $rawRecipients))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->values()
            ->all();

        if ($recipients === []) {
            return;
        }

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient)->send(new TakeawayOrderReceived($order));
            } catch (\Throwable $exception) {
                Log::warning('Failed to send takeaway email notification.', [
                    'order_id' => $order->id,
                    'recipient' => $recipient,
                    'error' => $exception->getMessage(),
                ]);
            }
        }
    }

    private function supportsOrderTypeColumn(): bool
    {
        if ($this->hasOrderTypeColumn !== null) {
            return $this->hasOrderTypeColumn;
        }

        $this->hasOrderTypeColumn = Schema::hasColumn('orders', 'order_type');

        return $this->hasOrderTypeColumn;
    }

    private function supportsCustomerNameColumn(): bool
    {
        if ($this->hasCustomerNameColumn !== null) {
            return $this->hasCustomerNameColumn;
        }

        $this->hasCustomerNameColumn = Schema::hasColumn('orders', 'customer_name');

        return $this->hasCustomerNameColumn;
    }

    private function supportsCustomerPhoneColumn(): bool
    {
        if ($this->hasCustomerPhoneColumn !== null) {
            return $this->hasCustomerPhoneColumn;
        }

        $this->hasCustomerPhoneColumn = Schema::hasColumn('orders', 'customer_phone');

        return $this->hasCustomerPhoneColumn;
    }
}





