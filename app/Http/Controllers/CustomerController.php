<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Untuk Nampilin form
    public function create()
    {
        return view('customers.create');
    }

    // Nerima data dari form
    public function store(Request $request)
{
    $request->validate([
        'email' => 'required',
        'name' => 'required',
        'username' => 'required',
        'password' => 'required',
        'no_telp' => 'required',
    ], [
        'email.required' => 'Email tidak boleh kosong',
        'name.required' => 'Nama tidak boleh kosong',
        'username.required' => 'Username tidak boleh kosong',
        'password.required' => 'Password tidak boleh kosong',
        'no_telp.required' => 'Nomor HP tidak boleh kosong',
    ]);

    User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => $request->password,
        'no_telp' => $request->no_telp,
        'role' => 'customer'
    ]);
}
}
