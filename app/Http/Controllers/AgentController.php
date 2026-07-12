<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Services\AgentService;
use Illuminate\Http\Request;

// Class ini untuk: Menangani seluruh aksi HTTP terkait manajemen agen dari sisi Admin.
// Berfungsi pada: Halaman Admin - Agents List, Assign Agent, dan Detail Agent.
// Dibagian fitur: Menampilkan daftar agen, mencocokkan kepemilikan customer, assign agen baru, dan detail monitoring agen.
class AgentController extends Controller
{
    protected $agentService;

    public function __construct(AgentService $agentService)
    {
        $this->agentService = $agentService;
    }

    // Code ini untuk: Menampilkan daftar seluruh agen dari server Wazuh sekaligus mencocokkannya dengan data kepemilikan customer di database lokal Laravel.
    // Berfungsi pada halaman: Halaman Panel Admin - Agents List (Admin.agents.agents-list).
    // Dibagian fitur: Tabel utama daftar agen, untuk memantau status operasional agen SIEM dan melihat akun customer mana yang menguasai mesin tersebut.
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

        // Kembalikan data yang sudah di-mapping ke View
        return view('Admin.agents.agents-list', [
            'agents' => $agents, // Array agen Wazuh yang sudah dilengkapi info 'assigned_to'
            'totalUsers' => $stats['totalUsers'],
            'totalAgents' => count($agents),
            'totalAssignedAgents' => $stats['totalAssignedAgents'],
        ]);
    }

    // Code ini untuk: Membuka formulir untuk memplot/menugaskan agen kosong (belum dimiliki) ke user tertentu.
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

    // Code ini untuk: Validasi dan penyimpanan relasi kepemilikan agen (agen Wazuh ↔️ user customer) ke database lokal Laravel.
    // Berfungsi untuk: Halaman Form Assign Agent, bagian aksi tombol submit/save data.
    public function saveAssignAgent(Request $request)
    {
        $request->validate([
            'agent_id' => ['required'],
            'user_id' => ['required'],
        ]);

        $agent = collect($this->agentService->getAgents())->firstWhere('id', $request->agent_id);

        if (!$agent) {
            return back()->with('error', 'Agent Cannot be found');
        }

        if (Agen::where('id_wazuh_agen', $request->agent_id)->exists()) {
            return back()->with('error', 'Agent Assigned to another user');
        }

        Agen::create([
            'user_id' => $request->user_id,
            'id_wazuh_agen' => $request->agent_id,
            'nama_agen' => $agent['name'] ?? 'Unknown',
            'ip_agen' => $agent['ip'] ?? '-',
            'status' => 'aktif',
        ]);

        return redirect()->route('assignagent')->with('success', 'Agent has been successfully assigned to the user.');
    }

    // Code ini untuk: Menarik metrik live telemetri hardware, jaringan, status service, dan log keamanan satu komputer/agen.
    // Berfungsi untuk: Halaman Detail Agent khusus Admin, bagian pengisian data grafik serta tabel riwayat log aktivitas.
    public function showDetailAgent($id)
    {
        try {
            // 1. Ambil data dasar agen dari daftar global agen Wazuh
            $allAgents = collect($this->agentService->getAgents());
            $agentInfo = $allAgents->firstWhere('id', $id);

            if (!$agentInfo) {
                return redirect()->back()->with('error', 'Agent not found or not registered');
            }

            // 2. Ambil data riil komponen sistem dari Wazuh API via AgentService
            $hardware = $this->agentService->fetchSystemResources($id);
            $fimLogs = $this->agentService->fetchFileIntegrityLogs($id);
            $failedLogins = $this->agentService->fetchFailedLoginsCount($id);
            $loginActivities = $this->agentService->fetchUserLoginActivity($id);
            $services = $this->agentService->fetchServiceStatus($id);
            $network = $this->agentService->fetchNetworkTraffic($id);

            // 3. Ambil data SCA (Security Configuration Assessment) jika method-nya tersedia di service
            // CATATAN: fetchScaScore() belum dibuat di AgentService, jadi nilai ini akan selalu 0 untuk saat ini (fitur belum lengkap, bukan bug).
            $scaScore = method_exists($this->agentService, 'fetchScaScore')
                ? ($this->agentService->fetchScaScore($id)['score'] ?? 0)
                : 0;

            // 4. Hitung statistik FIM (File Integrity Monitoring) riil dari data yang sudah diambil
            $fimLogsCollection = collect($fimLogs);
            $fimAdded = $fimLogsCollection->where('status', 'added')->count();
            $fimModified = $fimLogsCollection->where('status', 'modified')->count();
            $fimDeleted = $fimLogsCollection->where('status', 'deleted')->count();

            // 5. Kerentanan & Alerts — CATATAN: belum ada method fetchVulnerabilities/fetchAlerts di service,
            // jadi nilainya sengaja dikosongkan (0/[]) agar tidak menampilkan data palsu, bukan bug tersembunyi.
            $vulnCritical = 0;
            $vulnHigh = 0;
            $vulnMedLow = 0;
            $recentAlerts = [];

            return view('Admin.agents.detailAgent', [
                'agentId' => $id,
                'agentName' => $agentInfo['name'] ?? 'Unknown Agent',
                'agentStatus' => $agentInfo['status'] ?? 'disconnected',
                'agentIp' => $agentInfo['ip'] ?? '0.0.0.0',
                'agentOs' => $agentInfo['os']['name'] ?? 'Unknown OS',
                'agentLastKeepAlive' => $agentInfo['lastKeepAlive'] ?? '-',

                'scaScore' => $scaScore,
                'vulnCritical' => $vulnCritical,
                'vulnCriticalPercent' => $vulnCritical * 10,
                'vulnHigh' => $vulnHigh,
                'vulnHighPercent' => $vulnHigh * 10,
                'vulnMediumLow' => $vulnMedLow,
                'vulnMediumLowPercent' => $vulnMedLow * 10,

                'fimAdded' => $fimAdded,
                'fimModified' => $fimModified,
                'fimDeleted' => $fimDeleted,

                'hardware' => $hardware,
                'loginActivities' => $loginActivities,
                'services' => $services,
                'network' => $network,
                'failedLogins' => $failedLogins,
                'recentAlerts' => $recentAlerts,

                // Indikator bahwa server Wazuh terhubung dengan baik
                'wazuhOffline' => false
            ]);

        } catch (\Throwable $e) {
            // FALLBACK JIKA WAZUH MATI / API OFFLINE
            // Mengirimkan data kosong/nol dan menandai 'wazuhOffline' => true agar view bisa memberikan info server off
            return view('Admin.agents.detailAgent', [
                'agentId' => $id,
                'agentName' => 'Server Offline',
                'agentStatus' => 'disconnected',
                'agentIp' => '0.0.0.0',
                'agentOs' => '-',
                'agentLastKeepAlive' => '-',

                'scaScore' => 0,
                'vulnCritical' => 0,
                'vulnCriticalPercent' => 0,
                'vulnHigh' => 0,
                'vulnHighPercent' => 0,
                'vulnMediumLow' => 0,
                'vulnMediumLowPercent' => 0,

                'fimAdded' => 0,
                'fimModified' => 0,
                'fimDeleted' => 0,

                'hardware' => null,
                'loginActivities' => [],
                'services' => [],
                'network' => null,
                'failedLogins' => ['count' => 0],
                'recentAlerts' => [],

                // Pemicu notifikasi di halaman Blade
                'wazuhOffline' => true
            ]);
        }
    }
}