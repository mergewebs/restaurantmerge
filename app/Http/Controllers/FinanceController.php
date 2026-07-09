<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use App\Models\Category;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    private const DEFAULT_STATS_RANGE = 'this_month';

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

        $query = trim((string) $request->get('history_query', ''));

        $historyQuery = $this->buildHistoryQuery($query, $dateFrom, $dateTo);
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
            'categories', 'history', 'query', 'latestTransactions'
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
        if ($request->filled('range')) {
            $params['mode'] = $request->query('range');
        }
        if ($request->filled('date_from')) {
            $params['date_from'] = $request->query('date_from');
        }
        if ($request->filled('date_to')) {
            $params['date_to'] = $request->query('date_to');
        }

        return redirect()->to(route('finance.index', $params) . '#transactions');
    }

    private function resolveStatsRange(Request $request): array
    {
        $mode = $request->get('mode', self::DEFAULT_STATS_RANGE);
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        [
            'preset' => $mode,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'start' => $start,
            'end' => $end,
        ] = $this->resolveDateRangePreset($mode, $dateFrom, $dateTo, self::DEFAULT_STATS_RANGE);

        return compact('mode', 'dateFrom', 'dateTo', 'start', 'end');
    }

    private function resolveDateRangePreset(?string $preset, ?string $dateFrom, ?string $dateTo, string $defaultPreset): array
    {
        $preset = $this->normalizeDateRangePreset($preset, $defaultPreset);
        $now = Carbon::now();

        switch ($preset) {
            case 'today':
                $start = $now->copy()->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'yesterday':
                $start = $now->copy()->subDay()->startOfDay();
                $end = $now->copy()->subDay()->endOfDay();
                break;
            case 'this_week':
                $start = $now->copy()->startOfWeek()->startOfDay();
                $end = $now->copy()->endOfWeek()->endOfDay();
                break;
            case 'last_week':
                $start = $now->copy()->subWeek()->startOfWeek()->startOfDay();
                $end = $now->copy()->subWeek()->endOfWeek()->endOfDay();
                break;
            case 'last_month':
                $start = $now->copy()->subMonth()->startOfMonth()->startOfDay();
                $end = $now->copy()->subMonth()->endOfMonth()->endOfDay();
                break;
            case 'last_3_months':
                $start = $now->copy()->subMonths(3)->startOfDay();
                $end = $now->copy()->endOfDay();
                break;
            case 'this_year':
                $start = $now->copy()->startOfYear()->startOfDay();
                $end = $now->copy()->endOfYear()->endOfDay();
                break;
            case 'last_year':
                $start = $now->copy()->subYear()->startOfYear()->startOfDay();
                $end = $now->copy()->subYear()->endOfYear()->endOfDay();
                break;
            case 'custom':
                $start = $dateFrom
                    ? Carbon::parse($dateFrom)->startOfDay()
                    : $now->copy()->startOfMonth()->startOfDay();
                $end = $dateTo
                    ? Carbon::parse($dateTo)->endOfDay()
                    : $now->copy()->endOfMonth()->endOfDay();
                break;
            case 'this_month':
            default:
                $start = $now->copy()->startOfMonth()->startOfDay();
                $end = $now->copy()->endOfMonth()->endOfDay();
                break;
        }

        return [
            'preset' => $preset,
            'dateFrom' => $start->toDateString(),
            'dateTo' => $end->toDateString(),
            'start' => $start,
            'end' => $end,
        ];
    }

    private function normalizeDateRangePreset(?string $preset, string $defaultPreset): string
    {
        return match ($preset) {
            'daily', 'today' => 'today',
            'yesterday' => 'yesterday',
            'weekly', 'this_week' => 'this_week',
            'last_week' => 'last_week',
            'monthly', 'this_month', null, '' => 'this_month',
            'last_month' => 'last_month',
            'this_year' => 'this_year',
            'last_year' => 'last_year',
            'last_3_months' => 'last_3_months',
            'custom' => 'custom',
            default => $defaultPreset,
        };
    }

    private function getStatsData(Carbon $start, Carbon $end): array
    {
        $startDate = $start->toDateString();
        $endDate = $end->toDateString();

        $incomeRecords = Income::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $expenseRecords = Expense::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('date, SUM(amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $totalIncome = (float) $incomeRecords->sum();
        $totalExpenses = (float) $expenseRecords->sum();
        $netProfit = $totalIncome - $totalExpenses;

        $period = CarbonPeriod::create($startDate, $endDate);
        $chartLabels = [];
        $chartIncome = [];
        $chartExpense = [];
        $chartProfit = [];

        foreach ($period as $day) {
            $date = $day->toDateString();
            $income = (float) ($incomeRecords[$date] ?? 0);
            $expense = (float) ($expenseRecords[$date] ?? 0);

            $chartLabels[] = $day->format('d M');
            $chartIncome[] = round($income, 2);
            $chartExpense[] = round($expense, 2);
            $chartProfit[] = round($income - $expense, 2);
        }

        $incomeByType = Income::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('type, SUM(amount) as total')
            ->groupBy('type')
            ->get();

        $incomeByMethod = Income::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->get();

        $expenseByMethod = Expense::query()
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('method, SUM(amount) as total')
            ->groupBy('method')
            ->get();

        $expenseByCategory = Expense::query()
            ->whereBetween('date', [$startDate, $endDate])
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
        $incomes = Income::select('id', 'amount', 'date', 'description', 'method', 'type as category', DB::raw("'income' as transaction_type"), 'user_id', 'created_at');
        $expenses = Expense::select('id', 'amount', 'date', 'description', 'method', 'category', DB::raw("'expense' as transaction_type"), 'user_id', 'created_at');

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
