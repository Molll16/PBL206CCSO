<?php

namespace App\Services;

use App\Models\Agen;
use App\Services\WazuhApiService;
class AlertService
{
    protected $wazuh;

    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    // ================================
    // MAPPING ALERTS
    // ================================
    private function mapAlerts($data)
    {
        $results = [];

        if (!isset($data['hits']['hits'])) {
            return [];
        }

        foreach ($data['hits']['hits'] as $item) {

            $source = $item['_source'];

            $results[] = [
                'description' =>
                    $source['rule']['description'] ?? '-',

                'level' =>
                    $source['rule']['level'] ?? 0,

                'agent' => [
                    'name' =>
                        $source['agent']['name'] ?? 'unknown',

                    'id' =>
                        $source['agent']['id'] ?? null
                ],

                'time' =>
                    $source['@timestamp'] ?? null,

                'user' =>
                    $source['data']['srcuser']
                    ?? $source['data']['dstuser']
                    ?? 'unknown',
            ];
        }

        return $results;
    }

    // ================================
    // FINAL ALERTS
    // ================================
    public function getAlerts()
    {
        $raw = $this->wazuh->getRawAlerts();

        if (!empty($raw['error'])) {
            return [];
        }

        return $this->mapAlerts($raw);
    }

    // ================================================== //
    // Untuk mendapatkan 5 alert terbaru untuk dashboard  //
    // ================================================== //
    public function getLatestAlerts($limit = 5)
    {
        // ambil agent milik user login
        $myAgents = Agen::where(
            'user_id',
            auth()->id()
        )
            ->pluck('id_wazuh_agen')
            ->toArray();

        // ambil semua alerts
        $alerts = collect(
            $this->getAlerts()
        );

        // filter alert berdasarkan agent user
        return $alerts

            ->filter(function ($alert) use ($myAgents) {

                return in_array(
                    $alert['agent']['id'] ?? null,
                    $myAgents
                );
            })

            ->take($limit);
    }

    // ================================
    // TODAY ALERTS
    // ================================
    public function getTodayAlerts()
    {
        // agent milik user
        $myAgents = Agen::where(
            'user_id',
            auth()->id()
        )
            ->pluck('id_wazuh_agen')
            ->toArray();

        // tanggal hari ini
        $today = now()->format('Y-m-d');

        // semua alerts
        $alerts = collect(
            $this->getAlerts()
        );

        return $alerts

            // filter agent
            ->filter(function ($alert) use ($myAgents) {

                return in_array(
                    $alert['agent']['id'] ?? null,
                    $myAgents
                );
            })

            // filter hari ini
            ->filter(function ($alert) use ($today) {

                return isset($alert['time']) &&
                    str_contains($alert['time'], $today);
            })

            // urut terbaru
            ->sortByDesc('time')

            ->values();
    }
}