<?php

namespace App\Http\Controllers;

use App\Services\AgentService;
use App\Services\AlertService;

// Class ini untuk: Menangani tampilan halaman utama Dashboard Admin.
// Berfungsi pada: Halaman Admin - Dashboard (halaman pertama yang dilihat admin setelah login).
// Dibagian fitur: Statistik status agen, grafik log mingguan, dan ringkasan jumlah user/customer.
class AdminDashboardController extends Controller
{
    protected $agentService;
    protected $alertService;

    public function __construct(AgentService $agentService, AlertService $alertService)
    {
        $this->agentService = $agentService;
        $this->alertService = $alertService;
    }

    // Code ini untuk: Mengambil status agen, grafik log mingguan, dan ringkasan jumlah user dari service.
    // Berfungsi untuk: Menampilkan halaman utama Dashboard Admin (Admin.dashboard).
    public function index()
    {
        try {
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
            // FALLBACK JIKA WAZUH MATI / API OFFLINE
            // Kirim data kosong/nol dan tandai 'wazuhOffline' => true agar view bisa memberi info server down
            return view('Admin.dashboard', [
                'error' => 'Server monitoring is currently unavailable',
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