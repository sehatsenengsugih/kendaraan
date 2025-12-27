<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GarasiController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LembagaController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\ParokiController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root to login or dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Guest Routes (unauthenticated users only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Global Search API
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // Profile (semua user bisa akses)
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Servis (super_admin, admin, admin_servis)
    Route::middleware('can:manage-servis')->group(function () {
        Route::post('servis/{servis}/selesai', [ServisController::class, 'selesai'])->name('servis.selesai');
        Route::resource('servis', ServisController::class)->parameters(['servis' => 'servis']);
    });

    // Kendaraan - create/store/edit/update/delete harus di atas {kendaraan}
    Route::middleware('can:access-main-menu')->group(function () {
        Route::get('kendaraan/create', [KendaraanController::class, 'create'])->name('kendaraan.create');
        Route::post('kendaraan', [KendaraanController::class, 'store'])->name('kendaraan.store');
        Route::get('kendaraan/{kendaraan}/edit', [KendaraanController::class, 'edit'])->name('kendaraan.edit');
        Route::put('kendaraan/{kendaraan}', [KendaraanController::class, 'update'])->name('kendaraan.update');
        Route::delete('kendaraan/{kendaraan}', [KendaraanController::class, 'destroy'])->name('kendaraan.destroy');
    });

    // Kendaraan read-only (index & show) - termasuk admin_servis
    Route::middleware('can:view-kendaraan')->group(function () {
        Route::get('kendaraan', [KendaraanController::class, 'index'])->name('kendaraan.index');
        Route::get('kendaraan/{kendaraan}', [KendaraanController::class, 'show'])->name('kendaraan.show');
    });

    // Master Data & Pajak (super_admin, admin, user)
    Route::middleware('can:access-main-menu')->group(function () {
        // Master Data
        Route::resource('garasi', GarasiController::class);
        Route::resource('merk', MerkController::class);
        Route::resource('paroki', ParokiController::class);
        Route::resource('lembaga', LembagaController::class);

        // Penugasan
        Route::post('penugasan/{penugasan}/selesai', [PenugasanController::class, 'selesai'])->name('penugasan.selesai');
        Route::resource('penugasan', PenugasanController::class);

        // Pajak
        Route::post('pajak/{pajak}/bayar', [PajakController::class, 'bayar'])->name('pajak.bayar');
        Route::resource('pajak', PajakController::class);
    });

    // User Management (Super Admin only)
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });

    // Audit Logs (Super Admin only)
    Route::middleware('can:view-audit-logs')->group(function () {
        Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('audit-logs/{auditLog}', [AuditLogController::class, 'show'])->name('audit-logs.show');
    });
});
