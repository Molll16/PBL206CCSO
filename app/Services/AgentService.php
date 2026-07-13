<?php

namespace App\Services;

use App\Models\Agen;
use App\Models\User;

// Class ini untuk: Menghubungkan backend Laravel dengan API Wazuh secara terpusat (data agen, hardware, log, network, dst).
// Berfungsi pada: Halaman Admin (Dashboard & Detail Agen) dan Halaman Customer (Dashboard, Logs, Widget monitoring).
// Dibagian fitur: Integrasi data SIEM, pemantauan resource perangkat keras, log aktivitas user, audit FIM, dan status jaringan.
class AgentService
{
    protected $wazuh;

    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    // Code ini untuk: Menarik seluruh daftar agen mentah dari server Wazuh.
    // Berfungsi untuk: Manajemen Agen Admin & Sinkronisasi Agen Customer.
    public function getAgents()
    {
        return $this->wazuh->agents()['data']['affected_items'] ?? [];
    }

    // Code ini untuk: Menyaring agen Wazuh yang belum di-pairing (belum dimiliki) oleh customer manapun.
    // Berfungsi untuk: Form Tambah/Edit Agen di sisi Administrator.
    public function getAvailableAgents()
    {
        $assignedAgents = Agen::pluck('id_wazuh_agen')->toArray();

        return collect($this->getAgents())
            ->whereNotIn('id', $assignedAgents)
            ->values()
            ->toArray();
    }

    // Code ini untuk: Menghitung akumulasi status operasional (Active, Disconnected, dll) seluruh mesin agen.
    // Berfungsi untuk: Dashboard Utama Panel Admin.
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

    // Code ini untuk: Menghitung status online/offline agen spesifik milik satu customer yang sedang login.
    // Berfungsi untuk: Dashboard Utama Panel Customer.
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

    // Code ini untuk: Merangkum total akun pelanggan terdaftar beserta jumlah mesin proteksi yang disewa.
    // Berfungsi untuk: Manajemen Pengguna (Customer Management) Admin.
    public function getCustomerManagementSummary(): array
    {
        $users = User::where('role', 'customer')->withCount('agents')->get();

        return [
            'users' => $users,
            'totalUsers' => $users->count(),
            'totalAssignedAgents' => $users->sum('agents_count'),
        ];
    }

    // Code ini untuk: Menarik dan mengalkulasi metrik pemakaian hardware (CPU % & RAM %) real-time dari komputer target.
    // Berfungsi untuk: Detail Monitoring Perangkat Utama Agen (Admin & Customer) & Widget System Resources.
    public function fetchSystemResources($agentId): array
    {
        try {
            $cleanId = ltrim($agentId, '0') ?: '0';
            $response = $this->wazuh->get("/agents/{$cleanId}/wdb/hardware");
            $hardware = $response['data']['affected_items'][0] ?? null;

            if (!$hardware || !is_array($hardware))
                return ['cpu' => 0, 'ram' => 0];

            // Kalkulasi persentase RAM terpakai dari total & sisa memori
            $ramTotalStr = $hardware['host']['memory']['total'] ?? $hardware['ram_total'] ?? null;
            $ramFreeStr = $hardware['host']['memory']['free'] ?? $hardware['ram_free'] ?? null;
            $ramPercent = 0;

            if ($ramTotalStr && $ramFreeStr) {
                $total = floatval(preg_replace('/[^0-9.]/', '', $ramTotalStr));
                $free = floatval(preg_replace('/[^0-9.]/', '', $ramFreeStr));
                $ramPercent = $total > 0 ? round((($total - $free) / $total) * 100) : 0;
            }

            // Pembersihan nilai CPU (kadang format API beda-beda, kadang desimal 0-1, kadang langsung persen)
            $rawCpu = $hardware['host']['cpu']['usage'] ?? $hardware['cpu_usage'] ?? $hardware['cpu_load'] ?? 0;
            $cpuValue = floatval(preg_replace('/[^0-9.]/', '', $rawCpu));
            $cpuValue = ($cpuValue > 0 && $cpuValue <= 1) ? round($cpuValue * 100) : round($cpuValue);

            // Membatasi nilai akhir antara 0-100 memakai fungsi bawaan PHP
            return [
                'cpu' => (int) max(0, min(100, $cpuValue)),
                'ram' => (int) max(0, min(100, $ramPercent)),
            ];
        } catch (\Throwable $e) {
            return ['cpu' => 0, 'ram' => 0];
        }
    }

    // Code ini untuk: Mengaudit log integritas perubahan berkas (File Integrity Monitoring) di dalam direktori sensitif.
    // Berfungsi untuk: Tabel Fitur Audit FIM Log Komputer Agen & Widget FIM.
    public function fetchFileIntegrityLogs($agentId): array
    {
        try {
            $id = $agentId;
            $res = $this->wazuh->get("/syscheck/{$id}?limit=5");
            $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];

            // Fallback: coba lagi dengan ID tanpa padding nol jika hasil pertama kosong
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

    // Code ini untuk: Menghitung total akumulasi log insiden kegagalan otentikasi masuk sistem (brute force attempt).
    // Berfungsi untuk: Widget Ringkasan Alert Keamanan & Status Ancaman.
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

    // Code ini untuk: Mengaudit data riwayat sesi masuk log user lokal (aktivitas root/administrator).
    // Berfungsi untuk: Komponen Tabel Riwayat Aktivitas Pengguna (User Login Activity).
    public function fetchUserLoginActivity($agentId): array
    {
        try {
            $id = $agentId;
            $res = $this->wazuh->get("/syscheck/{$id}/pci");
            $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];

            // Fallback 1: coba ID tanpa padding nol
            if (empty($rows)) {
                $res = $this->wazuh->get("/syscheck/" . (ltrim($id, '0') ?: '0') . "/pci");
                $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];
            }

            // Fallback 2: coba endpoint rootcheck alerts jika syscheck/pci tetap kosong
            if (empty($rows)) {
                $res = $this->wazuh->get("/rootcheck/{$id}/alerts?limit=10");
                $rows = $res['data']['affected_items'] ?? $res['affected_items'] ?? [];
            }

            if (empty($rows) || !is_array($rows))
                return [];

            $list = [];
            foreach ($rows as $v) {
                $user = $v['user'] ?? $v['owner'] ?? $v['uid'] ?? 'SYSTEM';

                // Lewati akun sistem (mis. akun komputer "PC$" atau login anonim) agar tabel tidak penuh noise
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

                // Batasi maksimal 5 baris agar tabel tidak kepanjangan
                if (count($list) >= 5)
                    break;
            }
            return $list;
        } catch (\Throwable $e) {
            \Log::error("Wazuh Core Activity Error: " . $e->getMessage());
            return [];
        }
    }

    // Code ini untuk: Memetakan status proses aplikasi kritis di server target (Nginx, MySQL, SSH, PHP Engine, dll).
    // Berfungsi untuk: Tabel Status Kontrol Service Monitoring.
    public function fetchServiceStatus($agentId): array
    {
        try {
            $response = $this->wazuh->get("/syscollector/{$agentId}/processes?limit=500");
            $processes = $response['data']['affected_items'] ?? $response['affected_items'] ?? $response['data'] ?? [];

            // Fallback: coba lagi dengan ID tanpa padding nol jika hasil pertama kosong
            if (empty($processes)) {
                $response = $this->wazuh->get("/syscollector/" . (ltrim($agentId, '0') ?: '0') . "/processes?limit=500");
                $processes = $response['data']['affected_items'] ?? $response['affected_items'] ?? $response['data'] ?? [];
            }

            if (empty($processes) || !is_array($processes))
                return [];

            // Daftar service yang dipantau beserta kata kunci pencarian nama prosesnya
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

    // Code ini untuk: Menghitung total lalu lintas data jaringan (Inbound/Outbound GB) dan memetakan kartu jaringan yang UP/DOWN.
    // Berfungsi untuk: Widget Metrik Monitoring Jaringan (Network Traffic).
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

                // Format kecepatan otomatis: pakai satuan GB jika besar, MB jika kecil
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

    // Code ini untuk: Memantau keamanan firewall berdasarkan data log riil dari endpoint /manager/logs.
    // Berfungsi untuk: Widget Monitoring Firewall & Port Security.
    public function fetchFirewallEvents($agentId): array
    {
        try {
            $response = $this->wazuh->get("/manager/logs?limit=10&q=agent.id={$agentId}");
            $rawAlerts = $response['data']['affected_items'] ?? [];

            if (empty($rawAlerts)) {
                return [];
            }

            $events = [];

            foreach ($rawAlerts as $alert) {
                // Murni mengambil data apa adanya dari objek API Wazuh
                $ruleDesc = $alert['description'] ?? 'Event Keamanan Tidak Diketahui';
                $levelText = $alert['level'] ?? 'info';
                $tag = $alert['tag'] ?? '-'; // Default strip jika properti kosong dari API

                // Menentukan severity & warna murni berdasarkan level log asli Wazuh
                if ($levelText === 'error' || $levelText === 'alert' || $levelText === 'critical') {
                    $severity = 'HIGH';
                    $color = 'red';
                } elseif ($levelText === 'warning') {
                    $severity = 'MEDIUM';
                    $color = 'yellow';
                } else {
                    $severity = 'LOW';
                    $color = 'blue';
                }

                // Biarkan frontend yang menghandle jika field IP/Port tidak ada di payload (tidak dikarang manual)
                $events[] = [
                    'title' => $tag,
                    'description' => $ruleDesc,
                    'severity' => $severity,
                    'color' => $color
                ];
            }

            return $events;

        } catch (\Throwable $e) {
            \Log::error("Wazuh Firewall Events Connection Error: " . $e->getMessage());
            return [];
        }
    }

    // Code ini untuk: Mengambil data koneksi aktif riil dari agen sistem (protokol TCP).
    // Berfungsi untuk: Widget Active Connections.
    public function fetchActiveConnections($agentId): array
    {
        try {
            $response = $this->wazuh->get("/manager/logs?limit=10&q=data.protocol=TCP;agent.id={$agentId}");
            $rawItems = $response['data']['affected_items'] ?? [];

            if (empty($rawItems)) {
                return [];
            }

            $connections = [];

            foreach ($rawItems as $item) {
                $description = $item['description'] ?? '';
                $level = $item['level'] ?? 'info';

                // Ekstraksi IP sederhana lewat regex jika deskripsi log mengandung alamat IP
                preg_match('/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/', $description, $matches);
                $detectedIp = $matches[0] ?? 'External Node';

                // Menentukan tingkat risiko murni berdasarkan level log bawaan Wazuh
                if (in_array($level, ['error', 'alert', 'critical'])) {
                    $risk = 'high';
                } elseif ($level === 'warning') {
                    $risk = 'medium';
                } else {
                    $risk = 'low';
                }

                $connections[] = [
                    'src_ip' => $detectedIp,
                    'dst_ip' => 'Local Agent',
                    'service' => $item['tag'] ?? 'Network Event',
                    'protocol' => 'TCP',
                    'status' => strtoupper($level),
                    'duration' => 'Live',
                    'info' => $description,
                    'risk' => $risk
                ];
            }

            return $connections;

        } catch (\Throwable $e) {
            \Log::error("Wazuh Active Connections Error: " . $e->getMessage());
            return []; // Array kosong jika offline, biarkan frontend yang menampilkan fallback offline
        }
    }

    // Code ini untuk: Mengambil data serangan berbasis lokasi (GeoIP) riil dari log.
    // Berfungsi untuk: Widget GeoIP Attack Map.
    public function fetchGeoAttacks($agentId): array
    {
        try {
            $response = $this->wazuh->get("/manager/logs?limit=10&q=level=error;agent.id={$agentId}");
            $rawItems = $response['data']['affected_items'] ?? [];

            if (empty($rawItems)) {
                return [];
            }

            $attacks = [];

            foreach ($rawItems as $item) {
                // Karena log mentah tidak selalu punya lat/lng bawaan, cek dulu apakah field GeoIP tersedia.
                // Jika tidak ada, jangan mengarang koordinat — biarkan kosong (data jujur).
                $locationData = $item['data']['srcgeoip'] ?? null;

                if ($locationData && isset($locationData['latitude'], $locationData['longitude'])) {
                    $attacks[] = [
                        'country' => $locationData['country_name'] ?? 'Unknown',
                        'city' => $locationData['city_name'] ?? 'Unknown',
                        'lat' => (float) $locationData['latitude'],
                        'lng' => (float) $locationData['longitude'],
                        'severity' => ($item['level'] ?? 'info') === 'error' ? 'danger' : 'warning',
                        'attack' => $item['tag'] ?? 'Security Alert'
                    ];
                }
            }

            // Jika sepanjang loop tidak ditemukan data koordinat riil, array ini berstatus kosong (jujur, bukan dummy)
            return $attacks;

        } catch (\Throwable $e) {
            \Log::error("Wazuh GeoIP Attack Map Error: " . $e->getMessage());
            return [];
        }
    }
}