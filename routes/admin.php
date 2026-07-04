<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->group(function () {

    // ================================
    // DASHBOARD
    // ================================
    Route::get(
        '/dashboard',
        [AdminDashboardController::class, 'index']
    )->name('dashboard-admin');


    // ================================
    // CUSTOMER MANAGEMENT
    // ================================

    // LIST CUSTOMER
    Route::get(
        '/users',
        [CustomerController::class, 'index']
    )->name('usersadmin');

    // FORM CREATE CUSTOMER
    Route::get(
        '/users/create',
        [CustomerController::class, 'create']
    )->name('adduser');

    // SIMPAN CUSTOMER
    Route::post(
        '/users/create',
        [CustomerController::class, 'store']
    )->name('customers.store');

    // FORM EDIT CUSTOMER
    Route::get(
        '/customers/{id}/edit',
        [CustomerController::class, 'edit']
    )->name('customers.edit');

    // UPDATE CUSTOMER
    Route::put(
        '/customers/{id}',
        [CustomerController::class, 'update']
    )->name('customers.update');

    // DELETE CUSTOMER
    Route::delete(
        '/customers/{id}',
        [CustomerController::class, 'destroy']
    )->name('customers.destroy');


    // ================================
    // AGENT
    // ================================
    Route::get(
        '/agents',
        [AgentController::class, 'agents']
    )->name('agents-list');

    // Code ini untuk: Menghubungkan halaman detail agen admin dengan controller
    // Berfungsi pada halaman: List Agent Admin saat tombol View diklik
    Route::get(
        '/agent/{id}',
        [AgentController::class, 'showDetailAgent']
    )->name('admin.agent.show');

    Route::get(
        '/assign-agent',
        [AgentController::class, 'assignAgentPage']
    )->name('assignagent');

    Route::post(
        '/assign-agent/save',
        [AgentController::class, 'saveAssignAgent']
    )->name('assignagent.save');

    Route::post('/admin/agents/detach', [AgentController::class, 'detachAgent'])->name('agents.detach');

    // Daftarkan di dalam middleware admin/auth kamu yang mengatur halaman agent list
    Route::get('/agents/sync-refresh', [AgentController::class, 'refreshSync'])->name('agents.refresh');


    // ======== //
    //  PROFILE //
    // ======== //
    Route::prefix('profile')->group(function () {

        // PROFILE SETTINGS
        Route::get(
            '/setting',
            [ProfileController::class, 'settings']
        )->name('profile-setting-admin');

        // PROFILE AGENT
        Route::get(
            '/agent',
            [ProfileController::class, 'agent']
        )->name('profile-agent');

        // UPDATE PROFILE
        Route::post(
            '/update',
            [ProfileController::class, 'update']
        )->name('profile.update');

        // UPDATE PASSWORD
        Route::post(
            '/change-password',
            [ProfileController::class, 'updatePassword']
        )->name('changepw.update');
    });
});