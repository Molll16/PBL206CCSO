<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\User;

class AgentService
{
    // ambil semua agent dari wazuh
    public function getAgents(WazuhApiService $wazuh)
    {
        $response = $wazuh->agents();

        return $response['data']['affected_items'] ?? [];
    }

    // statistik agent customer
    public function getStats(WazuhApiService $wazuh)
    {
        // ambil agent milik user
        $myAgents = DB::table('agen')
            ->where('user_id', auth()->id())
            ->pluck('id_wazuh_agen')
            ->toArray();

        // ambil semua agent dari wazuh
        $agents = $wazuh->agents();

        $agentOnline = 0;
        $agentOffline = 0;
        $agentTotal = 0;

        // cek apakah ada data agent
        if (isset($agents['data']['affected_items'])) {

            $list = collect($agents['data']['affected_items']);

            // filter agent milik user
            $list = $list->whereIn('id', $myAgents);

            $agentTotal = $list->count();

            foreach ($list as $agent) {

                if ($agent['status'] == 'active') {
                    $agentOnline++;
                } else {
                    $agentOffline++;
                }
            }
        }

        return [
            'online' => $agentOnline,
            'offline' => $agentOffline,
            'total' => $agentTotal,
        ];
    }

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