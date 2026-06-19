<?php

use Illuminate\Support\Facades\Route;
use App\Models\Fitur;
use App\Models\DasborKustom;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\ManagementDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WidgetController;

/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->middleware('auth')->group(function () {

    // DASHBOARD MAIN VIEW
    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])
        ->name('dashboard-customer');

    // ==========================================================
    // JALUR API/AJAX BARU UNTUK ISI DATA WIDGET (SUDAH DIPERBARUI)
    // ==========================================================
    Route::prefix('widget')->group(function () {
        Route::get('/agent-status', [WidgetController::class, 'getAgentStatus'])->name('widget.agent-status');
        Route::get('/latest-alerts', [WidgetController::class, 'getLatestAlerts'])->name('widget.latest-alerts');
        Route::get('/threat-summary', [WidgetController::class, 'getThreatSummary'])->name('widget.threat-summary');

        // 🌟 1. TAMBAHKAN JALUR INI UNTUK WIDGET SYSTEM RESOURCES
        Route::get('/system-resources', [WidgetController::class, 'getSystemResources'])->name('widget.system-resources');
        Route::get('/file-integrity', [WidgetController::class, 'getFileIntegrity'])->name('widget.file-integrity');
        Route::get('/failed-logins', [WidgetController::class, 'getFailedLogins'])->name('widget.failed-login-monitoring');
        Route::get('/user-login-activity', [WidgetController::class, 'getUserLoginActivity'])->name('widget.user-login-activity');
        Route::get('/widget/most-active-rules', [WidgetController::class, 'getMostActiveRules'])->name('widget.active-rules');
    });

    // CUSTOM DASHBOARD MANAGEMENT
    Route::get('/kustom', function () {
        $fitur = Fitur::all();
        return view('Customer.customize.kustom', compact('fitur'));
    })->name('kustomisasi');

    Route::post(
        '/dashboard/save',
        [ManagementDashboardController::class, 'save']
    )->name('dashboard.save');

    Route::get('/choosedashboard', function () {
        $dashboard = DasborKustom::where('user_id', auth()->id())->get();

        return view(
            'Customer.customize.choosedashboard',
            compact('dashboard')
        );
    })->name('pilih-dasbor');

    Route::get(
        '/kustom/{id}',
        [ManagementDashboardController::class, 'edit']
    )->name('dashboard.edit');

    Route::delete(
        '/dashboard/{id}',
        [ManagementDashboardController::class, 'destroy']
    )->name('dashboard.delete');

    Route::post(
        '/dashboard/use/{id}',
        [ManagementDashboardController::class, 'use']
    )->name('dashboard.use');

    // ==========================================================
    // MANAGEMENT PROFILE CUSTOMER (SUDAH BERSIH DARI DOUBLE PREFIX)
    // ==========================================================
    Route::prefix('profile')->group(function () {
        // VIEW / HALAMAN 
        Route::get('/setting', [ProfileController::class, 'customerAccountSettings'])->name('profile-setting');
        Route::get('/server', [ProfileController::class, 'customerServerSettings'])->name('profile-server');
        Route::get('/custom', [ProfileController::class, 'customerCustomizeSettings'])->name('profile-custom');

        // ACTION / PROSES 
        Route::put('/update', [ProfileController::class, 'update'])->name('customer.profile.update');
        Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('changepw.update');

        // 🌟 2. BERSIHKAN URL INI (Dibuang kata '/customer' di depannya karena sudah masuk prefix grup)
        // URL akses aslinya sekarang menjadi: customer/profile/agent/switch
        Route::post('/agent/switch', [ProfileController::class, 'switchAgent'])->name('customer.agent.switch');
    });

    // LOGS
    Route::get('/logs', [
        AlertController::class,
        'index'
    ])->name('daftarlog');
});