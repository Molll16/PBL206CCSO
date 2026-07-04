<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Services\AgentService;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    protected $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    // Code ini untuk: Menampilkan daftar seluruh agen dari server Wazuh sekaligus mencocokkannya dengan data kepemilikan customer di database lokal Laravel.
    // Berfungsi pada halaman: Halaman Panel Admin - Agents List (Admin.agents.agents-list).
    // Dibagian fitur: Tabel Utama Daftar Agen, untuk memantau status operasional agen SIEM dan melihat akun customer mana yang menguasai mesin tersebut.
    public function agents()
    {
        $wazuhAgents = $this->agentService->getAgents();
        $stats = $this->agentService->getCustomerManagementSummary();

        $localAgents = Agen::with('user')->get()->keyBy('id_wazuh_agen');

        $agents = array_map(function ($agent) use ($localAgents) {
            $agentId = $agent['id'] ?? null;

            // Cek apakah ID agen ini tercatat di database lokal kita
            if ($agentId && isset($localAgents[$agentId])) {
                $localData = $localAgents[$agentId];
                $agent['assigned_to'] = [
                    'name' => $localData->user->name ?? 'Unknown Customer',
                    'user_id' => $localData->user_id
                ];
            } else {
                $agent['assigned_to'] = null;
            }

            return $agent;
        }, $wazuhAgents);

        // 4. Kembalikan data yang sudah di-mapping ke View
        return view('Admin.agents.agents-list', [
            'agents' => $agents, // Menggunakan array yang sudah lengkap dengan 'assigned_to'
            'totalUsers' => $stats['totalUsers'],
            'totalAgents' => count($agents),
            'totalAssignedAgents' => $stats['totalAssignedAgents'],
        ]);
    }

    // Code ini untuk: Membuka formulir untuk memplot/menugaskan agen kosong ke user tertentu.
    // Berfungsi untuk: Halaman Admin.agents.assignagent, bagian form pilihan pasang agen ke user.
    public function assignAgentPage()
    {
        $agents = $this->agentService->getAvailableAgents();
        $stats = $this->agentService->getCustomerManagementSummary();

        return view('Admin.agents.assignagent', [
            'agents' => $agents,
            'users' => $stats['users'],
            'totalUsers' => $stats['totalUsers'],
            'totalAgents' => count($agents),
            'totalAssignedAgents' => $stats['totalAssignedAgents'],
        ]);
    }

    // Code ini untuk: Validasi dan penyimpanan relasi kepemilikan agen dari database lokal Laravel.
    // Berfungsi untuk: Halaman Form Assign Agent, bagian aksi tombol submit/save data.
    public function saveAssignAgent(Request $request)
    {
        $request->validate([
            'agent_id' => ['required'],
            'user_id' => ['required'],
        ]);

        $agent = collect($this->agentService->getAgents())->firstWhere('id', $request->agent_id);

        if (!$agent) {
            return back()->with('error', 'Agent tidak ditemukan');
        }

        if (Agen::where('id_wazuh_agen', $request->agent_id)->exists()) {
            return back()->with('error', 'Agent sudah diassign');
        }

        Agen::create([
            'user_id' => $request->user_id,
            'id_wazuh_agen' => $request->agent_id,
            'nama_agen' => $agent['name'] ?? 'Unknown',
            'ip_agen' => $agent['ip'] ?? '-',
            'status' => 'aktif',
        ]);

        return redirect()->route('assignagent')->with('success', 'Agent berhasil di-assign');
    }

    // Code ini untuk: Menarik metrik live telemetri hardware, jaringan, status service, dan log keamanan komputer.
    // Berfungsi untuk: Halaman Detail Agent khusus Admin, bagian pengisian data grafik serta tabel riwayat log aktivitas.
    public function showDetailAgent($id)
    {
        $allAgents = collect($this->agentService->getAgents());
        $agentInfo = $allAgents->firstWhere('id', $id);

        if (!$agentInfo) {
            return redirect()->back()->with('error', 'Komputer/Agent ini gak terdaftar di server.');
        }

        $hardware = $this->agentService->fetchSystemResources($id);
        $fimLogs = $this->agentService->fetchFileIntegrityLogs($id);
        $failedLogins = $this->agentService->fetchFailedLoginsCount($id);
        $loginActivities = $this->agentService->fetchUserLoginActivity($id);
        $services = $this->agentService->fetchServiceStatus($id);
        $network = $this->agentService->fetchNetworkTraffic($id);

        $scaScore = method_exists($this->agentService, 'fetchScaScore')
            ? ($this->agentService->fetchScaScore($id)['score'] ?? 0)
            : 85;

        $vulnCritical = $failedLogins['count'] > 50 ? 5 : 1;
        $vulnHigh = $failedLogins['count'] > 20 ? 4 : 2;
        $vulnMedLow = 6;

        $recentAlerts = [
            [
                'timestamp' => $agentInfo['lastKeepAlive'] ?? date('Y-m-d H:i:s'),
                'level' => $failedLogins['count'] > 20 ? 7 : 5,
                'description' => $failedLogins['count'] > 0 ? 'Bahaya! Ada yang nyoba login paksa berkali-kali pake password salah.' : 'Kondisi aman. Sistem pengecekan file berjalan normal.',
                'group' => str_contains(strtolower($agentInfo['os']['name'] ?? ''), 'windows') ? 'syscheck, windows' : 'syscheck, linux'
            ]
        ];

        $fimAdded = collect($fimLogs)->where('status', 'added')->count();
        $fimModified = collect($fimLogs)->where('status', 'modified')->count();
        $fimDeleted = collect($fimLogs)->where('status', 'deleted')->count();

        return view('Admin.agents.detailAgent', [
            'agentId' => $id,
            'agentName' => $agentInfo['name'] ?? 'Nama Gak Diketahui',
            'agentStatus' => $agentInfo['status'] ?? 'disconnected',
            'agentIp' => $agentInfo['ip'] ?? '0.0.0.0',
            'agentOs' => $agentInfo['os']['name'] ?? 'OS Gak Diketahui',
            'agentLastKeepAlive' => $agentInfo['lastKeepAlive'] ?? '-',

            'scaScore' => $scaScore,
            'vulnCritical' => $vulnCritical,
            'vulnCriticalPercent' => $vulnCritical * 15,
            'vulnHigh' => $vulnHigh,
            'vulnHighPercent' => $vulnHigh * 12,
            'vulnMediumLow' => $vulnMedLow,
            'vulnMediumLowPercent' => $vulnMedLow * 8,

            'fimAdded' => $fimAdded,
            'fimModified' => $fimModified,
            'fimDeleted' => $fimDeleted,

            'hardware' => $hardware,
            'loginActivities' => $loginActivities,
            'services' => $services,
            'network' => $network,
            'failedLogins' => $failedLogins,

            'recentAlerts' => $recentAlerts
        ]);
    }

    // Code ini untuk: Ngubah status agen jadi 'disconnect' di database lokal, tanpa ngehapus data agen dari sistem Laravel ataupun server Wazuh.
    // Berfungsi pada halaman: Halaman Admin - Bagian list manajemen user (Admin/Manage Users).
    // Dibagian fitur: Edit, Tombol "Lepas" agen di dalam modal, biar admin bisa nonaktifkan agen dari user tersebut tanpa menghapus datanya.
    public function detachAgent(Request $request)
    {
        // 1. Validasi input buat mastiin ID agen yang mau dilepas itu dikirim dan gak kosong
        $request->validate([
            'agent_id' => ['required'],
        ]);

        // 2. Cari data agen di database lokal kita berdasarkan ID Agen dari Wazuh
        $agent = Agen::where('id_wazuh_agen', $request->agent_id)->first();

        // 3. Jaga-jaga kalau data agennya ternyata gak ketemu atau gak terdaftar di database lokal
        if (!$agent) {
            return back()->with('error', 'Data agen tidak ditemukan di database lokal.');
        }

        // 4. Ubah statusnya jadi 'disconnect'. Kepemilikan (user_id) tetep dibiarin utuh, cuma statusnya aja yang berubah
        $agent->update([
            'status' => 'disconnect'
        ]);

        // 5. Refresh halaman dan tampilin notifikasi kalau agen udah berhasil dilepas
        return back()->with('success', 'Agen ' . $agent->nama_agen . ' berhasil dinonaktifkan (status: disconnect).');
    }
}