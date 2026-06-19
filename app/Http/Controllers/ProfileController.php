<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Models\DasborKustom;
use App\Services\AgentService;
use App\Services\WazuhApiService; // Di-import untuk menarik data server riil
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function customerAccountSettings()
    {
        // // CODE: Mengambil data diri customer yang sedang login ke sistem.
        // // WEB: Menu Profile > Tab "Profile Settings" (Edit Akun Utama).
        // // UNTUK: Menampilkan informasi profile user agar bisa diperbarui (Nama, Email, No Telp).
        return view('Customer.profile.profileset', [
            'user' => auth()->user()
        ]);
    }

    public function customerServerSettings()
    {
        // // CODE: Memfilter daftar ID agen dari database lokal dan mencocokkannya dengan API Wazuh.
        // // WEB: Menu Profile > Tab "Server" (Inventaris Perangkat).
        // // UNTUK: Menampilkan daftar mesin yang di-assign ke customer & menghitung statistik keaktifannya secara real-time.
        try {
            // 1. Ambil list ID agen yang benar-benar ditugaskan ke customer ini dari database lokal
            $myAssignedAgentIds = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();

            // 2. Ambil data agents dari API Wazuh
            $wazuhResponse = $this->wazuhApi->agents();
            $agents = [];

            if (isset($wazuhResponse['data']['affected_items'])) {
                foreach ($wazuhResponse['data']['affected_items'] as $item) {

                    // PROTEKSI KEAMANAN: Jika ID agen dari Wazuh tidak ada dalam daftar milik user di DB lokal, lewati (skip)
                    // ID '000' (ccso) sekarang bisa lolos ke bawah asalkan ID tersebut sudah kamu assign ke user ini di DB lokal
                    if (!in_array($item['id'], $myAssignedAgentIds)) {
                        continue;
                    }

                    $agents[] = (object) [
                        'id_wazuh' => $item['id'], // Menyimpan ID asli untuk pemicu Switch Agent nanti
                        'server_name' => $item['name'] ?? 'Unknown Agent',
                        'server_ip' => $item['ip'] ?? '0.0.0.0',
                        'status' => ucfirst($item['status'] ?? 'disconnected'),
                    ];
                }
            }

            $agentsCollection = collect($agents);

            // 3. Hitung statistik otomatis berdasarkan status riil dari API Wazuh yang sudah difilter
            $agentsTotal = $agentsCollection->count();
            $activeAgents = $agentsCollection->whereIn('status', ['Active', 'active', 'Disconnect', 'Active'])->count();
            $disconnectAgents = $agentsCollection->whereIn('status', ['Disconnected', 'disconnected', 'Never_connected'])->count();

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

    public function customerCustomizeSettings()
    {
        // // CODE: Mengambil semua daftar template kustomisasi layout dashboard milik pengguna dari database.
        // // WEB: Menu Profile > Tab "Customization Dashboard" (Layout Builder).
        // // UNTUK: Menampilkan status template mana yang sedang aktif digunakan ("In Use") atau siap dihapus.
        $myDashboards = DasborKustom::where('user_id', auth()->id())->get();

        $totalDashboard = $myDashboards->count();

        $activeDashboard = $myDashboards->where('status_dasbor', 'aktif')->count();

        $dashboardInUseItem = $myDashboards->where('status_dasbor', 'aktif')->first();
        $dashboardInUse = $dashboardInUseItem ? sprintf("%02d", $dashboardInUseItem->id) : '00';

        return view('Customer.profile.profilecustom', [
            'dashboards' => $myDashboards,
            'totalDashboard' => $totalDashboard,
            'activeDashboard' => $activeDashboard,
            'dashboardInUse' => $dashboardInUse,
        ]);
    }

    public function destroyCustomize($id)
    {
        // // CODE: Mencari data ID layout dashboard kustom tertentu lalu menghapusnya dari database MySQL.
        // // WEB: Aksi tombol "Delete" pada baris tabel halaman Customization Dashboard.
        // // UNTUK: Menghapus konfigurasi widget kustom yang sudah tidak digunakan lagi oleh customer.
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

        // Di sini biarkan hanya mengirim success saja, tanpa password_success
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

        // PERBAIKAN DI SINI: Tambahkan ->with('password_success', true)
        return back()
            ->with('success', 'Your password has been successfully updated!')
            ->with('password_success', true);
    }

    // ==========================================
    // 4. FITUR BARU: SWITCH AGENT LOGIC
    // ==========================================

    public function switchAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|string'
        ]);

        // KONDISI KHUSUS: Jika user memilih "All Agents", langsung loloskan ke session
        if ($request->agent_id === 'all') {
            session(['active_wazuh_agent_id' => 'all']);
            return back()->with('success', 'Berhasil beralih ke semua agen.');
        }

        // PROTEKSI KEAMANAN: Memastikan customer tidak menembak ID agen milik orang lain
        $isValidAgent = Agen::where('user_id', auth()->id())
            ->where('id_wazuh_agen', $request->agent_id)
            ->exists();

        if ($isValidAgent) {
            session(['active_wazuh_agent_id' => $request->agent_id]);
            return back()->with('success', 'Berhasil beralih ke agen ' . $request->agent_id);
        }

        return back()->with('error', 'Akses agen tidak diizinkan.');
    }
}