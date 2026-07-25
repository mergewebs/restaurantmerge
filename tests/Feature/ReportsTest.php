<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_view_complete_restaurant_reports(): void
    {
        $owner=User::factory()->create(['role'=>'owner']);
        $waiter=User::factory()->create(['role'=>'waiter','name'=>'Top Waiter']);
        $burger=MenuItem::create(['name'=>'Report Burger','category'=>'Burgers','price'=>20,'is_active'=>true]);
        $drink=MenuItem::create(['name'=>'Report Lemonade','category'=>'Drinks','price'=>5,'is_active'=>true]);
        $order=Order::create(['assigned_user_id'=>$waiter->id,'status'=>'closed','workflow_status'=>'completed','order_type'=>'delivery','payment_status'=>'paid','total'=>45,'discount_amount'=>5,'completed_at'=>now()]);
        $order->items()->create(['menu_item_id'=>$burger->id,'name'=>$burger->name,'quantity'=>2,'unit_price'=>20,'line_total'=>40]);
        $order->items()->create(['menu_item_id'=>$drink->id,'name'=>$drink->name,'quantity'=>1,'unit_price'=>5,'line_total'=>5]);
        OrderPayment::create(['order_id'=>$order->id,'method'=>'card','transaction_type'=>'payment','amount'=>45,'user_id'=>$owner->id,'paid_at'=>now()]);
        OrderPayment::create(['order_id'=>$order->id,'method'=>'card','transaction_type'=>'refund','amount'=>-3,'user_id'=>$owner->id,'paid_at'=>now()]);
        Expense::create(['amount'=>10,'category'=>'Supplies','method'=>'cash','description'=>'Report expense','date'=>today(),'user_id'=>$owner->id]);
        Order::create(['assigned_user_id'=>$waiter->id,'status'=>'closed','workflow_status'=>'cancelled','order_type'=>'pickup','payment_status'=>'unpaid','total'=>12,'cancelled_at'=>now()]);

        $this->actingAs($owner)->get(route('reports.index',['period'=>'month']))
            ->assertOk()->assertSee('Reports')->assertSee('Report Burger')->assertSee('Burgers')->assertSee('Delivery')->assertSee('Top Waiter')
            ->assertViewHas('sales',45.0)->assertViewHas('refundTotal',3.0)->assertViewHas('expenseTotal',10.0)->assertViewHas('estimatedProfit',32.0)
            ->assertViewHas('averageOrderValue',45.0);
    }

    public function test_reports_support_daily_weekly_and_monthly_periods(): void
    {
        $owner=User::factory()->create(['role'=>'owner']);
        foreach(['day','week','month'] as $period){$this->actingAs($owner)->get(route('reports.index',['period'=>$period]))->assertOk()->assertViewHas('period',$period);}
    }

    public function test_waiter_cannot_view_management_reports(): void
    {
        $waiter=User::factory()->create(['role'=>'waiter']);
        $this->actingAs($waiter)->get(route('reports.index'))->assertForbidden();
    }
}
