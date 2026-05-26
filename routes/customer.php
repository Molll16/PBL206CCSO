<?php

use Illuminate\Support\Facades\Route;
use App\Models\Fitur;
use App\Models\DasborKustom;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ManagementDashboardController;
use App\Http\Controllers\ProfileController;
/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])
        ->name('dashboard-customer');

    // CUSTOM DASHBOARD
    Route::get('/kustom', function () {
        $fitur = Fitur::all();
        return view('Customer.customize.kustom', compact('fitur'));
    })->name('kustomisasi');

    Route::post('/dashboard/save',
        [ManagementDashboardController::class, 'save']
    )->name('dashboard.save');

    Route::get('/choosedashboard', function () {
        $dashboard = DasborKustom::where('user_id', auth()->id())->get();

        return view(
            'Customer.customize.choosedashboard',
            compact('dashboard')
        );
    })->name('pilih-dasbor');

    Route::get('/kustom/{id}',
        [ManagementDashboardController::class, 'edit']
    )->name('dashboard.edit');

    Route::delete('/dashboard/{id}',
        [ManagementDashboardController::class, 'destroy']
    )->name('dashboard.delete');

    Route::post('/dashboard/use/{id}',
        [ManagementDashboardController::class, 'use']
    )->name('dashboard.use');

    // ==========================================
    // MANAGEMENT PROFILE CUSTOMER
    // ==========================================
    Route::prefix('profile')->group(function () {
        // VIEW / HALAMAN
        Route::get('/setting', [ProfileController::class, 'customerSettings'])->name('profile-setting');
        Route::get('/server', fn() => view('Customer.profile.profileserver'))->name('profile-server');
        Route::get('/custom', fn() => view('Customer.profile.profilecustom'))->name('profile-custom');
        Route::get('/overview', fn() => view('Customer.profile.profileover'))->name('profile-overview');
        Route::post('/update', [ProfileController::class, 'update'])->name('customer.profile.update');
        Route::post('/change-password', [ProfileController::class, 'updatePassword'])->name('changepw.update');
    });

    // LOGS
    Route::get('/logs', [
        AlertController::class,
        'index'
    ])->name('daftarlog');
});