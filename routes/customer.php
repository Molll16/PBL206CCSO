<?php

use Illuminate\Support\Facades\Route;
use App\Models\Fitur;
use App\Models\DasborKustom;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ManagementDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WidgetController; // 1. TAMBAHKAN INI AGAR TIDAK EROR CLASS NOT FOUND

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->middleware('auth')->group(function () {

    // DASHBOARD MAIN VIEW
    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])
        ->name('dashboard-customer');

    // ==========================================
    // JALUR API/AJAX BARU UNTUK ISI DATA WIDGET
    // ==========================================
    // Dimasukkan ke dalam prefix 'customer' agar otomatis aman di bawah middleware 'auth'
    Route::prefix('widget')->group(function () {
        Route::get('/agent-status', [WidgetController::class, 'getAgentStatus'])->name('widget.agent-status');
        Route::get('/latest-alerts', [WidgetController::class, 'getLatestAlerts'])->name('widget.latest-alerts');
        Route::get('/threat-summary', [WidgetController::class, 'getThreatSummary'])->name('widget.threat-summary');
    });

    // CUSTOM DASHBOARD MANAGEMENT
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
        // VIEW / HALAMAN (Sekarang semua terstruktur lewat Controller)
        Route::get('/setting', [ProfileController::class, 'customerAccountSettings'])->name('profile-setting');
        Route::get('/server', [ProfileController::class, 'customerServerSettings'])->name('profile-server');
        Route::get('/custom', [ProfileController::class, 'customerCustomizeSettings'])->name('profile-custom');

        // ACTION / PROSES (Menggunakan PUT/PATCH sesuai standar RESTful Laravel untuk Update data)
        Route::put('/update', [ProfileController::class, 'update'])->name('customer.profile.update');
        Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('changepw.update');
    });

    // LOGS
    Route::get('/logs', [
        AlertController::class,
        'index'
    ])->name('daftarlog');
});