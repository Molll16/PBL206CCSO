<?php

use Illuminate\Support\Facades\Route;
use App\Models\Fitur;
use App\Models\DasborKustom;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\AgentController;


/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landingpage');
});

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

        return view('Customer.customize.kustom', compact('fitur'));

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
            'Customer.customize.choosedashboard',
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
        return view('Customer.profile.profileset');
    })->name('profile-setting');

    Route::get('/profileserver', function () {
        return view('Customer.profile.profileserver');
    })->name('profile-server');

    Route::get('/profilecustom', function () {
        return view('Customer.profile.profilecustom');
    })->name('profile-custom');

    Route::get('/profileover', function () {
        return view('Customer.profile.profileover');
    })->name('profile-overview');

    Route::get('/changepw', function () {
        return view('Customer.profile.changepw');
    })->name('changepw');

    Route::get('/daftarlog', function () {
        return view('Customer.logs.daftarlog');
    })->name('daftarlog');

});


/*
|--------------------------------------------------------------------------
| Admin Route
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/usersadmin', function () {
        return view('Admin.users.users-admin');
    })->name('usersadmin');

    Route::get('/Admin/adduser-admin', function () {
        return view('Admin.users.adduser-admin');
    })->name('adduser');

    // untuk permasalahan route assign agent
    Route::get('/Admin/assignagent',
        [AgentController::class, 'assignAgentPage']
    )->name('assignagent');

    Route::post('/Admin/assignagent/save',
        [AgentController::class, 'saveAssignAgent']
    )->name('assignagent.save');

    Route::get('/Admin/dashboard',
        [AdminDashboardController::class, 'index']
    )->name('dashboard-admin');

    Route::get('/customers/create',
        [CustomerController::class, 'create']
    );

    Route::post('/customers',
        [CustomerController::class, 'store']
    );

    Route::get('/Admin/agents',
        [AgentController::class, 'agents']
    )->name('agents-list');

    Route::get('/profile-agent', function () {
        return view('Admin.profile.profile-agent');
    })->name('profile-agent');
    
        Route::get('/profile-overview', function () {
        return view('Admin.profile.profile-overview');
    })->name('profile-overview-admin');

        Route::get('/profile-setting', function () {
        return view('Admin.profile.profileset-admin');
    })->name('profile-setting-admin');

});