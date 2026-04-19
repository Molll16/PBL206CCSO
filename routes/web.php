<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('create');
});

Route::get('/daftar', function () {
    return view('daftar');
})->name('daftar');

Route::get('/login', function () {
    return view('login');
    })->name('login');

Route::get('/onboarding', function () {
    return view('onboarding');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});



// kode Admin kelola akun customer
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers', [CustomerController::class, 'store']);
