<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// Class ini untuk: Menangani manajemen data akun customer (CRUD) dari sisi Admin.
// Berfungsi pada: Halaman Admin - Users/Customer Management.
// Dibagian fitur: Menampilkan daftar customer, form tambah customer baru, simpan data baru, dan hapus data customer.
class CustomerController extends Controller
{
    protected $agentService;

    // Code ini untuk: Menghubungkan Controller dengan AgentService agar bisa mengambil data statistik ringkasan (total user & agen ter-assign).
    // Berfungsi pada halaman: List Customer Admin dan Add Customer Admin.
    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    // Code ini untuk: Menampilkan halaman utama manajemen user beserta tabel daftar customer.
    // Berfungsi pada halaman: List Customer Admin (Admin.users.users-admin).
    // Dibagian fitur: Menampilkan data (Read) nama lengkap, username, email, dan nomor telepon seluruh customer.
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

    // Code ini untuk: Menampilkan halaman formulir kosong untuk menambah data customer baru.
    // Berfungsi pada halaman: Form Add New User (Admin.users.adduser-admin), saat admin klik tombol "+ Add New User".
    // Dibagian fitur: Menampilkan form tambah user.
    public function create()
    {
        $stats = $this->agentService->getCustomerManagementSummary();

        return view('Admin.users.adduser-admin', [
            'totalUsers' => $stats['totalUsers'] ?? 0,
            'totalAssignedAgents' => $stats['totalAssignedAgents'] ?? 0,
        ]);
    }

    // Code ini untuk: Memvalidasi data inputan form dan menyimpannya sebagai akun customer baru ke database.
    // Berfungsi pada halaman: Form Add New User, bagian aksi tombol "Simpan / Tambah Akun".
    // Dibagian fitur: Menambahkan data baru (Create).
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

        return redirect()->route('usersadmin')->with('success', 'Customer account has been successfully added.');
    }

    // Code ini untuk: Menghapus data akun customer tertentu dari database berdasarkan ID.
    // Berfungsi pada halaman: List Customer Admin, bagian tombol "Hapus" di dalam baris tabel daftar customer.
    // Dibagian fitur: Menghapus data (Delete).
    public function destroy($id)
    {
        User::where('role', 'customer')->findOrFail($id)->delete();

        return redirect()->route('usersadmin')->with('success', 'Customer account has been successfully deleted.');
    }
}