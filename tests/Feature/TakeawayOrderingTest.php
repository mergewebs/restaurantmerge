<?php

namespace Tests\Feature;

use App\Mail\TakeawayOrderReceived;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TakeawayOrderingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_takeaway_page_is_accessible_without_login(): void
    {
        $response = $this->get(route('takeaway.public'));

        $response->assertOk();
        $response->assertSee('Take Away');
    }

    public function test_guest_can_submit_takeaway_order_and_email_notification_is_sent(): void
    {
        Mail::fake();

        config()->set('takeaway.notification.email_enabled', true);
        config()->set('takeaway.notification.email_recipients', 'owner@example.test');

        $burger = MenuItem::create([
            'name' => 'Takeaway Burger',
            'category' => 'Smash Burgers',
            'price' => 12.50,
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $drink = MenuItem::create([
            'name' => 'Takeaway Cola',
            'category' => 'Drinks',
            'price' => 3.00,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $response = $this->postJson(route('takeaway.public.store'), [
            'customer_name' => 'Alex Guest',
            'customer_phone' => '+34123456789',
            'items' => [
                ['menu_item_id' => $burger->id, 'quantity' => 2],
                ['menu_item_id' => $drink->id, 'quantity' => 1],
            ],
        ]);

        $response->assertCreated()
            ->assertJsonPath('message', 'Order #1 received. We are preparing your takeaway.');

        $order = Order::query()->latest('id')->firstOrFail();

        $this->assertSame(Order::TYPE_TAKEAWAY, $order->order_type);
        $this->assertSame('Alex Guest', $order->customer_name);
        $this->assertSame('+34123456789', $order->customer_phone);
        $this->assertEquals(28.00, (float) $order->total);
        $this->assertCount(2, $order->items);

        Mail::assertSent(TakeawayOrderReceived::class, 1);
    }

    public function test_takeaway_order_is_included_in_kitchen_snapshot_and_marked_as_takeaway(): void
    {
        config()->set('takeaway.notification.email_enabled', false);

        $menuItem = MenuItem::create([
            'name' => 'Snapshot Fries',
            'category' => 'Sides',
            'price' => 4.50,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $this->postJson(route('takeaway.public.store'), [
            'customer_name' => 'Kitchen Guest',
            'items' => [
                ['menu_item_id' => $menuItem->id, 'quantity' => 1],
            ],
        ])->assertCreated();

        $user = User::factory()->create();

        $snapshot = $this->actingAs($user)
            ->getJson(route('tables.kitchen.snapshot', ['since_version' => 0]));

        $snapshot->assertOk()
            ->assertJsonPath('changed', true);

        $firstOrder = collect($snapshot->json('orders'))->first();
        $this->assertNotNull($firstOrder);
        $this->assertSame('takeaway', $firstOrder['order_type']);
        $this->assertSame('public_takeaway', $firstOrder['source']);
        $this->assertSame('Public Takeaway', $firstOrder['source_label']);
    }
}
