<?php

namespace App\Http\Controllers;

use App\Models\DasborKustom;
use App\Models\HasilKustom;
use App\Models\Agen;
use App\Services\AgentService;
use App\Services\AlertService;

class CustomerDashboardController extends Controller
{
    protected $agentService;
    protected $alertService;

    public function __construct(AgentService $agentService, AlertService $alertService)
    {
        $this->agentService = $agentService;
        $this->alertService = $alertService;
    }

    public function dashboard()
    {
        // 1. Ambil data semua agen yang di-assign ke customer ini dari MySQL lokal
        $allMyAgents = Agen::where('user_id', auth()->id())->get();

        // 2. Cek session browser untuk pilihan agen aktif
        $activeAgentId = session('active_wazuh_agent_id', $allMyAgents->first()->id_wazuh_agen ?? null);

        // 3. Load data kustomisasi layout dashboard milik customer (Tetap berupa Object Eloquent)
        $dashboard = DasborKustom::where('user_id', auth()->id())->where('status_dasbor', 'aktif')->first();
        $widgets = $dashboard ? HasilKustom::with('fitur')->where('dasbor_kustom_id', $dashboard->id)->get() : collect([]);

        // 4. Siapkan fallback data default (State Safety)
        $viewData = [
            'dashboard' => $dashboard,
            'widgets' => $widgets,
            'activeAgentId' => $activeAgentId,
            'agentOnline' => 0,
            'agentOffline' => 0,
            'agentTotal' => 0,
            'alerts' => [],
            'wazuhOffline' => false,
            'threatSummary' => ['active' => 0, 'pending' => 0, 'resolved' => 0, 'categories' => []]
        ];

        // 5. Inject data real-time jika koneksi aman
        try {
            $agentStats = $this->agentService->getCustomerStats();

            $viewData['alerts'] = $this->alertService->getLatestAlerts(5, $activeAgentId);
            $viewData['threatSummary'] = $this->alertService->getThreatSummary($activeAgentId);

            $viewData['agentOnline'] = $agentStats['online'];
            $viewData['agentOffline'] = $agentStats['offline'];
            $viewData['agentTotal'] = $agentStats['total'];
        } catch (\Throwable $e) {
            $viewData['wazuhOffline'] = true;
        }

        return view('Customer.dashboard', $viewData);
    }
}