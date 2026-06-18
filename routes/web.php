<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PosController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.dashboard'));

// Authentication
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.attempt');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin area (auth required)
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('categories/children', [CategoryController::class, 'children'])->name('categories.children');
    Route::resource('categories', CategoryController::class)->except('show');

    // Custom product tools — must precede the resource so they aren't treated as {product}.
    Route::get('products/bulk-pricing', [ProductController::class, 'bulkPricing'])->name('products.bulk-pricing');
    Route::post('products/bulk-pricing', [ProductController::class, 'bulkPricingUpdate'])->name('products.bulk-pricing.update');
    Route::get('products/labels', [ProductController::class, 'labels'])->name('products.labels');
    Route::get('products/bulk-import', [ProductController::class, 'bulkImport'])->name('products.bulk-import');
    Route::post('products/bulk-import', [ProductController::class, 'bulkImportStore'])->name('products.bulk-import.store');
    Route::get('products/import-template', [ProductController::class, 'importTemplate'])->name('products.import-template');
    Route::resource('products', ProductController::class);

    // Contacts
    Route::resource('suppliers', SupplierController::class)->except('show');
    Route::resource('customers', CustomerController::class)->except('show');

    // Purchases (create/view/delete only — no edit to keep stock consistent)
    Route::get('purchases/bulk', [PurchaseController::class, 'bulkCreate'])->name('purchases.bulk');
    Route::post('purchases/bulk', [PurchaseController::class, 'bulkStore'])->name('purchases.bulk.store');
    Route::get('purchases/import-template', [PurchaseController::class, 'importTemplate'])->name('purchases.import-template');
    Route::resource('purchases', PurchaseController::class)->only(['index', 'create', 'store', 'show', 'destroy']);

    // Sales (created via POS; managed here)
    Route::resource('sales', SaleController::class)->only(['index', 'show', 'destroy']);

    // POS
    Route::get('pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('pos', [PosController::class, 'store'])->name('pos.store');
    Route::post('pos/customers', [PosController::class, 'storeCustomer'])->name('pos.customers.store');

    // Stock
    Route::get('stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('stock/movements', [StockController::class, 'movements'])->name('stock.movements');
    Route::get('stock/{product}/adjust', [StockController::class, 'adjust'])->name('stock.adjust');
    Route::post('stock/{product}/adjust', [StockController::class, 'storeAdjust'])->name('stock.adjust.store');

    // Reports
    Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
    Route::get('reports/products', [ReportController::class, 'products'])->name('reports.products');
    Route::get('reports/purchases', [ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('reports/stock', [ReportController::class, 'stock'])->name('reports.stock');

    // Access control
    Route::resource('users', UserController::class)->except('show');
    Route::resource('roles', RoleController::class)->except('show');
    Route::resource('permissions', PermissionController::class)->except('show');
});
