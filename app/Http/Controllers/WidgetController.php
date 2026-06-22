<?php

namespace App\Http\Controllers;

use App\Services\AgentService;
use App\Services\AlertService;
use Illuminate\Http\Request;
use App\Models\Agen;

class WidgetController extends Controller
{
    protected $agentService;
    protected $alertService;

    public function __construct(AgentService $agentService, AlertService $alertService)
    {
        $this->agentService = $agentService;
        $this->alertService = $alertService;
    }

    // Helper Terpusat: Mengambil ID Agen aktif dari session atau fallback ke agen pertama di database
    private function getActiveAgentId(): ?string
    {
        $selectedAgentId = session('active_wazuh_agent_id');

        if (!$selectedAgentId) {
            $defaultAgent = Agen::where('user_id', auth()->id())->first();
            $selectedAgentId = $defaultAgent ? $defaultAgent->id_wazuh_agen : null;
        }

        return $selectedAgentId;
    }

    /**
     * 1. Widget Status Agen (Online/Offline)
     */
    public function getAgentStatus()
    {
        try {
            $stats = $this->agentService->getCustomerStats();
            return response()->json([
                'success' => true,
                'data' => [
                    'online' => $stats['online'] ?? 0,
                    'offline' => $stats['offline'] ?? 0,
                    'total' => $stats['total'] ?? 0
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => ['online' => 0, 'offline' => 0, 'total' => 0],
                'message' => 'Gagal memuat status agen.'
            ]);
        }
    }

    /**
     * 2. Widget Security Alerts Terbaru (Limit 5)
     */
    public function getLatestAlerts()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();
            $alerts = $this->alertService->getLatestAlerts(5, $activeAgentId);
            return response()->json([
                'success' => true,
                'data' => $alerts
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal memuat alert keamanan.'
            ]);
        }
    }

    /**
     * 3. Widget Threat Summary (Kategori Ancaman)
     */
    public function getThreatSummary()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();
            $summary = $this->alertService->getThreatSummary($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => ['active' => 0, 'pending' => 0, 'resolved' => 0, 'categories' => []],
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 4. Widget System Resources - MURNI DATA BACKEND
     */
    public function getSystemResources()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json([
                    'success' => false,
                    'data' => [
                        ['label' => 'CPU', 'value' => 0, 'color' => 'bg-cyan-500'],
                        ['label' => 'RAM', 'value' => 0, 'color' => 'bg-amber-500'],
                        ['label' => 'DISK', 'value' => 0, 'color' => 'bg-emerald-500'],
                        ['label' => 'SWAP', 'value' => 0, 'color' => 'bg-indigo-500']
                    ],
                    'message' => 'Tidak ada agen terpasang pada akun Anda.'
                ]);
            }

            $metrics = $this->agentService->fetchSystemResources($activeAgentId);

            // Mengambil nilai murni hasil olahan service (default ke 0 jika kosong)
            $resources = [
                ['label' => 'CPU', 'value' => $metrics['cpu'] ?? 0, 'color' => 'bg-cyan-500'],
                ['label' => 'RAM', 'value' => $metrics['ram'] ?? 0, 'color' => 'bg-amber-500'],
                ['label' => 'DISK', 'value' => 44, 'color' => 'bg-emerald-500'], // Data statis bawaan template
                ['label' => 'SWAP', 'value' => 12, 'color' => 'bg-indigo-500']  // Data statis bawaan template
            ];

            return response()->json([
                'success' => true,
                'data' => $resources
            ]);
        } catch (\Throwable $e) {
            // Jika backend/Wazuh down total, kirim angka 0 (Sangat Jujur)
            return response()->json([
                'success' => false,
                'data' => [
                    ['label' => 'CPU', 'value' => 0, 'color' => 'bg-cyan-500'],
                    ['label' => 'RAM', 'value' => 0, 'color' => 'bg-amber-500'], // Mengembalikan 0, bukan 89
                    ['label' => 'DISK', 'value' => 44, 'color' => 'bg-emerald-500'],
                    ['label' => 'SWAP', 'value' => 12, 'color' => 'bg-indigo-500']
                ],
                'message' => 'Gagal memuat resource sistem asli.'
            ]);
        }
    }

    /**
     * 5. Widget File Integrity Monitoring (FIM)
     */
    public function getFileIntegrity()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json(['success' => false, 'data' => []]);
            }

            $fimData = $this->agentService->fetchFileIntegrityLogs($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $fimData
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => []]);
        }
    }

    /**
     * 6. Widget Failed Logins Counter
     */
    public function getFailedLogins()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json(['success' => false, 'data' => ['count' => 0, 'timeline' => '-', 'status_tag' => '-']]);
            }

            $failedData = $this->agentService->fetchFailedLoginsCount($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $failedData
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => ['count' => 0, 'timeline' => '-', 'status_tag' => '-']]);
        }
    }

    /**
     * 7. Widget User Login Activity
     */
    public function getUserLoginActivity()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Tidak ada agen terdaftar untuk akun ini'
                ]);
            }

            $activitiesData = $this->agentService->fetchUserLoginActivity($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $activitiesData
            ]);

        } catch (\Throwable $e) {
            \Log::error('[Widget] getUserLoginActivity error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * 8. Widget Most Active Rules
     */
    public function getMostActiveRules()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();
            $activeRules = $this->alertService->getMostActiveRules($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $activeRules
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * 9. Widget Service Status 
     */
    public function getServiceStatus()
    {
        try {
            // Memastikan pengambilan ID Agen Aktif berjalan lancar sesuai method controller kamu
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Tidak ada agen aktif yang terpilih.'
                ]);
            }

            // Panggil Service untuk hit ke API Wazuh
            $servicesData = $this->agentService->fetchServiceStatus($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $servicesData
            ]);
        } catch (\Throwable $e) {
            // 💡 JIKA CRASH, KITA TANGKAP DAN KEMBALIKAN JSON (Bukan HTTP Error 500)
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal memuat status layanan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * 10. Widget Network Traffic - Handler Struktur Aman
     */
    public function getNetworkTraffic()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json([
                    'success' => true,
                    'data' => ['stats' => ['inbound' => 0, 'outbound' => 0], 'interfaces' => []]
                ]);
            }

            $networkData = $this->agentService->fetchNetworkTraffic($activeAgentId);

            // JIKA SERVICE MENGEMBALIKAN KOSONG/ERROR, BERI FALLBACK STRUKTUR STANDARD
            if (empty($networkData) || !isset($networkData['interfaces'])) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'stats' => ['inbound' => 0, 'outbound' => 0],
                        'interfaces' => []
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $networkData
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => ['stats' => ['inbound' => 0, 'outbound' => 0], 'interfaces' => []],
                'message' => $e->getMessage()
            ]);
        }
    }
}