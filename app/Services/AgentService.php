<?php

namespace App\Services;

use App\Models\Agen;
use App\Models\User;

class AgentService
{
    protected $wazuh;

    // OOP Constructor Injection: API Wazuh kini otomatis tersedia di semua method
    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    public function getAgents()
    {
        $response = $this->wazuh->agents();
        return $response['data']['affected_items'] ?? [];
    }

    public function getAvailableAgents()
    {
        $agents = $this->getAgents();
        $assignedAgents = Agen::pluck('id_wazuh_agen')->toArray();

        return collect($agents)
            ->whereNotIn('id', $assignedAgents)
            ->values()
            ->toArray();
    }

    /**
     * Mengambil statistik Agen khusus halaman Admin
     * (Pindahan logika dari AdminDashboardController)
     */
    public function getAdminStats(): array
    {
        $agents = collect($this->getAgents());

        return [
            'active' => $agents->where('status', 'active')->count(),
            'pending' => $agents->where('status', 'pending')->count(),
            'disconnected' => $agents->where('status', 'disconnected')->count(),
            'never' => $agents->where('status', 'never_connected')->count(),
            'total' => $agents->count()
        ];
    }

    /**
     * Mengambil statistik Agen khusus milik Customer yang login
     */
    public function getCustomerStats(): array
    {
        $myAgents = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
        $agents = collect($this->getAgents())->whereIn('id', $myAgents);

        return [
            'online' => $agents->filter(fn($a) => strtolower($a['status'] ?? '') === 'active')->count(),
            'offline' => $agents->filter(fn($a) => strtolower($a['status'] ?? '') !== 'active')->count(),
            'total' => $agents->count(),
        ];
    }

    /**
     * Mengambil data relasi Customer dan Agen
     */
    public function getCustomerManagementSummary(): array
    {
        $users = User::where('role', 'customer')->withCount('agents')->get();

        return [
            'users' => $users,
            'totalUsers' => $users->count(),
            'totalAssignedAgents' => $users->sum('agents_count'),
        ];
    }
}