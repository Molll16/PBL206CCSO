<?php

namespace App\Services;

use App\Models\Agen;
use App\Models\User;

/**
 * Class AgentService
 * Code ini untuk: Menghubungkan backend aplikasi Laravel dengan API Endpoint Server Wazuh secara terpusat.
 * Berfungsi pada halaman: Halaman Admin (Dashboard & Detail Agen) dan Halaman Customer (Dashboard & Logs).
 * Dibagian fitur: Integrasi data SIEM, pemantauan resource perangkat keras, log aktivitas user, audit FIM, dan status jaringan.
 */
class AgentService
{
    protected $wazuh;

    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    /**
     * Code ini untuk: Menarik seluruh daftar agen mentah dari server Wazuh.
     * Berfungsi pada halaman: Manajemen Agen Admin & Sinkronisasi Agen Customer.
     */
    public function getAgents()
    {
        return $this->wazuh->agents()['data']['affected_items'] ?? [];
    }

    /**
     * Code ini untuk: Menyaring agen Wazuh yang belum di-pairing (belum dimiliki) oleh customer manapun.
     * Berfungsi pada halaman: Form Tambah/Edit Agen di sisi Administrator.
     */
    public function getAvailableAgents()
    {
        $assignedAgents = Agen::pluck('id_wazuh_agen')->toArray();

        return collect($this->getAgents())
            ->whereNotIn('id', $assignedAgents)
            ->values()
            ->toArray();
    }

    /**
     * Code ini untuk: Menghitung akumulasi status operasional (Active, Disconnected, dll) seluruh mesin agen.
     * Berfungsi pada halaman: Dashboard Utama Panel Admin.
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
     * Code ini untuk: Menghitung status online/offline agen spesifik milik satu customer yang sedang login.
     * Berfungsi pada halaman: Dashboard Utama Panel Customer.
     */
    public function getCustomerStats(): array
    {
        $myAgents = Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
        $agents = collect($this->getAgents())->whereIn('id', $myAgents);
        $online = $agents->filter(fn($a) => strtolower($a['status'] ?? '') === 'active')->count();

        return [
            'online' => $online,
            'offline' => $agents->count() - $online,
            'total' => $agents->count(),
        ];
    }

    /**
     * Code ini untuk: Merangkum total akun pelanggan terdaftar beserta jumlah mesin proteksi yang disewa.
     * Berfungsi pada halaman: Manajemen Pengguna (Customer Management) Admin.
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

    /**
     * Code ini untuk: Menarik dan mengalkulasi metrik pemakaian hardware (CPU % & RAM %) real-time dari komputer target.
     * Berfungsi pada halaman: Detail Monitoring Perangkat Utama Agen (Admin & Customer).
     */
    public function fetchSystemResources($agentId): array
    {
        try {
            $cleanId = ltrim($agentId, '0') ?: '0';
            $response = $this->wazuh->get("/agents/{$cleanId}/wdb/hardware");
            $hardware = $response['data']['affected_items'][0] ?? null;

            if (!$hardware || !is_array($hardware))
                return ['cpu' => 0, 'ram' => 0];

            // Kalkulasi RAM Pemakaian
            $ramTotalStr = $hardware['host']['memory']['total'] ?? $hardware['ram_total'] ?? null;
            $ramFreeStr = $hardware['host']['memory']['free'] ?? $hardware['ram_free'] ?? null;
            $ramPercent = 0;

            if ($ramTotalStr && $ramFreeStr) {
                $total = floatval(preg_replace('/[^0-9.]/', '', $ramTotalStr));
                $free = floatval(preg_replace('/[^0-9.]/', '', $ramFreeStr));
                $ramPercent = $total > 0 ? round((($total - $free) / $total) * 100) : 0;
            }

            // Pembersihan Nilai CPU
            $rawCpu = $hardware['host']['cpu']['usage'] ?? $hardware['cpu_usage'] ?? $hardware['cpu_load'] ?? 0;
            $cpuValue = floatval(preg_replace('/[^0-9.]/', '', $rawCpu));
            $cpuValue = ($cpuValue > 0 && $cpuValue <= 1) ? round($cpuValue * 100) : round($cpuValue);

            // Menghilangkan helper luar dengan membatasi nilai 0 - 100 memakai bawaan PHP
            return [
                'cpu' => (int) max(0, min(100, $cpuValue)),
                'ram' => (int) max(0, min(100, $ramPercent)),
            ];
        } catch (\Throwable $e) {
            return ['cpu' => 0, 'ram' => 0];
        }
    }

    /**
     * Code ini untuk: Mengaudit log integritas perubahan berkas (File Integrity Monitoring) yang terjadi di dalam direktori sensitif.
     * Berfungsi pada halaman: Tabel Fitur Audit FIM Log Komputer Agen.
     */
    public function fetchFileIntegrityLogs($agentId): array
    {
        try {
            $id = $agentId;
            $res = $this->wazuh->get("/syscheck/{$id}?limit=5");
            $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];

            if (empty($rows)) {
                $res = $this->wazuh->get("/syscheck/" . (ltrim($id, '0') ?: '0'));
                $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];
            }

            if (empty($rows) || !is_array($rows))
                return [];

            $list = [];
            foreach ($rows as $v) {
                $time = $v['date'] ?? $v['mtime'] ?? null;
                $list[] = [
                    'path' => $v['file'] ?? 'Unknown File',
                    'agent' => 'Agent ' . $id,
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
     * Code ini untuk: Menghitung total akumulasi logs insiden kegagalan otentikasi masuk sistem (brute force attempt).
     * Berfungsi pada halaman: Widget Ringkasan Alert Keamanan & Status Ancaman.
     */
    public function fetchFailedLoginsCount($agentId): array
    {
        try {
            $response = $this->wazuh->get("/alerts?limit=1&level=5&search=failed&q=agent.id={$agentId}");
            $totalCount = $response['data']['total_affected_items'] ?? 0;

            $statusTag = 'Normal';
            if ($totalCount > 100)
                $statusTag = 'Spike Detected';
            elseif ($totalCount > 20)
                $statusTag = 'Warning';

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
     * Code ini untuk: Mengaudit data riwayat sesi masuk log user lokal (aktivitas root/administrator).
     * Berfungsi pada halaman: Komponen Tabel Riwayat Aktivitas Pengguna (User Login Activity).
     */
    public function fetchUserLoginActivity($agentId): array
    {
        try {
            $id = $agentId;
            $res = $this->wazuh->get("/syscheck/{$id}/pci");
            $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];

            if (empty($rows)) {
                $res = $this->wazuh->get("/syscheck/" . (ltrim($id, '0') ?: '0') . "/pci");
                $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];
            }

            if (empty($rows)) {
                $res = $this->wazuh->get("/rootcheck/{$id}/alerts?limit=10");
                $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];
            }

            if (empty($rows) || !is_array($rows))
                return [];

            $list = [];
            foreach ($rows as $v) {
                $user = $v['user'] ?? $v['owner'] ?? $v['uid'] ?? 'SYSTEM';

                if (str_ends_with($user, '$') || strtoupper($user) === 'ANONYMOUS LOGON')
                    continue;

                $file = $v['file'] ?? '';
                $act = !empty($file) ? "Audit: " . basename($file) : ($v['event'] ?? $v['description'] ?? 'System Event');
                $time = $v['date'] ?? $v['mtime'] ?? $v['scan_time'] ?? null;

                $list[] = [
                    'user' => $user,
                    'activity' => $act,
                    'ip' => 'Local Event',
                    'status' => 'success',
                    'time' => $time ? date('H:i', strtotime($time)) . ' WIB' : 'Real-time'
                ];

                if (count($list) >= 5)
                    break;
            }
            return $list;
        } catch (\Throwable $e) {
            \Log::error("Wazuh Core Activity Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Code ini untuk: Memetakan status proses aplikasi kritis di server target (Nginx, MySQL, SSH, PHP Engine).
     * Berfungsi pada halaman: Tabel Status Kontrol Service Monitoring.
     */
    public function fetchServiceStatus($agentId): array
    {
        try {
            $response = $this->wazuh->get("/syscollector/{$agentId}/processes?limit=500");
            $processes = $response['data']['affected_items'] ?? $response['affected_items'] ?? $response['data'] ?? [];

            if (empty($processes)) {
                $response = $this->wazuh->get("/syscollector/" . (ltrim($agentId, '0') ?: '0') . "/processes?limit=500");
                $processes = $response['data']['affected_items'] ?? $response['affected_items'] ?? $response['data'] ?? [];
            }

            if (empty($processes) || !is_array($processes))
                return [];

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
                foreach ($processes as $proc) {
                    $procName = strtolower($proc['name'] ?? '');
                    $procCmd = strtolower($proc['cmd'] ?? '');

                    foreach ($info['search'] as $keyword) {
                        if (str_contains($procName, $keyword) || str_contains($procCmd, $keyword)) {
                            $isRunning = true;
                            break 2;
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
     * Code ini untuk: Menghitung total lalu lintas data jaringan (Inbound/Outbound GB) beserta memetakan kartu jaringan yang UP/DOWN.
     * Berfungsi pada halaman: Widget Metrik Monitoring Jaringan (Network Traffic).
     */
    public function fetchNetworkTraffic($agentId): array
    {
        try {
            $response = $this->wazuh->get("/syscollector/{$agentId}/netiface?limit=50");
            $interfaces = $response['data']['affected_items'] ?? $response['affected_items'] ?? $response['data'] ?? [];

            if (empty($interfaces) || !is_array($interfaces)) {
                return ['stats' => ['inbound' => 0, 'outbound' => 0], 'interfaces' => []];
            }

            $networkInterfaces = [];
            $totalInboundBytes = 0;
            $totalOutboundBytes = 0;

            foreach ($interfaces as $iface) {
                $rxBytes = $iface['rx']['bytes'] ?? $iface['rx_bytes'] ?? 0;
                $txBytes = $iface['tx']['bytes'] ?? $iface['tx_bytes'] ?? 0;

                $totalInboundBytes += $rxBytes;
                $totalOutboundBytes += $txBytes;

                $speedFormatted = ($rxBytes > 1073741824)
                    ? round($rxBytes / 1073741824, 1) . 'G'
                    : round($rxBytes / 1048576, 0) . 'M';

                $networkInterfaces[] = [
                    'name' => $iface['name'] ?? 'unknown',
                    'ip' => $iface['ipv4']['address'] ?? $iface['mac'] ?? $iface['adapter'] ?? '-',
                    'direction' => (strtolower($iface['state'] ?? $iface['status'] ?? 'up') === 'down') ? 'down' : 'up',
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