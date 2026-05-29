<?php

namespace App\Http\Controllers;

use App\Services\AgentService;
use App\Services\AlertService;

class AdminDashboardController extends Controller
{
    protected $agentService;
    protected $alertService;

    // DI via Constructor
    public function __construct(AgentService $agentService, AlertService $alertService)
    {
        $this->agentService = $agentService;
        $this->alertService = $alertService;
    }

    public function index()
    {
        try {
            // Ambil data manifestasi objek dari service
            $agents = $this->agentService->getAgents();
            $agentStats = $this->agentService->getAdminStats();
            $chartStats = $this->alertService->getWeeklyChartData();
            $users = $this->agentService->getCustomerManagementSummary();

            return view('Admin.dashboard', [
                'agents' => $agents,
                'active' => $agentStats['active'],
                'disconnected' => $agentStats['disconnected'],
                'totalUsers' => $users['totalUsers'] ?? 0,
                'totalAgents' => $agentStats['total'] ?? 0,
                'chartLabels' => $chartStats['labels'],
                'chartData' => $chartStats['data'],
                'totalAlerts' => $chartStats['total'],
                'wazuhOffline' => false
            ]);

        } catch (\Throwable $e) {
            // Fallback terpusat jika API Wazuh/Indexer down
            return view('Admin.dashboard', [
                'error' => 'Server monitoring sedang tidak tersedia',
                'agents' => [],
                'active' => 0,
                'disconnected' => 0,
                'totalUsers' => 0,
                'totalAgents' => 0,
                'chartLabels' => [],
                'chartData' => [],
                'totalAlerts' => 0,
                'wazuhOffline' => true,
            ]);
        }
    }
}