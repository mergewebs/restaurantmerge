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
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('dashboard');
        }

        [
            'mode' => $mode,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'start' => $start,
            'end' => $end,
        ] = $this->resolveStatsRange($request);

        extract($this->getStatsData($start, $end));

        $query = $request->get('history_query');
        $historyDateFrom = $request->get('history_date_from', Carbon::now()->startOfMonth()->toDateString());
        $historyDateTo = $request->get('history_date_to', Carbon::now()->endOfMonth()->toDateString());

        $historyQuery = $this->buildHistoryQuery($query, $historyDateFrom, $historyDateTo);
        $history = (clone $historyQuery)
            ->paginate(20)
            ->withQueryString();
        $latestTransactions = $this->buildHistoryQuery(null, null, null)
            ->limit(5)
            ->get();

        return view('finance.index', compact(
            'mode', 'start', 'end', 'dateFrom', 'dateTo',
            'totalIncome', 'totalExpenses', 'netProfit',
            'chartLabels', 'chartIncome', 'chartExpense', 'chartProfit',
            'incomeByType', 'expenseByCategory', 'incomeByMethod', 'expenseByMethod',
            'categories', 'history', 'query', 'historyDateFrom', 'historyDateTo', 'latestTransactions'
        ));
    }

    public function stats(Request $request)
    {
        return redirect()->route('finance.index', $request->query());
    }

    public function history(Request $request)
    {
        $params = [];

        if ($request->filled('query')) {
            $params['history_query'] = $request->query('query');
        }
        if ($request->filled('date_from')) {
            $params['history_date_from'] = $request->query('date_from');
        }
        if ($request->filled('date_to')) {
            $params['history_date_to'] = $request->query('date_to');
        }

        return redirect()->to(route('finance.index', $params) . '#transactions');
    }

    private function resolveStatsRange(Request $request): array
    {
        $mode = $request->get('mode', 'monthly');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        switch ($mode) {
            case 'daily':
                $start = Carbon::now()->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'weekly':
                $start = Carbon::now()->subDays(6)->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'last_month':
                $start = Carbon::now()->subMonth()->startOfMonth()->startOfDay();
                $end = Carbon::now()->subMonth()->endOfMonth()->endOfDay();
                break;
            case 'last_3_months':
                $start = Carbon::now()->subMonths(3)->startOfDay();
                $end = Carbon::now()->endOfDay();
                break;
            case 'this_year':
                $start = Carbon::now()->startOfYear()->startOfDay();
                $end = Carbon::now()->endOfYear()->endOfDay();
                break;
            case 'last_year':
                $start = Carbon::now()->subYear()->startOfYear()->startOfDay();
                $end = Carbon::now()->subYear()->endOfYear()->endOfDay();
                break;
            case 'custom':
                $start = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : Carbon::now()->startOfMonth()->startOfDay();
                $end = $dateTo ? Carbon::parse($dateTo)->endOfDay() : Carbon::now()->endOfMonth()->endOfDay();
                break;
            case 'monthly':
            default:
                $start = Carbon::now()->startOfMonth()->startOfDay();
                $end = Carbon::now()->endOfMonth()->endOfDay();
                break;
        }

        return compact('mode', 'dateFrom', 'dateTo', 'start', 'end');
    }

    private function getStatsData(Carbon $start, Carbon $end): array
    {
        $totalIncome = Income::whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())
            ->sum('amount');
        $totalExpenses = Expense::whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())
            ->sum('amount');
        $netProfit = $totalIncome - $totalExpenses;

        $period = CarbonPeriod::create($start->toDateString(), $end->toDateString());
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];
        $chartProfit = [];

        foreach ($period as $day) {
            $date = $day->toDateString();
            $income = Income::whereDate('date', $date)->sum('amount');
            $expense = Expense::whereDate('date', $date)->sum('amount');

            $chartLabels[] = $day->format('d M');
            $chartIncome[] = round((float) $income, 2);
            $chartExpense[] = round((float) $expense, 2);
            $chartProfit[] = round((float) ($income - $expense), 2);
        }

        $incomeByType = Income::whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->get();

        $incomeByMethod = Income::whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->get();

        $expenseByMethod = Expense::whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->get();

        $expenseByCategory = Expense::whereDate('date', '>=', $start->toDateString())
            ->whereDate('date', '<=', $end->toDateString())
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')
            ->get();

        $categories = Category::orderBy('name')->get();

        return compact(
            'totalIncome', 'totalExpenses', 'netProfit',
            'chartLabels', 'chartIncome', 'chartExpense', 'chartProfit',
            'incomeByType', 'expenseByCategory', 'incomeByMethod', 'expenseByMethod',
            'categories'
        );
    }

    private function buildHistoryQuery(?string $query, ?string $dateFrom, ?string $dateTo)
    {
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
            $incomes->where(function ($builder) use ($query) {
                $builder->where('description', 'like', "%{$query}%")
                    ->orWhere('amount', 'like', "%{$query}%");
            });
            $expenses->where(function ($builder) use ($query) {
                $builder->where('description', 'like', "%{$query}%")
                    ->orWhere('amount', 'like', "%{$query}%");
            });
        }

        return $incomes->union($expenses)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc');
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

        $type = $request->get('type');

        if ($type === 'income') {
            $record = Income::findOrFail($id);
        } else {
            $record = Expense::findOrFail($id);
        }

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
