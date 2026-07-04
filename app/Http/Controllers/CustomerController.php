<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agen;
use App\Services\AgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    protected $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    public function index()
    {
        // Memanfaatkan data terpusat dari service, menghindari duplikasi query Eloquent
        $stats = $this->agentService->getCustomerManagementSummary();

        return view('Admin.users.users-admin', [
            'users' => $stats['users'],
            'totalUsers' => $stats['totalUsers'],
            'totalAssignedAgents' => Agen::count(),
        ]);
    }

    public function create()
    {
        $stats = $this->agentService->getCustomerManagementSummary();

        return view('Admin.users.adduser-admin', [
            'totalUsers' => $stats['totalUsers'],
            'totalAssignedAgents' => $stats['totalAssignedAgents'],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|min:6',
            'no_telp' => 'required',
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

    public function edit($id)
    {
        $user = User::where('role', 'customer')->findOrFail($id);
        return view('Admin.users.edit-customer', ['user' => $user]);
    }

    public function destroy($id)
    {
        User::where('role', 'customer')->findOrFail($id)->delete();
        return redirect()->route('usersadmin')->with('success', 'Customer berhasil dihapus');
    }
}