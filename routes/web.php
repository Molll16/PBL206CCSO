<?php

use Illuminate\Support\Facades\Route;
use App\Models\Fitur;
use App\Models\DasborKustom;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerDashboardController;


/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landingpage');
});

Route::get('/daftar', function () {
    return view('daftar');
})->name('daftar');

Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Customer Route
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])
        ->name('dashboard-customer');

    Route::get('/kustom', function () {

        $fitur = Fitur::all();

        return view('Customer.kustom', compact('fitur'));

    })->name('kustomisasi');

    Route::post('/dashboard/save',
        [CustomerDashboardController::class, 'save']
    )->name('dashboard.save');

    Route::get('/choosedashboard', function () {

        $dashboard = DasborKustom::where(
            'user_id',
            auth()->id()
        )->get();

        return view(
            'Customer.choosedashboard',
            compact('dashboard')
        );

    })->name('pilih-dasbor');

    Route::get('/kustom/{id}',
        [CustomerDashboardController::class, 'edit']
    )->name('dashboard.edit');

    Route::delete('/dashboard/{id}',
        [CustomerDashboardController::class, 'destroy']
    )->name('dashboard.delete');

    Route::post('/dashboard/use/{id}',
        [CustomerDashboardController::class, 'useDashboard']
    )->name('dashboard.use');

    Route::get('/profileset', function () {
        return view('Customer.profileset');
    })->name('profile-setting');

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

});


/*
|--------------------------------------------------------------------------
| Admin Route
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/usersadmin', function () {
        return view('Admin.users-admin');
    })->name('usersadmin');

    Route::get('/Admin/adduser-admin', function () {
        return view('Admin.adduser-admin');
    })->name('adduser');

    Route::get('/Admin/assignagent', function () {
        return view('Admin.assignagent');
    })->name('assignagent');

    Route::get('/Admin/dashboard',
        [AdminDashboardController::class, 'index']
    )->name('dashboard-admin');

    Route::get('/customers/create',
        [CustomerController::class, 'create']
    );

    Route::post('/customers',
        [CustomerController::class, 'store']
    );

});