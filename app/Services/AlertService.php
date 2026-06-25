<?php

namespace App\Services;

use App\Models\Agen;

class AlertService
{
    protected $wazuh;

    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    // =========================================================================
    // HELPER INTERNAL
    // =========================================================================

    // Code ini untuk: Mengambil semua data log mentah dari server Wazuh.
    // Berfungsi untuk: Sumber data internal (tidak terikat halaman spesifik).
    public function getAlerts(): array
    {
        $raw = $this->wazuh->getRawAlerts();
        return empty($raw['error']) ? $this->mapAlerts($raw) : [];
    }

    // Code ini untuk: Menyaring dokumen JSON Wazuh ke array sederhana.
    // Berfungsi untuk: Pembersihan data internal.
    private function mapAlerts($data): array
    {
        if (!isset($data['hits']['hits'])) {
            return [];
        }

        $results = [];
        foreach ($data['hits']['hits'] as $item) {
            $source = $item['_source'] ?? [];

            $description = $source['rule']['description'] ??
                $source['rule']['title'] ??
                $source['data']['win']['eventdata']['targetUserName'] ??
                $source['data']['win']['system']['message'] ??
                'Unknown Security Event';

            $results[] = [
                'description' => $description,
                'level' => (int) ($source['rule']['level'] ?? 0),
                'agent' => [
                    'name' => $source['agent']['name'] ?? 'unknown',
                    'id' => $source['agent']['id'] ?? null
                ],
                'time' => $source['@timestamp'] ?? null,
                'user' => $source['data']['srcuser'] ?? $source['data']['dstuser'] ?? 'unknown',
            ];
        }
        return $results;
    }

    // Code ini untuk: Mencari daftar ID agen milik user yang sedang login.
    // Berfungsi untuk: Proteksi dan filter multi-tenant internal.
    private function getMyAgentIds(?string $agentId = null): array
    {
        if ($agentId && $agentId !== 'all') {
            return [str_pad($agentId, 3, '0', STR_PAD_LEFT)];
        }

        $currentUserId = auth()->user()->id ?? null;

        return Agen::where('user_id', $currentUserId)
            ->get()
            ->pluck('id_wazuh_agen')
            ->map(fn($id) => str_pad((string) $id, 3, '0', STR_PAD_LEFT))
            ->toArray();
    }

    // =========================================================================
    // HALAMAN: DASHBOARD CUSTOMER
    // =========================================================================

    // Code ini untuk: Mengambil 5 log keamanan terbaru.
    // Berfungsi untuk: Halaman Dashboard Customer, bagian widget "Alert Summary".
    public function getLatestAlerts(int $limit = 5, ?string $agentId = null)
    {
        $myAgents = $this->getMyAgentIds($agentId);

        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->take($limit);
    }

    // Code ini untuk: Menghitung status tingkat bahaya log dan top 5 kategori ancaman.
    // Berfungsi untuk: Halaman Dashboard Customer, bagian widget "Threat Summary" (Donut Chart & List).
    public function getThreatSummary(?string $agentId = null): array
    {
        $myAgents = $this->getMyAgentIds($agentId);
        $alerts = collect($this->getAlerts())->filter(fn($a) => in_array($a['agent']['id'] ?? null, $myAgents));
        $totalEvents = $alerts->count();

        $activeCount = $alerts->where('level', '>=', 10)->count();
        $pendingCount = $alerts->whereBetween('level', [5, 9])->count();
        $resolvedCount = $alerts->where('level', '<', 5)->count();

        $categories = $totalEvents === 0 ? [] : $alerts->groupBy('description')->map(function ($items, $name) use ($totalEvents) {
            $maxLevel = $items->max('level') ?? 0;
            return [
                'name' => $name,
                'count' => $items->count(),
                'percentage' => round(($items->count() / $totalEvents) * 100),
                'severity' => $maxLevel >= 10 ? 'high' : ($maxLevel >= 5 ? 'medium' : 'low'),
            ];
        })->sortByDesc('count')->take(5)->values()->toArray();

        return [
            'active' => $activeCount,
            'pending' => $pendingCount,
            'resolved' => $resolvedCount,
            'categories' => $categories
        ];
    }

    // Code ini untuk: Mencari 3 aturan keamanan yang paling sering terpicu.
    // Berfungsi untuk: Halaman Dashboard Customer, bagian widget "Most Active Rules" (Progress Bar).
    public function getMostActiveRules(?string $agentId = null): array
    {
        try {
            $myAgents = $this->getMyAgentIds($agentId);
            $alerts = collect($this->getAlerts())->filter(fn($a) => in_array($a['agent']['id'] ?? null, $myAgents));

            if ($alerts->count() === 0) {
                return [];
            }

            $groupedRules = $alerts->groupBy('description')->map(function ($items, $description) {
                return [
                    'desc' => $description,
                    'count' => $items->count(),
                    'level' => $items->first()['level'] ?? 0
                ];
            })->sortByDesc('count')->take(3)->values();

            $maxCount = $groupedRules->first()['count'] ?? 1;

            return $groupedRules->map(function ($rule) use ($maxCount) {
                $color = $rule['level'] >= 10 ? 'bg-red-500' : ($rule['level'] < 5 ? 'bg-orange-500' : 'bg-amber-500');
                return [
                    'desc' => $rule['desc'],
                    'count' => $rule['count'],
                    'w' => round(($rule['count'] / $maxCount) * 100) . '%',
                    'color' => $color
                ];
            })->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    // =========================================================================
    // HALAMAN: LOGS / FILTER & ANALYTICS
    // =========================================================================

    // Code ini untuk: Memfilter log berdasarkan agen, dan tingkat keparahan (severity).
    // Berfungsi untuk: Halaman Alert Customer, bagian tabel utama "Daftar Log/Alerts".
    public function getFilteredAlerts(?string $agentId = null, ?string $severity = null)
    {
        $myAgents = $this->getMyAgentIds($agentId);

        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->filter(function ($alert) use ($severity) {
                if (!$severity)
                    return true;
                if ($severity === 'critical')
                    return $alert['level'] >= 13;
                if ($severity === 'high')
                    return $alert['level'] >= 10 && $alert['level'] <= 12;
                if ($severity === 'medium')
                    return $alert['level'] >= 5 && $alert['level'] <= 9;
                return $severity === 'low' ? $alert['level'] < 5 : true;
            })
            ->sortByDesc('time')
            ->values();
    }

    // Code ini untuk: Menghitung total angka statistik log (Critical, High, Medium, Low).
    // Berfungsi untuk: Halaman Logs/Analytics, bagian card "Card Statistik".
    public function getLogsAnalytics(?string $agentId = null): array
    {
        $allAlerts = $this->getFilteredAlerts($agentId, null);

        return [
            'totalAlerts' => $allAlerts->count(),
            'criticalAlerts' => $allAlerts->where('level', '>=', 13)->count(),
            'highAlerts' => $allAlerts->whereBetween('level', [10, 12])->count(),
            'mediumAlerts' => $allAlerts->whereBetween('level', [5, 9])->count(),
            'lowAlerts' => $allAlerts->where('level', '<', 5)->count(),
        ];
    }

    // =========================================================================
    // HALAMAN: DASHBOARD UTAMA ADMIN
    // =========================================================================

    // Code ini untuk: Menghitung tren total volume log harian selama 7 hari terakhir.
    // Berfungsi untuk: Halaman Dashboard Utama Admin, bagian grafik batang/area chart.
    public function getWeeklyChartData(): array
    {
        $alerts = $this->getAlerts();
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = now()->subDays($i)->format('D');
            $chartData[] = collect($alerts)->filter(fn($item) => isset($item['time']) && str_contains($item['time'], $date))->count();
        }

        return [
            'labels' => $chartLabels,
            'data' => $chartData,
            'total' => array_sum($chartData)
        ];
    }
}