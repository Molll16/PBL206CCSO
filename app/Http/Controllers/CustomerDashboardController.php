<?php

namespace App\Http\Controllers;

use App\Models\DasborKustom;
use App\Models\HasilKustom;
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
        // 1. Load data kustomisasi layout dashboard milik customer
        $dashboard = DasborKustom::where('user_id', auth()->id())->where('status_dasbor', 'aktif')->first();
        $widgets = $dashboard ? HasilKustom::with('fitur')->where('dasbor_kustom_id', $dashboard->id)->get() : [];

        // 2. Siapkan fallback data default (State Safety)
        $viewData = [
            'dashboard' => $dashboard,
            'widgets' => $widgets,
            'agentOnline' => 0,
            'agentOffline' => 0,
            'agentTotal' => 0,
            'alerts' => [],
            'wazuhOffline' => false,
            'threatSummary' => ['active' => 0, 'pending' => 0, 'resolved' => 0, 'categories' => []]
        ];

        // 3. Inject data real-time jika koneksi aman
        try {
            $agentStats = $this->agentService->getCustomerStats();

            $viewData['alerts'] = $this->alertService->getLatestAlerts(5);
            $viewData['agentOnline'] = $agentStats['online'];
            $viewData['agentOffline'] = $agentStats['offline'];
            $viewData['agentTotal'] = $agentStats['total'];
            $viewData['threatSummary'] = $this->alertService->getThreatSummary();
        } catch (\Throwable $e) {
            $viewData['wazuhOffline'] = true;
        }

        return view('Customer.dashboard', $viewData);
    }
}