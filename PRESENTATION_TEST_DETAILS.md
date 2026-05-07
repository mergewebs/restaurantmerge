# Presentation Test Details

Use these demo accounts after running `php artisan db:seed`.

| Role | Email | Password | Best pages to show |
| --- | --- | --- | --- |
| Super Admin | `superadmin@example.com` | `password` | Users, dashboard, finance, restaurant config |
| Owner | `owner@example.com` | `password` | Dashboard, finance stats/history, reservations |
| Waiter | `waiter@example.com` | `password` | Tables, table order screen, kitchen flow |

## Quick Demo Flow

1. Log in as Super Admin and open Users to show role management.
2. Open Dashboard to show today's reservations, sales, expenses, and recent transactions.
3. Open Tables to show active table orders for tables 2 and 5.
4. Open Kitchen to show pending, in-preparation, and ready orders, including public takeaway.
5. Open `/takeaway` in another tab to show the public customer order flow.
6. Log in as Waiter to show focused operational access for table service.
7. Log in as Owner to show finance stats, transaction history, cashout, and restaurant configuration.

## Seeded Presentation Data

- Demo menu with burgers, bowls, drinks, desserts, and package items.
- Eight restaurant tables plus a hidden takeaway table.
- Active orders for two tables and one public takeaway order.
- Confirmed reservations for today and tomorrow, plus one completed reservation.
- Daily finance records across cash/card income and operating expenses.
