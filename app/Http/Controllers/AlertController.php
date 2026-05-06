<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;

class AlertController extends Controller
{
    public function getAlerts(WazuhApiService $wazuh)
    {
        $response = $wazuh->alerts();

        if (!isset($response['data']['affected_items'])) {
            return collect([]);
        }

        return collect($response['data']['affected_items'])
            ->take(5)
            ->map(function ($alert) {

                return [
                    'description' => $alert['rule']['description'] ?? '-',
                    'agent'       => $alert['agent']['name'] ?? '-',
                    'time'        => $alert['timestamp'] ?? '-',
                    'level'       => $alert['rule']['level'] ?? 0,
                ];
            });
    }
}