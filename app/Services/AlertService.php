<?php

namespace App\Services;

use App\Models\Agen;

class AlertService
{
    protected $wazuh;

    // Menghubungkan service ini dengan WazuhApiService untuk menarik data dari server Wazuh
    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    /**
     * =========================================================================
     * HELPER INTERNAL (Fungsi Pembantu agar Kode Ringkas & Bebas Garis Kuning)
     * =========================================================================
     */

    // Mengambil semua data log mentah dari server Wazuh
    public function getAlerts(): array
    {
        $raw = $this->wazuh->getRawAlerts();
        return empty($raw['error']) ? $this->mapAlerts($raw) : [];
    }

    // Menyaring dan merapikan dokumen JSON Wazuh yang rumit ke array sederhana
    private function mapAlerts($data): array
    {
        if (!isset($data['hits']['hits']))
            return [];

        $results = [];
        foreach ($data['hits']['hits'] as $item) {
            $source = $item['_source'] ?? [];

            // Mencari deskripsi log (Fallback: cek deskripsi, jika kosong cek title, dst)
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

    // Fungsi Ringkas: Otomatis mencari daftar ID agen milik user yang sedang login (Menghemat puluhan baris kode!)
    private function getMyAgentIds(?string $agentId = null): array
    {
        if ($agentId) {
            return [str_pad($agentId, 3, '0', STR_PAD_LEFT)];
        }

        $currentUserId = auth()->user()->id ?? null;

        return Agen::where('user_id', $currentUserId)
            ->get()
            ->pluck('id_wazuh_agen')
            ->map(fn($id) => str_pad((string) $id, 3, '0', STR_PAD_LEFT))
            ->toArray();
    }


    /**
     * =========================================================================
     * FUNGSI UNTUK HALAMAN: DASHBOARD CUSTOMER / USER
     * =========================================================================
     */

    // BAGIAN: Widget "Log Aktivitas Terbaru" (Menampilkan 5 atau beberapa log terakhir)
    public function getLatestAlerts(int $limit = 5, ?string $agentId = null)
    {
        $myAgents = $this->getMyAgentIds($agentId);

        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->take($limit);
    }

    // BAGIAN: Widget "Threat Summary" / Ringkasan Ancaman (Donut/Pie Chart & List Top 5 Ancaman)
    public function getThreatSummary(?string $agentId = null): array
    {
        $myAgents = $this->getMyAgentIds($agentId);
        $alerts = collect($this->getAlerts())->filter(fn($a) => in_array($a['agent']['id'] ?? null, $myAgents));
        $totalEvents = $alerts->count();

        // Mengelompokkan status berdasarkan level tingkat bahaya
        $activeCount = $alerts->where('level', '>=', 10)->count();
        $pendingCount = $alerts->whereBetween('level', [5, 9])->count();
        $resolvedCount = $alerts->where('level', '<', 5)->count();

        // Membuat data list Top 5 ancaman terbanyak beserta persentasenya
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

    // BAGIAN: Widget "Most Active Rules" (Progress Bar 3 Aturan Keamanan yang Paling Sering Terpicu)
    public function getMostActiveRules(?string $agentId = null): array
    {
        try {
            $myAgents = $this->getMyAgentIds($agentId);
            $alerts = collect($this->getAlerts())->filter(fn($a) => in_array($a['agent']['id'] ?? null, $myAgents));

            if ($alerts->count() === 0)
                return [];

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


    /**
     * =========================================================================
     * FUNGSI UNTUK HALAMAN: LOGS / FILTER & ANALYTICS
     * =========================================================================
     */

    // BAGIAN: Halaman Utama "Daftar Log/Alerts" (Menampilkan tabel log dengan Filter Tanggal & Severity)
    public function getFilteredAlerts(?string $agentId = null, ?string $date = null, ?string $severity = null)
    {
        $myAgents = $this->getMyAgentIds($agentId);
        $targetDate = $date ? $date : now()->format('Y-m-d');

        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->filter(fn($alert) => isset($alert['time']) && str_contains($alert['time'], $targetDate))
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

    // BAGIAN: Widget "Counter Card Statistik" (Menampilkan kotak total angka: Critical, High, Med, Low)
    public function getLogsAnalytics(?string $agentId = null, ?string $date = null): array
    {
        $targetDate = $date ? $date : now()->format('Y-m-d');
        $allAlertsForDate = $this->getFilteredAlerts($agentId, $targetDate, null);

        return [
            'totalAlerts' => $allAlertsForDate->count(),
            'criticalAlerts' => $allAlertsForDate->where('level', '>=', 13)->count(),
            'highAlerts' => $allAlertsForDate->whereBetween('level', [10, 12])->count(),
            'mediumAlerts' => $allAlertsForDate->whereBetween('level', [5, 9])->count(),
            'lowAlerts' => $allAlertsForDate->where('level', '<', 5)->count(),
        ];
    }


    /**
     * =========================================================================
     * FUNGSI UNTUK HALAMAN: DASHBOARD UTAMA ADMIN
     * =========================================================================
     */

    // BAGIAN: Grafik Batang / Area Chart (Menghitung tren total log per hari selama 7 hari terakhir)
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