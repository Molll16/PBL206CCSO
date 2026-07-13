<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Models\DasborKustom;
use App\Services\AgentService;
use App\Services\WazuhApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

// Class ini untuk: Menangani seluruh halaman "Profile" baik untuk Admin maupun Customer.
// Berfungsi pada: Halaman Profile Admin (Settings, Agent) dan Halaman Profile Customer (Settings, Server, Customize).
// Dibagian fitur: Ganti password, lihat inventaris server/agen, kelola dashboard kustom, dan switch agen aktif.
class ProfileController extends Controller
{
    protected $agentService;
    protected $wazuhApi;

    public function __construct(AgentService $agentService, WazuhApiService $wazuhApi)
    {
        $this->agentService = $agentService;
        $this->wazuhApi = $wazuhApi;
    }

    // Code ini untuk: Menampilkan halaman pengaturan profil khusus Admin.
    // Berfungsi untuk: Halaman Profile Admin (Admin.profile.profileset-admin), tab utama.
    public function settings()
    {
        return view('Admin.profile.profileset-admin');
    }

    // Code ini untuk: Menampilkan data semua agen untuk kebutuhan Admin.
    // Berfungsi untuk: Halaman Manajemen Agen di panel Admin (Admin.profile.profile-agent).
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

    // Code ini untuk: Menampilkan data profil milik Customer yang sedang login.
    // Berfungsi untuk: Halaman Profile Customer (Customer.profile.profileset), tab "Profile Settings".
    public function customerAccountSettings()
    {
        return view('Customer.profile.profileset', [
            'user' => auth()->user()
        ]);
    }

    // Code ini untuk: Menampilkan daftar server/agen yang ditugaskan ke Customer beserta status real-time.
    // Berfungsi untuk: Halaman Profile Customer (Customer.profile.profileserver), tab "Server" (Inventaris Perangkat).
    public function customerServerSettings()
    {
        try {
            $myAssignedAgentIds = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
            $wazuhResponse = $this->wazuhApi->agents();
            $agents = [];

            if (isset($wazuhResponse['data']['affected_items'])) {
                foreach ($wazuhResponse['data']['affected_items'] as $item) {
                    if (!in_array($item['id'], $myAssignedAgentIds)) {
                        continue;
                    }

                    $agents[] = (object) [
                        'id_wazuh' => $item['id'],
                        'server_name' => $item['name'] ?? 'Unknown Agent',
                        'server_ip' => $item['ip'] ?? '0.0.0.0',
                        'status' => ucfirst($item['status'] ?? 'disconnected'),
                    ];
                }
            }

            $agentsCollection = collect($agents);
            $agentsTotal = $agentsCollection->count();
            $activeAgents = $agentsCollection->whereIn('status', ['Active', 'active'])->count();
            $disconnectAgents = $agentsCollection->whereIn('status', ['Disconnected', 'disconnected', 'Disconnect', 'Never_connected'])->count();

        } catch (\Throwable $e) {
            $agents = [];
            $agentsTotal = 0;
            $activeAgents = 0;
            $disconnectAgents = 0;
        }

        return view('Customer.profile.profileserver', compact(
            'agents',
            'agentsTotal',
            'activeAgents',
            'disconnectAgents'
        ));
    }

    // Code ini untuk: Menampilkan daftar layout kustomisasi dashboard milik Customer.
    // Berfungsi untuk: Halaman Profile Customer (Customer.profile.profilecustom), tab "Customization Dashboard".
    public function customerCustomizeSettings()
    {
        $myDashboards = DasborKustom::where('user_id', auth()->id())->get();
        $totalDashboard = $myDashboards->count();
        $activeDashboard = $myDashboards->where('status_dasbor', 'aktif')->count();

        // Ambil baris data dashboard yang saat ini aktif digunakan
        $dashboardInUseItem = $myDashboards->where('status_dasbor', 'aktif')->first();

        // Ambil nama dasbor aslinya, kalau tidak ada fallback ke 'Default'
        $dashboardNameInUse = $dashboardInUseItem ? $dashboardInUseItem->nama_dasbor : 'Default';

        return view('Customer.profile.profilecustom', [
            'dashboards' => $myDashboards,
            'totalDashboard' => $totalDashboard,
            'activeDashboard' => $activeDashboard,
            'dashboardNameInUse' => $dashboardNameInUse,
        ]);
    }

    // Code ini untuk: Menghapus konfigurasi kustomisasi dashboard tertentu.
    // Berfungsi untuk: Halaman Profile Customer, tab Customize, aksi tombol "Delete" di tabel kustomisasi.
    public function destroyCustomize($id)
    {
        $dashboard = DasborKustom::where('user_id', auth()->id())->findOrFail($id);
        $dashboard->delete();

        return back()->with('success', 'Dashboard kustom berhasil dihapus!');
    }

    // Code ini untuk: Mengubah password akun user (dipakai bersama oleh Admin & Customer).
    // Berfungsi untuk: Form "Change Password" di halaman profil Admin maupun Customer.
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

        return back()
            ->with('success', 'Your password has been successfully updated!')
            ->with('password_success', true);
    }

    // Code ini untuk: Mengubah session target ID agen yang aktif dipantau.
    // Berfungsi untuk: Komponen Dropdown "Switch Agent" di halaman Dashboard Customer.

    public function switchAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|string'
        ]);

        // Hapus session lama jika masih ada biar tidak bentrok ke depannya
        session()->forget('active_wazuh_agent_id');

        // Skenario 1: Jika memilih 'all'
        if ($request->agent_id === 'all') {
            // Simpan ke Cookie selama 24 jam (60 menit * 24)
            Cookie::queue('active_wazuh_agent_id', 'all', 60 * 24);
            return back()->with('success', 'Berhasil beralih ke semua agen.');
        }

        // Cek validasi kepemilikan agen di DB
        $isValidAgent = Agen::where('user_id', auth()->id())
            ->where('id_wazuh_agen', $request->agent_id)
            ->exists();

        // Skenario 2: Jika agen valid dan sah milik user tersebut
        if ($isValidAgent) {
            // Simpan ke Cookie selama 24 jam
            Cookie::queue('active_wazuh_agent_id', $request->agent_id, 60 * 24);
            return back()->with('success', 'Berhasil beralih ke agen ' . $request->agent_id);
        }

        return back()->with('error', 'Akses agen tidak diizinkan.');
    }
}