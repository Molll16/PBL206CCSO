<?php

namespace App\Http\Controllers;

use App\Services\AgentService;
use App\Services\AlertService;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    protected $agentService;
    protected $alertService;

    // Suntikkan service yang dibutuhkan via Constructor
    public function __construct(AgentService $agentService, AlertService $alertService)
    {
        $this->agentService = $agentService;
        $this->alertService = $alertService;
    }

    /**
     * 1. Widget Status Agen (Online/Offline)
     */
    public function getAgentStatus()
    {
        try {
            $stats = $this->agentService->getCustomerStats();
            return response()->json([
                'success' => true,
                'data' => [
                    'online' => $stats['online'] ?? 0,
                    'offline' => $stats['offline'] ?? 0,
                    'total' => $stats['total'] ?? 0
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => ['online' => 0, 'offline' => 0, 'total' => 0],
                'message' => 'Gagal memuat status agen.'
            ]);
        }
    }

    /**
     * 2. Widget Security Alerts Terbaru (Limit 5)
     */
    public function getLatestAlerts()
    {
        try {
            $alerts = $this->alertService->getLatestAlerts(5);
            return response()->json([
                'success' => true,
                'data' => $alerts
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal memuat alert keamanan.'
            ]);
        }
    }

    /**
     * 3. Widget Threat Summary (Kategori Ancaman)
     */
    public function getThreatSummary()
    {
        try {
            // Ambil ID Agen yang aktif dari session dropdown
            $selectedAgentId = session('active_wazuh_agent_id');

            // Jalankan pencarian di service dengan filter ID agen tersebut
            $summary = $this->alertService->getThreatSummary($selectedAgentId);

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => ['active' => 0, 'pending' => 0, 'resolved' => 0, 'categories' => []],
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * 4. Widget System Resources (Sudah Mendukung Dynamic Agent Switch)
     */
    public function getSystemResources()
    {
        try {
            // 1. Cek apakah ada Agent ID spesifik yang sedang aktif di session (dari dropdown pojok kanan atas)
            // Jika session kosong, kita jadikan null sebagai default
            $activeAgentId = session('active_wazuh_agent_id');

            // 2. Jika di session belum ada (misal baru pertama kali login), 
            // ambil agen pertama milik customer tersebut sebagai fallback/bawaan awal
            if (!$activeAgentId) {
                $myAgents = \App\Models\Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
                $activeAgentId = $myAgents[0] ?? null;
            }

            // 3. Jika setelah dicek ke database user memang tidak punya agen sama sekali, stop proses
            if (!$activeAgentId) {
                return response()->json([
                    'success' => false,
                    'data' => [],
                    'message' => 'Tidak ada agen terpasang pada akun Anda.'
                ]);
            }

            // 4. Barulah fetch data metrik asli dari AgentService berdasarkan Agent ID yang aktif/dipilih
            $metrics = $this->agentService->fetchSystemResources($activeAgentId);

            $resources = [
                [
                    'label' => 'CPU',
                    'value' => $metrics['cpu'],
                    'color' => 'bg-cyan-500'
                ],
                [
                    'label' => 'RAM',
                    'value' => $metrics['ram'],
                    'color' => 'bg-amber-500'
                ],
                [
                    'label' => 'DISK',
                    'value' => 44, // Nanti bisa kamu kembangkan dinamis jika diperlukan
                    'color' => 'bg-emerald-500'
                ],
                [
                    'label' => 'SWAP',
                    'value' => 12,
                    'color' => 'bg-indigo-500'
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $resources
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Gagal memuat resource sistem asli.'
            ]);
        }
    }

    /**
     * 5. Widget File Integrity Monitoring (FIM)
     */
    public function getFileIntegrity()
    {
        try {
            // Cek agent yang sedang aktif dipilih di dropdown session
            $activeAgentId = session('active_wazuh_agent_id');

            if (!$activeAgentId) {
                $myAgents = \App\Models\Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
                $activeAgentId = $myAgents[0] ?? null;
            }

            if (!$activeAgentId) {
                return response()->json(['success' => false, 'data' => []]);
            }

            // Panggil service untuk ambil data FIM asli dari Wazuh
            $fimData = $this->agentService->fetchFileIntegrityLogs($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $fimData
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => []]);
        }
    }

    /**
     * 6. Widget Failed Logins Counter (Realtime & Filtered)
     */
    public function getFailedLogins()
    {
        try {
            // Ambil agent ID aktif dari session dropdown
            $activeAgentId = session('active_wazuh_agent_id');

            if (!$activeAgentId) {
                $myAgents = \App\Models\Agen::where('user_id', auth()->id())->pluck('id_wazuh_agen')->toArray();
                $activeAgentId = $myAgents[0] ?? null;
            }

            if (!$activeAgentId) {
                return response()->json(['success' => false, 'data' => ['count' => 0, 'timeline' => '-', 'status_tag' => '-']]);
            }

            // Ambil data failed login asli lewat service
            $failedData = $this->agentService->fetchFailedLoginsCount($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $failedData
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'data' => ['count' => 0, 'timeline' => '-', 'status_tag' => '-']]);
        }
    }

    /**
     * Menyediakan data log aktivitas login untuk widget frontend.
     */
    public function getUserLoginActivity()
    {
        try {
            $selectedAgentId = session('active_wazuh_agent_id');

            $agent = \App\Models\Agen::where('user_id', auth()->id())
                ->where(function ($query) use ($selectedAgentId) {
                    $query->where('id', $selectedAgentId)
                        ->orWhere('id_wazuh_agen', $selectedAgentId);
                })->first();

            if (!$agent) {
                $agent = \App\Models\Agen::where('user_id', auth()->id())->first();
            }

            if (!$agent) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'message' => 'Tidak ada agen terdaftar untuk akun ini'
                ]);
            }

            $activeAgentId = $agent->id_wazuh_agen;
            $activitiesData = $this->agentService->fetchUserLoginActivity($activeAgentId);

            return response()->json([
                'success' => true,
                'data' => $activitiesData
            ]);

        } catch (\Throwable $e) {
            \Log::error('[Widget] getUserLoginActivity error: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Menyediakan data JSON realtime untuk widget Most Active Rules.
     */
    public function getMostActiveRules()
    {
        try {
            $selectedAgentId = session('active_wazuh_agent_id');

            // Proteksi: Jika session dropdown kosong, otomatis cari agen pertama user di DB lokal
            if (!$selectedAgentId) {
                $defaultAgent = \App\Models\Agen::where('user_id', auth()->id())->first();
                $selectedAgentId = $defaultAgent ? $defaultAgent->id_wazuh_agen : null;
            }

            // Ambil data yang sudah diproses oleh AlertService
            $activeRules = $this->alertService->getMostActiveRules($selectedAgentId);

            return response()->json([
                'success' => true,
                'data' => $activeRules
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => $e->getMessage()
            ], 200); // Menggunakan status 200 agar AJAX JavaScript tidak crash 500
        }
    }
}