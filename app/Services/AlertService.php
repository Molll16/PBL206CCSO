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

    // CODE: Mengambil data log terbaru milik customer (dibatasi jumlahnya) dengan filter per agen.
    // WEB: Widget Kustomisasi Dashboard "Security Alerts Terbaru".
    // UNTUK: Menampilkan daftar beberapa log ancaman paling baru dari 1 agen yang sedang aktif dipilih.
    public function getLatestAlerts($limit = 5, $agentId = null)
    {
        $myAgents = $agentId ? [$agentId] : Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();

        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->take($limit);
    }

    // CODE: Mengambil dan menyaring data log berdasarkan Tanggal Kalender (History) dan Tingkat Severity.
    // WEB: Halaman View Logs / Daftar Log Monitoring Utama Customer (`daftarlog.blade.php`).
    // UNTUK: Memasok data baris tabel agar bisa difilter tanggal kemarin (history) atau level tertentu (Low/Critical).
    // NOTE: Jika parameter tanggal kosong, otomatis default ke hari ini (Otomatis reset otomatis dari 0 tiap ganti hari).
    public function getFilteredAlerts($agentId = null, $date = null, $severity = null)
    {
        $myAgents = $agentId ? [$agentId] : Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
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
                if ($severity === 'low')
                    return $alert['level'] < 5;
                return true;
            })
            ->sortByDesc('time')
            ->values();
    }

    // CODE: Menghitung total statistik log (Critical, High, Medium, Low) sesuai tanggal yang dipilih.
    // WEB: Kotak Counter Angka & Grafik Lingkaran atas di halaman `daftarlog.blade.php`.
    // UNTUK: Memastikan grafik lingkaran dan kotak angka ikut berubah naik/turun nilainya saat user memilih tanggal history.
    public function getLogsAnalytics($agentId = null, $date = null): array
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

    // CODE: Menghitung total seluruh log per hari selama 7 hari ke belakang.
    // WEB: Halaman Dashboard Utama milik ADMIN (Grafik Chart Batang/Garis).
    // UNTUK: Memasok data grafik (Labels hari & Angka total) supaya grafik tren mingguan muncul.
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

    // CODE: Mengelompokkan jenis ancaman terbanyak per agen dan menghitung persentasenya.
    // WEB: Widget Kustomisasi Dashboard "Threat Summary" (Kategori Ancaman).
    // UNTUK: Menampilkan status ringkasan dan Top 5 serangan paling sering khusus dari agen pilihan.
    public function getThreatSummary($agentId = null): array
    {
        $myAgents = $agentId ? [$agentId] : Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
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