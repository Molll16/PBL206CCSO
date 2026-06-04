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

    // CODE: Membaca session agen aktif lalu mengirimkannya ke method getLatestAlerts().
    // UNTUK: Mengamankan endpoint API internal agar data log terbaru yang ditarik mengikuti filter agen terpilih.
    public function getAlerts()
    {
        $allMyAgents = Agen::where('user_id', auth()->id())->get();
        $activeAgentId = session('active_wazuh_agent_id', $allMyAgents->first()->id_wazuh_agen ?? null);

        return $this->alertService->getLatestAlerts(5, $activeAgentId);
    }

    // CODE: Menangkap interaksi filter dari user (Kalender & Dropdown Level), serta membagi data halaman (Slide).
    // WEB: Halaman View Logs / Daftar Log Monitoring Utama Customer (`daftarlog.blade.php`).
    // UNTUK: Mengatur logika pembagian lembar tabel (Pagination) maksimal 20 baris per slide agar web tidak lemot dan panjang saat di-scroll.
    public function index(Request $request)
    {
        // 1. Ambil list semua agen kepemilikan customer dari database lokal
        $allMyAgents = Agen::where('user_id', auth()->id())->get();

        // 2. Ambil ID agen aktif dari session. Jika session kosong, jadikan ID agen pertama sebagai cadangan (fallback).
        $activeAgentId = session('active_wazuh_agent_id', $allMyAgents->first()->id_wazuh_agen ?? null);

        // 3. Menangkap parameter request filter dari element HTML Form di Blade (jika ada inputan user)
        $selectedDate = $request->input('date', now()->format('Y-m-d')); // Default otomatis tersetting tanggal hari ini jika kosong
        $selectedSeverity = $request->input('severity'); // Menangkap inputan filter level (Low, Medium, dll)

        // 4. Ambil data hitungan angka counter meteran (Critical, High, dll) berdasarkan tanggal terpilih
        $analyticsData = $this->alertService->getLogsAnalytics($activeAgentId, $selectedDate);

        // 5. Ambil data baris log kasar yang sudah disaring berdasarkan tanggal kalender & tingkat level keparahan
        $allFilteredAlerts = $this->alertService->getFilteredAlerts($activeAgentId, $selectedDate, $selectedSeverity);

        // 6. LOGIKA SLIDE / PAGINATION MANUAL (Memotong baris koleksi array otomatis per 20 entri data)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 20; // Batas limit baris per halaman
        $currentItems = $allFilteredAlerts->slice(($currentPage - 1) * $perPage, $perPage)->all();

        // Membungkus array potongan tadi agar dibaca Laravel sebagai sistem pagination resmi
        $paginatedAlerts = new LengthAwarePaginator(
            $currentItems,
            $allFilteredAlerts->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 7. Melempar semua paket variabel data olahan di atas menuju file layout HTML Blade
        return view('Customer.logs.daftarlog', array_merge($analyticsData, [
            'alerts' => $paginatedAlerts,
            'selectedDate' => $selectedDate,
            'selectedSeverity' => $selectedSeverity
        ]));
    }
}