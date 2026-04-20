<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('adduser-admin');
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

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard-admin');

Route::get('/usersadmin', function () {
    return view('users-admin');
})->name('usersadmin');

Route::get('/adduser-admin', function () {
    return view('adduser-admin');
})->name('adduser');

// kode Login 
Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);

// kode Admin kelola akun customer
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers', [CustomerController::class, 'store']);
