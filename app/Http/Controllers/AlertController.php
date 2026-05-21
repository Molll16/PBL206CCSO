<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;
use App\Models\Agen;

class AlertController extends Controller
{
    public function getAlerts(WazuhApiService $wazuh)
    {
        // ambil id agent milik user login
        $myAgents = Agen::where('user_id', auth()->id())
            ->pluck('id_wazuh_agen')
            ->toArray();

        // ambil semua alerts
        $alerts = collect($wazuh->getAlerts());

        // filter alerts berdasarkan agent user
        $filterAlerts = $alerts->filter(function ($alert) use ($myAgents) {
            return in_array($alert['agent']['id'] ?? null, $myAgents);
        });

        // ambil 5 terbaru
        return $filterAlerts->take(5);
    }
}