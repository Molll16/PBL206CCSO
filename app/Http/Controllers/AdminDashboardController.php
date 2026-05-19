<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;

class AdminDashboardController extends Controller
{
    // =========================================
    // DASHBOARD ADMIN
    // =========================================
    public function index(WazuhApiService $wazuh)
    {
        // =========================================
        // AMBIL DATA DARI WAZUH
        // =========================================

        // data semua agent
        $agentsResponse = $wazuh->agents();

        // data alert dari OpenSearch
        $alerts = $wazuh->getAlerts();


        // =========================================
        // CEK ERROR KONEKSI API
        // =========================================
        if (!empty($agentsResponse['error'])) {

            return view('Admin.dashboard', [
                'error'        => 'Server monitoring sedang tidak tersedia',

                // fallback data kosong
                'agents'       => [],
                'active'       => 0,
                'pending'      => 0,
                'disconnected' => 0,
                'never'        => 0,
                'chartLabels'  => [],
                'chartData'    => [],
                'totalAlerts'  => 0,
            ]);
        }


        // =========================================
        // AMBIL LIST AGENT
        // =========================================
        $agents = $agentsResponse['data']['affected_items'] ?? [];


        // =========================================
        // UBAH MENJADI COLLECTION
        // =========================================
        $collection = collect($agents);


        // =========================================
        // HITUNG STATUS AGENT
        // =========================================

        // agent online
        $active = $collection
            ->where('status', 'active')
            ->count();

        // agent pending
        $pending = $collection
            ->where('status', 'pending')
            ->count();

        // agent disconnected
        $disconnected = $collection
            ->where('status', 'disconnected')
            ->count();

        // agent never connected
        $never = $collection
            ->where('status', 'never_connected')
            ->count();


        // =========================================
        // DATA CHART ALERT 7 HARI
        // =========================================
        $chartLabels = [];
        $chartData   = [];


        // looping 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {

            // format tanggal
            $date = now()
                ->subDays($i)
                ->format('Y-m-d');

            // format label chart
            $label = now()
                ->subDays($i)
                ->format('D');


            // hitung total alert per hari
            $total = collect($alerts)

                ->filter(function ($item) use ($date) {

                    return isset($item['time']) &&
                           str_contains($item['time'], $date);
                })

                ->count();


            // masukkan label chart
            $chartLabels[] = $label;

            // masukkan jumlah alert
            $chartData[] = $total;
        }


        // =========================================
        // TOTAL SELURUH ALERT
        // =========================================
        $totalAlerts = array_sum($chartData);


        // =========================================
        // KIRIM DATA KE VIEW
        // =========================================
        return view('Admin.dashboard', compact(
            'agents',
            'active',
            'pending',
            'disconnected',
            'never',
            'chartLabels',
            'chartData',
            'totalAlerts'
        ));
    }
}