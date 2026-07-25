<?php

namespace App\Http\Controllers;

use App\Models\CashRegisterSession;
use App\Models\Income;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CashRegisterController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureAdmin();
        $date = $request->date('date') ?? today();
        $openSession = CashRegisterSession::with('opener')->where('status', 'open')->latest('opened_at')->first();
        $payments = OrderPayment::with(['order.table', 'user', 'refundedPayment', 'refunds'])
            ->whereDate('paid_at', $date)->latest('paid_at')->get();
        $totals = collect(OrderPayment::METHODS)->mapWithKeys(fn ($method) => [$method => (float) $payments->where('method', $method)->sum('amount')]);
        $grossPayments = (float) $payments->where('transaction_type', 'payment')->sum('amount');
        $refunds = abs((float) $payments->where('transaction_type', 'refund')->sum('amount'));
        $discounts = (float) Order::whereDate('created_at', $date)->sum('discount_amount');
        $unpaidOrders = Order::with('table')->whereIn('payment_status', [Order::PAYMENT_STATUS_UNPAID, Order::PAYMENT_STATUS_PARTIAL])->latest()->get();
        $closedSessions = CashRegisterSession::with(['opener', 'closer'])->whereDate('opened_at', $date)->where('status', 'closed')->latest('closed_at')->get();

        return view('cash-register.index', compact('date', 'openSession', 'payments', 'totals', 'grossPayments', 'refunds', 'discounts', 'unpaidOrders', 'closedSessions'));
    }

    public function open(Request $request)
    {
        $this->ensureAdmin();
        if (CashRegisterSession::where('status', 'open')->exists()) return back()->with('error', 'A cash register is already open.');
        $validated = $request->validate(['opening_float' => ['required', 'numeric', 'min:0'], 'notes' => ['nullable', 'string', 'max:500']]);
        CashRegisterSession::create($validated + ['opened_by' => auth()->id(), 'opened_at' => now(), 'status' => 'open']);
        return back()->with('success', 'Cash register opened.');
    }

    public function close(Request $request, CashRegisterSession $session)
    {
        $this->ensureAdmin();
        abort_unless($session->status === 'open', 422);
        $validated = $request->validate(['actual_cash' => ['required', 'numeric', 'min:0'], 'notes' => ['nullable', 'string', 'max:500']]);
        $expected = $session->calculateExpectedCash();
        $session->update(['closed_by' => auth()->id(), 'expected_cash' => $expected, 'actual_cash' => $validated['actual_cash'], 'difference' => (float) $validated['actual_cash'] - $expected, 'closed_at' => now(), 'status' => 'closed', 'notes' => $validated['notes'] ?? $session->notes]);
        return back()->with('success', 'Cash register closed.');
    }

    public function refund(Request $request, OrderPayment $payment)
    {
        $this->ensureAdmin();
        abort_unless($payment->transaction_type === 'payment', 422);
        $refunded = abs((float) $payment->refunds()->sum('amount'));
        $validated = $request->validate(['amount' => ['required', 'numeric', 'gt:0', 'max:'.max(0, (float) $payment->amount - $refunded)], 'reference' => ['nullable', 'string', 'max:150']]);
        $session = CashRegisterSession::where('status', 'open')->latest('opened_at')->first();

        DB::transaction(function () use ($payment, $validated, $session) {
            $refund = $payment->order->payments()->create(['cash_register_session_id' => $session?->id, 'method' => $payment->method, 'transaction_type' => 'refund', 'refunded_payment_id' => $payment->id, 'amount' => -(float) $validated['amount'], 'reference' => $validated['reference'] ?? 'Refund', 'user_id' => auth()->id(), 'paid_at' => now()]);
            Income::create(['amount' => $refund->amount, 'type' => 'refund', 'method' => $payment->method === 'cash' ? 'cash' : 'card', 'description' => 'Refund for order #'.$payment->order_id, 'date' => today(), 'user_id' => auth()->id()]);
            $paid = (float) $payment->order->payments()->sum('amount');
            $payment->order->update(['payment_status' => $paid <= 0 ? Order::PAYMENT_STATUS_UNPAID : ($paid >= (float) $payment->order->total ? Order::PAYMENT_STATUS_PAID : Order::PAYMENT_STATUS_PARTIAL)]);
        });
        return back()->with('success', 'Refund recorded.');
    }

    private function ensureAdmin(): void { abort_unless(auth()->user()?->isAdmin(), 403); }
}
