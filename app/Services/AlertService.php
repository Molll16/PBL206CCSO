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

    public function getAlerts(): array
    {
        $raw = $this->wazuh->getRawAlerts();
        if (!empty($raw['error'])) {
            return [];
        }
        return $this->mapAlerts($raw);
    }

    private function mapAlerts($data): array
    {
        if (!isset($data['hits']['hits']))
            return [];

        $results = [];
        foreach ($data['hits']['hits'] as $item) {
            $source = $item['_source'];
            $results[] = [
                'description' => $source['rule']['description'] ?? '-',
                'level' => (int) ($source['rule']['level'] ?? 0), // Enkapsulasi konversi integer di sini
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

    public function getLatestAlerts($limit = 5)
    {
        $myAgents = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->take($limit);
    }

    public function getTodayAlerts()
    {
        $myAgents = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
        $today = now()->format('Y-m-d');

        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->filter(fn($alert) => isset($alert['time']) && str_contains($alert['time'], $today))
            ->sortByDesc('time')
            ->values();
    }

    /**
     * Menganalisis log hari ini untuk menghasilkan summary counter tingkat keparahan
     * (Pindahan logika dari AlertController)
     */
    public function getTodayLogsAnalytics(): array
    {
        $alerts = $this->getTodayAlerts();

        return [
            'alerts' => $alerts,
            'totalAlerts' => $alerts->count(),
            'criticalAlerts' => $alerts->where('level', '>=', 13)->count(),
            'highAlerts' => $alerts->whereBetween('level', [10, 12])->count(),
            'mediumAlerts' => $alerts->whereBetween('level', [5, 9])->count(),
            'lowAlerts' => $alerts->where('level', '<', 5)->count(),
        ];
    }

    /**
     * Membuat data label dan poin chart untuk 7 hari ke belakang
     * (Pindahan logika dari AdminDashboardController)
     */
    public function getWeeklyChartData(): array
    {
        $alerts = $this->getAlerts();
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->format('D');

            $total = collect($alerts)
                ->filter(fn($item) => isset($item['time']) && str_contains($item['time'], $date))
                ->count();

            $chartLabels[] = $label;
            $chartData[] = $total;
        }

        return [
            'labels' => $chartLabels,
            'data' => $chartData,
            'total' => array_sum($chartData)
        ];
    }

    public function getThreatSummary(): array
    {
        $myAgents = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
        $alerts = collect($this->getAlerts())->filter(fn($a) => in_array($a['agent']['id'] ?? null, $myAgents));
        $totalEvents = $alerts->count();

        $activeCount = $alerts->where('level', '>=', 10)->count();
        $pendingCount = $alerts->whereBetween('level', [5, 9])->count();
        $resolvedCount = $alerts->where('level', '<', 5)->count();

        $categories = $totalEvents === 0 ? [] : $alerts->groupBy('description')
            ->map(function ($items, $name) use ($totalEvents) {
                $maxLevel = $items->max('level') ?? 0;
                return [
                    'name' => $name,
                    'count' => $items->count(),
                    'percentage' => round(($items->count() / $totalEvents) * 100),
                    'severity' => $maxLevel >= 10 ? 'high' : ($maxLevel >= 5 ? 'medium' : 'low'),
                ];
            })
            ->sortByDesc('count')->take(5)->values()->toArray();

        return [
            'active' => $activeCount,
            'pending' => $pendingCount,
            'resolved' => $resolvedCount,
            'categories' => $categories
        ];
    }
}