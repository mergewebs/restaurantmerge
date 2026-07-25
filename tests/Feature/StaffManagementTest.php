<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\RestaurantTable;
use App\Models\StaffAttendance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_create_schedule_and_assign_employee_permissions_and_tables(): void
    {
        $owner = User::factory()->create(['role'=>'owner']);
        $table = RestaurantTable::create(['number'=>31,'area'=>'Terrace']);
        $this->actingAs($owner)->post(route('users.store'), [
            'name'=>'Eva Waiter','role'=>'waiter','passcode'=>'123456','passcode_confirmation'=>'123456',
            'assigned_area'=>'Terrace','can_discount'=>1,'table_ids'=>[$table->id],
        ])->assertRedirect();
        $waiter = User::where('name','Eva Waiter')->firstOrFail();
        $this->assertTrue($waiter->can_discount);
        $this->assertFalse($waiter->can_refund);
        $this->assertDatabaseHas('restaurant_tables',['id'=>$table->id,'assigned_user_id'=>$waiter->id]);

        $this->actingAs($owner)->post(route('staff.schedules.store'), [
            'user_id'=>$waiter->id,'shift_date'=>today()->format('Y-m-d'),'starts_at'=>'12:00','ends_at'=>'20:00','area'=>'Terrace',
        ])->assertRedirect();
        $this->assertDatabaseHas('staff_schedules',['user_id'=>$waiter->id,'area'=>'Terrace']);

        Order::create(['assigned_user_id'=>$waiter->id,'status'=>'closed','workflow_status'=>'completed','order_type'=>'dine_in','payment_status'=>'paid','total'=>75,'completed_at'=>now()]);
        $this->actingAs($owner)->get(route('users.index'))->assertOk()->assertSee('Staff Management')->assertSee('Eva Waiter')->assertSee('€75.00')->assertSee('Terrace');
    }

    public function test_employee_can_clock_in_take_break_and_clock_out(): void
    {
        $waiter = User::factory()->create(['role'=>'waiter']);
        $this->actingAs($waiter)->get(route('users.index'))->assertOk()->assertSee('My shift')->assertDontSee('+ Employee');
        $this->actingAs($waiter)->post(route('staff.clock-in'))->assertRedirect();
        $attendance = StaffAttendance::where('user_id',$waiter->id)->firstOrFail();
        $this->actingAs($waiter)->post(route('staff.break.start'))->assertRedirect();
        $this->assertNotNull($attendance->fresh()->break_started_at);
        $this->travel(5)->minutes();
        $this->actingAs($waiter)->post(route('staff.break.end'))->assertRedirect();
        $this->assertGreaterThanOrEqual(5,$attendance->fresh()->break_minutes);
        $this->actingAs($waiter)->post(route('staff.clock-out'))->assertRedirect();
        $this->assertNotNull($attendance->fresh()->clocked_out_at);
    }

    public function test_discount_and_cancellation_permissions_are_enforced(): void
    {
        $waiter = User::factory()->create(['role'=>'waiter','can_discount'=>false,'can_cancel_orders'=>false]);
        $order = Order::create(['assigned_user_id'=>$waiter->id,'status'=>'open','workflow_status'=>'confirmed','order_type'=>'takeaway','payment_status'=>'unpaid','total'=>20]);
        $this->actingAs($waiter)->put(route('orders.adjustments.update',$order),['discount_amount'=>5])->assertForbidden();
        $this->actingAs($waiter)->patch(route('orders.status.update',$order),['workflow_status'=>'cancelled'])->assertForbidden();

        $waiter->update(['can_discount'=>true,'can_cancel_orders'=>true]);
        $this->actingAs($waiter)->put(route('orders.adjustments.update',$order),['discount_amount'=>5])->assertRedirect();
        $this->actingAs($waiter)->patch(route('orders.status.update',$order),['workflow_status'=>'cancelled'])->assertRedirect();
        $this->assertDatabaseHas('orders',['id'=>$order->id,'workflow_status'=>'cancelled','discount_amount'=>5]);
    }

    public function test_refund_permission_is_enforced(): void
    {
        $waiter = User::factory()->create(['role'=>'waiter','can_refund'=>false]);
        $order = Order::create(['status'=>'open','order_type'=>'takeaway','payment_status'=>'paid','total'=>20]);
        $payment = OrderPayment::create(['order_id'=>$order->id,'method'=>'cash','transaction_type'=>'payment','amount'=>20,'paid_at'=>now()]);
        $this->actingAs($waiter)->post(route('cash-register.refund',$payment),['amount'=>5])->assertForbidden();
        $waiter->update(['can_refund'=>true]);
        $this->actingAs($waiter)->post(route('cash-register.refund',$payment),['amount'=>5,'reference'=>'Approved return'])->assertRedirect();
        $this->assertDatabaseHas('order_payments',['refunded_payment_id'=>$payment->id,'amount'=>-5,'user_id'=>$waiter->id]);
    }
}
