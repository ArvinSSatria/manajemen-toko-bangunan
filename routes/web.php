<?php

use App\Http\Controllers\BoController;
use App\Http\Controllers\CashflowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreSwitchController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Store Switcher (Super Admin)
    Route::get('/store/switch', StoreSwitchController::class)->name('store.switch');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Stores
    Route::resource('stores', StoreController::class)->except(['show', 'create', 'edit']);

    // Users
    Route::resource('users', UserController::class)->except(['show', 'create', 'edit']);

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);

    // Products
    Route::resource('products', ProductController::class)->except(['show', 'create', 'edit']);

    // Inventory
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::put('/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');

    // Customers
    Route::resource('customers', CustomerController::class)->except(['create', 'edit']);
    Route::post('/customers/{customer}/deposit', [CustomerController::class, 'deposit'])->name('customers.deposit');

    // POS
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');

    // Sales
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');
    Route::get('/sales/{sale}/receipt', [SaleController::class, 'receipt'])->name('sales.receipt');

    // Receivables
    Route::get('/receivables', [ReceivableController::class, 'index'])->name('receivables.index');
    Route::get('/receivables/{receivable}', [ReceivableController::class, 'show'])->name('receivables.show');
    Route::post('/receivables/{receivable}/pay', [ReceivableController::class, 'pay'])->name('receivables.pay');

    // Income
    Route::get('/incomes', [IncomeController::class, 'index'])->name('incomes.index');

    // Expenses
    Route::resource('expenses', ExpenseController::class)->except('show');

    // BO / Supplier
    Route::resource('bo', BoController::class)->except(['create', 'edit']);
    Route::post('/bo/{bo}/orders', [BoController::class, 'storeOrder'])->name('bo.orders.store');
    Route::post('/bo-orders/{order}/pay', [BoController::class, 'payOrder'])->name('bo.orders.pay');

    // Stock Transfer
    Route::get('/transfers', [StockTransferController::class, 'index'])->name('transfers.index');
    Route::post('/transfers', [StockTransferController::class, 'store'])->name('transfers.store');
    Route::get('/transfers/{transfer}', [StockTransferController::class, 'show'])->name('transfers.show');
    Route::post('/transfers/{transfer}/complete', [StockTransferController::class, 'complete'])->name('transfers.complete');
    Route::post('/transfers/{transfer}/cancel', [StockTransferController::class, 'cancel'])->name('transfers.cancel');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

    // Cashflow
    Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflow.index');
});

require __DIR__.'/auth.php';
