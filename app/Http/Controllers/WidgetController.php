<?php

namespace App\Http\Controllers;

use App\Services\AgentService;
use App\Services\AlertService;
use Illuminate\Http\Request;
use App\Models\Agen;

// Controller buat endpoint API widget-widget di Dashboard Customer
class WidgetController extends Controller
{
    protected $agentService;
    protected $alertService;

    public function __construct(AgentService $agentService, AlertService $alertService)
    {
        $this->agentService = $agentService;
        $this->alertService = $alertService;
    }

    // Ambil ID agen yang lagi aktif dipantau, kalau session kosong pakai agen pertama punya user
    private function getActiveAgentId(): ?string
    {
        $selectedAgentId = session('active_wazuh_agent_id');

        if (!$selectedAgentId) {
            $defaultAgent = Agen::where('user_id', auth()->id())->first();
            $selectedAgentId = $defaultAgent ? $defaultAgent->id_wazuh_agen : null;
        }

        return $selectedAgentId;
    }

    // Widget status agen online/offline - Dashboard Customer
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
                'message' => 'Failed to fetch agent status: Connection issue occurred.'
            ]);
        }
    }

    // Widget alert keamanan terbaru - Dashboard Customer
    public function getLatestAlerts()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json(['success' => false, 'data' => [], 'message' => 'Tidak ada agen aktif.']);
            }

            $alerts = $this->alertService->getLatestAlerts(5, $activeAgentId);
            return response()->json([
                'success' => true,
                'data' => $alerts
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to fetch security alerts: Connection issue occurred.'
            ]);
        }
    }

    // Widget threat summary (donut chart & list kategori ancaman) - Dashboard Customer
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
                'message' => 'Failed to fetch threat summary: ' . $e->getMessage()
            ]);
        }
    }

    // Widget pemakaian CPU/RAM/Disk/Swap - Dashboard Customer
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
                    'message' => 'Failed to fetch system resources: No registered agent for this account.'
                ]);
            }

            $metrics = $this->agentService->fetchSystemResources($activeAgentId);

            // disk & swap pakai data riil kalau ada di metrics, kalau gak ada ya 0 (bukan data karangan)
            $resources = [
                ['label' => 'CPU', 'value' => $metrics['cpu'] ?? 0, 'color' => 'bg-cyan-500'],
                ['label' => 'RAM', 'value' => $metrics['ram'] ?? 0, 'color' => 'bg-amber-500'],
                ['label' => 'DISK', 'value' => $metrics['disk'] ?? 0, 'color' => 'bg-emerald-500'],
                ['label' => 'SWAP', 'value' => $metrics['swap'] ?? 0, 'color' => 'bg-indigo-500']
            ];

            return response()->json([
                'success' => true,
                'data' => $resources
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [
                    ['label' => 'CPU', 'value' => 0, 'color' => 'bg-cyan-500'],
                    ['label' => 'RAM', 'value' => 0, 'color' => 'bg-amber-500'],
                    ['label' => 'DISK', 'value' => 0, 'color' => 'bg-emerald-500'],
                    ['label' => 'SWAP', 'value' => 0, 'color' => 'bg-indigo-500']
                ],
                'message' => 'Failed to fetch system resources: Connection lost.'
            ]);
        }
    }

    // Widget File Integrity Monitoring (FIM) - Dashboard Customer
    public function getFileIntegrity()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json(['success' => false, 'data' => [], 'message' => 'Failed to fetch FIM data: Inactive agent.']);
            }

            $fimData = $this->agentService->fetchFileIntegrityLogs($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $fimData
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => [], 'message' => 'Failed to fetch FIM data.']);
        }
    }

    // Widget failed login counter - Dashboard Customer
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
            return response()->json(['success' => false, 'data' => ['count' => 0, 'timeline' => 'Connection lost', 'status_tag' => 'Offline']]);
        }
    }

    // Widget riwayat login user lokal - Dashboard Customer
    public function getUserLoginActivity()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Failed to fetch user login activity: No registered agent for this account.'
                ]);
            }

            $activitiesData = $this->agentService->fetchUserLoginActivity($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $activitiesData
            ]);
        } catch (\Throwable $e) {
            \Log::error('[Widget] getUserLoginActivity error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to fetch login activity: Server is not responding.'
            ]);
        }
    }

    // Widget rule keamanan paling sering kepicu - Dashboard Customer
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
                'message' => 'Failed to fetch active rules: ' . $e->getMessage()
            ]);
        }
    }

    // Widget status service (Nginx, MySQL, SSH, dll) - Dashboard Customer
    public function getServiceStatus()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Failed to fetch service status: No active agent selected.'
                ]);
            }

            $servicesData = $this->agentService->fetchServiceStatus($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $servicesData
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to fetch service status: Network issue occurred.'
            ]);
        }
    }

    // Widget network traffic (inbound/outbound + status interface) - Dashboard Customer
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

            if (empty($networkData) || !isset($networkData['interfaces'])) {
                return response()->json([
                    'success' => true,
                    'data' => ['stats' => ['inbound' => 0, 'outbound' => 0], 'interfaces' => []]
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
                'message' => 'Failed to fetch network traffic: Connection lost.'
            ]);
        }
    }

    // Widget log firewall - Dashboard Customer
    public function getFirewallEvents()
    {
        try {
            $activeAgentId = $this->getActiveAgentId();

            if (!$activeAgentId) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Failed to fetch firewall events: No active agent selected.'
                ]);
            }

            $events = $this->agentService->fetchFirewallEvents($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Throwable $e) {
            \Log::error('[Widget] getFirewallEvents error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Failed to fetch firewall events: Connection failed.'
            ]);
        }
    }

    // Widget active connections - Dashboard Customer
    // Perbaikan: sebelumnya baca session key yang salah ('active_agent_id'), sekarang pakai getActiveAgentId() biar konsisten
    public function getActiveConnections(Request $request)
    {
        try {
            $agentId = $this->getActiveAgentId() ?? '000';

            $data = $this->agentService->fetchActiveConnections($agentId);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Throwable $e) {
            \Log::error("Widget Active Connections Controller Error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active connections: Connection failed.'
            ], 500);
        }
    }

    // Widget GeoIP attack map - Dashboard Customer
    // Perbaikan: sama kayak getActiveConnections(), session key-nya salah, sekarang dibenerin
    public function getGeoAttacks(Request $request)
    {
        try {
            $agentId = $this->getActiveAgentId() ?? '000';

            $data = $this->agentService->fetchGeoAttacks($agentId);

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Throwable $e) {
            \Log::error("Widget GeoIP Attack Map Controller Error: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch geo-attacks: Connection failed.'
            ], 500);
        }
    }
}