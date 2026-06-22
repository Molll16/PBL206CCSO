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

    // Code ini untuk: Mengambil statistik status agen dan ringkasan data log ancaman.
    // Berfungsi untuk: Menampilkan halaman utama Dashboard Customer beserta komponen widget kustomisasinya.
    public function dashboard()
    {
        $allMyAgents = Agen::where('user_id', auth()->id())->get();

        $defaultAgentId = $allMyAgents->first()->id_wazuh_agen ?? null;
        $activeAgentId = session('active_wazuh_agent_id', $defaultAgentId);

        $dashboard = DasborKustom::where('user_id', auth()->id())->where('status_dasbor', 'aktif')->first();
        $widgets = $dashboard ? HasilKustom::with('fitur')->where('dasbor_kustom_id', $dashboard->id)->get() : collect([]);

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