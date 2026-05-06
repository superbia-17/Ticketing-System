<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\TicketController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman awal aplikasi
Route::view('/', 'welcome');

// Rute Otentikasi (Guest)
Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Rute Terproteksi (Login Required)
Route::middleware('auth')->group(function() {
    
    // Dashboard Utama: Menampilkan statistik & riwayat tiket
    Route::get('/dashboard', [TicketController::class, 'index'])->name('dashboard');

    // Manajemen Tiket
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/my', [TicketController::class, 'my'])->name('tickets.my');
    
    // Rute Riwayat Global (Jika ingin halaman riwayat terpisah)
    Route::get('/tickets/all', [TicketController::class, 'all'])->name('tickets.all');

    // Detail & Balasan Tiket
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});