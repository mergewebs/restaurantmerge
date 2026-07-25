<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemOptionController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RestaurantConfigController;
use App\Http\Controllers\TableManagementController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/dashboard');

Route::redirect('/admin', '/admin/dashboard');

Route::prefix('admin')->middleware(['auth', 'noindex'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/details', [OrderController::class, 'updateDetails'])->name('orders.details.update');
    Route::post('/orders/{order}/items', [OrderController::class, 'addItem'])->name('orders.items.store');
    Route::delete('/orders/{order}/items/{item}', [OrderController::class, 'removeItem'])->name('orders.items.destroy');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status.update');
    Route::put('/orders/{order}/adjustments', [OrderController::class, 'updateAdjustments'])->name('orders.adjustments.update');
    Route::post('/orders/{order}/payments', [OrderController::class, 'storePayments'])->name('orders.payments.store');
    Route::get('/orders/{order}/ticket', [OrderController::class, 'ticket'])->name('orders.ticket');
    Route::get('/orders/{order}/ticket/download', [OrderController::class, 'downloadTicket'])->name('orders.ticket.download');
    Route::patch('/reservations/{reservation}/status', [ReservationController::class, 'updateStatus'])->name('reservations.status.update');
    Route::patch('/reservations/{reservation}/table', [ReservationController::class, 'assignTable'])->name('reservations.table.update');
    Route::resource('reservations', ReservationController::class);
    Route::get('/restaurant/schedule-data', [ReservationController::class, 'getScheduleData'])->name('restaurant.schedule.data');
    Route::get('/restaurant/closed-dates', [ReservationController::class, 'getClosedDates'])->name('restaurant.closed-dates');

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
    Route::post('/staff/schedules', [StaffController::class, 'storeSchedule'])->name('staff.schedules.store');
    Route::delete('/staff/schedules/{schedule}', [StaffController::class, 'destroySchedule'])->name('staff.schedules.destroy');
    Route::put('/staff/{user}/assignment', [StaffController::class, 'assign'])->name('staff.assignment.update');
    Route::post('/staff/clock-in', [StaffController::class, 'clockIn'])->name('staff.clock-in');
    Route::post('/staff/break/start', [StaffController::class, 'startBreak'])->name('staff.break.start');
    Route::post('/staff/break/end', [StaffController::class, 'endBreak'])->name('staff.break.end');
    Route::post('/staff/clock-out', [StaffController::class, 'clockOut'])->name('staff.clock-out');

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
        Route::patch('/tables/{table}/floor-status', [TableManagementController::class, 'updateFloorStatus'])->name('tables.floor-status.update');
        Route::post('/tables/{table}/assign-order', [TableManagementController::class, 'assignOrder'])->name('tables.assign-order');
        Route::post('/tables/{table}/move-order', [TableManagementController::class, 'moveOrder'])->name('tables.move-order');
        Route::post('/tables/{table}/merge', [TableManagementController::class, 'mergeTable'])->name('tables.merge');
        Route::delete('/tables/{table}/merge', [TableManagementController::class, 'unmergeTable'])->name('tables.unmerge');
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
    Route::post('/menu-categories', [MenuCategoryController::class, 'store'])->name('menu-categories.store');
    Route::put('/menu-categories/{menuCategory}', [MenuCategoryController::class, 'update'])->name('menu-categories.update');
    Route::delete('/menu-categories/{menuCategory}', [MenuCategoryController::class, 'destroy'])->name('menu-categories.destroy');
    Route::post('/menu-items/{menuItem}/options', [MenuItemOptionController::class, 'store'])->name('menu-items.options.store');
    Route::delete('/menu-items/{menuItem}/options/{option}', [MenuItemOptionController::class, 'destroy'])->name('menu-items.options.destroy');
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/ingredients', [InventoryController::class, 'storeIngredient'])->name('inventory.ingredients.store');
    Route::put('/inventory/ingredients/{ingredient}', [InventoryController::class, 'updateIngredient'])->name('inventory.ingredients.update');
    Route::post('/inventory/suppliers', [InventoryController::class, 'storeSupplier'])->name('inventory.suppliers.store');
    Route::put('/inventory/suppliers/{supplier}', [InventoryController::class, 'updateSupplier'])->name('inventory.suppliers.update');
    Route::post('/inventory/movements', [InventoryController::class, 'storeMovement'])->name('inventory.movements.store');
    Route::post('/inventory/products/{menuItem}/recipe', [InventoryController::class, 'storeRecipe'])->name('inventory.recipes.store');
    Route::delete('/inventory/products/{menuItem}/recipe/{ingredient}', [InventoryController::class, 'destroyRecipe'])->name('inventory.recipes.destroy');
    Route::get('/cash-register', [CashRegisterController::class, 'index'])->name('cash-register.index');
    Route::post('/cash-register/open', [CashRegisterController::class, 'open'])->name('cash-register.open');
    Route::post('/cash-register/{session}/close', [CashRegisterController::class, 'close'])->name('cash-register.close');
    Route::post('/cash-register/payments/{payment}/refund', [CashRegisterController::class, 'refund'])->name('cash-register.refund');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
