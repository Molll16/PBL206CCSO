<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\DasborKustom;
use App\Models\HasilKustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // Fungsi untuk menampilkan form pembuatan akun customer
    public function create()
    {
        return view('customers.create');
    }

    // Fungsi untuk menyimpan data customer baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'no_telp' => 'required',
        ]);

        // Buat akun customer
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'role' => 'customer'
        ]);

    //Mentrigger event pembuatan dashboard default untuk customer baru
        // Buat dashboard default
        $dashboard = DasborKustom::create([
            'user_id' => $user->id,
            'nama_dasbor' => 'Default',
            'jenis_dasbor' => 'default',
            'status_dasbor' => 'aktif'
        ]);


        // Isi widget default
        HasilKustom::insert([
            [
                'dasbor_kustom_id' => $dashboard->id,
                'fitur_id' => 1,
                'kolom' => 6,
                'baris' => 2,
                'status_fitur' => 'aktif'
            ],
            [
                'dasbor_kustom_id' => $dashboard->id,
                'fitur_id' => 2,
                'kolom' => 6,
                'baris' => 2,
                'status_fitur' => 'aktif'
            ],
            [
                'dasbor_kustom_id' => $dashboard->id,
                'fitur_id' => 3,
                'kolom' => 12,
                'baris' => 4,
                'status_fitur' => 'aktif'
            ]
        ]);


        return back()->with(
            'success',
            'Akun customer berhasil dibuat.'
        );
    }
}