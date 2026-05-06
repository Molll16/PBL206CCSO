<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\WazuhApiService;

class AdminDashboardController extends Controller
{
    // ================================
    // DASHBOARD ADMIN
    // ================================
    public function index(WazuhApiService $wazuh)
    {
        // ===== AMBIL DATA DARI API =====
        $agentsResponse = $wazuh->agents();
        $alertsResponse = $wazuh->alerts();

        // ===== CEK ERROR API =====
        if (!empty($agentsResponse['error']) || !empty($alertsResponse['error'])) {
            return view('Admin.dashboard', [
                'error'        => 'Server monitoring sedang tidak tersedia',
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

        // ===== AMBIL DATA =====
        $agents = $agentsResponse['data']['affected_items'] ?? [];
        $alerts = $alertsResponse['data']['affected_items'] ?? [];

        $collection = collect($agents);

        // ===== STATISTIK AGENT =====
        $active        = $collection->where('status', 'active')->count();
        $pending       = $collection->where('status', 'pending')->count();
        $disconnected  = $collection->where('status', 'disconnected')->count();
        $never         = $collection->where('status', 'never_connected')->count();

        // ===== CHART ALERT (7 HARI) =====
        $chartLabels = [];
        $chartData   = [];

        for ($i = 6; $i >= 0; $i--) {

            $date  = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('D');

            $total = collect($alerts)->filter(function ($item) use ($date) {
                return isset($item['timestamp']) || isset($item['@timestamp']) &&
                       str_contains($item['timestamp'], $date);
            })->count();

            $chartLabels[] = $label;
            $chartData[]   = $total;
        }

        $totalAlerts = array_sum($chartData);

        // ===== RETURN VIEW =====
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