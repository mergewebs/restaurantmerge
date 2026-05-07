<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Category;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class FinanceController extends Controller
{
    public function stats(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        }

        // Determine filter mode & date range
        $mode      = $request->get('mode', 'daily');   // daily | weekly | monthly | custom
        $dateFrom  = $request->get('date_from');
        $dateTo    = $request->get('date_to');

        switch ($mode) {
            case 'weekly':
                $start = Carbon::now()->subDays(6)->startOfDay();
                $end   = Carbon::now()->endOfDay();
                break;
            case 'monthly':
                $start = Carbon::now()->startOfMonth()->startOfDay();
                $end   = Carbon::now()->endOfDay();
                break;
            case 'custom':
                $start = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->startOfDay();
                $end   = $dateTo   ? Carbon::parse($dateTo)->endOfDay()     : Carbon::now()->endOfDay();
                break;
            default: // daily
                $start = Carbon::now()->startOfDay();
                $end   = Carbon::now()->endOfDay();
                break;
        }

        // Totals
        $totalIncome   = Income::whereDate('date', '>=', $start->toDateString())
                               ->whereDate('date', '<=', $end->toDateString())
                               ->sum('amount');
        $totalExpenses = Expense::whereDate('date', '>=', $start->toDateString())
                                ->whereDate('date', '<=', $end->toDateString())
                                ->sum('amount');
        $netProfit     = $totalIncome - $totalExpenses;

        // Chart series — group by day across the range
        $period = CarbonPeriod::create($start->toDateString(), $end->toDateString());
        $chartLabels  = [];
        $chartIncome  = [];
        $chartExpense = [];
        $chartProfit  = [];

        foreach ($period as $day) {
            $d = $day->toDateString();
            $inc = Income::whereDate('date', $d)->sum('amount');
            $exp = Expense::whereDate('date', $d)->sum('amount');
            $chartLabels[]  = $day->format('d M');
            $chartIncome[]  = round((float) $inc, 2);
            $chartExpense[] = round((float) $exp, 2);
            $chartProfit[]  = round((float) ($inc - $exp), 2);
        }

        // Breakdown by income type & expense category
        $incomeByType    = Income::whereDate('date', '>=', $start->toDateString())
                                 ->whereDate('date', '<=', $end->toDateString())
                                 ->selectRaw('type, SUM(amount) as total')
                                 ->groupBy('type')->get();
        
        $incomeByMethod = Income::whereDate('date', '>=', $start->toDateString())
                                ->whereDate('date', '<=', $end->toDateString())
                                ->selectRaw('method, SUM(amount) as total')
                                ->groupBy('method')->get();

        $expenseByMethod = Expense::whereDate('date', '>=', $start->toDateString())
                                   ->whereDate('date', '<=', $end->toDateString())
                                   ->selectRaw('method, SUM(amount) as total')
                                   ->groupBy('method')->get();

        $expenseByCategory = Expense::whereDate('date', '>=', $start->toDateString())
                                     ->whereDate('date', '<=', $end->toDateString())
                                     ->selectRaw('category, SUM(amount) as total')
                                     ->groupBy('category')->get();

        $categories = Category::orderBy('name')->get();

        return view('finance.stats', compact(
            'mode', 'start', 'end', 'dateFrom', 'dateTo',
            'totalIncome', 'totalExpenses', 'netProfit',
            'chartLabels', 'chartIncome', 'chartExpense', 'chartProfit',
            'incomeByType', 'expenseByCategory', 'incomeByMethod', 'expenseByMethod',
            'categories'
        ));
    }


    private function denyWaiter()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Access denied.');
        }
        return null;
    }



    public function store(Request $request)
    {
        if ($deny = $this->denyWaiter()) return $deny;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'finance_type' => 'required|in:income,expense',
            'method' => 'required|in:card,cash',
            'date' => 'required|date',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        if ($validated['finance_type'] === 'income') {
            Income::create([
                'amount' => $validated['amount'],
                'type' => $validated['category'],
                'method' => $validated['method'],
                'date' => $validated['date'],
                'description' => $validated['description'],
                'user_id' => $validated['user_id'],
            ]);
        } else {
            Expense::create([
                'amount' => $validated['amount'],
                'category' => $validated['category'],
                'method' => $validated['method'],
                'date' => $validated['date'],
                'description' => $validated['description'],
                'user_id' => $validated['user_id'],
            ]);
        }

        $previousUrl = url()->previous();
        $separator = str_contains($previousUrl, '?') ? '&' : '?';
        $redirectUrl = $previousUrl . $separator . 'finance_modal=1';

        return redirect()->to($redirectUrl)
            ->with('success', 'Finance recorded successfully.')
            ->with('keep_finance_modal', true)
            ->withInput($request->except('_token'));
    }

    public function update(Request $request, $id)
    {
        if ($deny = $this->denyWaiter()) return $deny;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'finance_type' => 'required|in:income,expense',
            'method' => 'required|in:card,cash',
            'date' => 'required|date',
            'category' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $type = $request->get('type'); // original type to find the record

        if ($type === 'income') {
            $record = Income::findOrFail($id);
        } else {
            $record = Expense::findOrFail($id);
        }

        // If type changed from income to expense or vice versa, we need to delete and create
        if ($type !== $validated['finance_type']) {
            $record->delete();
            
            if ($validated['finance_type'] === 'income') {
                Income::create([
                    'amount' => $validated['amount'],
                    'type' => $validated['category'],
                    'method' => $validated['method'],
                    'date' => $validated['date'],
                    'description' => $validated['description'],
                    'user_id' => auth()->id(),
                ]);
            } else {
                Expense::create([
                    'amount' => $validated['amount'],
                    'category' => $validated['category'],
                    'method' => $validated['method'],
                    'date' => $validated['date'],
                    'description' => $validated['description'],
                    'user_id' => auth()->id(),
                ]);
            }
        } else {
            $record->update([
                'amount' => $validated['amount'],
                'method' => $validated['method'],
                'date' => $validated['date'],
                'description' => $validated['description'],
                ($type === 'income' ? 'type' : 'category') => $validated['category'],
            ]);
        }

        return back()->with('success', 'Finance entry updated successfully.');
    }




    public function history(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        }

        $query = $request->get('query');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $incomes = Income::select('id', 'amount', 'date', 'description', 'method', 'type as category', \DB::raw("'income' as transaction_type"), 'user_id', 'created_at');
        $expenses = Expense::select('id', 'amount', 'date', 'description', 'method', 'category', \DB::raw("'expense' as transaction_type"), 'user_id', 'created_at');

        if ($dateFrom) {
            $incomes->whereDate('date', '>=', $dateFrom);
            $expenses->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $incomes->whereDate('date', '<=', $dateTo);
            $expenses->whereDate('date', '<=', $dateTo);
        }
        if ($query) {
            $incomes->where(function($q) use ($query) {
                $q->where('description', 'like', "%{$query}%")
                  ->orWhere('amount', 'like', "%{$query}%");
            });
            $expenses->where(function($q) use ($query) {
                $q->where('description', 'like', "%{$query}%")
                  ->orWhere('amount', 'like', "%{$query}%");
            });
        }

        $history = $incomes->union($expenses)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('finance.history', compact('history', 'query', 'dateFrom', 'dateTo', 'categories'));
    }

    public function destroy(Request $request, $id)
    {
        if (!auth()->user()->isAdmin()) {
            return back()->with('error', 'Unauthorized.');
        }

        $type = $request->get('type');
        if ($type === 'income') {
            Income::findOrFail($id)->delete();
        } else {
            Expense::findOrFail($id)->delete();
        }

        return back()->with('success', 'Entry deleted successfully.');
    }
}
