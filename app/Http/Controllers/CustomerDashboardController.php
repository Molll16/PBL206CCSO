<?php

namespace App\Http\Controllers;

use App\Models\DasborKustom;
use App\Models\HasilKustom;
use App\Models\Agen;
use App\Services\AgentService;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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
    public function dashboard(Request $request)
    {
        // 1. Ambil semua agen sah milik user yang sedang login
        $allMyAgents = Agen::where('user_id', auth()->id())->get();
        $myAgentIds = $allMyAgents->pluck('id_wazuh_agen')->toArray();
        $defaultAgentId = $allMyAgents->first()->id_wazuh_agen ?? null;

        // 2. Baca ID agen dari COOKIE (bukan session lagi)
        $activeAgentId = $request->cookie('active_wazuh_agent_id', $defaultAgentId);

        // validasi: jika ID agen dari cookie tidak ada di daftar agen milik user, paksa ganti ke default
        if ($activeAgentId && !in_array($activeAgentId, $myAgentIds)) {
            $activeAgentId = $defaultAgentId;
            // Paksa timpa cookie yang ilegal dengan ID default yang aman
            Cookie::queue('active_wazuh_agent_id', $defaultAgentId, 60 * 24);
        }

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