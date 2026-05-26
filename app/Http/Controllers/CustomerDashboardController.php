<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;
use App\Models\DasborKustom;
use App\Models\HasilKustom;
use App\Services\AgentService;
use App\Services\AlertService;

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
        // 1. TES KONEKSI UTAMA WAZUH (PING)
        // =========================================
        try {
            // Kita lakukan pengetesan token / koneksi dasar dulu ke wazuh
            // Jika service API kamu punya fungsi getToken() atau sejenisnya, panggil di sini.
            // Jika tidak ada, biarkan kosong atau pakai request ringan ke API.
            $wazuh->Token();
        } catch (\Throwable $e) {
            // Hanya jika API benar-benar tidak merespon/RTO, server ditandai offline
            $wazuhOffline = true;
        }

        // =========================================
        // 2. AMBIL DATA DENGAN TRY-CATCH TERPISAH
        // =========================================
        if (!$wazuhOffline) {

            // Cari bagian ini di Controller:
            try {
                // GANTI YANG LAMA INI:
                // $alerts = $alertController->getAlerts($wazuh);

                // MENJADI INI (Mengambil 5 alert terbaru milik agen si customer):
                $alerts = app(AlertService::class)->getLatestAlerts(5);

            } catch (\Throwable $e) {
                $alerts = [];
            }

            // Ambil Statistik Agent (Jika gagal, jangan buat server kelihatan offline)
            try {
                $agentStats = $agentService->getStats($wazuh);

                $agentOnline = $agentStats['online'] ?? 0;
                $agentOffline = $agentStats['offline'] ?? 0;
                $agentTotal = $agentStats['total'] ?? 0;
            } catch (\Throwable $e) {
                // Jika error, biarkan nilainya tetap default 0, tapi banner merah tidak akan muncul
                $agentOnline = 0;
                $agentOffline = 0;
                $agentTotal = 0;
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
            'wazuhOffline'
        ));
    }
}