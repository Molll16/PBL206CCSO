<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class WazuhApiService
{
    private string $url;
    private string $user;
    private string $pass;

    public function __construct()
    {
        $this->url = config('services.wazuh.url');
        $this->user = config('services.wazuh.user');
        $this->pass = config('services.wazuh.pass');
    }

    // Mengambil token keamanan (Cached 15 menit)
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

    // Helper internal untuk request ke Wazuh Manager (Port 55000)
    public function get(string $endpoint): array
    {
        try {
            return Http::withoutVerifying()
                ->timeout(3)
                ->withToken($this->token())
                ->get("{$this->url}{$endpoint}")
                ->json() ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    // Mengambil daftar seluruh agen dari Wazuh Manager
    public function agents(): array
    {
        return $this->get('/agents');
    }

    /**
     * Mengambil data log alert dari Wazuh Indexer (Port 9200) dengan query dinamis.
     * Mengeliminasi penarikan data berlebih (Memory Exhaustion).
     */
    public function getRawAlerts(array $agentIds = [], ?string $date = null): array
    {
        try {
            $url = config('services.indexer.url');
            $user = config('services.indexer.user');
            $pass = config('services.indexer.pass');

            // Set query dasar: rentang waktu 14 hari ke belakang
            $mustQueries = [
                ['range' => ['@timestamp' => ['gte' => 'now-14d/d', 'lte' => 'now']]]
            ];

            // Tambahkan filter tanggal jika spesifik diminta
            if ($date) {
                $mustQueries[] = ['wildcard' => ['@timestamp' => "*{$date}*"]];
            }

            // Tambahkan filter multi-agent ID agar data tidak bocor antar-customer
            if (!empty($agentIds)) {
                $mustQueries[] = ['terms' => ['agent.id' => $agentIds]];
            }

            return Http::withoutVerifying()
                ->timeout(5)
                ->withBasicAuth($user, $pass)
                ->post("{$url}/wazuh-alerts-*/_search", [
                    'size' => 500,
                    'sort' => [['@timestamp' => ['order' => 'desc']]],
                    'query' => ['bool' => ['must' => $mustQueries]]
                ])
                ->json() ?? [];

        } catch (\Throwable $e) {
            return ['error' => true, 'message' => 'Wazuh Indexer Offline'];
        }
    }
}