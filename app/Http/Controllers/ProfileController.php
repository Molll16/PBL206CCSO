<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Models\DasborKustom;
use App\Services\AgentService;
use App\Services\WazuhApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    protected $agentService;
    protected $wazuhApi;

    public function __construct(AgentService $agentService, WazuhApiService $wazuhApi)
    {
        $this->agentService = $agentService;
        $this->wazuhApi = $wazuhApi;
    }

    // ==========================================
    // 1. ROUTE MANAGEMENT UNTUK ADMIN
    // ==========================================

    // Code ini untuk: Menampilkan halaman pengaturan profil khusus Admin.
    // Berfungsi untuk: Halaman Profile Admin (Tab utama).
    public function settings()
    {
        return view('Admin.profile.profileset-admin');
    }

    // Code ini untuk: Menampilkan data semua agen untuk kebutuhan Admin.
    // Berfungsi untuk: Halaman Manajemen Agen di panel Admin.
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

    // Code ini untuk: Menampilkan data profil milik Customer yang sedang login.
    // Berfungsi untuk: Halaman Profile Customer, bagian tab "Profile Settings".
    public function customerAccountSettings()
    {
        return view('Customer.profile.profileset', [
            'user' => auth()->user()
        ]);
    }

    // Code ini untuk: Menampilkan daftar server/agen yang ditugaskan ke Customer beserta status real-time.
    // Berfungsi untuk: Halaman Profile Customer, bagian tab "Server" (Inventaris Perangkat).
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

    // Code ini untuk: Menampilkan daftar layout kustomisasi dashboard milik Customer.
    // Berfungsi untuk: Halaman Profile Customer, bagian tab "Customization Dashboard".
    public function customerCustomizeSettings()
    {
        $myDashboards = DasborKustom::where('user_id', auth()->id())->get();
        $totalDashboard = $myDashboards->count();
        $activeDashboard = $myDashboards->where('status_dasbor', 'aktif')->count();

        // Mengambil baris data dashboard yang saat ini aktif digunakan
        $dashboardInUseItem = $myDashboards->where('status_dasbor', 'aktif')->first();

        // 🛠️ PERBAIKAN: Ambil nama dasbor aslinya, jika tidak ada fallback ke 'Default'
        $dashboardNameInUse = $dashboardInUseItem ? $dashboardInUseItem->nama_dasbor : 'Default';

        return view('Customer.profile.profilecustom', [
            'dashboards' => $myDashboards,
            'totalDashboard' => $totalDashboard,
            'activeDashboard' => $activeDashboard,
            'dashboardNameInUse' => $dashboardNameInUse, // Dikirim ke view blade
        ]);
    }

    // Code ini untuk: Menghapus konfigurasi kustomisasi dashboard tertentu.
    // Berfungsi untuk: Halaman Profile Customer, aksi tombol "Delete" di tabel kustomisasi.
    public function destroyCustomize($id)
    {
        $dashboard = DasborKustom::where('user_id', auth()->id())->findOrFail($id);
        $dashboard->delete();

        return back()->with('success', 'Dashboard kustom berhasil dihapus!');
    }

    // ==========================================
    // 3. LOGIC PROSES UPDATE (SHARED)
    // ==========================================

    // Code ini untuk: Memperbarui informasi nama, email, dan no telepon dengan proteksi role.
    // Berfungsi untuk: Form "Edit Profile" (Admin bisa ubah semua, Customer dikunci/tidak disimpan).
    public function update(Request $request)
    {
        $user = auth()->user();

        // JIKA CUSTOMER: bypass langsung tanpa mengubah data, hindari error di frontend
        if ($user->role === 'customer') {
            return back()->with('success', 'Profile berhasil diupdate');
        }

        // JIKA ADMIN: jalankan validasi dan update seperti biasa
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'no_telp' => 'required',
        ]);

        $user->update($request->only('name', 'email', 'no_telp'));

        return back()->with('success', 'Profile berhasil diupdate');
    }

    // Code ini untuk: Mengubah password akun user (Shared Admin & Customer).
    // Berfungsi untuk: Form "Change Password" di halaman profil Admin dan Customer.
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

    // ==========================================
    // 4. FITUR SWITCH AGENT LOGIC
    // ==========================================

    // Code ini untuk: Mengubah session target ID agen yang aktif dipantau.
    // Berfungsi untuk: Komponen Dropdown "Switch Agent" di halaman Dashboard Customer.
    public function switchAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|string'
        ]);

        if ($request->agent_id === 'all') {
            session(['active_wazuh_agent_id' => 'all']);
            return back()->with('success', 'Berhasil beralih ke semua agen.');
        }

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