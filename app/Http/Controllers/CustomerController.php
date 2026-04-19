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
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'no_telp' => $request->no_telp,
            'role' => 'customer'
        ]);
    }
}
