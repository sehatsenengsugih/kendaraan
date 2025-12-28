<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ManualController;
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
use App\Models\Pengguna;
use Illuminate\Support\Facades\Route;

// Route model binding for 'user' parameter to use Pengguna model
Route::model('user', Pengguna::class);

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

        // Kendaraan Image Management
        Route::post('kendaraan/{kendaraan}/gambar/reorder', [KendaraanController::class, 'reorderGambar'])->name('kendaraan.gambar.reorder');
        Route::post('kendaraan/{kendaraan}/gambar/{gambar}/set-avatar', [KendaraanController::class, 'setGambarAsAvatar'])->name('kendaraan.gambar.set-avatar');
    });

    // Kendaraan read-only (index & show) - termasuk admin_servis
    Route::middleware('can:view-kendaraan')->group(function () {
        Route::get('kendaraan', [KendaraanController::class, 'index'])->name('kendaraan.index');
        Route::get('kendaraan/{kendaraan}', [KendaraanController::class, 'show'])->name('kendaraan.show');
    });

    // Master Data (super_admin, admin only)
    Route::middleware('can:access-master-data')->group(function () {
        Route::resource('garasi', GarasiController::class);
        Route::resource('merk', MerkController::class);
        Route::resource('paroki', ParokiController::class);
        Route::resource('lembaga', LembagaController::class);
    });

    // Penugasan & Pajak (super_admin, admin, user)
    Route::middleware('can:access-main-menu')->group(function () {
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

    // Calendar
    Route::get('kalender', [CalendarController::class, 'index'])->name('calendar.index');

    // Manual / Panduan Pengguna (Public read)
    Route::get('manual', [ManualController::class, 'index'])->name('manual.index');
    Route::get('manual/section/{section}', [ManualController::class, 'section'])->name('manual.section');

    // Manual Admin (Super Admin only)
    Route::middleware('can:manage-users')->prefix('manual/admin')->name('manual.admin.')->group(function () {
        Route::get('/', [ManualController::class, 'adminIndex'])->name('index');
        Route::get('/create', [ManualController::class, 'create'])->name('create');
        Route::post('/', [ManualController::class, 'store'])->name('store');
        Route::get('/{manualSection}/edit', [ManualController::class, 'edit'])->name('edit');
        Route::put('/{manualSection}', [ManualController::class, 'update'])->name('update');
        Route::delete('/{manualSection}', [ManualController::class, 'destroy'])->name('destroy');
        Route::post('/reorder', [ManualController::class, 'reorder'])->name('reorder');
    });

    // Calendar API
    Route::prefix('api/calendar')->group(function () {
        Route::get('events', [CalendarController::class, 'events'])->name('api.calendar.events');
        Route::get('mini-events', [CalendarController::class, 'miniCalendarEvents'])->name('api.calendar.mini-events');
        Route::post('pajak', [CalendarController::class, 'storePajak'])->name('api.calendar.pajak.store');
        Route::put('pajak/{pajak}', [CalendarController::class, 'updatePajak'])->name('api.calendar.pajak.update');
        Route::delete('pajak/{pajak}', [CalendarController::class, 'destroyPajak'])->name('api.calendar.pajak.destroy');
        Route::post('servis', [CalendarController::class, 'storeServis'])->name('api.calendar.servis.store');
        Route::put('servis/{servis}', [CalendarController::class, 'updateServis'])->name('api.calendar.servis.update');
        Route::delete('servis/{servis}', [CalendarController::class, 'destroyServis'])->name('api.calendar.servis.destroy');
        Route::post('{type}/{id}/move', [CalendarController::class, 'moveEvent'])->name('api.calendar.move');
    });
});
