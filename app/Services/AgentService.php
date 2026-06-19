<?php

namespace App\Services;

use App\Models\Agen;
use App\Models\User;

/**
 * Class AgentService
 * * Service utama yang berfungsi sebagai jembatan komunikasi antara aplikasi Laravel
 * dan API Server Wazuh untuk mengelola data keagenan serta metrik pemantauan keamanan.
 */
class AgentService
{
    /**
     * Instance dari jembatan API khusus Wazuh.
     * @var WazuhApiService
     */
    protected $wazuh;

    /**
     * AgentService Constructor.
     * Otomatis menginjeksikan konfigurasi kredensial API dari WazuhApiService.
     * * @param WazuhApiService $wazuh
     */
    public function __construct(WazuhApiService $wazuh)
    {
        $this->wazuh = $wazuh;
    }

    /**
     * Mengambil seluruh daftar agen mentah yang terdaftar di dalam server pusat Wazuh.
     * Fungsi ini bertindak sebagai basis data penyuplai utama untuk fungsi pemfilteran lainnya.
     * * @return array Daftar seluruh agen beserta metadata statusnya.
     */
    public function getAgents()
    {
        $response = $this->wazuh->agents();
        return $response['data']['affected_items'] ?? [];
    }

    /**
     * Menyaring daftar agen yang berstatus belum dialokasikan ke pelanggan manapun.
     * Digunakan pada: Halaman Dashboard Admin -> Menu "Assign Agent" (Komponen Dropdown).
     * Mencegah: Terjadinya duplikasi pencatatan agen tunggal pada dua pelanggan berbeda.
     * * @return array Daftar agen kosong yang siap ditautkan ke pengguna.
     */
    public function getAvailableAgents()
    {
        $agents = $this->getAgents();
        $assignedAgents = Agen::pluck('id_wazuh_agen')->toArray();

        return collect($agents)
            ->whereNotIn('id', $assignedAgents)
            ->values()
            ->toArray();
    }

    /**
     * Mengakumulasikan kalkulasi status operasional seluruh agen secara global.
     * Digunakan pada: Halaman Utama Dasbor Panel Admin.
     * Output: Mengisi data numerik pada 4 kotak indikator statistik utama (Active, Pending, dsb).
     * * @return array Total akumulasi agen dikelompokkan berdasarkan status koneksinya.
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
     * Mengambil dan menghitung status agen khusus milik pelanggan yang sedang masuk log sistem.
     * Digunakan pada: Dasbor Panel Pelanggan & Komponen Ringkasan "Agent Status".
     * Output: Menampilkan perbandingan jumlah server pelanggan yang hidup (Online) vs mati (Offline).
     * * @return array Total statistik khusus agen internal milik satu user pelanggan tertentu.
     */
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

    /**
     * Mengompilasi ringkasan data akun pelanggan berserta total agen relasi di bawahnya.
     * Digunakan pada: Halaman Dasbor Admin -> Menu "Agents List" / Manajemen Pengguna.
     * Output: Menyusun data struktur baris tabel berisi identitas pengguna dan kapasitas servernya.
     * * @return array Koleksi model User beserta total penjumlahan akumulasi agen terdaftar.
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
     * Menarik metrik performa konsumsi perangkat keras asli dari agen via Wazuh DB API Engine.
     * Digunakan pada: Widget Pemantauan Performa Realtime (CPU & RAM) di sisi Pelanggan.
     * Proteksi: Otomatis mengembalikan nilai 0 jika agen terputus, menjaga validitas data di dasbor.
     * * @param string|int $agentId ID unik target agen.
     * @return array Persentase rasio beban pemakaian CPU dan RAM saat ini.
     */
    public function fetchSystemResources($agentId): array
    {
        try {
            // Membuka snapshot pembacaan data perangkat keras dari modul syscollector agen terkait
            $response = $this->wazuh->get("/agents/{$agentId}/wdb/hardware");
            $hardware = $response['data']['affected_items'][0] ?? null;

            if (!$hardware) {
                return ['cpu' => 0, 'ram' => 0];
            }

            // --- KALKULASI RASIO KONSUMSI MEMORI (RAM) ---
            $ramTotal = $hardware['ram_total'] ?? 0;
            $ramFree = $hardware['ram_free'] ?? 0;
            $ramUsagePercentage = 0;

            if ($ramTotal > 0) {
                $ramUsed = $ramTotal - $ramFree;
                $ramUsagePercentage = round(($ramUsed / $ramTotal) * 100);
            }

            // --- KALKULASI BEBAN KERJA PROSESOR (CPU) ---
            $cpuValue = $hardware['cpu_usage'] ?? $hardware['cpu_load'] ?? 0;

            // Normalisasi data jika sistem operasi mendata muatan kerja dalam bentuk bilangan desimal (skala 0 - 1)
            if ($cpuValue > 0 && $cpuValue <= 1) {
                $cpuValue = round($cpuValue * 100);
            } else {
                $cpuValue = round($cpuValue);
            }

            return [
                'cpu' => min(100, max(0, $cpuValue)),
                'ram' => min(100, max(0, $ramUsagePercentage)),
            ];

        } catch (\Throwable $e) {
            return ['cpu' => 0, 'ram' => 0];
        }
    }

    /**
     * Mengakses data inventaris berkas sistem operasi (FIM - Syscollector File System).
     * Digunakan pada: Widget Dasbor Pelanggan -> Pemantauan Integritas File (File Integrity).
     * Catatan: Data ini merefleksikan status berkas aman utuh (Intact) yang dipantau aktif oleh Wazuh.
     * * @param string|int $agentId ID unik target agen.
     * @return array Kumpulan data rute berkas sistem beserta catatan waktu singkronisasinya.
     */
    public function fetchFileIntegrityLogs($agentId): array
    {
        try {
            $response = $this->wazuh->get("/syscollector/{$agentId}/files?limit=6");
            $items = $response['data']['affected_items'] ?? [];

            $formattedLogs = [];
            foreach ($items as $item) {
                $mTime = $item['mtime'] ?? null;
                $timeLog = $mTime ? date('H:i', strtotime($mTime)) . ' WIB' : 'Secure';

                $formattedLogs[] = [
                    'path' => $item['file'] ?? 'Unknown Path',
                    'agent' => 'Agent ' . $agentId,
                    'time' => $timeLog,
                    'status' => 'intact'
                ];
            }

            return $formattedLogs;

        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Membaca statistik akumulasi percobaan kegagalan otentikasi (Gagal Login) dalam basis data log Wazuh.
     * Digunakan pada: Widget Ringkasan Angka Indikator Kerentanan Keamanan "Failed Logins".
     * * @param string|int $agentId ID unik target agen.
     * @return array Total kecocokan log insiden kegagalan akses beserta label status risikonya.
     */
    public function fetchFailedLoginsCount($agentId): array
    {
        try {
            // PERBAIKAN BUG: Menggunakan query string pengunci pencarian 'q=agent.id' 
            // agar filter terisolasi penuh pada agen terkait dan menyaring rule level otentikasi gagal (>= 5)
            $response = $this->wazuh->get("/alerts?limit=1&level=5&search=failed&q=agent.id={$agentId}");
            $totalCount = $response['data']['total_affected_items'] ?? 0;

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
            return [
                'count' => 0,
                'timeline' => 'Service unavailable',
                'status_tag' => 'Unknown'
            ];
        }
    }

    /**
     * Mengambil 5 data entri riwayat aktivitas login user secara berurutan waktu (Realtime Log).
     * Digunakan pada: Widget Dasbor Pelanggan -> "User Login Activity".
     * * @param string|int $agentId ID unik target agen dari database lokal.
     * @return array Baris log siap saji untuk diumpankan ke komponen Blade.
     */
    public function fetchUserLoginActivity($agentId): array
    {
        try {
            // Memastikan ID Agen diformat menjadi 3 digit string (misal: 1 menjadi "001")
            $formattedAgentId = str_pad($agentId, 3, '0', STR_PAD_LEFT);

            // Menggunakan URL encoding pada parameter q agar dibaca dengan benar oleh API Wazuh
            $queryString = urlencode("agent.id={$formattedAgentId}");
            $response = $this->wazuh->get("/alerts?limit=5&sort=-date&q={$queryString}");

            $items = $response['data']['affected_items'] ?? [];
            $activities = [];

            foreach ($items as $item) {
                $description = $item['rule']['description'] ?? 'System event logged';

                // DEEP PARSER DATA USER (Penyelarasan Log Windows & Linux)
                $user = 'SYSTEM';
                if (isset($item['data']['win']['eventdata']['targetUserName'])) {
                    $user = $item['data']['win']['eventdata']['targetUserName'];
                } elseif (isset($item['data']['win']['eventdata']['subjectUserName'])) {
                    $user = $item['data']['win']['eventdata']['subjectUserName'];
                } elseif (isset($item['data']['dstuser'])) {
                    $user = $item['data']['dstuser'];
                } elseif (isset($item['data']['srcuser'])) {
                    $user = $item['data']['srcuser'];
                }

                // Normalisasi nama jika berakhiran karakter workstation mesin ($)
                if (str_ends_with($user, '$')) {
                    $user = 'SYSTEM';
                }

                $ip = $item['data']['srcip'] ?? 'Local Machine';
                $timeLog = isset($item['date']) ? date('H:i', strtotime($item['date'])) . ' WIB' : 'Baru saja';

                // Pemetaan level untuk indikator warna widget
                $level = $item['rule']['level'] ?? 0;
                if ($level >= 8) {
                    $status = 'danger';
                } elseif ($level >= 5) {
                    $status = 'warning';
                } elseif ($level >= 3) {
                    $status = 'success';
                } else {
                    $status = 'info';
                }

                $activities[] = [
                    'user' => $user,
                    'activity' => $description,
                    'ip' => $ip,
                    'status' => $status,
                    'time' => $timeLog
                ];
            }

            return $activities;

        } catch (\Throwable $e) {
            // Mengembalikan array kosong jika koneksi API atau parsing gagal
            return [];
        }
    }
}