<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;

class AdminDashboardController extends Controller
{
    public function index(WazuhApiService $wazuh)
    {
        $agents = $wazuh->agents();

        $items = $agents['data']['affected_items'] ?? [];

        $alerts = $wazuh->logs();
        $alertItems = $alerts['data']['affected_items'] ?? [];
        $active = collect($items)->where('status', 'active')->count();
        $pending = collect($items)->where('status', 'pending')->count();
        $disconnected = collect($items)->where('status', 'disconnected')->count();
        $never = collect($items)->where('status', 'never_connected')->count();

        $chartLabels = [];
        $chartData = [];
        
        for ($i = 6; $i >= 0; $i--) {
        
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('D');
        
            $total = collect($alertItems)->filter(function ($item) use ($date) {
                return isset($item['timestamp']) &&
                       str_contains($item['timestamp'], $date);
            })->count();
        
            $chartLabels[] = $label;
            $chartData[] = $total;
        }
        
        $totalAlerts = array_sum($chartData);

        return view('Admin.dashboard', compact(
            'items',
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