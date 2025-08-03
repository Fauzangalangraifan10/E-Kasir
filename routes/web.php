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
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\PasswordResetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama -> login
Route::get('/', function () {
    return view('auth.login');
});

// =========================
// PRODUCT ROUTES
// =========================
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/download-template', [ProductController::class, 'downloadTemplate'])->name('download-template');
    Route::get('/low-stock', [ProductController::class, 'lowStock'])->name('low-stock');
    Route::get('/bulk-import', [ProductController::class, 'bulkImport'])->name('bulk-import');
    Route::post('/bulk-import', [ProductController::class, 'processBulkImport'])->name('process-bulk-import');
});
Route::resource('products', ProductController::class);

// =========================
// RESET PASSWORD ROUTES
// =========================
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

// Custom Direct Reset Password (Tanpa Email Link)
Route::get('/forgot-password-direct', [PasswordResetController::class, 'showForgotForm'])->name('password.request.direct');

// POST -> Redirect ke halaman form reset password langsung
Route::post('/forgot-password-direct', [PasswordResetController::class, 'showResetForm'])->name('password.redirect');

// POST -> Proses reset password langsung
Route::post('/reset-password-direct', [PasswordResetController::class, 'resetDirect'])->name('password.reset.direct');

// =========================
// EMAIL VERIFICATION ROUTES
// =========================
Route::get('/email/verify', EmailVerificationPromptController::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// =========================
// AUTHENTICATED ROUTES
// =========================
Route::middleware(['auth'])->group(function () {

    // DASHBOARD (Hanya untuk email terverifikasi)
    Route::middleware(['verified'])->group(function () {

        // Dashboard utama
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // =========================
        // PROFILE ROUTES
        // =========================
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // =========================
        // ADMIN ROUTES
        // =========================
        Route::middleware('role:admin,super_admin')->group(function () {
            Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        });

        // =========================
        // KASIR ROUTES
        // =========================
        Route::middleware('role:kasir')->group(function () {
            Route::get('/kasir/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard');
        });

        // =========================
        // CATEGORY MANAGEMENT
        // =========================
        Route::resource('categories', CategoryController::class);

        // =========================
        // TRANSACTION MANAGEMENT
        // =========================
        Route::resource('transactions', TransactionController::class)->except(['show']);
        Route::get('/transactions/{transaction}/show', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{transaction}/print-pdf', [TransactionController::class, 'printPdf'])->name('transactions.print-pdf');

        // =========================
        // SETTINGS MANAGEMENT
        // =========================
        Route::resource('settings', SettingsController::class)->only(['index']);
        Route::post('settings/update-profile', [SettingsController::class, 'updateProfile'])->name('settings.updateProfile');
        Route::post('settings/update-tax', [SettingsController::class, 'updateTax'])->name('settings.updateTax');
        Route::post('settings/payment-method', [SettingsController::class, 'storePaymentMethod'])->name('settings.storePaymentMethod');
        Route::delete('settings/payment-method/{id}', [SettingsController::class, 'deletePaymentMethod'])->name('settings.deletePaymentMethod');

        // =========================
        // USER MANAGEMENT (Super Admin & Admin)
        // =========================
        Route::middleware('role:super_admin,admin')->group(function () {
            Route::resource('users', UserManagementController::class);
            Route::patch('/users/{id}/deactivate', [UserManagementController::class, 'deactivate'])->name('users.deactivate');
            Route::patch('/users/{id}/activate', [UserManagementController::class, 'activate'])->name('users.activate');
        });

        // =========================
        // REPORT MANAGEMENT
        // =========================
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'salesReport'])->name('index');
            Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
            Route::get('/stock', [ReportController::class, 'stockReport'])->name('stock');
        });
    });
});

// =========================
// LARAVEL DEFAULT AUTH
// =========================
require __DIR__ . '/auth.php';
