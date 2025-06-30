<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\AksesController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\DhtController;

// AUTH ROUTES
Route::get('/', [LoginController::class, 'LoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('firebase.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// ADMIN ROUTES (Protected)
Route::middleware(['auth.firebase', 'is.admin'])->prefix('admin')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Pengguna
    Route::prefix('pengguna')->group(function () {
        Route::get('/', [PenggunaController::class, 'index'])->name('pengguna');
        Route::get('/create', [PenggunaController::class, 'create'])->name('pengguna.create');
        Route::post('/store', [PenggunaController::class, 'store'])->name('pengguna.store');
        Route::get('/{uid}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/{uid}', [PenggunaController::class, 'update'])->name('pengguna.update');
        Route::delete('/{uid}/hapus', [PenggunaController::class, 'destroy'])->name('pengguna.hapus');
        Route::view('/ubahp', 'admin.pengguna.ubahp')->name('pengguna.ubahp');
    });

    // Akses Pintu
    Route::prefix('aksespintu')->group(function () {
        Route::get('/', [AksesController::class, 'index'])->name('aksespintu');
        Route::get('/edit/{uid}', [AksesController::class, 'edit'])->name('aksespintu.edit');
        Route::put('/update/{uid}', [AksesController::class, 'update'])->name('aksespintu.update');
        Route::delete('/{uid}', [AksesController::class, 'destroy'])->name('aksespintu.destroy');
    });

    // History Akses Pintu
    Route::get('/historyaksespintu', [HistoryController::class, 'index'])->name('historyaksespintu');

    // Suhu & Kelembaban
    Route::get('/suhudankelembaban', [DhtController::class, 'index'])->name('suhukelembaban');

    // Profile Admin
    Route::prefix('profile')->group(function () {
        Route::get('/', [PenggunaController::class, 'editProfile'])->name('admin.editprofile');
        Route::put('/', [PenggunaController::class, 'updateProfile'])->name('admin.updateprofile');
    });
});

// USER ROUTES (Protected)
Route::middleware(['auth.firebase', 'is.pengguna'])->prefix('pengguna')->group(function () {

    // Dashboard Pengguna
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pengguna.dashboard');

    // History Akses Pintu
    Route::get('/historyaksespintu', [HistoryController::class, 'index'])->name('historyaksespintu');

    // Suhu & Kelembaban
    Route::get('/suhudankelembaban', [DhtController::class, 'index'])->name('suhukelembaban');

    // Profile Pengguna
    Route::prefix('profile')->group(function () {
        Route::get('/', [PenggunaController::class, 'editProfile'])->name('pengguna.editprofile');
        Route::put('/', [PenggunaController::class, 'updateProfile'])->name('pengguna.updateprofile');
    });
});

