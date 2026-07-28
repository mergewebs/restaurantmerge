<?php

namespace App\Services;

use App\Models\Income;
use App\Models\Order;
use App\Models\Reservation;
use App\Models\StaffSchedule;
use Database\Seeders\ClientDemoSeeder;
use Illuminate\Support\Facades\Cache;

class DemoDataManager
{
    private const REFRESH_LOCK = 'demo-experience-data-refresh';

    public function refreshIfNeeded(): void
    {
        if ($this->isCurrent()) {
            return;
        }

        Cache::lock(self::REFRESH_LOCK, 30)->block(10, function (): void {
            if ($this->isCurrent()) {
                return;
            }

            (new ClientDemoSeeder)->setContainer(app())->run();
        });
    }

    private function isCurrent(): bool
    {
        $today = today()->toDateString();

        return Reservation::query()
            ->where('customer_email', 'client.demo1@example.com')
            ->whereDate('reservation_time', $today)
            ->exists()
            && Order::query()
                ->where('notes', 'Client demo order 1')
                ->whereDate('completed_at', $today)
                ->exists()
            && Income::query()
                ->where('description', 'Client demo income 5')
                ->whereDate('date', $today)
                ->exists()
            && StaffSchedule::query()
                ->where('notes', 'Client demo shift 1')
                ->whereDate('shift_date', $today)
                ->exists();
    }
}
