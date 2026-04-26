<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WazuhApiService
{
    private $url;
    private $user;
    private $pass;

    public function __construct()
    {
        $this->url  = config('services.wazuh.url');
        $this->user = config('services.wazuh.user');
        $this->pass = config('services.wazuh.pass');
    }

    public function token()
    {
        $response = Http::withoutVerifying()
            ->timeout(30)
            ->withBasicAuth($this->user, $this->pass)
            ->get($this->url . '/security/user/authenticate?raw=true');

        return $response->body();
    }

    public function agents()
    {
        $token = $this->token();

        return Http::withoutVerifying()
            ->timeout(30)
            ->withToken($token)
            ->get($this->url . '/agents')
            ->json();
    }

    public function logs()
    {
        $token = $this->token();

        return Http::withoutVerifying()
            ->timeout(30)
            ->withToken($token)
            ->get($this->url . '/manager/logs?limit=500')
            ->json();
    }

    public function alerts()
    {
        $token = $this->token();

        return Http::withoutVerifying()
            ->timeout(30)
            ->withToken($token)
            ->get($this->url . '/security/events')
            ->json();
    }

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