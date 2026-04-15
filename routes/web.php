<?php

use Illuminate\Support\Facades\Route;

Route::get('/landingpage', function () {
    return view('/landingpage');
});

Route::get('/daftar', function () {
    return view('daftar');
})->name('daftar');

Route::get('/login', function () {
    return view('login');
    })->name('login');

Route::get('/onboarding', function () {
    return view('onboarding');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});



