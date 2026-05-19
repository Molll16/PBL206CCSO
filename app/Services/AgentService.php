<?php

namespace App\Services;

use App\Models\Agen;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AgentService
{
    // =========================================
    // AMBIL SEMUA AGENT DARI WAZUH
    // =========================================
    public function getAgents(WazuhApiService $wazuh)
    {
        $response = $wazuh->agents();

        return $response['data']['affected_items'] ?? [];
    }

    // =========================================
    // AMBIL AGENT YANG BELUM DIASSIGN
    // =========================================
    public function getAvailableAgents(WazuhApiService $wazuh)
    {
        // semua agent
        $agents = $this->getAgents($wazuh);

        // agent yang sudah dipakai
        $assignedAgents = Agen::pluck('id_wazuh_agen')->toArray();

        // filter agent yang belum diassign
        return collect($agents)
            ->whereNotIn('id', $assignedAgents)
            ->values()
            ->toArray();
    }

    // =========================================
    // STATISTIK AGENT CUSTOMER
    // =========================================
    public function getStats(WazuhApiService $wazuh)
    {
        // agent milik user login
        $myAgents = Agen::where('user_id', auth()->id())
            ->pluck('id_wazuh_agen')
            ->toArray();

        // semua agent dari wazuh
        $agents = $this->getAgents($wazuh);

        // filter agent user
        $list = collect($agents)
            ->whereIn('id', $myAgents);

        // hitung statistik
        $online = $list->where('status', 'active')->count();
        $offline = $list->where('status', '!=', 'active')->count();

        return [
            'online' => $online,
            'offline' => $offline,
            'total' => $list->count(),
        ];
    }

    // =========================================
    // STATISTIK CUSTOMER
    // =========================================
    public function getCustomerStats()
    {
        $users = User::where('role', 'customer')
            ->withCount('agents')
            ->get();

        return [
            'users' => $users,
            'totalUsers' => $users->count(),
            'totalAssignedAgents' => $users->sum('agents_count'),
        ];
    }
}