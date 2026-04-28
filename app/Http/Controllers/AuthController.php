<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('login');
    }

    // Menangani proses login
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        // Coba login dengan kredensial yang diberikan
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            // Redirect ke dashboard sesuai dengan role user yang login
            if (Auth::user()->role == 'admin') {
                return redirect()->route('dashboard-admin');
            }

            return redirect()->route('dashboard-customer');
        }

        return back()->with(
            'error',
            'Login gagal, username / password salah.'
        );
    }

    // Menangani proses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}