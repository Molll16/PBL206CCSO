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
        $this->url = config('services.wazuh.url');
        $this->user = config('services.wazuh.user');
        $this->pass = config('services.wazuh.pass');
    }

    public function token()
    {
        $response = Http::withoutVerifying()
            ->withBasicAuth($this->user, $this->pass)
            ->get($this->url . '/security/user/authenticate?raw=true');

        return $response->body();
    }

    public function agents()
    {
        $token = $this->token();

        return Http::withoutVerifying()
            ->withToken($token)
            ->get($this->url . '/agents')
            ->json();
    }

    public function logs()
    {
        $token = $this->token();

        return Http::withoutVerifying()
            ->withToken($token)
            ->get($this->url . '/manager/logs')
            ->json();
    }

    public function alerts()
    {
        $token = $this->token();

        return Http::withoutVerifying()
            ->withToken($token)
            ->get($this->url . '/alerts')
            ->json();
    }
}