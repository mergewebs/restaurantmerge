<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class FinanceFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_summary_filters_support_yesterday_preset(): void
    {
        Carbon::setTestNow('2026-07-09 12:00:00');

        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Income::create([
            'amount' => 100,
            'type' => 'sale',
            'method' => 'cash',
            'description' => 'Yesterday income',
            'date' => '2026-07-08',
            'user_id' => $user->id,
        ]);

        Income::create([
            'amount' => 250,
            'type' => 'sale',
            'method' => 'cash',
            'description' => 'Today income',
            'date' => '2026-07-09',
            'user_id' => $user->id,
        ]);

        Expense::create([
            'amount' => 25,
            'category' => 'supplies',
            'method' => 'card',
            'description' => 'Yesterday expense',
            'date' => '2026-07-08',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('finance.index', [
            'mode' => 'yesterday',
        ]));

        $response->assertOk();
        $response->assertSee('08 Jul 2026 - 08 Jul 2026');
        $response->assertSee('name="mode"', false);
        $response->assertSee('value="yesterday" selected', false);
        $response->assertSee('100.00', false);
        $response->assertSee('25.00', false);

        Carbon::setTestNow();
    }

    public function test_summary_date_filter_applies_to_filtered_transactions_but_not_latest_entries(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Income::create([
            'amount' => 88,
            'type' => 'sale',
            'method' => 'cash',
            'description' => 'Ramen lunch',
            'date' => '2026-07-09',
            'user_id' => $user->id,
        ]);

        Income::create([
            'amount' => 44,
            'type' => 'sale',
            'method' => 'card',
            'description' => 'Burger yesterday',
            'date' => '2026-07-08',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('finance.index', [
            'mode' => 'today',
            'history_query' => 'Ramen',
        ]));

        $response->assertOk();
        $response->assertSee('Ramen lunch');
        $response->assertSee('Burger yesterday');
        $response->assertSee('data-auto-submit-search', false);
        $response->assertSee('name="date_from" value="' . now()->toDateString() . '"', false);
        $response->assertDontSee('name="history_range"', false);
        $response->assertDontSee('name="history_date_from"', false);
    }

    public function test_summary_filters_support_custom_date_range(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Income::create([
            'amount' => 50,
            'type' => 'sale',
            'method' => 'cash',
            'description' => 'Inside range income',
            'date' => '2026-07-02',
            'user_id' => $user->id,
        ]);

        Income::create([
            'amount' => 90,
            'type' => 'sale',
            'method' => 'cash',
            'description' => 'Outside range income',
            'date' => '2026-07-08',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('finance.index', [
            'mode' => 'custom',
            'date_from' => '2026-07-01',
            'date_to' => '2026-07-03',
        ]));

        $response->assertOk();
        $response->assertSee('01 Jul 2026 - 03 Jul 2026');
        $response->assertSee('50.00', false);
        $response->assertSee('value="2026-07-01"', false);
        $response->assertSee('value="2026-07-03"', false);
    }

    public function test_filtered_transactions_follow_summary_custom_date_range(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        Income::create([
            'amount' => 77,
            'type' => 'sale',
            'method' => 'cash',
            'description' => 'Inside history range',
            'date' => '2026-07-02',
            'user_id' => $user->id,
        ]);

        Income::create([
            'amount' => 33,
            'type' => 'sale',
            'method' => 'card',
            'description' => 'Outside history range',
            'date' => '2026-07-09',
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('finance.index', [
            'mode' => 'custom',
            'date_from' => '2026-07-01',
            'date_to' => '2026-07-03',
        ]));

        $response->assertOk();
        $response->assertSee('Inside history range');
        $response->assertSee('Outside history range');
        $response->assertSee('From: 01 Jul 2026', false);
        $response->assertSee('To: 03 Jul 2026', false);
        $response->assertDontSee('name="history_date_to"', false);
    }
}

