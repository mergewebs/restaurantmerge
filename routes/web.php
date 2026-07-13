<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicTakeawayController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RestaurantConfigController;
use App\Http\Controllers\TableManagementController;
use App\Http\Controllers\UserController;
use App\Models\MenuItem;
use App\Models\RestaurantSchedule;
use App\Support\Seo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $menuItems = \App\Models\MenuItem::where('is_active', true)
            ->orderedByCategory()
            ->orderBy('sort_order')
            ->get();
        $schedule = \App\Models\RestaurantSchedule::orderBy('day_of_week')->get();
    } catch (\Exception $e) {
        $menuItems = collect();
        $schedule = collect();
    }

    $categories = $menuItems->pluck('category')->filter()->unique()->take(3)->implode(', ');
    $description = __('public.homepage_meta_description');

    if ($menuItems->isNotEmpty() && $categories !== '') {
        $description .= ' ' . __('public.seo_home_dynamic_suffix', [
            'count' => $menuItems->count(),
            'categories' => $categories,
        ]);
    }

    $seo = Seo::make([
        'title' => __('public.seo_home_title'),
        'description' => $description,
        'keywords' => __('public.seo_home_keywords'),
        'canonical' => route('home'),
        'structured_data' => [
            Seo::websiteStructuredData(),
            Seo::restaurantStructuredData($schedule),
        ],
    ]);

    return view('welcome', compact('menuItems', 'schedule', 'seo'));
})->name('home');

Route::get('/robots.txt', function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
        'Disallow: /admin',
        'Disallow: /order',
        'Disallow: /takeaway',
        'Disallow: /restaurant/schedule-data',
        'Disallow: /restaurant/closed-dates',
        'Sitemap: ' . route('sitemap'),
    ];

    return response(implode(PHP_EOL, $lines) . PHP_EOL, 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
    ]);
})->name('robots');

Route::get('/sitemap.xml', function () {
    $latestPublicUpdate = collect([
        MenuItem::query()->max('updated_at'),
        RestaurantSchedule::query()->max('updated_at'),
    ])->filter()->map(fn ($value) => Carbon::parse($value))->sortDesc()->first() ?? now();

    $urls = [
        ['loc' => route('home'), 'lastmod' => $latestPublicUpdate->toAtomString(), 'changefreq' => 'weekly', 'priority' => '1.0'],
        ['loc' => route('menu.public'), 'lastmod' => $latestPublicUpdate->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.9'],
        ['loc' => route('reservations.public.create'), 'lastmod' => $latestPublicUpdate->toAtomString(), 'changefreq' => 'weekly', 'priority' => '0.8'],
    ];

    $xml = view('sitemap.index', ['urls' => $urls])->render();

    return response($xml, 200, [
        'Content-Type' => 'application/xml; charset=UTF-8',
    ]);
})->name('sitemap');

Route::post('/locale', [LocaleController::class, 'update'])->name('locale.update');

Route::get('/reservation', [ReservationController::class, 'publicCreate'])->name('reservations.public.create');
Route::post('/reservation', [ReservationController::class, 'publicStore'])->name('reservations.public.store');
Route::middleware('noindex')->group(function () {
    Route::get('/restaurant/schedule-data', [ReservationController::class, 'getScheduleData'])->name('restaurant.schedule.data');
    Route::get('/restaurant/closed-dates', [ReservationController::class, 'getClosedDates'])->name('restaurant.closed-dates');
});

Route::get('/menu', [PublicTakeawayController::class, 'menu'])->name('menu.public');

Route::middleware(['feature:takeaway', 'noindex'])->group(function () {
    Route::get('/order', [PublicTakeawayController::class, 'index'])->name('order.public');
    Route::post('/order', [PublicTakeawayController::class, 'store'])->name('order.public.store');

    Route::get('/takeaway', [PublicTakeawayController::class, 'index'])->name('takeaway.public');
    Route::post('/takeaway/orders', [PublicTakeawayController::class, 'store'])->name('takeaway.public.store');
});

Route::redirect('/admin', '/admin/dashboard');

Route::prefix('admin')->middleware(['auth', 'noindex'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('reservations', ReservationController::class);

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance', [FinanceController::class, 'store'])->name('finance.store');
    Route::put('/finance/{id}', [FinanceController::class, 'update'])->name('finance.update');
    Route::get('/finance/stats', [FinanceController::class, 'stats'])->name('finance.stats');
    Route::get('/finance/history', [FinanceController::class, 'history'])->name('finance.history');
    Route::delete('/finance/{id}', [FinanceController::class, 'destroy'])->name('finance.destroy');

    Route::get('/finance/categories', [\App\Http\Controllers\FinanceCategoryController::class, 'index'])->name('finance.categories.index');
    Route::post('/finance/categories', [\App\Http\Controllers\FinanceCategoryController::class, 'store'])->name('finance.categories.store');
    Route::put('/finance/categories/{category}', [\App\Http\Controllers\FinanceCategoryController::class, 'update'])->name('finance.categories.update');
    Route::delete('/finance/categories/{category}', [\App\Http\Controllers\FinanceCategoryController::class, 'destroy'])->name('finance.categories.destroy');

    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    Route::get('/restaurant/config', [RestaurantConfigController::class, 'index'])->name('restaurant.config');
    Route::put('/restaurant/schedule', [RestaurantConfigController::class, 'updateSchedule'])->name('restaurant.schedule.update');
    Route::post('/restaurant/exceptions', [RestaurantConfigController::class, 'storeException'])->name('restaurant.exceptions.store');
    Route::delete('/restaurant/exceptions/{exception}', [RestaurantConfigController::class, 'destroyException'])->name('restaurant.exceptions.destroy');
    Route::post('/restaurant/toggle-today', [RestaurantConfigController::class, 'toggleToday'])->name('restaurant.toggle-today');

    Route::middleware('feature:tables')->group(function () {
        Route::get('/tables', [TableManagementController::class, 'index'])->name('tables.index');
        Route::get('/tables/completed', [TableManagementController::class, 'completedOrders'])->name('tables.completed');
        Route::get('/tables/{table}/order', [TableManagementController::class, 'orderPage'])->name('tables.order');
        Route::post('/tables', [TableManagementController::class, 'storeTable'])->name('tables.store');
        Route::post('/tables/{table}/items', [TableManagementController::class, 'storeItem'])->name('tables.items.store');
        Route::post('/tables/{table}/menu-items', [TableManagementController::class, 'quickAddMenuItem'])->name('tables.menu-items.quick-add');
        Route::post('/tables/{table}/sync', [TableManagementController::class, 'syncOrder'])->name('tables.order.sync');
        Route::post('/tables/{table}/send-to-kitchen', [TableManagementController::class, 'sendToKitchen'])->name('tables.send-to-kitchen');
        Route::put('/tables/{table}/items/{item}', [TableManagementController::class, 'updateItem'])->name('tables.items.update');
        Route::delete('/tables/{table}/items/{item}', [TableManagementController::class, 'destroyItem'])->name('tables.items.destroy');
        Route::post('/tables/{table}/close-order', [TableManagementController::class, 'closeOrder'])->name('tables.close-order');
        Route::delete('/tables/{table}/delete-order', [TableManagementController::class, 'deleteOrder'])->name('tables.delete-order');
        Route::delete('/tables/{table}', [TableManagementController::class, 'destroyTable'])->name('tables.destroy');
    });

    Route::middleware('feature:kitchen')->group(function () {
        Route::get('/kitchen', [TableManagementController::class, 'kitchen'])->name('tables.kitchen');
        Route::get('/kitchen/snapshot', [TableManagementController::class, 'kitchenSnapshot'])->name('tables.kitchen.snapshot');
        Route::get('/kitchen/stream', [TableManagementController::class, 'kitchenStream'])->name('tables.kitchen.stream');
        Route::patch('/tables/orders/{order}/kitchen-status', [TableManagementController::class, 'updateKitchenOrderStatus'])->name('tables.orders.kitchen-status.update');
        Route::patch('/tables/items/{item}/kitchen-status', [TableManagementController::class, 'updateKitchenItemStatus'])->name('tables.items.kitchen-status.update');
    });

    Route::get('/menu-items', [MenuItemController::class, 'index'])->name('menu-items.index');
    Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
    Route::put('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
    Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
