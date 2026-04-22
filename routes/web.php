<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landingpage');
});

Route::get('/daftar', function () {
    return view('daftar');
})->name('daftar');

Route::get('/login', function () {
    return view('login');
    })->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
});

// rute untuk customer
Route::get('/Customer/customisasi', function () {
    return view('Customer.customisasi');
})->name('customisasi');

Route::get('/profileset', function () {
    return view('Customer.profileset');
})->name('profile-setting');

Route::get('/profileserver', function () {
    return view('Customer.profileserver');
})->name('profile-server');

Route::get('/profilecostume', function () {
    return view('Customer.profilecostume');
})->name('profile-costume');

Route::get('/profile', function () {
    return view('Customer.profile');
})->name('profile-overview');

Route::get('/dashboarddft', function () {
    return view('Customer.dashboarddft');
})->name('dashboard-default');

Route::get('/changepw', function () {
    return view('Customer.changepw');
})->name('dashboard-changepw');
// penutup rute untuk customer

// rute untuk admin
Route::get('/Admin/dashboard', function () {
    return view('Admin.dashboard');
})->name('dashboard-admin');

Route::get('/usersadmin', function () {
    return view('Admin.users-admin');
})->name('usersadmin');

Route::get('/Admin/adduser-admin', function () {
    return view('Admin.adduser-admin');
})->name('adduser');

Route::get('/Admin/assignagent', function () {
    return view('Admin.assignagent');
})->name('assignagent');
// penutup rute untuk admin


// kode Login 
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// kode Admin kelola akun customer
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers', [CustomerController::class, 'store']);
