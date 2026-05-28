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
            $summary = $this->alertService->getThreatSummary();
            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => ['active' => 0, 'pending' => 0, 'resolved' => 0, 'categories' => []],
                'message' => 'Gagal memuat ringkasan ancaman.'
            ]);
        }
    }
}