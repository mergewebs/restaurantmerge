<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $period = in_array($request->query('period'), ['day','week','month'], true) ? $request->query('period') : 'week';
        [$start, $end, $periodLabel] = $this->periodRange($period);

        $completedOrders = Order::with(['items.menuItem', 'payments', 'assignedUser'])
            ->where('workflow_status', 'completed')->whereBetween('completed_at', [$start, $end])->get();
        $cancelledOrders = Order::with('assignedUser')->where('workflow_status', 'cancelled')->whereBetween('cancelled_at', [$start, $end])->get();
        $payments = OrderPayment::with('user')->whereBetween('paid_at', [$start, $end])->get();
        $expenses = Expense::with('user')->whereBetween('date', [$start->toDateString(), $end->toDateString()])->get();
        $menuItems = MenuItem::all()->keyBy('id');
        $menuByName = $menuItems->keyBy(fn($item) => mb_strtolower(trim($item->name)));

        $sales = (float) $completedOrders->sum('total');
        $orderCount = $completedOrders->count();
        $averageOrderValue = $orderCount ? $sales / $orderCount : 0;
        $expenseTotal = (float) $expenses->sum('amount');
        $refundTotal = abs((float) $payments->where('transaction_type', 'refund')->sum('amount'));
        $discountTotal = (float) $completedOrders->sum('discount_amount');
        $estimatedProfit = $sales - $refundTotal - $expenseTotal;

        $salesSnapshots = [
            'today' => $this->salesFor(today()->startOfDay(), today()->endOfDay()),
            'week' => $this->salesFor(now()->startOfWeek(), now()->endOfWeek()),
            'month' => $this->salesFor(now()->startOfMonth(), now()->endOfMonth()),
        ];

        $productSales = [];
        $categorySales = [];
        foreach ($completedOrders as $order) {
            foreach ($order->items as $item) {
                $menuItem = $item->menuItem ?: $menuByName->get(mb_strtolower(trim($item->name)));
                $name = $menuItem?->name ?? $item->name;
                $category = $menuItem?->category ?? 'Other';
                $productSales[$name] ??= ['name'=>$name,'category'=>$category,'quantity'=>0,'sales'=>0.0];
                $productSales[$name]['quantity'] += (int) $item->quantity;
                $productSales[$name]['sales'] += (float) $item->line_total;
                $categorySales[$category] ??= ['name'=>$category,'quantity'=>0,'sales'=>0.0];
                $categorySales[$category]['quantity'] += (int) $item->quantity;
                $categorySales[$category]['sales'] += (float) $item->line_total;
            }
        }
        foreach ($menuItems as $menuItem) {
            $productSales[$menuItem->name] ??= ['name'=>$menuItem->name,'category'=>$menuItem->category,'quantity'=>0,'sales'=>0.0];
        }
        $bestProducts = collect($productSales)->sortByDesc('quantity')->take(8)->values();
        $worstProducts = collect($productSales)->sortBy('quantity')->take(8)->values();
        $categorySales = collect($categorySales)->sortByDesc('sales')->values();

        $orderTypeSales = $completedOrders->groupBy('order_type')->map(fn($orders,$type)=>['name'=>ucfirst(str_replace('_',' ',$type)),'orders'=>$orders->count(),'sales'=>(float)$orders->sum('total')])->sortByDesc('sales')->values();
        $paymentTotals = collect(OrderPayment::METHODS)->map(fn($method)=>['name'=>ucfirst($method),'total'=>(float)$payments->where('method',$method)->sum('amount')])->values();
        $busyHours = $completedOrders->groupBy(fn($order)=>$order->created_at->format('H:00'))->map(fn($orders,$hour)=>['name'=>$hour,'orders'=>$orders->count(),'sales'=>(float)$orders->sum('total')])->sortByDesc('orders')->take(8)->values();
        $busyDays = $completedOrders->groupBy(fn($order)=>$order->completed_at->format('D, d M'))->map(fn($orders,$day)=>['name'=>$day,'orders'=>$orders->count(),'sales'=>(float)$orders->sum('total')])->sortByDesc('orders')->take(8)->values();
        $employeePerformance = $completedOrders->groupBy('assigned_user_id')->map(function($orders){$user=$orders->first()->assignedUser;return ['name'=>$user?->name??'Unassigned','orders'=>$orders->count(),'sales'=>(float)$orders->sum('total'),'average'=>$orders->count()?(float)$orders->sum('total')/$orders->count():0];})->sortByDesc('sales')->values();
        $trend = $completedOrders->groupBy(fn($order)=>$period==='day'?$order->completed_at->format('H:00'):$order->completed_at->format('d M'))->map(fn($orders,$label)=>['name'=>$label,'orders'=>$orders->count(),'sales'=>(float)$orders->sum('total')])->values();

        return view('reports.index', compact('period','periodLabel','start','end','sales','orderCount','averageOrderValue','expenseTotal','refundTotal','discountTotal','estimatedProfit','cancelledOrders','salesSnapshots','bestProducts','worstProducts','categorySales','orderTypeSales','paymentTotals','busyHours','busyDays','employeePerformance','trend','expenses'));
    }

    private function periodRange(string $period): array
    {
        return match($period){
            'day'=>[today()->startOfDay(),today()->endOfDay(),'Today'],
            'month'=>[now()->startOfMonth(),now()->endOfMonth(),now()->format('F Y')],
            default=>[now()->startOfWeek(),now()->endOfWeek(),'This week'],
        };
    }

    private function salesFor(Carbon $start, Carbon $end): array
    {
        $query=Order::where('workflow_status','completed')->whereBetween('completed_at',[$start,$end]);
        return ['sales'=>(float)(clone $query)->sum('total'),'orders'=>(clone $query)->count()];
    }
}
