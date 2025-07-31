<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;

// Halaman login default
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute yang membutuhkan otentikasi
Route::middleware(['auth'])->group(function () {

    // Dashboard untuk semua role (admin & kasir)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =====================
    // ADMIN - AKSES PENUH
    // =====================
    Route::middleware('role:admin')->group(function () {
        Route::resource('products', ProductController::class);
        Route::get('/products/download-template', [ProductController::class, 'downloadTemplate'])->name('products.download-template');
        Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
        Route::get('/products/bulk-import', [ProductController::class, 'bulkImport'])->name('products.bulk-import');
        Route::post('/products/bulk-import', [ProductController::class, 'processBulkImport'])->name('products.process-bulk-import');
    });

    // =====================
    // ADMIN & KASIR - PRODUK (READ ONLY UNTUK KASIR)
    // =====================
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    });

    // =====================
    // ADMIN & KASIR - KATEGORI & LAPORAN
    // =====================
    Route::middleware('role:admin,kasir')->group(function () {
        Route::resource('categories', CategoryController::class)->only(['index', 'show']);

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'salesReport'])->name('index');
            Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
        });
    });

    // =====================
    // ADMIN & KASIR - TRANSAKSI
    // =====================
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('transactions', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');
        Route::resource('transactions', TransactionController::class)->except(['show']);
        Route::get('/transactions/{transaction}/show', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{transaction}/print-pdf', [TransactionController::class, 'printPdf'])->name('transactions.print-pdf');
    });
});

// Rute otentikasi Laravel
require __DIR__ . '/auth.php';
