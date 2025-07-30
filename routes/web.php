<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
// use App\Http\Controllers\TransactionDetailController; // Tetap dikomentari jika tidak digunakan
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController; // Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute web untuk aplikasi Anda. Ini
| rute dimuat oleh RouteServiceProvider dan semuanya akan
| ditetapkan ke grup middleware "web". Buat sesuatu yang hebat!
|
*/

// Rute untuk halaman utama yang mengarahkan ke halaman login
Route::get('/', function () {
    return view('auth.login');
});

// Rute yang membutuhkan otentikasi (login)
Route::middleware(['auth'])->group(function () {

    // Dashboard
    // Ini adalah satu-satunya definisi untuk dashboard setelah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Routes (hanya dapat diakses oleh user dengan role 'admin')
    Route::middleware('role:admin')->group(function () {
        // Contoh: Dashboard Admin. Sesuaikan jika Anda punya controller Admin spesifik.
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        // Tambahkan rute khusus admin lainnya di sini
    });

    // Kasir Routes (hanya dapat diakses oleh user dengan role 'kasir')
    Route::middleware('role:kasir')->group(function () {
        // Contoh: Dashboard Kasir. Sesuaikan jika Anda punya controller Kasir spesifik.
        Route::get('/kasir/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard');
        // Tambahkan rute khusus kasir lainnya di sini
    });

    // Category Management (CRUD resource)
    Route::resource('categories', CategoryController::class);

    // Product Management (CRUD resource dan rute kustom)
    Route::resource('products', ProductController::class);
    Route::get('/products/download-template', [ProductController::class, 'downloadTemplate'])->name('products.download-template');
    Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
    Route::get('/products/bulk-import', [ProductController::class, 'bulkImport'])->name('products.bulk-import');
    Route::post('/products/bulk-import', [ProductController::class, 'processBulkImport'])->name('products.process-bulk-import');

    // Transaction Management (CRUD resource dan rute kustom)
    // Gunakan 'except' untuk mencegah pembuatan rute 'show' default
    // karena kita akan mendefinisikan 'show' dan 'printPdf' secara kustom.
    Route::resource('transactions', TransactionController::class)->except(['show']);

    // Custom Transaction Routes
    // Ini adalah rute untuk menampilkan detail struk di browser
    Route::get('/transactions/{transaction}/show', [TransactionController::class, 'show'])->name('transactions.show');
    // Ini adalah rute untuk mengunduh struk sebagai PDF
    Route::get('/transactions/{transaction}/print-pdf', [TransactionController::class, 'printPdf'])->name('transactions.print-pdf');

    // Transaction Details (placeholder - jika kelak Anda membuat controller terpisah)
    // Route::get('/transaction-details', [TransactionDetailController::class, 'index'])->name('transaction-details.index');

    // Report Management
    Route::prefix('reports')->name('reports.')->group(function () {
        // PERBAIKAN: Mengarahkan rute utama '/reports' ke salesReport
        // Ini mengatasi error "Method App\Http\Controllers\ReportController::index does not exist."
        Route::get('/', [ReportController::class, 'salesReport'])->name('index'); // Misalnya, index reports adalah sales report
        Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
        Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
        // Tambahkan rute report lainnya di sini
    });
});

// Rute-rute otentikasi bawaan Laravel (login, register, reset password, dll.)
require __DIR__.'/auth.php';