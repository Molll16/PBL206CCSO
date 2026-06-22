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

    // Code ini untuk: Mengambil ID agen Wazuh yang sedang aktif dipantau.
    // Berfungsi untuk: Pengecekan session internal (Helper).
    private function getActiveAgentId(): ?string
    {
        $allMyAgents = Agen::where('user_id', auth()->id())->get();

        return session(
            'active_wazuh_agent_id',
            $allMyAgents->first()->id_wazuh_agen ?? null
        );
    }

    // Code ini untuk: Mengambil 5 data log alert terbaru berdasarkan agen yang aktif.
    // Berfungsi untuk: Endpoint API internal.
    public function getAlerts()
    {
        $activeAgentId = $this->getActiveAgentId();

        return $this->alertService->getLatestAlerts(5, $activeAgentId);
    }

    // Code ini untuk: Mengambil log terfilter, kalkulasi statistik card harian, dan memproses pagination.
    // Berfungsi untuk: Menampilkan halaman utama menu "Logs / Filter & Analytics" di sisi Customer.
    public function index(Request $request)
    {
        // 1. Ambil ID agen aktif dari session
        $activeAgentId = $this->getActiveAgentId();

        // 2. Menangkap parameter request filter dari element HTML Form di Blade
        $selectedDate = $request->input('date', now()->format('Y-m-d'));
        $selectedSeverity = $request->input('severity');

        // 3. Ambil data hitungan angka counter card statistik berdasarkan tanggal terpilih
        $analyticsData = $this->alertService->getLogsAnalytics($activeAgentId, $selectedDate);

        // 4. Ambil data baris log yang sudah disaring berdasarkan tanggal dan severity
        $allFilteredAlerts = $this->alertService->getFilteredAlerts($activeAgentId, $selectedDate, $selectedSeverity);

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
            'selectedDate' => $selectedDate,
            'selectedSeverity' => $selectedSeverity
        ]));
    }
}