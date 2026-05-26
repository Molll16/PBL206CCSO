<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;
use App\Models\DasborKustom;
use App\Models\HasilKustom;
use App\Services\AgentService;
use App\Services\AlertService;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    public function dashboard(
        WazuhApiService $wazuh,
        AgentService $agentService,
        AlertService $alertService
    ) {
        // =========================================
        // DASHBOARD CUSTOM
        // =========================================
        $dashboard = DasborKustom::where('user_id', auth()->id())
            ->where('status_dasbor', 'aktif')
            ->first();

        $widgets = [];

        if ($dashboard) {
            $widgets = HasilKustom::with('fitur')
                ->where('dasbor_kustom_id', $dashboard->id)
                ->get();
        }

        // =========================================
        // DEFAULT DATA
        // =========================================
        $alerts = [];
        $agentOnline = 0;
        $agentOffline = 0;
        $agentTotal = 0;
        $wazuhOffline = false;

        // Array default jika data API gagal dimuat
        $threatSummary = [
            'active' => 0,
            'pending' => 0,
            'resolved' => 0,
            'categories' => []
        ];

        // =========================================
        // 1. TES KONEKSI UTAMA WAZUH (PING)
        // =========================================
        try {
            // Memastikan token atau koneksi dasar ke Wazuh API aman
            $wazuh->Token();
        } catch (\Throwable $e) {
            // Jika API benar-benar RTO atau mati, tandai server offline
            $wazuhOffline = true;
        }

        // =========================================
        // 2. AMBIL DATA DENGAN TRY-CATCH TERPISAH
        // =========================================
        if (!$wazuhOffline) {

            // Ambil 5 Security Alerts Terbaru
            try {
                $alerts = $alertService->getLatestAlerts(5);
            } catch (\Throwable $e) {
                $alerts = [];
            }

            // Ambil Statistik Agent Status
            try {
                $agentStats = $agentService->getStats($wazuh);

                $agentOnline = $agentStats['online'] ?? 0;
                $agentOffline = $agentStats['offline'] ?? 0;
                $agentTotal = $agentStats['total'] ?? 0;
            } catch (\Throwable $e) {
                $agentOnline = 0;
                $agentOffline = 0;
                $agentTotal = 0;
            }

            // Ambil Data Threat Summary Real-time
            try {
                $threatSummary = $alertService->getThreatSummary();
            } catch (\Throwable $e) {
                // Tetap menggunakan array default jika terjadi error pemrosesan data
            }
        }

        // =========================================
        // VIEW
        // =========================================
        return view('Customer.dashboard', compact(
            'dashboard',
            'widgets',
            'agentOnline',
            'agentOffline',
            'agentTotal',
            'alerts',
            'wazuhOffline',
            'threatSummary'
        ));
    }
}