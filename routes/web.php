<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestaurantConfigController;
use App\Http\Controllers\TableManagementController;
use App\Http\Controllers\PublicTakeawayController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Public Routes (for iframe)
Route::get('/reservation', [ReservationController::class, 'publicCreate'])->name('reservations.public.create');
Route::post('/reservation', [ReservationController::class, 'publicStore'])->name('reservations.public.store');
Route::get('/restaurant/schedule-data', [ReservationController::class, 'getScheduleData'])->name('restaurant.schedule.data');
Route::get('/restaurant/closed-dates', [ReservationController::class, 'getClosedDates'])->name('restaurant.closed-dates');
Route::get('/takeaway', [PublicTakeawayController::class, 'index'])->name('takeaway.public');
Route::post('/takeaway/orders', [PublicTakeawayController::class, 'store'])->name('takeaway.public.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reservations
    Route::resource('reservations', ReservationController::class);

    // Finance (simplified)
    Route::post('/finance', [FinanceController::class, 'store'])->name('finance.store');
    Route::put('/finance/{id}', [FinanceController::class, 'update'])->name('finance.update');
    Route::get('/finance/stats', [FinanceController::class, 'stats'])->name('finance.stats');
    Route::get('/finance/history', [FinanceController::class, 'history'])->name('finance.history');
    Route::delete('/finance/{id}', [FinanceController::class, 'destroy'])->name('finance.destroy');

    // Finance Categories
    Route::get('/finance/categories', [\App\Http\Controllers\FinanceCategoryController::class, 'index'])->name('finance.categories.index');
    Route::post('/finance/categories', [\App\Http\Controllers\FinanceCategoryController::class, 'store'])->name('finance.categories.store');
    Route::put('/finance/categories/{category}', [\App\Http\Controllers\FinanceCategoryController::class, 'update'])->name('finance.categories.update');
    Route::delete('/finance/categories/{category}', [\App\Http\Controllers\FinanceCategoryController::class, 'destroy'])->name('finance.categories.destroy');

    // Users
    Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);

    // Restaurant Configuration
    Route::get('/restaurant/config', [RestaurantConfigController::class, 'index'])->name('restaurant.config');
    Route::put('/restaurant/schedule', [RestaurantConfigController::class, 'updateSchedule'])->name('restaurant.schedule.update');
    Route::post('/restaurant/exceptions', [RestaurantConfigController::class, 'storeException'])->name('restaurant.exceptions.store');
    Route::delete('/restaurant/exceptions/{exception}', [RestaurantConfigController::class, 'destroyException'])->name('restaurant.exceptions.destroy');
    Route::post('/restaurant/toggle-today', [RestaurantConfigController::class, 'toggleToday'])->name('restaurant.toggle-today');

    // Table Management (TPV)
    Route::get('/tables', [TableManagementController::class, 'index'])->name('tables.index');
    Route::get('/tables/completed', [TableManagementController::class, 'completedOrders'])->name('tables.completed');
    Route::get('/kitchen', [TableManagementController::class, 'kitchen'])->name('tables.kitchen');
    Route::get('/kitchen/snapshot', [TableManagementController::class, 'kitchenSnapshot'])->name('tables.kitchen.snapshot');
    Route::get('/kitchen/stream', [TableManagementController::class, 'kitchenStream'])->name('tables.kitchen.stream');
    Route::get('/tables/{table}/order', [TableManagementController::class, 'orderPage'])->name('tables.order');
    Route::post('/tables', [TableManagementController::class, 'storeTable'])->name('tables.store');
    Route::post('/tables/{table}/items', [TableManagementController::class, 'storeItem'])->name('tables.items.store');
    Route::post('/tables/{table}/menu-items', [TableManagementController::class, 'quickAddMenuItem'])->name('tables.menu-items.quick-add');
    Route::post('/tables/{table}/sync', [TableManagementController::class, 'syncOrder'])->name('tables.order.sync');
    Route::post('/tables/{table}/send-to-kitchen', [TableManagementController::class, 'sendToKitchen'])->name('tables.send-to-kitchen');
    Route::put('/tables/{table}/items/{item}', [TableManagementController::class, 'updateItem'])->name('tables.items.update');
    Route::patch('/tables/orders/{order}/kitchen-status', [TableManagementController::class, 'updateKitchenOrderStatus'])->name('tables.orders.kitchen-status.update');
    Route::patch('/tables/items/{item}/kitchen-status', [TableManagementController::class, 'updateKitchenItemStatus'])->name('tables.items.kitchen-status.update');
    Route::delete('/tables/{table}/items/{item}', [TableManagementController::class, 'destroyItem'])->name('tables.items.destroy');
    Route::post('/tables/{table}/close-order', [TableManagementController::class, 'closeOrder'])->name('tables.close-order');
    Route::delete('/tables/{table}/delete-order', [TableManagementController::class, 'deleteOrder'])->name('tables.delete-order');
    Route::delete('/tables/{table}', [TableManagementController::class, 'destroyTable'])->name('tables.destroy');

    // Menu Management
    Route::get('/menu-items', [MenuItemController::class, 'index'])->name('menu-items.index');
    Route::post('/menu-items', [MenuItemController::class, 'store'])->name('menu-items.store');
    Route::put('/menu-items/{menuItem}', [MenuItemController::class, 'update'])->name('menu-items.update');
    Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
