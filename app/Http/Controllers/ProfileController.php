<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Services\AgentService;
use App\Services\WazuhApiService; // Di-import untuk menarik data server riil
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\DasborKustom;

class ProfileController extends Controller
{
    protected $agentService;
    protected $wazuhApi;

    // OOP Clean: Dependency Injection via Constructor
    public function __construct(AgentService $agentService, WazuhApiService $wazuhApi)
    {
        $this->agentService = $agentService;
        $this->wazuhApi = $wazuhApi;
    }

    // ==========================================
    // 1. ROUTE MANAGEMENT UNTUK ADMIN
    // ==========================================
    public function settings()
    {
        return view('Admin.profile.profileset-admin');
    }

    public function agent()
    {
        try {
            $agents = collect($this->agentService->getAgents());
            return view('Admin.profile.profile-agent', [
                'agents' => $agents,
                'totalAgents' => $agents->count(),
                'assignedAgents' => Agen::count(),
                'wazuhOffline' => false,
            ]);
        } catch (\Throwable $e) {
            return view('Admin.profile.profile-agent', [
                'agents' => collect(),
                'totalAgents' => 0,
                'assignedAgents' => 0,
                'wazuhOffline' => true,
            ]);
        }
    }


    // ==========================================
    // 2. METHOD KHUSUS HALAMAN CUSTOMER
    // ==========================================

    /**
     * TAB 1: Profile Settings (Edit Akun Utama)
     */
    public function customerAccountSettings()
    {
        return view('Customer.profile.profileset', [
            'user' => auth()->user()
        ]);
    }

    /**
     * TAB 2: Server / Agent (FIXED: Mengambil Data Langsung dari API Wazuh Riil)
     */
    public function customerServerSettings()
    {
        try {
            // 1. Ambil data agents dari API Wazuh melalui service
            $wazuhResponse = $this->wazuhApi->agents();
            $agents = [];

            if (isset($wazuhResponse['data']['affected_items'])) {
                foreach ($wazuhResponse['data']['affected_items'] as $item) {
                    // Menyembunyikan agent bawaan manager (ID 000) jika diperlukan
                    if ($item['id'] === '000') {
                        continue;
                    }

                    $agents[] = (object) [
                        'server_name' => $item['name'] ?? 'Unknown Agent',
                        'server_ip' => $item['ip'] ?? '0.0.0.0',
                        'status' => ucfirst($item['status'] ?? 'disconnected'), // Menyeragamkan huruf kapital awal
                    ];
                }
            }

            $agentsCollection = collect($agents);

            // 2. Hitung statistik otomatis berdasarkan status riil dari API Wazuh
            $agentsTotal = $agentsCollection->count();
            $activeAgents = $agentsCollection->whereIn('status', ['Active', 'active'])->count();
            $disconnectAgents = $agentsCollection->whereIn('status', ['Disconnected', 'disconnected', 'Never_connected'])->count();

        } catch (\Throwable $e) {
            // Jika koneksi server Wazuh tiba-tiba terkendala, fallback ke array kosong agar tidak crash
            $agents = [];
            $agentsTotal = 0;
            $activeAgents = 0;
            $disconnectAgents = 0;
        }

        // 3. Lempar semua variabel ke file blade server
        return view('Customer.profile.profileserver', compact(
            'agents',
            'agentsTotal',
            'activeAgents',
            'disconnectAgents'
        ));
    }

    /**
     * TAB 3: Customization Dashboard (FIXED dengan Kolom Database Asli)
     */
    public function customerCustomizeSettings()
    {
        // 1. Ambil semua kustomisasi dashboard milik customer yang sedang login dari tabel dasbor_kustoms
        $myDashboards = DasborKustom::where('user_id', auth()->id())->get();

        // 2. Hitung statistik berdasarkan nilai status_dasbor di MySQL ('aktif' / 'nonaktif')
        $totalDashboard = $myDashboards->count();

        // Menghitung yang berstatus 'aktif' di database Anda
        $activeDashboard = $myDashboards->where('status_dasbor', 'aktif')->count();

        // Mencari ID dashboard pertama yang statusnya 'aktif' (sebagai pengganti logic 'In Use')
        $dashboardInUseItem = $myDashboards->where('status_dasbor', 'aktif')->first();
        $dashboardInUse = $dashboardInUseItem ? sprintf("%02d", $dashboardInUseItem->id) : '00';

        // 3. Kirim data ke file Blade
        return view('Customer.profile.profilecustom', [
            'dashboards' => $myDashboards,
            'totalDashboard' => $totalDashboard,
            'activeDashboard' => $activeDashboard,
            'dashboardInUse' => $dashboardInUse,
        ]);
    }

    /**
     * Fitur Tambahan: Menangani Proses Hapus Data (Menghilangkan Eror 500)
     */
    public function destroyCustomize($id)
    {
        $dashboard = DasborKustom::where('user_id', auth()->id())->findOrFail($id);
        $dashboard->delete();

        return back()->with('success', 'Dashboard kustom berhasil dihapus!');
    }


    // ==========================================
    // 3. LOGIC PROSES UPDATE (SHARED)
    // ==========================================
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
        ]);

        auth()->user()->update($request->only('name', 'email', 'no_telp'));
        return back()->with('success', 'Profile berhasil diupdate');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'The current password you entered is incorrect.');
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'password_changed' => true,
        ]);

        return back()->with('success', 'Your password has been successfully updated!');
    }
}