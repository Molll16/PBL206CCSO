<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;
use Illuminate\Http\Request;
use App\Models\DasborKustom;
use App\Models\HasilKustom;
use App\Models\Fitur;
use App\Http\Controllers\AlertController;
use App\Services\AgentService;

class CustomerDashboardController extends Controller
{


    // Fungsi untuk menampilkan dashboard dengan data dari Wazuh API
    public function dashboard(
        WazuhApiService $wazuh,
        AlertController $alertController,
        AgentService $agentService
    )

    {
        // Ambil dashboard yang aktif milik user yang login (keamanan: tidak bisa ambil punya orang lain)
        $dashboard = DasborKustom::where('user_id', auth()->id())
            ->where('status_dasbor', 'aktif')
            ->first();
    
        $widgets = [];
    
        // Jika ada dashboard yang aktif, ambil semua widget/fitur yang terkait dengan dashboard tersebut
        if ($dashboard) {
            $widgets = HasilKustom::with('fitur')
                ->where('dasbor_kustom_id', $dashboard->id)
                ->get();
        }
    
        $alerts = $alertController->getAlerts($wazuh);

        $agentStats = $agentService->getStats($wazuh);
            $agentOnline  = $agentStats['online'];
            $agentOffline = $agentStats['offline'];
            $agentTotal   = $agentStats['total'];
    
        // fungsi untuk menampilkan dashboard dengan data dari Wazuh API
        return view('Customer.dashboard', compact(
            'dashboard',
            'widgets',
            'agentOnline',
            'agentOffline',
            'agentTotal',
            'alerts'
        ));
    }
}