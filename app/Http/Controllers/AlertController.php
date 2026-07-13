<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AlertService;
use App\Models\Agen;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cookie;

class AlertController extends Controller
{
    protected $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    // 🌟 PERBAIKAN: Helper internal sekarang membaca dan memvalidasi COOKIE secara ketat
    private function getActiveAgentId()
    {
        $userId = auth()->id();
        $myAgents = Agen::where('user_id', $userId)->get();

        if ($myAgents->isEmpty()) {
            return null;
        }

        $myAgentIds = $myAgents->pluck('id_wazuh_agen')->toArray();

        // Baca nilai dari COOKIE (bukan session)
        $lastViewedAgent = request()->cookie('active_wazuh_agent_id');

        // Dukung opsi 'all' jika ada di cookie
        if ($lastViewedAgent === 'all') {
            return 'all';
        }

        // VALIDASI KEAMANAN: Cookie dipakai hanya jika ID tersebut memang terdaftar milik user ini
        if ($lastViewedAgent && in_array($lastViewedAgent, $myAgentIds)) {
            return $lastViewedAgent;
        }

        // FALLBACK: Jika cookie kosong atau dimanipulasi, paksa pakai agen pertama milik user
        $defaultAgent = $myAgents->first()->id_wazuh_agen;
        Cookie::queue('active_wazuh_agent_id', $defaultAgent, 60 * 24);

        return $defaultAgent;
    }

    public function getAlerts()
    {
        $activeAgentId = $this->getActiveAgentId();

        return $this->alertService->getLatestAlerts(5, $activeAgentId);
    }

    public function index(Request $request)
    {
        $activeAgentId = $this->getActiveAgentId();
        $selectedSeverity = $request->input('severity');

        // Ambil daftar semua agen milik user untuk dikirim ke select dropdown di Blade
        $list_agen = Agen::where('user_id', auth()->id())->get();

        $analyticsData = $this->alertService->getLogsAnalytics($activeAgentId);
        $allFilteredAlerts = $this->alertService->getFilteredAlerts($activeAgentId, $selectedSeverity);

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20;
        $currentItems = $allFilteredAlerts->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $paginatedAlerts = new LengthAwarePaginator(
            $currentItems,
            $allFilteredAlerts->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 🌟 Pastikan 'list_agen' turut dikirim agar layout header dropdown bisa merendernya
        return view('Customer.logs.daftarlog', array_merge($analyticsData, [
            'alerts' => $paginatedAlerts,
            'selectedSeverity' => $selectedSeverity,
            'list_agen' => $list_agen,
            'activeAgentId' => $activeAgentId
        ]));
    }

    // 🌟 PERBAIKAN: Fungsi switchAgent sekarang menyimpan ke COOKIE dan membuang sisa Session
    public function switchAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|string'
        ]);

        // Bersihkan sisa session lama agar tidak terjadi konflik data
        session()->forget('active_wazuh_agent_id');

        // Skenario 1: Jika memilih melihat semua ('all')
        if ($request->agent_id === 'all') {
            Cookie::queue('active_wazuh_agent_id', 'all', 60 * 24);
            return back()->with('success', 'Successfully switched to all agents.');
        }

        // Skenario 2: Validasi kepemilikan agen sebelum disimpan ke cookie browser
        $isValidAgent = Agen::where('user_id', auth()->id())
            ->where('id_wazuh_agen', $request->agent_id)
            ->exists();

        if ($isValidAgent) {
            Cookie::queue('active_wazuh_agent_id', $request->agent_id, 60 * 24);
            return back()->with('success', 'Successfully switched to agent ' . $request->agent_id);
        }

        return back()->with('error', 'Agent access not allowed.');
    }
}