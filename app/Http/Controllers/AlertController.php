<?php

namespace App\Http\Controllers;

use App\services\WazuhApiService;
use App\Services\AlertService;
use App\Models\Agen;

class AlertController extends Controller
{
    public function getAlerts(
        AlertService $alertService
    )
    {
        return $alertService->getLatestAlerts();
    }

    public function index(AlertService $alertService) 
{
    // Mengubah array mentah dari service menjadi objek Collection Laravel
    $alerts = collect($alertService->getTodayAlerts())->map(function($alert) {
        // Memastikan field level dibaca sebagai integer, bukan string
        $alert['level'] = (int) ($alert['level'] ?? 0);
        return $alert;
    });

    return view('Customer.logs.daftarlog', [
        'alerts'         => $alerts,
        'totalAlerts'    => $alerts->count(),
        'criticalAlerts' => $alerts->where('level', '>=', 13)->count(),
        'highAlerts'     => $alerts->whereBetween('level', [10, 12])->count(),
        'mediumAlerts'   => $alerts->whereBetween('level', [5, 9])->count(),
        'lowAlerts'      => $alerts->where('level', '<', 5)->count(),
    ]);
}
}