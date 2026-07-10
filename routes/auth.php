<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ============== //
// PUBLIC / AUTH  //
// ============== //

Route::get('/', function () {
    return view('landingpage');
});

Route::controller(AuthController::class)->group(function () {
    // Route Login Lama Kamu
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');

    // 1. Untuk memproses form pop-up meminta link reset password (AJAX fetch)
    Route::post('/forgot-password', 'sendResetLinkEmail');

    // 2. Untuk membuka halaman form input password baru (ketika link di Gmail diklik kustomer)
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');

    // 3. Untuk mengeksekusi aksi penyimpanan password baru ke database
    Route::post('/reset-password', 'updatePassword');
});