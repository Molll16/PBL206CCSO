<?php

namespace App\Services;

use App\Models\Agen;

class AlertService
{
    protected $wazuh;

    // CODE: Constructor untuk manggil koneksi API Wazuh.
    // UNTUK: Menghubungkan otomatis service ini dengan data server Wazuh.
    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    // CODE: Mengambil semua data log (alerts) mentah dari API Wazuh.
    // UNTUK: Sumber data dasar utama, fungsi ini nantinya dipanggil oleh fungsi lain.
    public function getAlerts(): array
    {
        $raw = $this->wazuh->getRawAlerts();
        if (!empty($raw['error'])) {
            return [];
        }
        return $this->mapAlerts($raw);
    }

    // CODE: Memetakan (mapping) struktur data JSON rumit dari Wazuh ke array rapi.
    // UNTUK: Mengambil data penting saja (deskripsi, level bahaya, nama agen, waktu, user).
    private function mapAlerts($data): array
    {
        if (!isset($data['hits']['hits']))
            return [];

        $results = [];
        foreach ($data['hits']['hits'] as $item) {
            $source = $item['_source'];
            $results[] = [
                'description' => $source['rule']['description'] ?? '-',
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

    // CODE: Mengambil data log terbaru milik customer (dibatasi jumlahnya).
    // WEB: Widget Kustomisasi Dashboard "Security Alerts Terbaru".
    // UNTUK: Menampilkan daftar beberapa log ancaman paling baru di dashboard.
    public function getLatestAlerts($limit = 5)
    {
        $myAgents = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->take($limit);
    }

    // CODE: Mengambil dan menyaring semua log ancaman khusus untuk hari ini saja.
    // UNTUK: Dipakai sebagai data dasar untuk kalkulasi log harian (tidak langsung ke web).
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

    // CODE: Menghitung jumlah log hari ini berdasarkan levelnya (Critical, High, Med, Low).
    // WEB: Halaman Log Monitoring Utama Customer (`daftarlog.blade.php`).
    // UNTUK: Mengisi angka total counter warna-warni di atas tabel log hari ini.
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

    // CODE: Menghitung total seluruh log per hari selama 7 hari ke belakang.
    // WEB: Halaman Dashboard Utama milik ADMIN (Grafik Chart).
    // UNTUK: Memasok data grafik (Labels hari & Angka total) supaya grafik mingguan muncul.
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

    // CODE: Mengelompokkan jenis ancaman terbanyak dan menghitung persentasenya.
    // WEB: Widget Kustomisasi Dashboard "Threat Summary" (Kategori Ancaman).
    // UNTUK: Menampilkan status ringkasan (Active, Pending) dan Top 5 serangan paling sering.
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