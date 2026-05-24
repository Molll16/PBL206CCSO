<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;
use Illuminate\Http\Request;
use App\Models\DasborKustom;
use App\Models\HasilKustom;
use App\Http\Controllers\AlertController;
use App\Services\AgentService;

class CustomerDashboardController extends Controller
{
    public function dashboard(
        WazuhApiService $wazuh,
        AlertController $alertController,
        AgentService $agentService
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

        // =========================================
        // AMBIL DATA WAZUH
        // =========================================
        try {

            // alerts
            $alerts = $alertController->getAlerts($wazuh);

            // statistik agent
            $agentStats = $agentService->getStats($wazuh);

            $agentOnline = $agentStats['online'];
            $agentOffline = $agentStats['offline'];
            $agentTotal = $agentStats['total'];

        } catch (\Throwable $e) {

            // tandai server offline
            $wazuhOffline = true;
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
            'wazuhOffline'
        ));
    }
}