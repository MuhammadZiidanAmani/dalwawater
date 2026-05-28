<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockInController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::get('/login', function () {
    return view('auth.login');
})->name('login.form');

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function (): void {

    Route::post('/transactions', [TransactionController::class, 'store'])
        ->name('transactions.store');

    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'receipt'])
        ->name('transactions.receipt');

    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])
        ->name('transactions.destroy');

    Route::middleware('role:admin')->group(function (): void {

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::delete('/products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');

        Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store'])
            ->name('categories.store');

        Route::put('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        Route::post('/stock-ins', [StockInController::class, 'store'])
            ->name('stock-ins.store');

        Route::post('/cashiers', [CashierController::class, 'store'])
            ->name('cashiers.store');

        Route::put('/cashiers/{cashier}', [CashierController::class, 'update'])
            ->name('cashiers.update');

        Route::put('/cashiers/{cashier}/reset-password', [CashierController::class, 'resetPassword'])
            ->name('cashiers.reset-password');

        Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])
            ->name('reports.export-csv');
    });
});