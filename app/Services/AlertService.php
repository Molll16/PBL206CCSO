<?php

namespace App\Services;

use App\Models\Agen;

// Class ini untuk: Mengelola seluruh logic bisnis terkait data log/alert keamanan dari Wazuh.
// Berfungsi pada: Halaman Dashboard Admin, Dashboard Customer, dan halaman Logs/Analytics Customer.
// Dibagian fitur: Ambil & bersihkan data mentah alert, filter per-agen milik user, hitung statistik ancaman, dan data grafik.
class AlertService
{
    protected $wazuh;

    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    // Code ini untuk: Mengambil semua data log mentah dari server Wazuh via WazuhApiService, lalu membersihkannya.
    // Berfungsi untuk: Sumber data internal utama, dipakai oleh hampir semua method publik di class ini.
    public function getAlerts(): array
    {
        $raw = $this->wazuh->getRawAlerts();
        return empty($raw['error']) ? $this->mapAlerts($raw) : [];
    }

    // Code ini untuk: Menyaring dokumen JSON mentah dari Wazuh Indexer menjadi array sederhana (description, level, agent, time, user).
    // Berfungsi untuk: Pembersihan data internal, dipanggil oleh getAlerts().
    private function mapAlerts($data): array
    {
        if (!isset($data['hits']['hits'])) {
            return [];
        }

        $results = [];
        foreach ($data['hits']['hits'] as $item) {
            $source = $item['_source'] ?? [];

            // Urutan prioritas pengambilan deskripsi event, karena tidak semua log Wazuh punya field yang sama
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
                    'id' => isset($source['agent']['id']) ? (string) intval($source['agent']['id']) : null // Pembersihan padding ID Wazuh (mis. "007" jadi "7")
                ],
                'time' => $source['@timestamp'] ?? null,
                'user' => $source['data']['srcuser'] ?? $source['data']['dstuser'] ?? 'unknown',
            ];
        }
        return $results;
    }

    // Code ini untuk: Mencari daftar ID agen Wazuh milik user yang sedang login (mendukung single ID, array ID, atau opsi 'all').
    // Berfungsi untuk: Proteksi & filter multi-tenant, dipanggil oleh semua method publik di bawah agar data tidak bocor antar customer.
    private function getMyAgentIds($agentId = null): array
    {
        $currentUserId = auth()->id() ?? null;
        if (!$currentUserId) {
            return ['FORCE_EMPTY_VAL_NOT_FOUND'];
        }

        // 1. Tarik daftar asli ID agen milik user yang sedang login dari DB sebagai Whitelist
        $myRealAgentIds = Agen::where('user_id', $currentUserId)
            ->pluck('id_wazuh_agen')
            ->map(fn($id) => (string) intval($id))
            ->toArray();

        // Jika user ternyata tidak punya agen sama sekali di DB
        if (empty($myRealAgentIds)) {
            return ['FORCE_EMPTY_VAL_NOT_FOUND'];
        }

        // 2. Jika parameter berupa array
        if (is_array($agentId)) {
            $cleanIds = array_map(fn($id) => (string) intval($id), $agentId);
            // Hanya kembalikan ID yang beririsan (memang milik si user)
            return array_intersect($cleanIds, $myRealAgentIds);
        }

        // 3. Jika single ID dan bukan 'all'
        if ($agentId && $agentId !== 'all') {
            $cleanTargetId = (string) intval($agentId);

            // 🌟 VALIDASI MUTLAK: Cek apakah ID yang diminta ada di dalam Whitelist DB user
            if (!in_array($cleanTargetId, $myRealAgentIds, true)) {
                return ['FORCE_EMPTY_VAL_NOT_FOUND']; // Gagalkan total jika ID milik Customer B disusupkan
            }

            return [$cleanTargetId];
        }

        // 4. Jika 'all' atau null, kembalikan semua agen milik user ini saja
        return $myRealAgentIds;
    }

    // =========================================================================
    // HALAMAN: DASHBOARD CUSTOMER
    // =========================================================================

    // Code ini untuk: Mengambil 5 log keamanan terbaru milik agen user yang login.
    // Berfungsi untuk: Halaman Dashboard Customer, bagian widget "Alert Summary".
    public function getLatestAlerts(int $limit = 5, $agentId = null)
    {
        $myAgents = $this->getMyAgentIds($agentId);

        return collect($this->getAlerts())
            ->filter(fn($alert) => in_array($alert['agent']['id'] ?? null, $myAgents))
            ->take($limit);
    }

    // Code ini untuk: Menghitung status tingkat bahaya log (active/pending/resolved) dan top 5 kategori ancaman.
    // Berfungsi untuk: Halaman Dashboard Customer, bagian widget "Threat Summary" (Donut Chart & List).
    public function getThreatSummary($agentId = null): array
    {
        $myAgents = $this->getMyAgentIds($agentId);
        $alerts = collect($this->getAlerts())->filter(fn($a) => in_array($a['agent']['id'] ?? null, $myAgents));
        $totalEvents = $alerts->count();

        // Klasifikasi level bahaya: >=10 dianggap Active/High, 5-9 Pending/Medium, <5 Resolved/Low
        $activeCount = $alerts->where('level', '>=', 10)->count();
        $pendingCount = $alerts->whereBetween('level', [5, 9])->count();
        $resolvedCount = $alerts->where('level', '<', 5)->count();

        // Kelompokkan berdasarkan nama ancaman (description), ambil 5 kategori dengan kemunculan terbanyak
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

    // Code ini untuk: Mencari 3 aturan keamanan (rule) yang paling sering terpicu, lengkap dengan proporsi bar chart-nya.
    // Berfungsi untuk: Halaman Dashboard Customer, bagian widget "Most Active Rules" (Progress Bar).
    public function getMostActiveRules($agentId = null): array
    {
        try {
            $myAgents = $this->getMyAgentIds($agentId);
            $alerts = collect($this->getAlerts())->filter(fn($a) => in_array($a['agent']['id'] ?? null, $myAgents));

            if ($alerts->count() === 0) {
                return [];
            }

            // Kelompokkan per description, ambil 3 rule dengan jumlah kemunculan terbanyak
            $groupedRules = $alerts->groupBy('description')->map(function ($items, $description) {
                return [
                    'desc' => $description,
                    'count' => $items->count(),
                    'level' => $items->first()['level'] ?? 0
                ];
            })->sortByDesc('count')->take(3)->values();

            $maxCount = $groupedRules->first()['count'] ?? 1;

            // Hitung lebar progress bar (%) relatif terhadap rule dengan jumlah terbanyak, plus warna sesuai level
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
    // HALAMAN: ALERTS DAN FILTERING ALERTS CUSTOMER
    // =========================================================================

    // Code ini untuk: Memfilter log berdasarkan agen milik user dan tingkat keparahan (severity: critical/high/medium/low).
    // Berfungsi untuk: Halaman alerts Customer, bagian tabel utama "Daftar Log/Alerts".
    public function getFilteredAlerts($agentId = null, ?string $severity = null)
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

    // Code ini untuk: Menghitung total angka statistik log berdasarkan tingkat keparahan (Critical, High, Medium, Low).
    // Berfungsi untuk: Halaman Alerts/Analytics Customer, bagian card "Card Statistik" di bagian atas tabel.
    public function getLogsAnalytics($agentId = null): array
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

    // Code ini untuk: Menghitung tren total volume log harian selama 7 hari terakhir (untuk grafik pada halaman admin).
    // Berfungsi untuk: Halaman Dashboard Utama Admin, bagian grafik batang/area chart mingguan.
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