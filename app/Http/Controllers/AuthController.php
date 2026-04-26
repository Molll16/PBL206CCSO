<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // nampilin UI login
    public function showLoginForm()
    {
        return view('login');
    }

    // proses login
    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (Auth::attempt($credentials)) {

            if (Auth::user()->role == 'admin') {
                return redirect()->route('dashboard-admin');
            } else {
                return redirect()->route('dashboard-customer');
            }
        }

        // Login failed
        return back()->with('error', 'Login Gagal, pastikan username dan password benar!');
    }
}
