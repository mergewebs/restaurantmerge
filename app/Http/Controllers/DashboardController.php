<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Reservation;
use App\Models\RestaurantException;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $date = $request->get('date', now()->toDateString());

        // Financial stats (Daily)
        $dailyIncome = Income::whereDate('date', $date)->sum('amount');
        $dailyExpenses = Expense::whereDate('date', $date)->sum('amount');
        
        $dailyIncomeByMethod = Income::whereDate('date', $date)
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->pluck('total', 'method');
        
        $dailyExpensesByMethod = Expense::whereDate('date', $date)
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->pluck('total', 'method');
        
        // Reservations for the day
        $reservations = Reservation::whereDate('reservation_time', $date)
            ->where('status', 'confirmed')
            ->orderBy('reservation_time')
            ->get();
        $reservationsCount = $reservations->count();
        $isClosedToday = RestaurantException::whereDate('date', now()->toDateString())
            ->where('is_closed', true)
            ->exists();

        // Recent transactions for the day
        $recentTransactions = Income::whereDate('date', $date)
            ->select('id', 'amount', 'date', 'description', 'method', 'type as category', DB::raw("'income' as transaction_type"), 'created_at')
            ->union(
                Expense::whereDate('date', $date)
                ->select('id', 'amount', 'date', 'description', 'method', 'category', DB::raw("'expense' as transaction_type"), 'created_at')
            )
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $ordersTodayQuery = Order::whereDate('created_at', $date);
        $ordersToday = (clone $ordersTodayQuery)->count();
        $pendingOrders = (clone $ordersTodayQuery)->where('status', Order::STATUS_OPEN)
            ->where('kitchen_status', Order::KITCHEN_STATUS_PENDING)->count();
        $preparingOrders = (clone $ordersTodayQuery)->where('status', Order::STATUS_OPEN)
            ->where('kitchen_status', Order::KITCHEN_STATUS_IN_PREPARATION)->count();
        $readyOrders = (clone $ordersTodayQuery)->where('status', Order::STATUS_OPEN)
            ->where('kitchen_status', Order::KITCHEN_STATUS_READY)->count();

        $totalTables = RestaurantTable::count();
        $occupiedTables = RestaurantTable::whereHas('openOrder')->count();
        $availableTables = max(0, $totalTables - $occupiedTables);

        $unpaidPayments = Order::where('payment_status', Order::PAYMENT_STATUS_UNPAID)
            ->where('total', '>', 0)->count();
        $unpaidAmount = Order::where('payment_status', Order::PAYMENT_STATUS_UNPAID)
            ->where('total', '>', 0)->sum('total');

        $lowStockProducts = MenuItem::whereNotNull('stock_quantity')
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->limit(5)
            ->get();

        $topSellingItems = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereDate('orders.created_at', $date)
            ->select('order_items.name', DB::raw('SUM(order_items.quantity) as quantity_sold'), DB::raw('SUM(order_items.line_total) as sales_total'))
            ->groupBy('order_items.name')
            ->orderByDesc('quantity_sold')
            ->limit(5)
            ->get();

        $recentActivity = collect()
            ->concat(Order::with('table')->latest()->limit(5)->get()->map(fn (Order $order) => [
                'type' => 'order',
                'title' => 'Order #'.$order->id.' · Table #'.($order->table?->number ?? '—'),
                'detail' => ucfirst(str_replace('_', ' ', $order->kitchen_status)).' · €'.number_format($order->total, 2),
                'at' => $order->updated_at,
            ]))
            ->concat(Reservation::latest()->limit(5)->get()->map(fn (Reservation $reservation) => [
                'type' => 'reservation',
                'title' => $reservation->customer_name,
                'detail' => $reservation->party_size.' guests · '.$reservation->reservation_time->format('d M, H:i'),
                'at' => $reservation->updated_at,
            ]))
            ->sortByDesc('at')
            ->take(6)
            ->values();

        $categories = \App\Models\Category::orderBy('name')->get();

        return view('dashboard', compact(
            'dailyIncome', 
            'dailyExpenses', 
            'reservations', 
            'reservationsCount', 
            'date',
            'isClosedToday',
            'recentTransactions',
            'dailyIncomeByMethod',
            'dailyExpensesByMethod',
            'categories',
            'ordersToday',
            'pendingOrders',
            'preparingOrders',
            'readyOrders',
            'totalTables',
            'occupiedTables',
            'availableTables',
            'unpaidPayments',
            'unpaidAmount',
            'lowStockProducts',
            'topSellingItems',
            'recentActivity'
        ));
    }
}
