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
                ->timeout(3)
                ->withBasicAuth($this->user, $this->pass)
                ->get("{$this->url}/security/user/authenticate?raw=true")
                ->body();
        });
    }

    // ================================
    // HELPER: REQUEST KE WAZUH API
    // ================================
    private function request(string $endpoint)
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
                'message' => 'Tidak dapat terhubung ke server Wazuh'
            ];
        }
    }

    // ============================================== //
    //      DATA DARI PORT 55000 (WAZUH-MANAGER)      //
    // ============================================== //

    // ================================
    // DATA AGENTS
    // ================================
    public function agents()
    {
        return $this->request('/agents');
    }

    // ================================
    // DATA ALERTS (WAZUH API)
    // ================================
    public function alerts()
    {
        return $this->request('/security/events');
    }

    // =============================================== //
    //  DATA DARI PORT 9200 (WAZUH-INDEXER/OPENSEARCH) //
    // =============================================== //

    // ================================
    // RAW ALERTS (INDEXER 9200)
    // ================================
    public function getRawAlerts()
    {
        try {

            $url = config('services.indexer.url');
            $user = config('services.indexer.user');
            $pass = config('services.indexer.pass');

            return Http::withoutVerifying()
                ->timeout(3)
                ->withBasicAuth($user, $pass)
                ->post("{$url}/wazuh-alerts-*/_search", [
                    'size' => 50,
                    'sort' => [
                        ['@timestamp' => ['order' => 'desc']]
                    ]
                ])
                ->json();

        } catch (\Throwable $e) {

            return [
                'error' => true,
                'message' => 'Indexer Wazuh offline'
            ];
        }
    }
}