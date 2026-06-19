<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WazuhApiService
{
    private $url;
    private $user;
    private $pass;

    public function __construct()
    {
        // Ambil kredensial server Wazuh dari config/services.php
        $this->url = config('services.wazuh.url');
        $this->user = config('services.wazuh.user');
        $this->pass = config('services.wazuh.pass');
    }

    // Fungsi untuk mengambil token keamanan dari API Wazuh
    // Token disimpan di cache selama 15 menit biar gak boros request
    public function token(): string
    {
        return Cache::remember('wazuh_token', 900, function () {
            return Http::withoutVerifying()
                ->timeout(3)
                ->withBasicAuth($this->user, $this->pass)
                ->get("{$this->url}/security/user/authenticate?raw=true")
                ->body();
        });
    }

    // Fungsi internal untuk mengirim request GET ke API Wazuh Manager (Port 55000)
    private function request(string $endpoint): array
    {
        try {
            $token = $this->token();

            return Http::withoutVerifying()
                ->timeout(3)
                ->withToken($token)
                ->get("{$this->url}{$endpoint}")
                ->json();

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'Gagal terhubung ke API Manager Wazuh'
            ];
        }
    }

    // Fungsi publik untuk menjembatani request dari service lain
    public function get(string $endpoint): array
    {
        $result = $this->request($endpoint);

        return is_array($result) ? $result : [];
    }

    // Mengambil semua daftar agen yang terdaftar di sistem Wazuh
    public function agents(): array
    {
        return $this->request('/agents');
    }

    // Mengambil log security events umum langsung dari manager
    public function alerts(): array
    {
        return $this->request('/security/events');
    }

    // Mengambil data paket dan proses dari modul Syscollector milik agen tertentu
    public function getAgentServices(string $agentId): array
    {
        try {
            $agentId = str_pad($agentId, 3, '0', STR_PAD_LEFT);
            $response = $this->get("/syscollector/{$agentId}/packages?limit=500");

            return $response['data']['affected_items'] ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    // Mengambil data log alert mentah dari database Wazuh Indexer (Port 9200)
    // Rentang waktu yang ditarik adalah 14 hari ke belakang dengan limit 500 baris
    public function getRawAlerts(): array
    {
        try {
            $url = config('services.indexer.url');
            $user = config('services.indexer.user');
            $pass = config('services.indexer.pass');

            return Http::withoutVerifying()
                ->timeout(5)
                ->withBasicAuth($user, $pass)
                ->post("{$url}/wazuh-alerts-*/_search", [
                    'size' => 500,
                    'sort' => [
                        ['@timestamp' => ['order' => 'desc']]
                    ],
                    'query' => [
                        'range' => [
                            '@timestamp' => [
                                'gte' => 'now-14d/d',
                                'lte' => 'now'
                            ]
                        ]
                    ]
                ])
                ->json();

        } catch (\Throwable $e) {
            return [
                'error' => true,
                'message' => 'Indexer Wazuh offline: ' . $e->getMessage()
            ];
        }
    }
}