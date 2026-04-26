<?php

use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Models\Fitur;
use App\Models\DasborKustom;


Route::get('/', function () {
    return view('landingpage');
});

Route::get('/daftar', function () {
    return view('daftar');
})->name('daftar');

// rute untuk customer

// rute untuk mendapatkan data dari tabel fitur
Route::get('/kustom', function () {

    $fitur = Fitur::all();

    return view('Customer.kustom', compact('fitur'));

})->name('kustomisasi');
// penutup rute untuk mendapatkan data dari tabel fitur

// rute untuk melakukan penyimpanan data dashboard kustom
Route::post('/dashboard/save', [CustomerDashboardController::class, 'save'])
    ->name('dashboard.save');
// penutup rute untuk melakukan penyimpanan data dashboard kustom

Route::get('/profileset', function () {
    return view('Customer.profileset');
})->name('profile-setting');

// rute untuk memilih dashboard yang sudah dibuat
Route::get('/choosedashboard', function () {

    $dashboard = DasborKustom::where('user_id', auth()->id())->get();

    return view('Customer.choosedashboard', compact('dashboard'));

})->name('pilih-dasbor');

// rute untuk mengedit dashboard yang sudah dibuat
Route::get('/kustom/{id}', [CustomerDashboardController::class, 'edit'])
    ->name('dashboard.edit');
// penutup rute untuk mengedit dashboard yang sudah dibuat

// rute untuk menghapus dashboard yang sudah dibuat
Route::delete('/dashboard/{id}',
[CustomerDashboardController::class, 'destroy'])
->name('dashboard.delete');
// penutup rute untuk menghapus dashboard yang sudah dibuat

// penutup rute untuk memilih dashboard yang sudah dibuat

Route::get('/profileserver', function () {
    return view('Customer.profileserver');
})->name('profile-server');

Route::get('/profilecustom', function () {
    return view('Customer.profilecustom');
})->name('profile-custom');

Route::get('/profileover', function () {
    return view('Customer.profileover');
})->name('profile-overview');

Route::get('/changepw', function () {
    return view('Customer.changepw');
})->name('dashboard-changepw');

Route::get('/dashboard', function () {
    return view('Customer.dashboard');
})->name('dashboard-customer');

// penutup rute untuk customer



// rute untuk admin
Route::get('/usersadmin', function () {
    return view('Admin.users-admin');
})->name('usersadmin');

Route::get('/Admin/adduser-admin', function () {
    return view('Admin.adduser-admin');
})->name('adduser');

Route::get('/Admin/assignagent', function () {
    return view('Admin.assignagent');
})->name('assignagent');

Route::get('/Admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('dashboard-admin');
// penutup rute untuk admin


// rute untuk Login 
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// kode Admin kelola akun customer
Route::get('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers', [CustomerController::class, 'store']);
