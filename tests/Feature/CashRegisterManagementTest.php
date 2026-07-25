<?php

namespace Tests\Feature;

use App\Models\CashRegisterSession;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashRegisterManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_open_reconcile_and_close_cash_register(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $this->actingAs($owner)->post(route('cash-register.open'), ['opening_float' => 100])->assertRedirect();
        $session = CashRegisterSession::firstOrFail();

        $order = Order::create(['status' => 'open', 'order_type' => 'takeaway', 'payment_status' => 'unpaid', 'total' => 50]);
        $this->actingAs($owner)->post(route('orders.payments.store', $order), ['payments' => [
            ['method' => 'cash', 'amount' => 20], ['method' => 'bizum', 'amount' => 30, 'reference' => 'BIZ-10'],
        ]])->assertRedirect();

        $this->assertDatabaseHas('order_payments', ['method' => 'cash', 'cash_register_session_id' => $session->id, 'user_id' => $owner->id]);
        $this->assertDatabaseHas('order_payments', ['method' => 'bizum', 'cash_register_session_id' => $session->id]);
        $this->assertSame(120.0, $session->calculateExpectedCash());

        $this->actingAs($owner)->post(route('cash-register.close', $session), ['actual_cash' => 119, 'notes' => 'One euro short'])->assertRedirect();
        $session->refresh();
        $this->assertSame('closed', $session->status);
        $this->assertSame(120.0, (float) $session->expected_cash);
        $this->assertSame(-1.0, (float) $session->difference);
        $this->assertSame($owner->id, $session->closed_by);
    }

    public function test_owner_can_refund_payment_and_bill_becomes_partially_paid(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $order = Order::create(['status' => 'open', 'order_type' => 'delivery', 'payment_status' => 'paid', 'total' => 30]);
        $payment = $order->payments()->create(['method' => 'online', 'transaction_type' => 'payment', 'amount' => 30, 'user_id' => $owner->id, 'paid_at' => now()]);

        $this->actingAs($owner)->post(route('cash-register.refund', $payment), ['amount' => 10, 'reference' => 'Customer refund'])->assertRedirect();

        $this->assertDatabaseHas('order_payments', ['order_id' => $order->id, 'method' => 'online', 'transaction_type' => 'refund', 'amount' => -10, 'refunded_payment_id' => $payment->id, 'user_id' => $owner->id]);
        $this->assertSame('partial', $order->fresh()->payment_status);
        $this->assertSame(20.0, $order->fresh()->paid_amount);
    }

    public function test_daily_register_report_lists_unpaid_bills_and_employee(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'name' => 'Maria Cashier']);
        $order = Order::create(['status' => 'open', 'order_type' => 'dine_in', 'payment_status' => 'unpaid', 'total' => 42]);
        OrderPayment::create(['order_id' => $order->id, 'method' => 'card', 'transaction_type' => 'payment', 'amount' => 10, 'user_id' => $owner->id, 'paid_at' => now()]);

        $this->actingAs($owner)->get(route('cash-register.index'))
            ->assertOk()->assertSee('Cash Register')->assertSee('Unpaid bills')->assertSee('Maria Cashier')->assertSee('Card')->assertSee('€32.00');
    }
}
