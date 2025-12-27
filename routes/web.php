<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GarasiController;
use App\Http\Controllers\MerkController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\PenugasanController;
use App\Http\Controllers\PajakController;
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

    // Master Data
    Route::resource('garasi', GarasiController::class);
    Route::resource('merk', MerkController::class);

    // Kendaraan
    Route::resource('kendaraan', KendaraanController::class);

    // Penugasan
    Route::post('penugasan/{penugasan}/selesai', [PenugasanController::class, 'selesai'])->name('penugasan.selesai');
    Route::resource('penugasan', PenugasanController::class);

    // Pajak
    Route::post('pajak/{pajak}/bayar', [PajakController::class, 'bayar'])->name('pajak.bayar');
    Route::resource('pajak', PajakController::class);

    // Servis
    Route::post('servis/{servis}/selesai', [ServisController::class, 'selesai'])->name('servis.selesai');
    Route::resource('servis', ServisController::class)->parameters(['servis' => 'servis']);

    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // User Management (Super Admin only)
    Route::middleware('can:manage-users')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});
