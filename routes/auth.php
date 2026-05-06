<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ============== //
// PUBLIC / AUTH  //
// ============== //

Route::get('/', function () {
    return view('landingpage');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
});