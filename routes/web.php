<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController; // ✅ DITAMBAHKAN

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama -> login
Route::get('/', function () {
    return view('auth.login');
});

// Product Custom Routes
Route::get('/products/download-template', [ProductController::class, 'downloadTemplate'])->name('products.download-template');
Route::get('/products/low-stock', [ProductController::class, 'lowStock'])->name('products.low-stock');
Route::get('/products/bulk-import', [ProductController::class, 'bulkImport'])->name('products.bulk-import');
Route::post('/products/bulk-import', [ProductController::class, 'processBulkImport'])->name('products.process-bulk-import');

// Product Resource Routes
Route::resource('products', ProductController::class);

// Reset Password Routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

// Email Verification Routes
Route::get('/email/verify', EmailVerificationPromptController::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Routes yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {

    // Dashboard (Hanya untuk email terverifikasi)
    Route::middleware(['verified'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Admin Routes
        Route::middleware('role:admin,super_admin')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        });

        // Kasir Routes
        Route::middleware('role:kasir')->group(function () {
            Route::get('/kasir/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard');
        });

        // Category Management
        Route::resource('categories', CategoryController::class);

        // Transactions
        Route::resource('transactions', TransactionController::class)->except(['show']);
        Route::get('/transactions/{transaction}/show', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{transaction}/print-pdf', [TransactionController::class, 'printPdf'])->name('transactions.print-pdf');

        // Settings
        Route::resource('settings', SettingsController::class)->only(['index']);
        Route::post('settings/update-profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
        Route::post('settings/update-tax', [SettingsController::class, 'updateTax'])->name('settings.updateTax');
        Route::post('settings/payment-method', [SettingsController::class, 'storePaymentMethod'])->name('settings.storePaymentMethod');
        Route::delete('settings/payment-method/{id}', [SettingsController::class, 'deletePaymentMethod'])->name('settings.deletePaymentMethod');

        // User Management (Super Admin & Admin)
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::resource('users', UserManagementController::class);
            Route::patch('/users/{id}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate');
            Route::patch('/users/{id}/activate', [UserManagementController::class, 'activate'])->name('users.activate');
        });
        

        
        // Report Management
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'salesReport'])->name('index');
            Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
        });
    });
});

// Laravel Auth Routes
require __DIR__ . '/auth.php';
