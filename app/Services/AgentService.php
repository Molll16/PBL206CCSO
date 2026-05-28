<?php

namespace App\Services;

use App\Models\Agen;
use App\Models\User;

class AgentService
{
    protected $wazuh;

    // CODE: Koneksi awal ke API Wazuh.
    // UNTUK: Biar semua fungsi di bawah bisa otomatis ngobrol sama server Wazuh.
    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    // CODE: Ambil semua data agen mentah dari Wazuh.
    // UNTUK: Sumber data utama, gak muncul di web tapi dipakai fungsi lain.
    public function getAgents()
    {
        $response = $this->wazuh->agents();
        return $response['data']['affected_items'] ?? [];
    }

    // CODE: Filter agen yang BELUM dipakai sama customer manapun.
    // WEB: Halaman Admin -> Menu "Assign Agent" (Dropdown pilihan).
    // UNTUK: Biar Admin gak double/salah masukin agen yang udah punya orang lain.
    public function getAvailableAgents()
    {
        $agents = $this->getAgents();
        $assignedAgents = Agen::pluck('id_wazuh_agen')->toArray();

        return collect($agents)
            ->whereNotIn('id', $assignedAgents)
            ->values()
            ->toArray();
    }

    // CODE: Hitung status agen (Active, Pending, Disconnected, Never).
    // WEB: Dashboard Utama milik ADMIN.
    // UNTUK: Ngisi angka di 4 kotak chart/metrik paling atas di layar Admin.
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

    // CODE: Ambil agen milik Customer yang lagi login, lalu cek mana yang online/offline.
    // WEB: Dashboard Utama CUSTOMER & Widget Kustomisasi "Agent Status".
    // UNTUK: Menampilkan jumlah server customer yang hidup (Online) atau mati (Offline).
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

    // CODE: Ambil data semua Customer sekaligus hitung total agen mereka.
    // WEB: Halaman Admin -> Menu "Agents List" (Tabel Manajemen User).
    // UNTUK: Menampilkan tabel relasi (User ini punya berapa agen) dan total user/agen di sistem.
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