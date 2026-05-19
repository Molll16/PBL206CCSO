<?php

namespace App\Http\Controllers;

use App\Models\Agen;
use App\Services\AgentService;
use App\Services\WazuhApiService;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    // =========================================
    // LIST AGENT
    // =========================================
    public function agents(
        WazuhApiService $wazuh,
        AgentService $agentService
    )
    {
        $agents = $agentService->getAgents($wazuh);
        $stats = $agentService->getCustomerStats();

        return view('Admin.agents.agents-list', [
            'agents' => $agents,
            'totalUsers' => $stats['totalUsers'],
            'totalAgents' => count($agents),
            'totalAssignedAgents' => $stats['totalAssignedAgents'],
        ]);
    }

    // =========================================
    // HALAMAN ASSIGN AGENT
    // =========================================
    public function assignAgentPage(
        WazuhApiService $wazuh,
        AgentService $agentService
    )
    {
        $agents = $agentService->getAvailableAgents($wazuh);
        $stats = $agentService->getCustomerStats();

        return view('Admin.agents.assignagent', [
            'agents' => $agents,
            'users' => $stats['users'],
            'totalUsers' => $stats['totalUsers'],
            'totalAgents' => count($agents),
            'totalAssignedAgents' => $stats['totalAssignedAgents'],
        ]);
    }

    // =========================================
    // SIMPAN ASSIGN AGENT
    // =========================================
    public function saveAssignAgent(
        Request $request,
        WazuhApiService $wazuh,
        AgentService $agentService
    )
    {
        // validasi
        $request->validate([
            'agent_id' => ['required'],
            'user_id' => ['required'],
        ]);

        // ambil data agent
        $agent = collect(
            $agentService->getAgents($wazuh)
        )->firstWhere('id', $request->agent_id);

        // cek agent
        if (!$agent) {
            return back()->with('error', 'Agent tidak ditemukan');
        }

        // cek duplicate
        if (Agen::where('id_wazuh_agen', $request->agent_id)->exists()) {
            return back()->with('error', 'Agent sudah diassign');
        }

        // simpan
        Agen::create([
            'user_id' => $request->user_id,
            'id_wazuh_agen' => $request->agent_id,
            'nama_agen' => $agent['name'] ?? 'Unknown',
            'ip_agen' => $agent['ip'] ?? '-',
            'status' => 'aktif',
        ]);

        return redirect()
            ->route('assignagent')
            ->with('success', 'Agent berhasil di-assign');
    }
}