<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini adalah definisi semua route web aplikasi Anda.
| Route di bawah sudah dilengkapi middleware untuk autentikasi dan role.
|
*/

// Halaman login default
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute yang membutuhkan otentikasi
Route::middleware(['auth'])->group(function () {

    // =====================
    // DASHBOARD
    // =====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // =====================
    // PROFILE MANAGEMENT
    // =====================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =====================
    // ADMIN - AKSES PENUH
    // =====================
    Route::middleware('role:admin')->group(function () {

        // Produk - CRUD lengkap
        Route::resource('products', ProductController::class)->except(['index', 'show']);

        // Produk - Tambahan fitur admin
        Route::get('/products/download-template', [ProductController::class, 'downloadTemplate'])->name('products.download-template');
        Route::get('/products/bulk-import', [ProductController::class, 'bulkImport'])->name('products.bulk-import');
        Route::post('/products/bulk-import', [ProductController::class, 'processBulkImport'])->name('products.process-bulk-import');
        Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    });

    // =====================
    // ADMIN & KASIR - PRODUK (READ ONLY UNTUK KASIR)
    // =====================
    Route::middleware('role:admin,kasir')->group(function () {
        // Produk hanya untuk view
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

        // Stok Menipis (bisa diakses admin & kasir)
        Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    });

    // =====================
    // ADMIN & KASIR - KATEGORI & LAPORAN
    // =====================
    Route::middleware('role:admin,kasir')->group(function () {

        // Kategori hanya bisa dilihat
        Route::resource('categories', CategoryController::class)->only(['index', 'show']);

        // Laporan
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

        // Form transaksi baru
        Route::get('transactions', [TransactionController::class, 'create'])->name('transactions.create');
        Route::post('transactions', [TransactionController::class, 'store'])->name('transactions.store');

        // CRUD transaksi kecuali show
        Route::resource('transactions', TransactionController::class)->except(['show']);

        // Show & Print PDF
        Route::get('/transactions/{transaction}/show', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{transaction}/print-pdf', [TransactionController::class, 'printPdf'])->name('transactions.print-pdf');
    });
});

// Rute otentikasi Laravel Breeze/Fortify/Jetstream
require __DIR__ . '/auth.php';
