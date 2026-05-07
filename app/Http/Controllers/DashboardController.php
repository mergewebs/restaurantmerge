<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $date = $request->get('date', now()->toDateString());

        // Financial stats (Daily)
        $dailyIncome = \App\Models\Income::whereDate('date', $date)->sum('amount');
        $dailyExpenses = \App\Models\Expense::whereDate('date', $date)->sum('amount');
        
        $dailyIncomeByMethod = \App\Models\Income::whereDate('date', $date)
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->pluck('total', 'method');
        
        $dailyExpensesByMethod = \App\Models\Expense::whereDate('date', $date)
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->pluck('total', 'method');
        
        // Reservations for the day
        $reservations = \App\Models\Reservation::whereDate('reservation_time', $date)
            ->where('status', 'confirmed')
            ->orderBy('reservation_time')
            ->get();
        $reservationsCount = $reservations->count();
        $isClosedToday = \App\Models\RestaurantException::whereDate('date', now()->toDateString())
            ->where('is_closed', true)
            ->exists();

        // Recent transactions for the day
        $recentTransactions = \App\Models\Income::whereDate('date', $date)
            ->select('id', 'amount', 'date', 'description', 'method', 'type as category', \DB::raw("'income' as transaction_type"), 'created_at')
            ->union(
                \App\Models\Expense::whereDate('date', $date)
                ->select('id', 'amount', 'date', 'description', 'method', 'category', \DB::raw("'expense' as transaction_type"), 'created_at')
            )
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

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
            'categories'
        ));
    }
}
