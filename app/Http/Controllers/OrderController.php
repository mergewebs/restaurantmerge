<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['table', 'payments'])->withCount('items')->latest();

        if ($request->filled('status')) {
            $query->where('workflow_status', $request->string('status'));
        }
        if ($request->filled('type')) {
            $query->where('order_type', $request->string('type'));
        }
        if ($request->filled('search')) {
            $term = '%'.$request->string('search').'%';
            $query->where(function ($builder) use ($term) {
                $builder->where('customer_name', 'like', $term)
                    ->orWhere('customer_phone', 'like', $term)
                    ->orWhere('id', trim($term, '%'))
                    ->orWhereHas('table', fn ($table) => $table->where('number', 'like', $term));
            });
        }

        $orders = $query->paginate(24)->withQueryString();
        $statusCounts = Order::selectRaw('workflow_status, COUNT(*) as total')->groupBy('workflow_status')->pluck('total', 'workflow_status');
        $tables = RestaurantTable::whereNull('merged_into_table_id')->whereDoesntHave('openOrder')->orderBy('number')->get();

        return view('orders.index', compact('orders', 'statusCounts', 'tables'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_type' => ['required', Rule::in(Order::ORDER_TYPES)],
            'restaurant_table_id' => ['nullable', 'required_if:order_type,dine_in', 'exists:restaurant_tables,id'],
            'customer_name' => ['nullable', 'string', 'max:120'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        if ($validated['order_type'] === 'dine_in') {
            $table = RestaurantTable::findOrFail($validated['restaurant_table_id']);
            if ($table->openOrder()->exists()) {
                return back()->withInput()->with('error', 'That table already has an active order.');
            }
        } else {
            $validated['restaurant_table_id'] = null;
        }

        $order = Order::create($validated + [
            'status' => Order::STATUS_OPEN,
            'workflow_status' => 'new',
            'kitchen_status' => Order::KITCHEN_STATUS_PENDING,
            'payment_status' => Order::PAYMENT_STATUS_UNPAID,
            'total' => 0,
        ]);

        return redirect()->route('orders.show', $order)->with('success', 'Order created. Add the items below.');
    }

    public function show(Order $order)
    {
        $order->load(['table', 'items', 'payments.user']);
        $menuItems = MenuItem::where('is_active', true)->orderedByCategory()->orderBy('sort_order')->get()->groupBy('category');
        $tables = RestaurantTable::whereNull('merged_into_table_id')
            ->where(fn ($query) => $query->whereDoesntHave('openOrder')->orWhere('id', $order->restaurant_table_id))
            ->orderBy('number')->get();

        return view('orders.show', compact('order', 'menuItems', 'tables'));
    }

    public function updateDetails(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_type' => ['required', Rule::in(Order::ORDER_TYPES)],
            'restaurant_table_id' => ['nullable', 'required_if:order_type,dine_in', 'exists:restaurant_tables,id'],
            'customer_name' => ['nullable', 'string', 'max:120'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        if ($validated['order_type'] !== 'dine_in') {
            $validated['restaurant_table_id'] = null;
        } elseif ((int) $validated['restaurant_table_id'] !== (int) $order->restaurant_table_id) {
            $table = RestaurantTable::findOrFail($validated['restaurant_table_id']);
            if ($table->openOrder()->exists()) {
                return back()->withInput()->with('error', 'That table already has an active order.');
            }
        }
        $order->update($validated);

        return back()->with('success', 'Order details updated.');
    }

    public function addItem(Request $request, Order $order)
    {
        abort_if(in_array($order->workflow_status, ['completed', 'cancelled'], true), 422, 'This order can no longer be edited.');
        $validated = $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:99'],
            'note' => ['nullable', 'string', 'max:300'],
        ]);
        $menuItem = MenuItem::findOrFail($validated['menu_item_id']);
        $quantity = (int) $validated['quantity'];
        $order->items()->create([
            'name' => $menuItem->name,
            'quantity' => $quantity,
            'unit_price' => $menuItem->price,
            'line_total' => (float) $menuItem->price * $quantity,
            'note' => $validated['note'] ?? null,
            'status' => OrderItem::STATUS_PENDING,
        ]);
        $order->recalculateTotal();

        return back()->with('success', $menuItem->name.' added.');
    }

    public function removeItem(Order $order, OrderItem $item)
    {
        abort_unless($item->order_id === $order->id, 404);
        $item->delete();
        $order->recalculateTotal();

        return back()->with('success', 'Item removed.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate(['workflow_status' => ['required', Rule::in(Order::WORKFLOW_STATUSES)]]);
        $workflowStatus = $validated['workflow_status'];
        $changes = ['workflow_status' => $workflowStatus];

        if ($workflowStatus === 'preparing') {
            $changes['kitchen_status'] = Order::KITCHEN_STATUS_IN_PREPARATION;
        } elseif ($workflowStatus === 'ready') {
            $changes['kitchen_status'] = Order::KITCHEN_STATUS_READY;
        } elseif (in_array($workflowStatus, ['new', 'confirmed'], true)) {
            $changes['kitchen_status'] = Order::KITCHEN_STATUS_PENDING;
        }
        if ($workflowStatus === 'completed') {
            $changes += ['status' => Order::STATUS_CLOSED, 'completed_at' => now(), 'cancelled_at' => null];
        } elseif ($workflowStatus === 'cancelled') {
            $changes += ['status' => Order::STATUS_CLOSED, 'cancelled_at' => now(), 'completed_at' => null];
        } else {
            $changes += ['status' => Order::STATUS_OPEN, 'completed_at' => null, 'cancelled_at' => null];
        }
        $order->update($changes);

        return back()->with('success', 'Order marked '.str_replace('_', ' ', $workflowStatus).'.');
    }

    public function updateAdjustments(Request $request, Order $order)
    {
        $validated = $request->validate([
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
            'service_charge' => ['nullable', 'numeric', 'min:0'],
        ]);
        $order->update([
            'discount_amount' => $validated['discount_amount'] ?? 0,
            'service_charge' => $validated['service_charge'] ?? 0,
        ]);
        $order->recalculateTotal();
        $paid = (float) $order->payments()->sum('amount');
        $order->update(['payment_status' => $paid <= 0 ? Order::PAYMENT_STATUS_UNPAID : ($paid >= (float) $order->total ? Order::PAYMENT_STATUS_PAID : Order::PAYMENT_STATUS_PARTIAL)]);

        return back()->with('success', 'Bill updated.');
    }

    public function storePayments(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payments' => ['required', 'array', 'min:1'],
            'payments.*.method' => ['required', Rule::in(['cash', 'card', 'bank_transfer', 'voucher', 'other'])],
            'payments.*.amount' => ['required', 'numeric', 'gt:0'],
            'payments.*.reference' => ['nullable', 'string', 'max:120'],
        ]);

        DB::transaction(function () use ($validated, $order) {
            foreach ($validated['payments'] as $paymentData) {
                $payment = $order->payments()->create($paymentData + ['user_id' => auth()->id(), 'paid_at' => now()]);
                Income::create([
                    'amount' => $payment->amount,
                    'type' => 'sale',
                    'method' => in_array($payment->method, ['cash', 'card'], true) ? $payment->method : 'card',
                    'description' => 'Order #'.$order->id.' payment',
                    'date' => now()->toDateString(),
                    'user_id' => auth()->id(),
                ]);
            }
            $paid = (float) $order->payments()->sum('amount');
            $order->update(['payment_status' => $paid >= (float) $order->total ? Order::PAYMENT_STATUS_PAID : Order::PAYMENT_STATUS_PARTIAL]);
        });

        return back()->with('success', 'Payment recorded.');
    }

    public function ticket(Order $order)
    {
        $order->load(['table', 'items']);

        return view('orders.ticket', compact('order'));
    }

    public function downloadTicket(Order $order)
    {
        $order->load(['table', 'items']);
        $lines = [config('restaurant.name'), 'KITCHEN TICKET #'.$order->id, strtoupper(str_replace('_', ' ', $order->order_type)), 'Created: '.$order->created_at->format('d/m/Y H:i')];
        if ($order->table) $lines[] = 'Table: '.$order->table->number;
        if ($order->customer_name) $lines[] = 'Customer: '.$order->customer_name;
        $lines[] = str_repeat('-', 32);
        foreach ($order->items as $item) {
            $lines[] = $item->quantity.' x '.$item->name.($item->note ? ' ['.$item->note.']' : '');
        }
        if ($order->notes) $lines[] = "\nORDER NOTE: ".$order->notes;

        return response(implode("\n", $lines)."\n", 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="order-'.$order->id.'-kitchen-ticket.txt"',
        ]);
    }
}
