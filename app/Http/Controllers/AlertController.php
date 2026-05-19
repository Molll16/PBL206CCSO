<?php

namespace App\Http\Controllers;

use App\Services\WazuhApiService;

class AlertController extends Controller
{
    public function getAlerts(WazuhApiService $wazuh)
    {
        return collect($wazuh->getAlerts())
            ->take(5);
    }
}