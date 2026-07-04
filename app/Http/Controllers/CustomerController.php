<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected $agentService;

    // Code ini untuk: Menghubungkan Controller dengan AgentService agar bisa mengambil data statistik dashboard
    // Berfungsi pada halaman: List Customer Admin dan Add Customer Admin
    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    // Code ini untuk: Menampilkan halaman utama manajemen user beserta tabel daftar customer
    // Berfungsi pada halaman: List Customer Admin (saat admin membuka menu ini)
    // Fitur: Menampilkan Data (Read) nama lengkap, username, email, dan nomor telepon
    public function index()
    {
        $stats = $this->agentService->getCustomerManagementSummary();

        $users = User::where('role', 'customer')->get();

        return view('Admin.users.users-admin', [
            'users' => $users,
            'totalUsers' => $stats['totalUsers'] ?? $users->count(),
            'totalAssignedAgents' => $stats['totalAssignedAgents'] ?? 0,
        ]);
    }

    // Code ini untuk: Menampilkan halaman formulir kosong untuk menambah data customer baru
    // Berfungsi pada halaman: Form Add New User (saat admin klik tombol + Add New User)
    // Fitur: Menampilkan Form Tambah User
    public function create()
    {
        $stats = $this->agentService->getCustomerManagementSummary();

        return view('Admin.users.adduser-admin', [
            'totalUsers' => $stats['totalUsers'] ?? 0,
            'totalAssignedAgents' => $stats['totalAssignedAgents'] ?? 0,
        ]);
    }

    // Code ini untuk: Memproses validasi data inputan form dan menyimpannya ke database
    // Berfungsi pada fitur: Tombol "Simpan / Tambah Akun" di halaman Add New User
    // Fitur: Menambahkan Data Baru (Create)
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'no_telp' => 'required|numeric|unique:users,no_telp', 
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telp' => $request->no_telp,
            'role' => 'customer',
        ]);

        return redirect()->route('usersadmin')->with('success', 'Akun customer berhasil dibuat.');
    }

    // Code ini untuk: Menghapus data akun customer tertentu dari database berdasarkan ID
    // Berfungsi pada fitur: Tombol "Hapus" di dalam baris tabel daftar customer
    // Fitur: Menghapus Data (Delete)
    public function destroy($id)
    {
        User::where('role', 'customer')->findOrFail($id)->delete();

        return redirect()->route('usersadmin')->with('success', 'Customer berhasil dihapus');
    }
}