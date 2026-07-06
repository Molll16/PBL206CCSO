<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AlertService;
use App\Models\Agen;
use Illuminate\Pagination\LengthAwarePaginator;

class AlertController extends Controller
{
    protected $alertService;

    public function __construct(AlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    // Code ini untuk: Mengambil ID agen Wazuh yang sedang aktif dipantau (Mendukung opsi 'all').
    // Berfungsi untuk: Pengecekan session internal (Helper).
    private function getActiveAgentId()
    {
        $userId = auth()->id();
        // Ambil semua agen milik user yang sedang login saat ini
        $myAgents = Agen::where('user_id', $userId)->get();

        if ($myAgents->isEmpty()) {
            return null; // Jaga-jaga kalau user belum punya agen sama sekali
        }

        $myAgentIds = $myAgents->pluck('id_wazuh_agen')->toArray();
        $lastViewedAgent = session('active_wazuh_agent_id');

        // KUNCI PENGAMAN: Jika ada session agen terakhir, dan ID tersebut MEMANG milik user ini
        if ($lastViewedAgent && in_array($lastViewedAgent, $myAgentIds)) {
            return $lastViewedAgent; // Gunakan agen terakhir yang dia lihat
        }

        // FALLBACK: Jika ganti akun (session agen lama bukan milik user ini) atau session kosong
        // Set session baru ke agen pertama milik user tersebut
        $defaultAgent = $myAgents->first()->id_wazuh_agen;
        session(['active_wazuh_agent_id' => $defaultAgent]);

        return $defaultAgent;
    }

    // Code ini untuk: Mengambil 5 data log alert terbaru berdasarkan agen yang aktif.
    // Berfungsi untuk: Endpoint API internal.
    public function getAlerts()
    {
        $activeAgentId = $this->getActiveAgentId();

        return $this->alertService->getLatestAlerts(5, $activeAgentId);
    }

    // Code ini untuk: Mengambil log terfilter, kalkulasi statistik card, dan memproses pagination.
    // Berfungsi untuk: Menampilkan halaman utama menu "Alert page" di sisi Customer.
    public function index(Request $request)
    {
        // 1. Ambil ID/Array ID agen aktif dari session khusus milik user login
        $activeAgentId = $this->getActiveAgentId();

        // 2. Menangkap parameter request filter dari element HTML Form di Blade
        $selectedSeverity = $request->input('severity');

        // 3. Ambil data hitungan angka counter card statistik
        $analyticsData = $this->alertService->getLogsAnalytics($activeAgentId);

        // 4. Ambil data baris log yang sudah disaring berdasarkan severity
        $allFilteredAlerts = $this->alertService->getFilteredAlerts($activeAgentId, $selectedSeverity);

        // 5. Membuat pagination manual dari data array (20 baris per halaman)
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

        // 6. Mengirim data statistik dan hasil paginasi ke view Blade
        return view('Customer.logs.daftarlog', array_merge($analyticsData, [
            'alerts' => $paginatedAlerts,
            'selectedSeverity' => $selectedSeverity
        ]));
    }

    // ==========================================
    // 4. FITUR SWITCH AGENT LOGIC
    // ==========================================
    // Code ini untuk: Mengubah session target ID agen yang aktif dipantau.
    public function switchAgent(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|string'
        ]);

        if ($request->agent_id === 'all') {
            session(['active_wazuh_agent_id' => 'all']);
            return back()->with('success', 'Berhasil beralih ke semua agen.');
        }

        $isValidAgent = Agen::where('user_id', auth()->id())
            ->where('id_wazuh_agen', $request->agent_id)
            ->exists();

        if ($isValidAgent) {
            session(['active_wazuh_agent_id' => $request->agent_id]);
            return back()->with('success', 'Berhasil beralih ke agen ' . $request->agent_id);
        }

        return back()->with('error', 'Akses agen tidak diizinkan.');
    }
}