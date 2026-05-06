<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    // ================================
    // LIST CUSTOMER
    // ================================
    public function index()
    {
        $users = User::where('role', 'customer')
            ->withCount('agents')
            ->get();

        $totalAssignedAgents = Agen::count();

        return view('Admin.users.users-admin', [
            'users'                => $users,
            'totalUsers'           => $users->count(),
            'totalAssignedAgents'  => $totalAssignedAgents,
        ]);
    }

    // ================================
    // FORM CREATE CUSTOMER
    // ================================
    public function create()
    {
        $users = User::where('role', 'customer')
            ->withCount('agents')
            ->get();
    
        $userActive = User::where('role', 'customer')
            ->count();
    
        $totalAssignedAgents = $users->sum('agents_count');
    
        return view('Admin.users.adduser-admin', [
            'totalUsers' => $users->count(),
            'totalAssignedAgents' => $totalAssignedAgents,
        ]);
    }

    // ================================
    // SIMPAN CUSTOMER
    // ================================
    public function store(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|unique:users,email',
            'name'     => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'no_telp'  => 'required',
        ]);

        // buat akun customer
        User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'no_telp'   => $request->no_telp,
            'role'      => 'customer',
        ]);

        return redirect()
            ->route('usersadmin')
            ->with('success', 'Akun customer berhasil dibuat.');
    }

    // ================================
    // FORM EDIT CUSTOMER
    // ================================
    public function edit($id)
    {
        $user = User::where('role', 'customer')
            ->findOrFail($id);

        return view('Admin.users.edit-customer', [
            'user' => $user
        ]);
    }

    // ================================
    // UPDATE CUSTOMER
    // ================================
    public function update(Request $request, $id)
    {
        $user = User::where('role', 'customer')
            ->findOrFail($id);

        $request->validate([
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'name'     => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
            'no_telp'  => 'required',
        ]);

        $user->update([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'no_telp'  => $request->no_telp,
        ]);

        return redirect()
            ->route('usersadmin')
            ->with('success', 'Customer berhasil diupdate');
    }

    // ================================
    // HAPUS CUSTOMER
    // ================================
    public function destroy($id)
    {
        $user = User::where('role', 'customer')
            ->findOrFail($id);

        $user->delete();

        return redirect()
            ->route('usersadmin')
            ->with('success', 'Customer berhasil dihapus');
    }
}