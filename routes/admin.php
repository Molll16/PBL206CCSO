<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->group(function () {

    // ================================
    // DASHBOARD
    // ================================
    Route::get('/dashboard',
        [AdminDashboardController::class, 'index']
    )->name('dashboard-admin');


    // ================================
    // CUSTOMER MANAGEMENT
    // ================================

    // LIST CUSTOMER
    Route::get('/users',
        [CustomerController::class, 'index']
    )->name('usersadmin');

    // FORM CREATE CUSTOMER
    Route::get('/users/create',
        [CustomerController::class, 'create']
    )->name('adduser');

    // SIMPAN CUSTOMER
    Route::post('/users/create',
        [CustomerController::class, 'store']
    )->name('customers.store');

    // FORM EDIT CUSTOMER
    Route::get('/customers/{id}/edit',
        [CustomerController::class, 'edit']
    )->name('customers.edit');

    // UPDATE CUSTOMER
    Route::put('/customers/{id}',
        [CustomerController::class, 'update']
    )->name('customers.update');

    // DELETE CUSTOMER
    Route::delete('/customers/{id}',
        [CustomerController::class, 'destroy']
    )->name('customers.destroy');


    // ================================
    // AGENT
    // ================================
    Route::get('/agents',
        [AgentController::class, 'agents']
    )->name('agents-list');

    Route::get('/assign-agent',
        [AgentController::class, 'assignAgentPage']
    )->name('assignagent');

    Route::post('/assign-agent/save',
        [AgentController::class, 'saveAssignAgent']
    )->name('assignagent.save');


    // ================================
    // PROFILE
    // ================================
    Route::prefix('profile')->group(function () {

        Route::get('/agent',
            fn() => view('Admin.profile.profile-agent')
        )->name('profile-agent');

        Route::get('/overview',
            fn() => view('Admin.profile.profile-overview')
        )->name('profile-overview-admin');

        Route::get('/setting',
            fn() => view('Admin.profile.profileset-admin')
        )->name('profile-setting-admin');
    });
});