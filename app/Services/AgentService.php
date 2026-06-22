<?php

namespace App\Services;

use App\Models\Agen;
use App\Models\User;

/**
 * Class AgentService
 * Menjadi penghubung utama antara aplikasi Laravel kita dengan API Server Wazuh.
 */
class AgentService
{
    protected $wazuh;

    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    /**
     * Mengambil semua daftar agen tanpa filter langsung dari server Wazuh.
     */
    public function getAgents()
    {
        $response = $this->wazuh->agents();
        return $response['data']['affected_items'] ?? [];
    }

    /**
     * Mencari agen yang masih kosong atau belum dipakai oleh pelanggan manapun.
     */
    public function getAvailableAgents()
    {
        $agents = $this->getAgents();

        // Ambil semua ID agen yang sudah terdaftar di database kita
        $assignedAgents = Agen::pluck('id_wazuh_agen')->toArray();

        // Saring agen yang ID-nya TIDAK ADA di dalam database kita
        return collect($agents)
            ->whereNotIn('id', $assignedAgents)
            ->values()
            ->toArray();
    }

    /**
     * Menghitung total status operasional seluruh agen secara global (untuk halaman Admin).
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
     * Menghitung berapa agen yang online dan offline khusus milik pelanggan yang sedang login.
     */
    public function getCustomerStats(): array
    {
        // 1. Ambil daftar ID agen milik user yang sedang login saat ini
        $myAgents = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();

        // 2. Ambil data dari Wazuh, lalu saring yang hanya milik user ini
        $agents = collect($this->getAgents())->whereIn('id', $myAgents);

        // 3. Kelompokkan berdasarkan status aktif atau tidak
        $onlineCount = $agents->filter(fn($a) => strtolower($a['status'] ?? '') === 'active')->count();

        return [
            'online' => $onlineCount,
            'offline' => $agents->count() - $onlineCount, // Total dikurangi yang online otomatis jadi jumlah yang offline
            'total' => $agents->count(),
        ];
    }

    /**
     * Membuat ringkasan data akun pelanggan beserta jumlah agen yang mereka miliki.
     */
    public function getCustomerManagementSummary(): array
    {
        // Menggunakan withCount agar Laravel otomatis menghitung jumlah relasi agen tiap user
        $users = User::where('role', 'customer')->withCount('agents')->get();

        return [
            'users' => $users,
            'totalUsers' => $users->count(),
            'totalAssignedAgents' => $users->sum('agents_count'), // Jumlahkan semua kolom agents_count
        ];
    }

    /**
     * Mengambil metrik penggunaan perangkat keras (CPU & RAM) dari komputer target.
     */
    public function fetchSystemResources($agentId): array
    {
        try {
            // Hilangkan angka 0 di depan ID Agen (contoh: "001" menjadi "1")
            $cleanAgentId = ltrim($agentId, '0') ?: '0';

            // Minta data hardware ke API Wazuh
            $response = $this->wazuh->get("/agents/{$cleanAgentId}/wdb/hardware");
            $hardware = $response['data']['affected_items'][0] ?? null;

            // Jika data dari Wazuh kosong atau bukan array, langsung return 0
            if (!$hardware || !is_array($hardware)) {
                return ['cpu' => 0, 'ram' => 0];
            }

            // --- PILIH DATA RAM ---
            // Menggunakan operator '??' (Null Coalescing) untuk otomatis memilih key mana yang isinya ada
            $ramTotalStr = $hardware['host']['memory']['total'] ?? $hardware['ram_total'] ?? null;
            $ramFreeStr = $hardware['host']['memory']['free'] ?? $hardware['ram_free'] ?? null;

            $ramUsagePercentage = 0;
            if ($ramTotalStr && $ramFreeStr) {
                // Buang huruf seperti 'GB' atau 'MB' agar tersisa angka saja
                $ramTotal = floatval(preg_replace('/[^0-9.]/', '', $ramTotalStr));
                $ramFree = floatval(preg_replace('/[^0-9.]/', '', $ramFreeStr));

                if ($ramTotal > 0) {
                    $ramUsagePercentage = round((($ramTotal - $ramFree) / $ramTotal) * 100);
                }
            }

            // --- PILIH DATA CPU ---
            $rawCpu = $hardware['host']['cpu']['usage'] ?? $hardware['cpu_usage'] ?? $hardware['cpu_load'] ?? 0;
            // Bersihkan teks non-angka pada nilai CPU
            $cpuValue = floatval(preg_replace('/[^0-9.]/', '', $rawCpu));

            // Jika nilainya desimal kecil (0.0 - 1.0), ubah ke bentuk persen (dikali 100)
            $cpuValue = ($cpuValue > 0 && $cpuValue <= 1) ? round($cpuValue * 100) : round($cpuValue);

            return [
                'cpu' => (int) clamp($cpuValue, 0, 100),
                'ram' => (int) clamp($ramUsagePercentage, 0, 100),
            ];

        } catch (\Throwable $e) {
            return ['cpu' => 0, 'ram' => 0]; // Pengaman jika koneksi atau API Wazuh bermasalah
        }
    }

    /**
     * Ambil log FIM secara real-time dari database core Wazuh.
     */
    public function fetchFileIntegrityLogs($agentId): array
    {
        try {
            $id = $agentId;
            $altId = ltrim($id, '0') ?: '0';

            // 1. UBAH DISINI: Hilangkan "/pci", tembak ke endpoint syscheck utama sesuai gambar Postman yang sukses
            $res = $this->wazuh->get("/syscheck/{$id}?limit=5");
            $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];

            // 2. Fallback jika ID asli kosong, coba dengan format ID tanpa angka 0 di depan
            if (empty($rows)) {
                $res = $this->wazuh->get("/syscheck/{$altId}");
                $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];
            }

            // Jika memang kosong dari server Wazuh, langsung kembalikan array kosong
            if (empty($rows) || !is_array($rows)) {
                return [];
            }

            $list = [];
            foreach ($rows as $v) {
                $time = $v['date'] ?? $v['mtime'] ?? null;

                $list[] = [
                    'path' => $v['file'] ?? 'Unknown File',
                    'agent' => 'Agent ' . $id,
                    // Mengubah format ISO 8601 (dari API) ke Jam:Menit WIB
                    'time' => $time ? date('H:i', strtotime($time)) . ' WIB' : 'Baru saja',
                    'status' => strtolower($v['event'] ?? 'modified')
                ];
            }

            return $list;

        } catch (\Throwable $e) {
            \Log::error("Wazuh FIM Log Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Menghitung berapa kali terjadi kegagalan login (Authentication Failed).
     */
    public function fetchFailedLoginsCount($agentId): array
    {
        try {
            // Cari alert yang mengandung kata 'failed' pada tingkat bahaya level 5
            $response = $this->wazuh->get("/alerts?limit=1&level=5&search=failed&q=agent.id={$agentId}");
            $totalCount = $response['data']['total_affected_items'] ?? 0;

            // Tentukan label status berdasarkan jumlah kegagalan login
            $statusTag = 'Normal';
            if ($totalCount > 100) {
                $statusTag = 'Spike Detected';
            } elseif ($totalCount > 20) {
                $statusTag = 'Warning';
            }

            return [
                'count' => $totalCount,
                'timeline' => 'Total logs recorded recently',
                'status_tag' => $statusTag
            ];
        } catch (\Throwable $e) {
            return ['count' => 0, 'timeline' => 'Service unavailable', 'status_tag' => 'Unknown'];
        }
    }

    /**
     * Ambil 5 riwayat aktivitas log dari core db agen wazuh.
     */
    public function fetchUserLoginActivity($agentId): array
    {
        try {
            $id = $agentId;
            $altId = ltrim($id, '0') ?: '0';

            // 1. Coba ambil data dari endpoint syscheck (pci)
            $res = $this->wazuh->get("/syscheck/{$id}/pci");
            $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];

            // Fallback ke ID alternatif jika kosong
            if (empty($rows)) {
                $res = $this->wazuh->get("/syscheck/{$altId}/pci");
                $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];
            }

            // 2. Jika masih kosong, coba endpoint rootcheck
            if (empty($rows)) {
                $res = $this->wazuh->get("/rootcheck/{$id}/alerts?limit=10");
                $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];
            }

            if (empty($rows) || !is_array($rows)) {
                return [];
            }

            $list = [];
            foreach ($rows as $v) {
                $user = $v['user'] ?? $v['owner'] ?? $v['uid'] ?? 'SYSTEM';

                // Lewati akun background bawaan Windows
                if (str_ends_with($user, '$') || strtoupper($user) === 'ANONYMOUS LOGON') {
                    continue;
                }

                // Tentukan deskripsi aktivitas singkat
                $file = $v['file'] ?? '';
                $act = !empty($file) ? "Audit: " . basename($file) : ($v['event'] ?? $v['description'] ?? 'System Event');

                // Tentukan format jam log
                $time = $v['date'] ?? $v['mtime'] ?? $v['scan_time'] ?? null;
                $clock = $time ? date('H:i', strtotime($time)) . ' WIB' : 'Real-time';

                $list[] = [
                    'user' => $user,
                    'activity' => $act,
                    'ip' => 'Local Event',
                    'status' => 'success',
                    'time' => $clock
                ];

                if (count($list) >= 5) {
                    break;
                }
            }

            return $list;

        } catch (\Throwable $e) {
            \Log::error("Wazuh Core Activity Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Memeriksa status aplikasi/service penting di dalam OS komputer target (Nginx, MySQL, dll).
     */
    public function fetchServiceStatus($agentId): array
    {
        try {
            // Gunakan ID agen asli bawaan (misal "001") tanpa memotong angka 0 di depan
            $cleanAgentId = $agentId;

            // Ambil daftar proses yang sedang berjalan dari komputer target via API Syscollector
            $response = $this->wazuh->get("/syscollector/{$cleanAgentId}/processes?limit=500");

            // Ekstraksi array data proses menggunakan operator Null Coalescing bertumpuk yang aman
            $processes = $response['data']['affected_items'] ?? $response['affected_items'] ?? $response['data'] ?? [];

            // Jika API Wazuh mengembalikan data kosong, kita coba fallback (cadangan) pakai format ID tanpa angka 0
            if (empty($processes)) {
                $fallbackId = ltrim($agentId, '0') ?: '0';
                $responseFallback = $this->wazuh->get("/syscollector/{$fallbackId}/processes?limit=500");
                $processes = $responseFallback['data']['affected_items'] ?? $responseFallback['affected_items'] ?? $responseFallback['data'] ?? [];
            }

            // Jika setelah dicoba kedua format ID tetap kosong, return array kosong agar tidak error
            if (empty($processes) || !is_array($processes)) {
                return [];
            }

            // Target service yang ingin kita pantau statusnya di dalam OS komputer agen
            $monitoredServices = [
                'nginx' => ['name' => 'Nginx Web Server', 'port' => '80, 443', 'search' => ['nginx']],
                'mysql' => ['name' => 'MySQL Database', 'port' => '3306', 'search' => ['mysql', 'mysqld', 'mariadb']],
                'redis' => ['name' => 'Redis Cache', 'port' => '6379', 'search' => ['redis-server', 'redis']],
                'sshd' => ['name' => 'SSH Daemon', 'port' => '22', 'search' => ['sshd', 'ssh']],
                'fail2ban' => ['name' => 'Fail2Ban Security', 'port' => '-', 'search' => ['fail2ban-server', 'fail2ban']],
                'php' => ['name' => 'PHP-FPM Engine', 'port' => '9000', 'search' => ['php-fpm', 'php']],
            ];

            $result = [];
            foreach ($monitoredServices as $info) {
                $isRunning = false;

                // COCOKKAN KATA KUNCI: Cari apakah ada nama atau command proses yang aktif di background
                foreach ($processes as $proc) {
                    $procName = strtolower($proc['name'] ?? '');
                    $procCmd = strtolower($proc['cmd'] ?? '');

                    foreach ($info['search'] as $keyword) {
                        if (str_contains($procName, $keyword) || str_contains($procCmd, $keyword)) {
                            $isRunning = true;
                            break 2; // Keluar dari perulangan karena service sudah dipastikan running
                        }
                    }
                }

                $result[] = [
                    'name' => $info['name'],
                    'port' => $info['port'],
                    'status' => $isRunning ? 'running' : 'stopped'
                ];
            }

            return $result;

        } catch (\Throwable $e) {
            \Log::error("Wazuh Service Monitoring Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Memantau dan mengalkulasi aktivitas kartu jaringan (Network Interface).
     */
    public function fetchNetworkTraffic($agentId): array
    {
        try {
            // Ambil data adapter jaringan langsung dari API Wazuh Syscollector
            $response = $this->wazuh->get("/syscollector/{$agentId}/netiface?limit=50");
            $interfaces = $response['data']['affected_items'] ?? $response['affected_items'] ?? $response['data'] ?? [];

            if (empty($interfaces) || !is_array($interfaces)) {
                return ['stats' => ['inbound' => 0, 'outbound' => 0], 'interfaces' => []];
            }

            $networkInterfaces = [];
            $totalInboundBytes = 0;
            $totalOutboundBytes = 0;

            foreach ($interfaces as $iface) {
                $name = $iface['name'] ?? 'unknown';

                // PILIH DATA BYTES (Ambil angka lalu lintas data masuk dan keluar)
                $rxBytes = $iface['rx']['bytes'] ?? $iface['rx_bytes'] ?? 0;
                $txBytes = $iface['tx']['bytes'] ?? $iface['tx_bytes'] ?? 0;

                $totalInboundBytes += $rxBytes;
                $totalOutboundBytes += $txBytes;

                // FORMAT SATUAN DATA: Ubah ukuran byte menjadi Megabyte (M) atau Gigabyte (G)
                $speedFormatted = ($rxBytes > 1073741824)
                    ? round($rxBytes / 1073741824, 1) . 'G'
                    : round($rxBytes / 1048576, 0) . 'M';

                // Identitas jaringan: Ambil IP, jika tidak ada ambil MAC Address, atau jenis adapternya
                $subText = $iface['ipv4']['address'] ?? $iface['mac'] ?? $iface['adapter'] ?? '-';

                $rawState = $iface['state'] ?? $iface['status'] ?? 'up';

                $networkInterfaces[] = [
                    'name' => $name,
                    'ip' => $subText,
                    'direction' => (strtolower($rawState) === 'down') ? 'down' : 'up',
                    'speed' => $speedFormatted
                ];
            }

            return [
                'stats' => [
                    'inbound' => round($totalInboundBytes / 1073741824, 1),
                    'outbound' => round($totalOutboundBytes / 1073741824, 1),
                ],
                'interfaces' => $networkInterfaces
            ];

        } catch (\Throwable $e) {
            \Log::error("Wazuh Network Monitoring Error parsing: " . $e->getMessage());
            return ['stats' => ['inbound' => 0, 'outbound' => 0], 'interfaces' => []];
        }
    }
}

/**
 * Fungsi Pembantu Mandiri (Helper) untuk memastikan nilai angka tetap berada di batas minimum dan maksimum.
 */
if (!function_exists('clamp')) {
    function clamp($value, $min, $max)
    {
        return max($min, min($max, $value));
    }
}