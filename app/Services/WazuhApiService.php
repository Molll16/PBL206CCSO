<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WazuhApiService
{
    // ================================
    // KONFIGURASI DASAR
    // ================================
    private $url;
    private $user;
    private $pass;

    public function __construct()
    {
        // Ambil konfigurasi dari config/services.php
        $this->url  = config('services.wazuh.url');
        $this->user = config('services.wazuh.user');
        $this->pass = config('services.wazuh.pass');
    }

    // ================================
    // AUTHENTICATION (AMBIL TOKEN)
    // ================================
    public function token()
    {
        return Cache::remember('wazuh_token', 900, function () {
            return Http::withoutVerifying()
                ->timeout(30)
                ->withBasicAuth($this->user, $this->pass)
                ->get($this->url . '/security/user/authenticate?raw=true')
                ->body();
        });
    }

    // ================================
    // HELPER: REQUEST DENGAN TOKEN
    // ================================
    private function request($endpoint)
    {
        $token = $this->token();

        return Http::withoutVerifying()
            ->timeout(30)
            ->withToken($token)
            ->get($this->url . $endpoint)
            ->json();
    }

    // ================================
    // DATA AGENTS
    // ================================
    public function agents()
    {
        return $this->request('/agents');
    }

    // ================================
    // DATA LOGS
    // ================================
    public function logs()
    {
        return $this->request('/manager/logs?limit=500');
    }

    // ================================
    // DATA ALERTS (WAZUH API)
    // ================================
    public function alerts()
    {
        return $this->request('/security/events');
    }

    // ================================
    // DATA ALERTS (INDEXER)
    // ================================
    public function alertsFromIndexer()
    {
        $url  = config('services.indexer.url');
        $user = config('services.indexer.user');
        $pass = config('services.indexer.pass');

        return Http::withoutVerifying()
            ->timeout(30)
            ->withBasicAuth($user, $pass)
            ->post($url . '/wazuh-alerts-*/_search', [
                'size' => 1000
            ])
            ->json();
    }
}